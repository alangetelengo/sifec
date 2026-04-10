<?php

namespace Modules\Naissance\Exceptions;

/**
 * Verrouillage OTP acte naissance (quota renvois / saisies incorrectes), comme le paraphe registre.
 */
class ActeNaissanceOtpLockedException extends \Exception
{
    /** @var int */
    public $retryAfterSeconds;

    public function __construct($message, $retryAfterSeconds)
    {
        parent::__construct($message);
        $this->retryAfterSeconds = $retryAfterSeconds;
    }
}
