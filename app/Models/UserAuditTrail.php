<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuditTrail extends Model
{
    use HasFactory;

    protected $table = 'tr_user_audit_trail';

    // Désactiver updated_at car c'est une table d'audit (on ne modifie jamais les enregistrements)
    const UPDATED_AT = null;

    protected $fillable = [
        'code_user',
        'action',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'session_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'code_user', 'code_user');
    }

    /**
     * Enregistrer une action d'audit
     */
    public static function log($codeUser, $action, $description = null, $oldValues = null, $newValues = null)
    {
        [$description, $oldValues, $newValues] = self::normalizeLogArguments($description, $oldValues, $newValues);

        return self::create([
            'code_user' => $codeUser,
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
        ]);
    }

    /**
     * Compatibilité avec d’anciens appels erronés du type log($u, $action, [], "Message")
     * (le 3e paramètre est la description textuelle, pas un tableau vide pour old_values).
     *
     * @return array{0: ?string, 1: mixed, 2: mixed}
     */
    private static function normalizeLogArguments($description, $oldValues, $newValues): array
    {
        // log(..., [], "texte") — texte réel en 4e argument
        if (is_array($description) && is_string($oldValues)) {
            $maybeOld = $description;

            return [$oldValues, empty($maybeOld) ? null : $maybeOld, $newValues];
        }

        // log(..., [], oldSnapshot, newSnapshot) sans libellé
        if ($description === [] && is_array($oldValues) && $newValues !== null) {
            return [null, $oldValues, $newValues];
        }

        // description restée un tableau non géré ci-dessus → JSON ou null (évite "Array to string conversion")
        if (is_array($description)) {
            $encoded = $description === [] ? null : json_encode($description, JSON_UNESCAPED_UNICODE);

            return [
                $encoded !== false ? $encoded : null,
                $oldValues,
                $newValues,
            ];
        }

        return [$description, $oldValues, $newValues];
    }

    /**
     * Actions disponibles
     */
    public static function getAvailableActions()
    {
        return [
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'login_failed' => 'Échec de connexion',
            'profile_update' => 'Modification du profil',
            'password_change' => 'Changement de mot de passe',
            '2fa_enabled' => '2FA activée',
            '2fa_disabled' => '2FA désactivée',
            '2fa_reset' => '2FA réinitialisée',
            '2fa_verified' => 'Vérification 2FA',
            'recovery_code_used' => 'Code de récupération utilisé',
            'recovery_codes_regenerated' => 'Codes de récupération régénérés',
            'permission_granted' => 'Permission accordée',
            'permission_revoked' => 'Permission révoquée',
            'account_activated' => 'Compte activé',
            'account_deactivated' => 'Compte désactivé',
        ];
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par utilisateur
     */
    public function scopeByUser($query, $codeUser)
    {
        return $query->where('code_user', $codeUser);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeByPeriod($query, $startDate, $endDate = null)
    {
        $query->where('created_at', '>=', $startDate);

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query;
    }
}
