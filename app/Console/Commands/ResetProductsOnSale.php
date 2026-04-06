<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetProductsOnSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:reset-onsale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset On_Sale flag to 0 for all products';

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
        $count = \App\Models\Admin\Product::query()->update(['On_Sale' => 0]);
        $this->info("Successfully reset On_Sale to 0 for {$count} products.");
        return 0;
    }
}
