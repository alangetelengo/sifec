<?php

namespace App\Models;


use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Modules\Referentiel\Entities\Personne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentification\Entities\Fonctionnalite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Deces\Entities\DeclarationDeces;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Notifiable fournit notifications() et unreadNotifications()

    protected $table = "tr_user";
    protected $primaryKey = "code_user";
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'recovery_codes',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_verified_at' => 'datetime',
        'google2fa_enabled' => 'boolean',
        'two_factor_required' => 'boolean',
        'must_change_password' => 'boolean',
    ];


    public function affectations(): HasMany
    {
        return $this->hasMany(InstitutionUser::class, 'code_user', 'code_user');
    }

    /**
     * Retourne l'affectation active de l'utilisateur (résultat direct)
     * @return InstitutionUser|null
     */
    public function affectationActive()
    {
        // Ne pas utiliser Collection::where('active', 1) : comparaison stricte ;
        // en base / PDO, active peut être 1, '1' ou true selon le driver.
        return $this->affectations->first(function (InstitutionUser $a) {
            return (int) $a->active === 1;
        });
    }

    /**
     * Retourne la relation pour l'affectation active (pour les queries)
     * @return HasMany
     */
    public function affectationActiveRelation(): HasMany
    {
        return $this->hasMany(InstitutionUser::class, 'code_user', 'code_user')->where("active", 1);
    }

    public function fonction(){
        $affectation = $this->affectationActive();
        return $affectation ? $affectation->fonction : null;
    }

    public function signateur($id){
        $fonction = $this->fonction();
        return $fonction && $fonction->code_fonction == $id ? $fonction : null;
    }

    public function institution(){
        $affectation = $this->affectationActive();
        return $affectation ? $affectation->institution : null;
    }

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_personne', 'code_personne');
    }


    public function fonctionnalites(): BelongsToMany
    {
        return $this->belongsToMany(Fonctionnalite::class, 'tr_uf', 'code_user', 'code_fonctionnalite');
    }

    public function toutesfonctionnalites(){
        $userFonctionnalites = $this->fonctionnalites;
        $fonction = $this->fonction();
        $fonctionFonctionnalites = $fonction ? $fonction->fonctionnalites : collect();
        return $userFonctionnalites
            ->merge($fonctionFonctionnalites)
            ->flatten()
            ->filter(function ($f) {
                return ($f->etat_fonctionnalite ?? 'Activé') === 'Activé';
            })
            ->values();
    }

    public function modules(){
        return $this->toutesfonctionnalites()->map->module->flatten();
    }

    //recuperer les declarations de deces de chaque localite
    public function declarationDeces()
    {
       return $this->affectationActive()->userPompeFunebres->flatten()->map->localite->flatten()->map->declarationDeces()->flatten();
    }


    public function MyLocalites()
    {
       return implode(" , ", $this->affectationActive()->userPompeFunebres->map->localite->flatten()->pluck("lib_localite")->toArray());
    }

    // ==========================================
    // MÉTHODES 2FA
    // ==========================================

    /**
     * Vérifier si l'utilisateur a la 2FA activée
     *
     * @return bool
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->google2fa_enabled && !empty($this->google2fa_secret);
    }

    /**
     * Vérifier si la 2FA est requise pour cet utilisateur
     *
     * @return bool
     */
    public function isTwoFactorRequired(): bool
    {
        return $this->two_factor_required;
    }

    /**
     * Générer des codes de récupération
     *
     * @return array
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        }

        $this->recovery_codes = encrypt(json_encode($codes));
        $this->save();

        return $codes;
    }

    /**
     * Obtenir les codes de récupération déchiffrés
     *
     * @return array
     */
    public function getRecoveryCodes(): array
    {
        if (empty($this->recovery_codes)) {
            return [];
        }

        try {
            return json_decode(decrypt($this->recovery_codes), true) ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Utiliser un code de récupération
     *
     * @param string $code
     * @return bool
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->getRecoveryCodes();
        $key = array_search(strtoupper($code), $codes);

        if ($key !== false) {
            unset($codes[$key]);
            $this->recovery_codes = encrypt(json_encode(array_values($codes)));
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Compter les codes de récupération restants
     *
     * @return int
     */
    public function getRemainingRecoveryCodesCount(): int
    {
        return count($this->getRecoveryCodes());
    }

    /**
     * Marquer la 2FA comme vérifiée
     *
     * @return void
     */
    public function markTwoFactorAsVerified(): void
    {
        $this->two_factor_verified_at = now();
        $this->save();
    }

    /**
     * Activer la 2FA
     *
     * @param string $secret
     * @return void
     */
    public function enableTwoFactor(string $secret): void
    {
        $this->google2fa_secret = encrypt($secret);
        $this->google2fa_enabled = true;
        $this->markTwoFactorAsVerified();
        $this->generateRecoveryCodes();
    }

    /**
     * Désactiver la 2FA
     *
     * @return void
     */
    public function disableTwoFactor(): void
    {
        $this->google2fa_enabled = false;
        $this->google2fa_secret = null;
        $this->recovery_codes = null;
        $this->two_factor_verified_at = null;
        $this->save();
    }

    /**
     * Obtenir le secret déchiffré
     *
     * @return string|null
     */
    public function getTwoFactorSecret(): ?string
    {
        if (empty($this->google2fa_secret)) {
            return null;
        }

        try {
            return decrypt($this->google2fa_secret);
        } catch (\Exception $e) {
            return null;
        }
    }

}
