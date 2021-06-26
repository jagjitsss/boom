<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;
use App\Model\User;
use Illuminate\Support\Facades\Input;
class checkUserApp
{

    public function handle($request, Closure $next)
    {
        $data = Input::all();
        $user_id = isset($data['user_id'])?$data['user_id']:'';
        $token   = isset($data['token'])?$data['token']:'';
        if($user_id && $token){
            $result = User::where(['id'=>$user_id,'token'=>$token])->count();
            if($result){
                return $next($request);
            }else{
                echo json_encode(array('status'=>'0','message'=>'Invalid credentials') , JSON_FORCE_OBJECT);
                exit;
            }
        }
        echo json_encode(array('status'=>'0','message'=>'Enter user id and token') , JSON_FORCE_OBJECT);
        exit;
    }
}
