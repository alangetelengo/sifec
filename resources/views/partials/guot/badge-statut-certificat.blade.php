{{--
  Badge statut certificat GUOT / Signum.
  @param string|null $notAfter  Date de fin de validité (nullable)
  @param string|null $actorId   Identifiant Signum (nullable) — absent = non enrôlé
--}}
@php
    $actorId = $actorId ?? null;
    $notAfter = $notAfter ?? null;
    $now = now();

    if (empty($actorId)) {
        $label = 'Non enrôlé';
        $class = 'bg-secondary';
        $icon = 'fa-minus-circle';
    } elseif (empty($notAfter)) {
        $label = 'Enrôlé';
        $class = 'bg-success';
        $icon = 'fa-check-circle';
    } else {
        $expire = \Carbon\Carbon::parse($notAfter);
        if ($expire->isPast()) {
            $label = 'Expiré';
            $class = 'bg-danger';
            $icon = 'fa-times-circle';
        } elseif ($expire->lte($now->copy()->addDays(30))) {
            $jours = (int) $now->diffInDays($expire, false);
            $label = 'Expire bientôt'.($jours >= 0 ? " ({$jours} j)" : '');
            $class = 'bg-warning text-dark';
            $icon = 'fa-exclamation-triangle';
        } else {
            $label = 'Actif';
            $class = 'bg-success';
            $icon = 'fa-shield-alt';
        }
    }
@endphp
<span class="badge {{ $class }} rounded-pill px-2 py-1 fw-semibold" style="font-size:0.72rem; letter-spacing:.02em;">
    <i class="fas {{ $icon }} me-1"></i>{{ $label }}
</span>
