<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Notificationlist;
use App\Model\Tokens;
use DB;

class Cron extends Command
{
    
    protected $signature = 'cron:insert';

    
    protected $description = 'dummy insert into table';

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        
           $getdetails         = Tokens::where(['cron_status' => 0])->select('*')->get();
                    if($getdetails){
                    foreach($getdetails as $value){

                           
                            Notificationlist::create(array('user_id' => "0", 'message' => $value->token_symbol));
                            $update = Token::where('id', $value->id)->update(array('delete_status' => '1'));
                            exit;
                        }

                         $updatee = Token::where(['cron_status' => 1])->update(array('cron_status' => '0'));
                    }
           }
}
