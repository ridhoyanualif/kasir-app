<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Discount;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeleteExpiredDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discounts:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all expired discounts based on end_datetime';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil ID diskon yang sudah kadaluarsa
        $expiredDiscountIds = Discount::where('end_datetime', '<', Carbon::now())->pluck('id');

        if ($expiredDiscountIds->isNotEmpty()) {
            Product::whereIn('fid_discount', $expiredDiscountIds)->update([
                'selling_price' => Product::where('fid_discount', $expiredDiscountIds)->value('selling_price_before'),
                'selling_price_before' => null,
                'fid_discount' => null,
                'profit' => (Product::where('fid_discount', $expiredDiscountIds)->value('selling_price_before') - Product::where('fid_discount', $expiredDiscountIds)->value('modal')),
            ]);

            // Hapus diskonnya
            $deletedCount = Discount::whereIn('id', $expiredDiscountIds)->delete();

            $this->info("Deleted {$deletedCount} expired discounts and updated related products.");
        } else {
            $this->info("No expired discounts found.");
        }
    }
}
