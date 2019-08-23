<?php
namespace App\Http\Controllers;

use App\Events\NewMessageNotification;
use App\Http\Controllers\Controller;
use App\Models\AppNotificationModels\Notification;
use Illuminate\Support\Facades\Auth;
use App\Broadcasting\SendData;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $data = array('user_id' => $user_id);

        return resposnse()->json($data, 200);
    }

    public function send()
    {
        // ...

        // message is being sent
        $message = new Notification;
        $message->setAttribute('type', 'new message');
        $message->setAttribute('notifiable_type', 'message');
        $message->setAttribute('notifiable_id', Auth::user()->id);
        $message->setAttribute('data', 'This is notoficatopn');
        $message->save();

        // want to broadcast NewMessageNotification event
        broadcast(new SendData(Auth::user(),$message));

        // ...
    }
}
