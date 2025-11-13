@forelse($notifications as $notification)
    <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item">
        {!! $notification->data['message'] ?? 'Nouvelle notification' !!}
        <br>
        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
    </a>
@empty
    <span class="dropdown-item text-center">Aucune notification</span>
@endforelse
<div class="dropdown-divider"></div>
<a href="{{ route('notifications.index') }}" class="dropdown-item text-center">Voir toutes les notifications</a>
