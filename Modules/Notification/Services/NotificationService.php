<?php

namespace Modules\Notification\Services;

use App\Models\User;
use Modules\Notification\Notifications\ActeAValiderNotification;
use Modules\Notification\Notifications\ActeDecesAValiderNotification;
use Modules\Notification\Notifications\ActeMariageAValiderNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notifie un utilisateur spécifique
     */
    public static function notifierUser($user, $notification)
    {
        $user->notify($notification);
    }

    /**
     * Notifie tous les utilisateurs actifs d'une institution, avec possibilité de filtrer par fonction(s)
     * @param string|\App\Models\Institution $institution  Le code institution ou l'objet institution
     * @param \Illuminate\Notifications\Notification $notification  La notification à envoyer
     * @param string|array|null $codesFonction  (optionnel) Un ou plusieurs codes fonction à filtrer (ex : 'FONC_0008' ou ['FONC_0008','FONC_0002'])
     * @return int Nombre d'utilisateurs notifiés
     */
    public static function notifierAgentsInstitution($institution, $notification, $codesFonction = null): int
    {
        $codeInstitution = is_object($institution) && isset($institution->code_institution)
            ? $institution->code_institution
            : $institution;

        return (int) DB::transaction(function () use ($codeInstitution, $notification, $codesFonction) {
            $agents = User::whereHas('affectations', function ($q) use ($codeInstitution, $codesFonction) {
                $q->where('code_institution', $codeInstitution)
                    ->where(function ($q2) {
                        $q2->where('active', 1)->orWhere('active', true);
                    });

                if ($codesFonction) {
                    if (is_array($codesFonction)) {
                        $q->whereIn('code_fonction', $codesFonction);
                    } else {
                        $q->where('code_fonction', $codesFonction);
                    }
                }
            })->get();

            foreach ($agents as $user) {
                try {
                    $user->notify($notification);
                } catch (\Throwable $e) {
                    Log::channel('sifec')->error('[NotificationService] notifierAgentsInstitution : notify() a échoué', [
                        'code_institution' => $codeInstitution,
                        'code_user' => $user->code_user ?? null,
                        'notification' => is_object($notification) ? $notification::class : null,
                        'message' => $e->getMessage(),
                        'exception' => $e::class,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    throw $e;
                }
            }

            return $agents->count();
        });
    }

    /**
     * Feuillets ajoutés : même logique que les certificats (tous les agents actifs du tribunal).
     */
    public static function notifierFeuilletRegistreAjoute($tribunal, $notification): int
    {
        $codeInstitution = is_object($tribunal) && isset($tribunal->code_institution)
            ? $tribunal->code_institution
            : $tribunal;

        return self::notifierAgentsInstitution($codeInstitution, $notification);
    }
}
