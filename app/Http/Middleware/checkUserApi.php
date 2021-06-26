<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;
use App\Model\User;
use Illuminate\Support\Facades\Input;

class checkUserApi
{

    public function handle($request, Closure $next)
    {
        $data = Input::all();
        $api_key = isset($data['api_key'])?$data['api_key']:'';
        $api_secret   = isset($data['api_secret'])?$data['api_secret']:'';
        if($api_key && $api_secret){
            $result = User::where(['api_key'=>$api_key,'api_secret'=>$api_secret])->count();
            if($result){
                return $next($request);
            }else{
                echo json_encode(array('status'=>'0','message'=>'Invalid Api credentials') , JSON_FORCE_OBJECT);
                exit;
            }
        }
        echo json_encode(array('status'=>'0','message'=>'Enter apikey and secretkey') , JSON_FORCE_OBJECT);
        exit;
    }
}
