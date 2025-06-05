<?php

namespace Modules\Notification\Jobs;

use App\Mail\ValidationActeDeceMailable;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ValidationacteDecesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $directeur_pompe_funebre;
    private $code_declaration_deces;
    private $nombre;
    private $code_otp;
    private $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($directeur_pompe_funebre,$nombre,$code_otp,$to)
    {
        $this->directeur_pompe_funebre = $directeur_pompe_funebre;
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
        Mail::to($this->to)->send(new ValidationActeDeceMailable($this->directeur_pompe_funebre,$this->nombre,$this->code_otp));
    }
}
