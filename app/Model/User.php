<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'sresu';

    protected $guarded = [];

    

     //To validate forgot password request
    public static $forgotRule = array(
        'useremail' => array('required', 'email')
    );

    //validation rule for admin login
    public static $adminLoginRule = array(
        'username' => array('required', 'email'),
        'user_pwd' => 'required',
        'pattern_code' => 'required'
    );

    //get user profile picture for header
    public static function getProfile($id) {
        $user =  User::where('id',$id)->select('first_name','last_name')->first();
        if($user)
            return $user->first_name.' '.$user->last_name;
        else
            return '';
    }

    
    //get site details for footer
    public static function getSiteDetails() {
        $getSiteDetails = SiteSettings::where('id',1)->select('contact_email','contact_number','contact_address','city','state','country','fb_url','twitter_url','linkedin_url','googleplus_url', 'copy_right_text','skype_id')->first();
        return $getSiteDetails;
    }

    //get site logo for header
    public static function getSiteLogo() {
        $getSiteDetails = SiteSettings::where('id',1)->select('site_logo','site_favicon')->first();
        return $getSiteDetails;
    }

    //get meta details
    public static function getMetaDetails() {
        $currentRoute = \Route::getCurrentRoute()->getActionName();
        $explodeRoute = explode('@', $currentRoute);
        $uri = $explodeRoute[1];
        return MetaContent::where('link',$uri)->first();
    }

    //get user notification
    public static function getNotification($id) {
        $getDetails = UserNotification::where('user_id',$id)->orderBy('id','desc')->limit(10)->get();
        if($getDetails->isEmpty()) {
            return "";
        } else {
            return $getDetails;
        }
    }

    //get btc address of the user
    public static function getAddress($id,$coin) {
        $getAddress = CoinAddress::where('user_id',$id)->where('currency',$coin)->select('address')->orderBy('id','desc')->first();
        if($getAddress->count() == 0) {
            return "";
        } else {
            return $getAddress->address;
        }
    }

    //retrieve country list
    public static function getCountry() {
        return Country::all();
    }

    //Associations
    //associte with user activities
    public function activities() {
        return $this->hasMany('App\Model\UserActivity');
    }

    //associte with user bank
    public function bank() {
        return $this->hasOne('App\Model\UserBank');
    }

    //associte with user verification
    public function verification() {
        return $this->hasOne('App\Model\ConsumerVerification');
    }

    //associate with wallet
    public function wallet() {
        return $this->hasMany('App\Model\Wallet');
    }

    //associate with transactions
    public function transactions() {
        return $this->hasMany('App\Model\Transaction');
    }

    //associate with deposit
    public function deposits() {
        return $this->hasMany('App\Model\Deposit');
    }

    //associate with withdraw
    public function withdraws() {
        return $this->hasMany('App\Model\Withdraw');
    }

    //associate with coin address
    public function coinAddress() {
        return $this->hasMany('App\Model\CoinAddress');
    }

}
