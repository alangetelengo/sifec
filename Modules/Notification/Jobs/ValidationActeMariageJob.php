<?php

namespace Modules\Notification\Jobs;

use App\Mail\ValidationActeMariageMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class ValidationActeMariageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $maire;
    private $code_declaration_mariage;
    private $code_otp;
    private $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($maire, $code_declaration_mariage, $code_otp, $to)
    {
        $this->maire = $maire;
        $this->code_declaration_mariage = $code_declaration_mariage;
        $this->code_otp = $code_otp;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->to)->send(new ValidationActeMariageMailable($this->maire, $this->code_declaration_mariage, $this->code_otp));
    }
}
