<?php

namespace App\Repositories\BidRepository;

use App\Models\AutoBid;
use App\Models\Bid;
use App\Models\Item;
use App\Notifications\AutoBidAlertNotification;
use App\Notifications\OutbidNotification;
use App\Repositories\BidRepository\Interfaces\BidRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
            'item_id' => $itemId, 'user_id' => $userId, 'bid_amount' => $bidAmount,
        ]);

        $this->updateItemCurrentAmount($bid->item_id, $bid->bid_amount);
        $this->checkBidAlert($bid);

        $this->notifyPreviousBidder($bid);
        $this->autoBid($bid->item_id, $bid->bid_amount, $userId);

        return $bid;
    }

    public function storeAutoBid($request)
    {
        return AutoBid::updateOrCreate(
            ['user_id' => auth()->id(), 'item_id' => $request->item_id],
            ['max_bid_amount' => $request->max_bid_amount, 'bid_alert_percentage' => $request->bid_alert_percentage, 'alert_sent' => false]
        );
    }

    public function deleteAutoBid($id): Response
    {
        $item = Item::findOrFail($id);
        AutoBid::where('user_id', Auth::id())->where('item_id', $item->id)->delete();

        return response()->noContent();
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

    private function notifyPreviousBidder($currentBid): void
    {
        $item = $currentBid->item;

        $previousHighestBid = $item->bids()
            ->where('bid_amount', '<', $currentBid->bid_amount)
            ->orderBy('bid_amount', 'desc')
            ->first();

        if ($previousHighestBid && $previousHighestBid->user_id != $currentBid->user_id) {
            $previousBidder = $previousHighestBid->user;
            Notification::send($previousBidder, new OutbidNotification($item));
        }
    }

    protected function checkBidAlert($bid): void
    {
        $autoBids = AutoBid::where('item_id', $bid->item_id)
            ->whereNot('user_id', $bid->user_id)
            ->where('alert_sent', false)
            ->where('is_active', true)
            ->get();

        foreach ($autoBids as $autoBid) {
            $thresholdAmount = ($autoBid->max_bid_amount * $autoBid->bid_alert_percentage) / 100;

            if ($bid->bid_amount >= $thresholdAmount) {
                $this->sendBidAlert($autoBid, $bid->bid_amount);
            }
        }
    }

    protected function sendBidAlert($autoBid, $currentAmount): void
    {
        $autoBid->update(['alert_sent' => true]);

        Notification::send($autoBid->user, new AutoBidAlertNotification($autoBid));
    }
}
