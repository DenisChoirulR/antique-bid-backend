<?php

namespace App\Services;

use App\Enums\BillStatus;
use App\Models\Bill;
use App\Models\Item;
use App\Models\Bid;
use App\Notifications\ItemWonNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class AwardHighestBidder
{
    public function __invoke()
    {
        $items = Item::where('end_time', '<=', Carbon::now())
            ->whereNull('winner_id')
            ->get();

        foreach ($items as $item) {
            $highestBid = Bid::where('item_id', $item->id)
                ->orderBy('bid_amount', 'desc')
                ->first();

            if ($highestBid) {
                $item->winner_id = $highestBid->user_id;
                $item->save();

                $bill = Bill::create([
                    'item_id' => $item->id,
                    'user_id' => $highestBid->user_id,
                    'amount' => $highestBid->bid_amount,
                    'status' => BillStatus::UNPAID->value,
                    'payment_due_date' => Carbon::now()->addDays(30),
                ]);

                Notification::send($highestBid->user, new ItemWonNotification($item, $bill));
            }
        }
    }
}
