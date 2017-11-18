<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MaterialType;
use App\Models\Material;

class MaterialController extends Controller
{
    public $materialModel;
    public $typeModel;

    public function __construct(Material $m, MaterialType $mt)
    {
        $this->materialModel = $m;
        $this->typeModel     = $mt;
    }

    /**
     * 首页
     */
    public function index()
    {
        $list = $this->typeModel::all();
        return view('service/material', [
            'title' => '官方培训资料',
            'list'  => $list
        ]);
    }

    /**
     * 资料列表
     */
    public function detail(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $typeId = $request->input('type_id', 0);
        if (!$typeId) {
            return redirect()->route('service_material');
        }
        $list = $this->materialModel->getList($typeId, $keyword);

        // 获取更多/分页数据 => 直接返回json
        if ($request->has('dataType') && $request->dataType == 'json') {
            return $list;
        }

        $type = $this->typeModel->find($typeId);
        return view('service/material_detail', [
            'title' => isset($type->name) ? $type->name : '',
            'list'  => $list,
            'type'  => $type,
            'keyword' => $keyword,
        ]);
    }
}
