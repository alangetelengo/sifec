<?php

namespace Modules\Notification\Jobs;

use App\Mail\CreationRegistreMailbale;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class CreationRegistreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $tribunal;
    private $type_registre;
    private $code_registre;
    private $cec;
    private $to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tribunal,$type_registre,$code_registre,$cec,$to)
    {
        $this->tribunal = $tribunal;
        $this->type_registre = $type_registre;
        $this->code_registre = $code_registre;
        $this->cec = $cec;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->to)->send(new CreationRegistreMailbale($this->tribunal,$this->type_registre,$this->code_registre,$this->cec));
    }
}
