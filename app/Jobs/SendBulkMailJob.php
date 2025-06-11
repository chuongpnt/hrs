<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBulkMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $userEmail;
    public $content;
    public $batchId;

    public function __construct($userEmail, $content, $batchId)
    {
        $this->userEmail = $userEmail;
        $this->content = $content;
        $this->batchId = $batchId;
        $this->queue = 'Mail';
    }

    public function handle()
    {
        Mail::raw($this->content, function ($message) {
            $message->to($this->userEmail)
                ->subject('Demo RabbitMQ Queue Email');
        });
    }
}
