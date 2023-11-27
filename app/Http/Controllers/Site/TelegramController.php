<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function updatedActivity()
    {
        $activity = Telegram::getUpdates(); 
        dd($activity);
    }

    public function sendMessage(Request $request)
    {
        $recruitment = Recruitment::where('status','!=',0)->get();
        $text = "Những việc làm có thể bạn quan tâm\n";
        foreach ($recruitment as $key => $value) {
            $text.= "<b>Công ty: </b>\n"
           .'<b>Vị trí tuyển dụng:</b>'
           ."<a href='tg://user?id=123456789'>inline mention of a user</a>";
        }

        Telegram::sendMessage([
            'chat_id' => -1001513307182,
            'parse_mode' => 'HTML',
            //'photo' => 'https://pbs.twimg.com/profile_images/890901007387025408/oztASP4n.jpg',
            'text' => $text
        ]);

        return dd("gui thanh cong");
    }
}
