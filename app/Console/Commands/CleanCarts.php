<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clean cart that persist for 12 hours and user did not proceed to checkout.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $carts = Cart::where('updated_at', '<', 
            Carbon::now()->subHours(12)->toDateTimeString()
        )->get();

        foreach ($carts as $cart) {
            $cart->products()->detach();
            $cart->delete();
        }
    }
}
