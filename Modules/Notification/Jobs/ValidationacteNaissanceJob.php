<?php

namespace Modules\Notification\Jobs;

use App\Mail\ValidationActeNaissanceMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ValidationacteNaissanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $maire;
    private $nombre;
    private $code_otp;
    private $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($maire,$nombre,$code_otp,$to)
    {
        $this->maire = $maire;
        $this->nombre = $nombre;
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
        Mail::to($this->to)->send(new ValidationActeNaissanceMailable($this->maire,$this->nombre,$this->code_otp));
    }
}
