<?php

namespace App\Repositories\BidRepository;

use App\Models\AutoBid;
use App\Models\Bid;
use App\Models\Item;
use App\Models\Notification;
use App\Repositories\BidRepository\Interfaces\BidRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BidRepository implements BidRepositoryInterface
{
    public function all()
    {
        $bids = Bid::where('user_id', Auth::id())->get();
        return $bids->load('item');
    }

    public function create($request)
    {
        return $this->store($request->item_id, $request->bid_amount, Auth::id());
    }

    public function store($itemId, $bidAmount, $userId)
    {
        $bid = Bid::create([
            'item_id' => $itemId,
            'user_id' => $userId,
            'bid_amount' => $bidAmount,
        ]);

        $this->updateItemCurrentAmount($bid->item_id, $bid->bid_amount);
        $this->checkBidAlert($bid->item_id, $bid->bid_amount, $userId);
        $this->autoBid($bid->item_id, $bid->bid_amount, $userId);

        return $bid;
    }

    public function activateAutoBid($request)
    {
        return AutoBid::updateOrCreate(
            ['user_id' => auth()->id(), 'item_id' => $request->item_id],
            ['max_bid_amount' => $request->max_bid_amount, 'bid_alert_percentage' => $request->bid_alert_percentage]
        );
    }

    public function updateItemCurrentAmount($itemId, $currentAmount)
    {
        return Item::find($itemId)->update([
           'current_price' => $currentAmount
        ]);
    }

    public function autoBid($itemId, $currentAmount, $userId): void
    {
        $autoBid = AutoBid::where('item_id', $itemId)
            ->whereNot('user_id', $userId)
            ->where('max_bid_amount', '>', $currentAmount)
            ->orderBy('max_bid_amount', 'asc')
            ->first();

        if ($autoBid) {
            $this->store($itemId, $currentAmount+1, $autoBid->user_id);
        }
    }

    protected function checkBidAlert($itemId, $currentAmount, $userId): void
    {
        $autoBids = AutoBid::where('item_id', $itemId)
            ->whereNot('user_id', $userId)
            ->get();

        foreach ($autoBids as $autoBid) {
            $thresholdAmount = ($autoBid->max_bid_amount * $autoBid->bid_alert_percentage) / 100;

            if ($currentAmount >= $autoBid->max_bid_amount) {
                $this->sendLoseBidAlert($autoBid->user_id, $itemId);
            } elseif ($currentAmount >= $thresholdAmount) {
                $this->sendBidAlert($autoBid->user_id, $autoBid->item_id, $currentAmount);
            }
        }
    }

    protected function sendBidAlert($userId, $itemId, $currentAmount): void
    {
        $notificationExists = Notification::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->where('message', 'LIKE', "%Your auto-bid for the item%")
            ->exists();

        if (!$notificationExists) {
            $item = Item::find($itemId);
            $message = "Your auto-bid for the item '{$item->name}' has reached the alert threshold you set";

            Notification::create([
                'user_id' => $userId,
                'item_id' => $itemId,
                'message' => $message,
                'is_read' => false,
            ]);
        }
    }

    protected function sendLoseBidAlert($userId, $itemId): void
    {
        $notificationExists = Notification::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->where('message', 'LIKE', "You've lost the bid for the item%")
            ->exists();

        if (!$notificationExists) {
            $item = Item::find($itemId);
            $message = "You've lost the bid for the item '{$item->name}' as another user has outbid your maximum bid amount";

            Notification::create([
                'user_id' => $userId,
                'item_id' => $itemId,
                'message' => $message,
                'is_read' => false,
            ]);
        }
    }
}
