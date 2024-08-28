<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return NotificationResource::collection(
            Auth::user()->notifications
        );
    }

    public function readAll(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'Notification marked as read'], 200);
    }
}
