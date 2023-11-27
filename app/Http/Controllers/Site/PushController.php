<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PushNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class PushController extends Controller
{


    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = Auth::user();
        if (isset($user)) {
            $user->updatePushSubscription($endpoint, $key, $token);
        }

        return response()->json('subscribe success', 200);
    }

    public function push(Request $request)
    {
        $users = User::all();
        $user = Auth::user();
        $notices = Notification::send($users, new PushNotification($user, $request->message));
    }
}
