<?php

namespace App\Http\Controllers\v1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;

class Work extends BaseController
{
    /**
     * メンバー作成.
     *
     * @param Request $request
     * @return void
     */
    public function getAll(Request $request)
    {
        try {
            $ret = [];
            try {
                $ret['code'] = 0;
                $works = DB::table('work')->get();
                $ret['works'] = $works;
            } catch (\Exception $e) {
                $ret['code'] = 9;
                $ret['error'] = $e;
            }
        } catch (Exception $ex) {
        }
        return response()->json($ret);
    }
}
