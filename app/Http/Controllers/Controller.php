<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Closure;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function transaction(Closure $callback)
    {
        return DB::transaction($callback);
    }

    public function sendResponse($status = true, $message, $code = 200)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    public function sendResponseWithDatas($datas, $message, $wrapper = false, $code = 200)
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if($wrapper)
            $response = array_merge($response, $this->dataWrapper($datas->toArray()));
        else
            $response['data'] = $datas;

        return response()->json($response, $code);
    }

    protected function dataWrapper($data)
    {
        $results = [];
        if (isset($data['data'])) {
            $results['data'] = $data['data'];

            unset($data['data']);

            $results['meta'] = $data;
        } else
            $results['data'] = $data;

        return $results;
    }

}
