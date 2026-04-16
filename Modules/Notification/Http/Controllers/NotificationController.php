<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $notifications = Auth::user()->notifications;
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $notifications->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginated = new LengthAwarePaginator(
            $currentItems,
            $notifications->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('notification::index', ['notifications' => $paginated]);
    }

    public function read($id)
    {
        $notification = Auth::user()->notifications->find($id);
        if (! $notification) {
            flash()->warning('Notification introuvable ou déjà supprimée.');

            return redirect()->route('notifications.index');
        }

        $notification->markAsRead();
        $url = $notification->data['url'] ?? null;

        return $url ? redirect()->to($url) : redirect()->route('notifications.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('notification::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('notification::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('notification::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();

        return response()->json(['count' => $count]);
    }

    public function unreadList()
    {
        $notifications = Auth::user()->unreadNotifications->take(8);
        $html = view('notification::partials.dropdown', compact('notifications'))->render();

        return response()->json(['html' => $html]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        flash()->success('Toutes les notifications ont été marquées comme lues');

        return redirect()->route('notifications.index');
    }
}
