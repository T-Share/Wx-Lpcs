<?php
/**
 *  TODO 邮箱验证接口
 * Created by PhpStorm.
 * User: pjw
 * Date: 2016/10/27
 * Time: 16:49
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class EmailVerifyController extends Controller
{
    public function verify(){
        $callback=rq('callback');
        $captcha=rq('captcha');
        $email = rq('email');
        /*检查用户和邮箱是否匹配*/
        $match=DB::select('select user_id from hh_user where user_email=?',[$email]);
        if ($match) {
            /*检查验证码*/
            if (!smsverify($email, $captcha)) {
                $arr = array("code" => "118",
                    "msg" => "验证码不正确"
                );
                return $callback . "(" . HHJson($arr) . ")";
            } else {
                $yxyzmsj = smsverify($email, $captcha);
            }
            if ((strtotime($yxyzmsj) + 1200) > time()) {
                $flag = create_pid();
                $time=date("Y-m-d H:i:s", time());
                $insert=DB::insert('insert into hh_token(userid,flag,time) values(?,?,?)',[$match[0]->user_id,$flag,$time]);
                if ($insert) {
                    $arr = array("code" => "000",
                        "msg" => "验证成功",
                        "data" => array("flag" => $flag)
                    );
                    return $callback . "(" . HHJson($arr) . ")";
                }else{
                    $arr = array("code" => "111",
                        "msg" => "验证失败"
                    );
                    return $callback . "(" . HHJson($arr) . ")";
                }
            } else {
                $arr = array("code" => "119",
                    "msg" => "验证码超时"
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        }
        else{
            $arr = array("code" => "126",
                "msg" => "邮箱和用户不匹配"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }
}