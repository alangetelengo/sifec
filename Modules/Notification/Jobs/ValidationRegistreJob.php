<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\ValidationRegistreMailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ValidationRegistreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tribunal;
    private $otp;
    private $code_registre;
    private $to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tribunal, $otp, $code_registre,$to)
    {
        $this->tribunal = $tribunal;
        $this->otp = $otp;
        $this->code_registre = $code_registre;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->to)->send(new ValidationRegistreMailable($this->tribunal,$this->otp,$this->code_registre));
    }
}
