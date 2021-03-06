<?php
/**
 * Created by PhpStorm.
 * User: heeyhome
 * Date: 2016/11/15
 * Time: 13:22
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class CollectshopController extends Controller
{
    public function collect(){
        $callback=rq('callback');
        $user_id=rq('user_id');
        $shop_id=rq('shop_id');
        $sel=DB::select('select id from hh_collection where collect_userid=? and iscollected_id=?',[$user_id,$shop_id]);
        if($sel){
            $arr = array("code" => "135",
                "msg" => "已经收藏过"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }else{
            $time=date("Y-m-d H:i:s", time());
            $insert=DB::insert('insert into hh_collection(collect_userid,iscollected_id,collect_time,collect_type) values (?,?,?,?)',[$user_id,$shop_id,$time,'shop']);
            if($insert){
                $arr = array("code" => "000",
                    "msg" => "收藏成功"
                );
                return $callback . "(" . HHJson($arr) . ")";
            }else{
                $arr = array("code" => "111",
                    "msg" => "收藏失败"
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        }
    }
    public function index(){
        $callback=rq('callback');
        $user_id=rq('user_id');
        $total=DB::select('select count(id) as total from hh_collection where collect_userid=? and collect_type=?',[$user_id,'shop']);
        $total=$total[0]->total;
        $newpage=new PageController();
        $offset=$newpage->page($total);
        /*收藏表和店铺表两表联查*/
        $select=DB::select('select hh_shop.shop_id,hh_shop.shop_name,hh_shop.authentication,hh_shop.shop_address,hh_shop.servicearea,hh_shop.shop_scan,hh_shop.shop_volume from hh_collection 
                    left join hh_shop on hh_collection.iscollected_id=hh_shop.shop_id where collect_userid=? and collect_type=? order by collect_time desc limit ?,?',[$user_id,'shop',$offset[0],$offset[1]]);
        foreach($select as $key=>$val){
            $img=DB::select('select shop_img from hh_shop_img where shop_id=? order by id desc',[$val->shop_id]);
            if($img){
                $select[$key]->img=$img[0]->shop_img;
            }else{
                $select[$key]->img=null;
            }
            $select[$key]->servicearea=explode(',',$val->servicearea);
            $authentication=explode(',',$val->authentication);
            $select[$key]->authentication=$authentication;
            $select[$key]->total=$total;
            $score=DB::select('select projectquality,serviceattitude,overallmerit from hh_score where shop_id=?',[$val->shop_id]);
            if($score){
                $select[$key]->shop_score=$score[0];
            }else{
                $select[$key]->shop_score=array("projectquality"=>10.0,
                    "serviceattitude"=>10.0,
                    "overallmerit"=>10.0
                );
            }

        }
        if($select){
            $arr = array("code" => "000",
                "data" => $select
            );
            return $callback . "(" . HHJson($arr) . ")";
        }else{
            $arr = array("code" => "117",
                "msg" => "信息不存在"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }
    public function del(){
        $callback=rq('callback');
        $user_id=rq('user_id');
        $shop_id=rq('shop_id');     //店铺id
        $del=DB::delete('delete from hh_collection where iscollected_id=? and  collect_userid=?',[$shop_id,$user_id]);
        if($del){
            $arr = array("code" => "000",
                "msg" => "删除成功"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }else{
            $arr = array("code" => "111",
                "msg" => "删除失败"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }
}