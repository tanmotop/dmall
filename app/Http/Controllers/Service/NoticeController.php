<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notice;

class NoticeController extends Controller
{
    public $noticeModel;

    public function __construct(Notice $notice)
    {
        $this->noticeModel = $notice;
    }

    /**
     * 公告列表
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $list = $this->noticeModel->getList($keyword);

        // 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $list;
        }

        return view('service/notice', [
            'title' => '公告栏',
            'list'  => $list,
            'keyword' => $keyword,
        ]);
    }

    /**
     * 公告详情
     */
    public function detail(Request $request)
    {
        $id = $request->input('id', 0);
        $notice = $this->noticeModel->find($id);
        if (!$id || empty($notice)) {
            return redirect()->route('service_notice');
        }

        return view('service/notice_detail', [
            'title'   => $notice->title,
            'notice'  => $notice,
        ]);
    }
}
