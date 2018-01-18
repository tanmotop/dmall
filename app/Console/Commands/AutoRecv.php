<?php

namespace App\Console\Commands;

use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoRecv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:recv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动收货命令';

    protected $ordersModel;

    /**
     * Create a new command instance.
     */
    public function __construct(Orders $orders)
    {
        parent::__construct();
        $this->ordersModel = $orders;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = new Carbon('-7 day');
        $orders = $this->ordersModel->where([
            ['status', 1],
            ['posted_at', '<=', $date]
        ])->select('id', 'sn')->get();

        if ($orders->isNotEmpty()) {
            $orders->each(function ($order) {
                $this->ordersModel->confirmOrder($order->id);
                $this->info("id:{$order->id}|sn:{$order->sn}");
            });
        }
        else {
            $this->info('no order');
        }
    }
}
