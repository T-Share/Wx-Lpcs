<?php
/**
 * Created by PhpStorm.
 * User: heeyhome
 * Date: 2016/12/16
 * Time: 12:30
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class MaterialslistController extends Controller
{
    public function index(){
        $callback=rq('callback');
        $order_id=rq('order_id');
        $elematerialist=DB::select('select * from hh_materials_material_name_view where cate_id=? and if_show=?',[1,1]);
        $material_ids=DB::select('select material_id from hh_order_material where order_id=? and material_type=?',[$order_id,1]);
        //dd($num);
        foreach($elematerialist as $key=>$value) {
            $spec = DB::select('select spec_id,spec_name from hh_material_spec where material_id=?', [$value->material_id]);
            if ($spec) {
                foreach ($spec as $k => $v) {
                    $ids = DB::select('select id from hh_materials where spec_id=?', [$v->spec_id]);
                    if ($ids) {
                        $spec[$k]->id = $ids[0]->id;
                        $spec[$k]->num = 0;
                        if ($material_ids) {
                            $num = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                            foreach ($num as $ke => $item) {
                                if ($ids[0]->id == $item->material_name_id) {
                                    $spec[$k]->num = $item->material_num;
                                }
                            }
                        }
                    }
                }
                $elematerialist[$key]->spec = $spec;
            }else{
                $spec_id=0;
                $ids = DB::select('select id from hh_materials where spec_id=? and material_id=?', [$spec_id,$value->material_id]);
                $num = 0;
                if ($material_ids) {
                    $nums = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                    foreach ($nums as $ke => $item) {
                        if ($ids[0]->id == $item->material_name_id) {
                            $num = $item->material_num;
                        }
                    }
                }
                $elematerialist[$key]->spec =array('spec_id'=>0,
                   'id'=>$ids[0]->id ,
                'num'=>$num);
            }
        }
        $material_ids=DB::select('select material_id from hh_order_material where order_id=? and material_type=?',[$order_id,3]);
        $brickmaterialist=DB::select('select * from hh_materials_material_name_view where cate_id=? and if_show=?',[2,1]);
        foreach($brickmaterialist as $key=>$value){
            $spec=DB::select('select spec_id,spec_name from hh_material_spec where material_id=?',[$value->material_id]);
            if($spec) {
                foreach ($spec as $k => $v) {
                    $ids = DB::select('select id from hh_materials where spec_id=?', [$v->spec_id]);
                    if ($ids) {
                        $spec[$k]->id = $ids[0]->id;
                        $spec[$k]->num = 0;
                        if ($material_ids) {
                            $num = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                            foreach ($num as $ke => $item) {
                                if ($ids[0]->id == $item->material_name_id) {
                                    $spec[$k]->num = $item->material_num;
                                }
                            }
                        }
                    }
                }
                $brickmaterialist[$key]->spec = $spec;
            }else{
                $spec_id=0;
                $ids = DB::select('select id from hh_materials where spec_id=? and material_id=?', [$spec_id,$value->material_id]);
                $num = 0;
                if ($material_ids) {
                    $nums = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                    //dd($nums);
                    foreach ($nums as $ke => $item) {
                        if ($ids[0]->id == $item->material_name_id) {
                            $num = $item->material_num;
                        }
                    }
                }
                $brickmaterialist[$key]->spec =array('spec_id'=>0,
                    'id'=>$ids[0]->id ,
                    'num'=>$num);
            }
        }
        $material_ids=DB::select('select material_id from hh_order_material where order_id=? and material_type=?',[$order_id,4]);
        $woodmaterialist=DB::select('select * from hh_materials_material_name_view where cate_id=? and if_show=?',[3,1]);
        foreach($woodmaterialist as $key=>$value){
            $spec=DB::select('select spec_id,spec_name from hh_material_spec where material_id=?',[$value->material_id]);
            if($spec) {
                foreach ($spec as $k => $v) {
                    $ids = DB::select('select id from hh_materials where spec_id=?', [$v->spec_id]);
                    if ($ids) {
                        $spec[$k]->id = $ids[0]->id;
                        $spec[$k]->num = 0;
                        if ($material_ids) {
                            $num = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                            foreach ($num as $ke => $item) {
                                if ($ids[0]->id == $item->material_name_id) {
                                    $spec[$k]->num = $item->material_num;
                                }
                            }
                        }
                    }
                }
                $woodmaterialist[$key]->spec = $spec;
            }else{
                $spec_id=0;
                $ids = DB::select('select id from hh_materials where spec_id=? and material_id=?', [$spec_id,$value->material_id]);
                $num = 0;
                if ($material_ids) {
                    $nums = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                    foreach ($nums as $ke => $item) {
                        if ($ids[0]->id == $item->material_name_id) {
                            $num = $item->material_num;
                        }
                    }
                }
                $woodmaterialist[$key]->spec =array('spec_id'=>0,
                    'id'=>$ids[0]->id ,
                    'num'=>$num);
            }
        }
        $material_ids=DB::select('select material_id from hh_order_material where order_id=? and material_type=?',[$order_id,5]);
        $paintmaterialist=DB::select('select * from hh_materials_material_name_view where cate_id=? and if_show=?',[4,1]);
        foreach($paintmaterialist as $key=>$value){
            $spec=DB::select('select spec_id,spec_name from hh_material_spec where material_id=?',[$value->material_id]);
            if($spec) {
                foreach ($spec as $k => $v) {
                    $ids = DB::select('select id from hh_materials where spec_id=?', [$v->spec_id]);
                    if ($ids) {
                        $spec[$k]->id = $ids[0]->id;
                        $spec[$k]->num = 0;
                        if ($material_ids) {
                            $num = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                            foreach ($num as $ke => $item) {
                                if ($ids[0]->id == $item->material_name_id) {
                                    $spec[$k]->num = $item->material_num;
                                }
                            }
                        }
                    }
                }
                $paintmaterialist[$key]->spec = $spec;
            }else{
                $spec_id=0;
                $ids = DB::select('select id from hh_materials where spec_id=? and material_id=?', [$spec_id,$value->material_id]);
                $num = 0;
                if ($material_ids) {
                    $nums = DB::select('select material_num,material_name_id from hh_order_material_list where material_id=?', [$material_ids[0]->material_id]);
                    foreach ($nums as $ke => $item) {
                        if ($ids[0]->id == $item->material_name_id) {
                            $num = $item->material_num;
                        }
                    }
                }
                $paintmaterialist[$key]->spec =array('spec_id'=>0,
                    'id'=>$ids[0]->id ,
                'num'=>$num);
            }
        }
        $arr = array("code" => "000",
            "data" => array("ele"=>$elematerialist,
                        "brick"=>$brickmaterialist,
                        "wood"=>$woodmaterialist,
                        "paint"=>$paintmaterialist
                )
        );
        return $callback . "(" . HHJson($arr) . ")";
    }
}