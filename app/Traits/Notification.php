<?php namespace App\Traits;

use Ghasedak\Laravel\GhasedakFacade;

trait Notification
{
   
    public function send_sms($param1, $param2)
    {
        $type = GhasedakFacade::VERIFY_MESSAGE_TEXT;
        $response = GhasedakFacade::setVerifyType($type)->Verify('09981284843', 'nikted', $param1, $param2);
    }

}