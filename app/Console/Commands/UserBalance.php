<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'recharge 100000 balance to all users';

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
        foreach (User::all() as $user) {
            $transaction = new Transaction;
            $transaction->user_id     = $user->id;
            $transaction->type        = 'credit';
            $transaction->amount      = '100000';
            $transaction->description = 'recharge the balance';
            $transaction->save();
        }
    }
}
