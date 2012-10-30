<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 30.10.12
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */
namespace x_youtube;
class Youtube extends \AbstractController {
    function init() {
        parent::init();
    }
    function get() {
//        foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&";
//     			$postdata.="cmd=_notify-validate";

        $curl = curl_init("https://gdata.youtube.com/feeds/api/users/bobeen2/uploads");
     			curl_setopt ($curl, CURLOPT_HEADER, 0);
     			curl_setopt ($curl, CURLOPT_POST, 1);
//     			curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
     			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
     			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
     			curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1);
     			$response = curl_exec ($curl);
     			curl_close ($curl);
    }
}