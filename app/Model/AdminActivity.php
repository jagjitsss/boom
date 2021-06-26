<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    protected $table = 'ytivitca_nimda';

    protected $guarded = [];

    //To get permission ID for the URL requested by Admin
    public static function checkPermission($uri) {
    	$list = array('adminSettings'=>0,'updateSite'=>0,'userFromChart'=>-1,'depWithChart'=>-1,'profitChart'=>-1,'csv'=>1,'checkEmailExists'=>0,'loginHistory'=>0,'viewSubadmin'=>0,'subadminStatus'=>0,'updateSubadmin'=>0,'subadminEdit'=>0,'deleteSubadmin'=>0,'viewCms'=>6,'cmsEdit'=>6,'cmsUpdate'=>6,'viewEmail'=>5,'emailEdit'=>5,'emailUpdate'=>5,'viewMeta'=>7,'metaEdit'=>7,'metaUpdate'=>7,'viewContactUs'=>2,'contactReply'=>2,'updateContact'=>2,'viewSupportTicket'=>12,'ticketReply'=>12,'updateTicket'=>12,'viewSupportCategory'=>12,'supportCategoryDelete'=>12,'addSupportCategory'=>12,'userList'=>1,'viewReferral'=>1,'userStatus'=>1,'userTfaStatus'=>1,'userDetail'=>1,'verifyUserStatus'=>1,'viewBalance'=>1,'viewUserBalance'=>1,'viewUserBank'=>1,'viewUserBankDetail'=>1,'viewFaq'=>4,'faqStatus'=>4,'faqEdit'=>4,'faqUpdate'=>4,'faqDelete'=>4,'viewFaqCategory'=>4,'faqCategoryDelete'=>4,'addFaqCategory'=>4,'viewAdminBank'=>9,'bankEdit'=>9,'bankUpdate'=>9,'viewAdminProfit'=>9,'viewBlockIp'=>8,'ipAddrStatus'=>8,'ipAddrDelete'=>8,'addIpAddress'=>8,'viewDeposit'=>11,'viewUserDeposit'=>11,'confirmDeposit'=>11,'rejectDeposit'=>11,'checkConfirmCode'=>11,'updateConfirmDeposit'=>11,'updateRejectDeposit'=>11,'viewWithdraw'=>11,'viewUserWithdraw'=>11,'confirmWithdraw'=>11,'rejectWithdraw'=>11,'checkConfirmCodeWithdraw'=>11,'updateConfirmWithdraw'=>11,'updateRejectWithdraw'=>11,'viewfiatdeposit'=>11,'editfiatdeposit'=>11,'viewfiatwithdraw'=>11,'editfiatwithdraw'=>11,'confirmfiatWithdraw'=>11,'rejectfiatWithdraw'=>11,'csv'=>11,'viewTradePairs'=>10,'tradePairEdit'=>10,'tradePairUpdate'=>10,'viewTradeFee'=>10,'tradeFeeEdit'=>10,'tradeFeeUpdate'=>10,'viewOrderHistory'=>10,'viewTradeHistory'=>10,'viewNotice'=>3,'noticeStatus'=>3,'noticeEdit'=>3,'noticeUpdate'=>3,'noticeDelete'=>3,'viewCurrency'=>15,'currencyStatus'=>15,'editCurrency'=>15,'currencyUpdate'=>15,'newsletter'=>14,'newsletter_insert'=>14,'coinList'=>16,'viewNewcoin'=>16,'addtoken'=>17,'edittoken'=>17,'viewbanner'=>18,'addbanner'=>18,'bannerEdit'=>18,'deleteBanner'=>18);
        return $list[$uri];
    }
}
