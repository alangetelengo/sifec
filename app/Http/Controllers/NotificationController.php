<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unreadList()
    {
        $user = auth()->user();
        $notifications = $user ? $user->unreadNotifications()->take(10)->get() : collect();

        $html = '';
        if ($notifications->count() === 0) {
            $html .= '<span class="dropdown-item text-center">Aucune notification non lue</span>';
        } else {
            foreach ($notifications as $notif) {
                $html .= '<a href="#" class="dropdown-item">'
                    . e($notif->data['message'] ?? 'Notification')
                    . '<br><small class="text-muted">' . $notif->created_at->diffForHumans() . '</small>'
                    . '</a>';
            }
            $html .= '<div class="dropdown-divider"></div>';
            $html .= '<a href="' . route('notifications.index') . '" class="dropdown-item text-center"><b>Voir toutes les notifications</b></a>';
        }

        return response()->json(['html' => $html]);
    }
}
