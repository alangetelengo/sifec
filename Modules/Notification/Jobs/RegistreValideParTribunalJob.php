<?php

namespace Modules\Notification\Jobs;

use App\Mail\RegistreValideParTribunalMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RegistreValideParTribunalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string */
    private $tribunalLib;

    /** @var string */
    private $typeRegistre;

    /** @var string */
    private $numeroOrdreRegistre;

    /** @var string */
    private $cecLib;

    /** @var string */
    private $to;

    public function __construct($tribunalLib, $typeRegistre, $numeroOrdreRegistre, $cecLib, $to)
    {
        $this->tribunalLib = $tribunalLib;
        $this->typeRegistre = $typeRegistre;
        $this->numeroOrdreRegistre = $numeroOrdreRegistre;
        $this->cecLib = $cecLib;
        $this->to = $to;
    }

    public function handle()
    {
        Mail::to($this->to)->send(new RegistreValideParTribunalMailable(
            $this->tribunalLib,
            $this->typeRegistre,
            $this->numeroOrdreRegistre,
            $this->cecLib
        ));
    }
}
