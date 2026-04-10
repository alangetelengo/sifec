<?php

namespace Modules\Notification\Jobs;

use App\Mail\ValidationActeNaissanceMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
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
        $to = trim((string) $this->to);
        if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::channel('sifec')->warning('ValidationacteNaissanceJob : destinataire e-mail ignoré (vide ou invalide).', [
                'to_masque' => $to === '' ? '(vide)' : substr($to, 0, 3).'…',
            ]);

            return;
        }

        Log::channel('sifec')->info('Envoi e-mail OTP validation acte naissance', [
            'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
        ]);

        try {
            Mail::to($to)->send(new ValidationActeNaissanceMailable($this->maire, $this->nombre, $this->code_otp));
            Log::channel('sifec')->info('E-mail OTP validation acte naissance : envoi SMTP terminé sans exception.', [
                'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
            ]);
        } catch (\Throwable $e) {
            Log::channel('sifec')->error('Échec envoi e-mail OTP validation acte naissance', [
                'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
