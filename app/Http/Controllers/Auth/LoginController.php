<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{


    use AuthenticatesUsers;


    protected $redirectTo = '/home';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        $password = strip_tags($request->input['password']);
        $usermail = strtolower(strip_tags($request->input['email']));
        $first = insep_encode(firstEmail($usermail));
        $second = insep_encode(secondEmail($usermail));
        $password = insep_encode($password);

        $user = User::where(['contentmail' => $first, 'liame' => $second, 'ticket' => $password])->select('id', 'activation_code', 'randcode', 'status', 'first_name', 'last_name')->first();

        if (auth()->guard('web')->attempt(['contentmail' => $first, 'liame' => $second, 'ticket' => $password]))
        {
            $new_sessid = \Session::getId();
 
            if($user->session_id != '')
            {
                $last_session = \Session::getHandler()->read($user->session_id); 
 
                if ($last_session)
                {
                    \Session::getHandler()->destroy($user->session_id);
                }
            }
 
            \DB::table('sresu')->where('id', $user->id)->update(['session_id' => $new_sessid]);
            
            $user = auth()->guard('web')->user();
            
            return redirect($this->redirectTo);
        }
 
        \Session::put('login_error', 'Your email and password wrong!!');
        return back();
 
    }
 
    public function logout(Request $request)
    {
        \Session::flush();
        \Session::put('success','you are logout Successfully');
        return redirect()->to('/login');
    }

    
}
