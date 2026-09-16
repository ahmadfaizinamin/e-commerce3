<?php

namespace App\Jobs;

use App\Mail\ReceiptMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReceiptMail implements ShouldQueue
{
    use Queueable;

    protected $orderData;
    /**
     * Create a new job instance.
     */
    public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = $this->orderData['email'];

        Log::channel('toko')->info("[QUEUE] Mengirim struk email ke {$email}");

        Mail::to($email)->send(new ReceiptMail($this->orderData));

        Log::channel('toko')->info("[QUEUE] Email terkirim ke {$email}");
    }
}
