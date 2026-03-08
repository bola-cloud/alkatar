<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TestBroadcastEvent;

class TestSocketBroadcast extends Command
{
    protected $signature = 'app:test-socket {message=Hello}';
    protected $description = 'Test web socket broadcasting by dispatching a TestBroadcastEvent';

    public function handle()
    {
        $message = $this->argument('message');
        $this->info("Broadcasting message: $message");
        
        event(new TestBroadcastEvent($message));
        
        $this->info("Event dispatched! Check your web socket dashboard or listener.");
        return 0;
    }
}
