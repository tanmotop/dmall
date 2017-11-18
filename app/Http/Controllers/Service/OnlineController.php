<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;

class OnlineController extends Controller
{
    public $msgModel;

    public function __construct(Message $msg)
    {
        $this->msgModel = $msg;
    }

    /**
     * 在线客服首页
     */
    public function index()
    {
        return view('service/online', ['title' => '客服中心']);
    }

    public function message()
    {
        return view('service/message', ['title' => '在线留言']);
    }

    public function submitMessage(Request $request)
    {
        $data = $request->all();
        if ($msg = $this->msgModel->create($data)) {
            return 1;
        } else {
            return 0;
        }
    }
}
