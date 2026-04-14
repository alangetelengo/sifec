@php
    $notifPreview = function ($data) {
        $raw = $data['message'] ?? 'Nouvelle notification';
        $normalized = preg_replace('#<br\s*/?>#i', ' ', $raw);
        $plain = trim(preg_replace('/\s+/u', ' ', strip_tags($normalized)));

        return \Illuminate\Support\Str::limit($plain, 200);
    };

    $notifMeta = function ($type, $data = []) {
        $label = 'Notification';
        $variant = 'default';
        $icon = 'fa-bell';

        if (str_contains($type, 'RegistreValideParTribunal')) {
            $label = 'Registre validé';
            $variant = 'success';
            $icon = 'fa-check-circle';
        } elseif (str_contains($type, 'CreationRegistreParCec')) {
            $label = 'Nouveau registre';
            $variant = 'registre';
            $icon = 'fa-book';
        } elseif (str_contains($type, 'FeuilletRegistreAjoute')) {
            $label = 'Feuillet registre';
            $variant = 'registre';
            $icon = 'fa-file-alt';
        } elseif (str_contains($type, 'ActeAValider')) {
            $label = 'Acte naissance';
            $variant = 'naissance';
            $icon = 'fa-user';
        } elseif (str_contains($type, 'ActeDecesAValider')) {
            $label = 'Acte décès';
            $variant = 'deces';
            $icon = 'fa-file-text';
        } elseif (str_contains($type, 'ActeMariageAValider')) {
            $label = 'Acte mariage';
            $variant = 'mariage';
            $icon = 'fa-heart';
        } elseif (str_contains($type, 'DeclarationMariageEnvoyee')) {
            $label = 'Déclaration mariage';
            $variant = 'mariage';
            $icon = 'fa-heart';
        } elseif (str_contains($type, 'DeclarationEnvoyeeCentre')) {
            $label = $data['badge_label'] ?? null;
            if (! is_string($label) || $label === '') {
                $msg = (string) ($data['message'] ?? '');
                if (preg_match('/certificat de (naissance|non inscription)|certificat de non inscription de décès/ui', $msg)) {
                    $label = 'Certificat';
                } elseif (preg_match('/tribunal\s*:|^tribunal\s*:/ui', $msg) || preg_match('/\bréquisition\b|\bjugement\b/ui', $msg)) {
                    $label = 'Tribunal';
                } else {
                    $label = 'Déclaration';
                }
            }
            $variant = 'declaration';
            $icon = 'fa-paper-plane';
        } elseif (str_contains($type, 'DocumentImporteTribunal')) {
            $label = 'Document tribunal';
            $variant = 'tribunal';
            $icon = 'fa-gavel';
        } elseif (str_contains($type, 'RectificationEnvoyeeTribunal')) {
            $label = 'Rectification';
            $variant = 'rectif';
            $icon = 'fa-edit';
        } elseif (str_contains($type, 'FormulaireTypeValide')) {
            $label = 'Formulaire';
            $variant = 'info';
            $icon = 'fa-check-square';
        } elseif (str_contains($type, 'DemandeDispenseEnvoyer')) {
            $label = 'Dispense';
            $variant = 'info';
            $icon = 'fa-envelope';
        }

        return compact('label', 'variant', 'icon');
    };
@endphp

<div class="sifec-notif-list-wrap">
    @forelse($notifications as $notification)
        @php
            $meta = $notifMeta($notification->type, $notification->data ?? []);
            $preview = $notifPreview($notification->data ?? []);
        @endphp
        <a href="{{ route('notifications.read', $notification->id) }}"
           class="sifec-notif-item"
           title="Ouvrir la notification">
            <span class="sifec-notif-item-icon sifec-notif-item-icon--{{ $meta['variant'] }}" aria-hidden="true">
                <i class="fa {{ $meta['icon'] }}"></i>
            </span>
            <span class="sifec-notif-item-body">
                <span class="sifec-notif-item-top">
                    <span class="sifec-notif-item-badge">{{ $meta['label'] }}</span>
                    <span class="sifec-notif-item-time">{{ $notification->created_at->diffForHumans() }}</span>
                </span>
                <span class="sifec-notif-item-text">{{ $preview }}</span>
            </span>
        </a>
    @empty
        <div class="sifec-notif-empty">
            <span class="sifec-notif-empty-icon" aria-hidden="true"><i class="fa fa-inbox"></i></span>
            <p class="sifec-notif-empty-title">Aucune notification non lue</p>
            <p class="sifec-notif-empty-hint">Vous êtes à jour.</p>
        </div>
    @endforelse
</div>
<div class="sifec-notif-dropdown-footer">
    <a href="{{ route('notifications.index') }}" class="sifec-notif-footer-link">Voir toutes les notifications</a>
</div>
