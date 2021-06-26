<?php 
require_once dirname(dirname(__FILE__)) . '/geetest/gt3-php-sdk-master/lib/class.geetestlib.php';
require_once dirname(dirname(__FILE__)) . '/geetest/gt3-php-sdk-master/config/config.php';
session_start();
$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
$data = array(
        "user_id" => $_SESSION['user_id'], 
        "client_type" => "web", 
        "ip_address" => "127.0.0.1" 
    );
if ($_SESSION['gtserver'] == 1) {   
    $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
    if ($result) {
        echo '{"status":"success"}';
    } else {
        echo '{"status":"fail"}';
    }
} else {  
    if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
        echo '{"status":"success"}';
    } else {
        echo '{"status":"fail"}';
    }
}
?>