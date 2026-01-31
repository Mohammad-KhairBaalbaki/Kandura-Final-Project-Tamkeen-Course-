<?php

namespace App\Http\Controllers;

use App\Services\FcmService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    protected $fcmService ;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function sendPushNotification(Request $request){
        $request->validate([
            'token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'required|array',
        ]);

        $token = $request->input('token');
        $title = $request->input('title');
        $body = $request->input('body');
        $data = $request->input('data',[]);

        $this->fcmService->sendNotification($token,$title,$body,$data);

        return $this->success(true,"Notification sent successfully",200);

    }

}
