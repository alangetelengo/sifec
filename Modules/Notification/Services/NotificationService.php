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
     */
    public static function notifierAgentsInstitution($institution, $notification, $codesFonction = null)
    {
        $codeInstitution = is_object($institution) && isset($institution->code_institution)
            ? $institution->code_institution
            : $institution;

        DB::transaction(function () use ($codeInstitution, $notification, $codesFonction) {
            $agents = User::whereHas('affectations', function($q) use ($codeInstitution, $codesFonction) {
                $q->where('code_institution', $codeInstitution)
                  ->where('active', 1);

                if ($codesFonction) {
                    if (is_array($codesFonction)) {
                        $q->whereIn('code_fonction', $codesFonction);
                    } else {
                        $q->where('code_fonction', $codesFonction);
                    }
                }
            })->get();



            // Log::channel('sifec')->info('[NotificationService] Agents trouvés pour notification', [
            //     'agents' => $agents->map(function($user) {
            //         return [
            //             'code_user' => $user->code_user,
            //             'code_fonction' => $user->affectations->first() ? $user->affectations->first()->code_fonction : null
            //         ];
            //     })
            // ]);

            foreach ($agents as $user) {
                $user->notify($notification);

                // Log::channel('sifec')->info('[NotificationService] Notification envoyée institution', [
                //     'user_id' => $user->code_user,
                //     'notification' => get_class($notification)
                // ]);
            }
        });
    }
}
