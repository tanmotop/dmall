<?php

namespace App\Http\Controllers\Finances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlowController extends Controller
{
    public function cost()
    {
        return view('finances/flow_cost', ['title' => '财务流水']);
    }

    public function charge()
    {
    	return view('finances/flow_charge', ['title' => '财务流水']);
    }
}
