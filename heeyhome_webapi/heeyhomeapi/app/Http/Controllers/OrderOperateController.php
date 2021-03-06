<?php
/**
 * TODO 订单操作
 * User: heeyhome
 * Date: 2016/11/30
 * Time: 14:01
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class OrderOperateController extends Controller
{

    //生成预算单
    public function generateActualListAndReckonList()
    {
        $order_id = rq('order_id');
        $list_data_json = rq('list_data_json');
        $callback = rq('callback');
        //存在list数据
        if ($list_data_json) {
            // $list_data_arr = json_decode($list_data_json, true);
            $list_dataArr = array_values(json_decode($list_data_json, true));
            $count = count($list_dataArr);
            $list_data_arr = array();
            $dataCount = 0;
            for ($i = 0; $i < 64; $i++) {
                if ($i < $count) {
                    $list_data_arr[$i] = $list_dataArr[$i];
                } else {
                    $list_data_arr[$i] = $dataCount;
                }
            }
            $list_data_exist = true;
        } else {
            $list_data_exist = false;
        }
        //查询订单是否存在，存在则获取装修人员id号
        $sel_order_tbl = DB::select('SELECT * FROM hh_order WHERE order_id = ?',
            [$order_id]);
        if (!$sel_order_tbl) {
            $arr = array(
                "code" => "200",
                "msg" => "订单号错误",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $order_personnel = $sel_order_tbl[0]->order_personnel;
        $shop_id = $sel_order_tbl[0]->shop_id;
        $sel_shop_price = DB::select('SELECT * FROM hh_shop_price WHERE shop_id = ?',
            [$shop_id]);
        //查询预算单是否存在
        $sel_order_tbl = DB::select('SELECT * FROM hh_order_reckon_list WHERE order_id = ? AND order_personnel = ?',
            [$order_id, $order_personnel]);
        if ($sel_order_tbl) {
            $arr = array(
                "code" => "200",
                "msg" => "预算单已经存在",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }

        //生成预算单与结算单表
        //预算
        if ($list_data_exist) {
            $reckon_list_tbl = DB::insert('INSERT INTO hh_order_reckon_list(order_id,order_personnel,foreman_price,service1,service2,service3,service4,service5,service6,service7,service8,
service9,service10,service11,service12,service13,service14,service15,service16,service17,service18,service19,service20,service21,service22,service23,service24,service25,service26,
service27,service28,service29,service30,service31,service32,service33,service34,service35,service36,service37,service38,service39,service40,service41,service42,service43,service44,
service45,service46,service47,service48,service49,service50,service51,service52,service53,service54,service55,service56,service57,service58,service59,service60,service61,service62,
service63,is_available) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                [$order_id, $order_personnel, $list_data_arr[63], $list_data_arr[0], $list_data_arr[1], $list_data_arr[2], $list_data_arr[3], $list_data_arr[4], $list_data_arr[5], $list_data_arr[6],
                    $list_data_arr[7], $list_data_arr[8], $list_data_arr[9], $list_data_arr[10], $list_data_arr[11], $list_data_arr[12], $list_data_arr[13], $list_data_arr[14],
                    $list_data_arr[15], $list_data_arr[16], $list_data_arr[17], $list_data_arr[18], $list_data_arr[19], $list_data_arr[20], $list_data_arr[21], $list_data_arr[22],
                    $list_data_arr[23], $list_data_arr[24], $list_data_arr[25], $list_data_arr[26], $list_data_arr[27], $list_data_arr[28], $list_data_arr[29], $list_data_arr[30],
                    $list_data_arr[31], $list_data_arr[32], $list_data_arr[33], $list_data_arr[34], $list_data_arr[35], $list_data_arr[36], $list_data_arr[37], $list_data_arr[38],
                    $list_data_arr[39], $list_data_arr[40], $list_data_arr[41], $list_data_arr[42], $list_data_arr[43], $list_data_arr[44], $list_data_arr[45], $list_data_arr[46],
                    $list_data_arr[47], $list_data_arr[48], $list_data_arr[49], $list_data_arr[50], $list_data_arr[51], $list_data_arr[52], $list_data_arr[53], $list_data_arr[54],
                    $list_data_arr[55], $list_data_arr[56], $list_data_arr[57], $list_data_arr[58], $list_data_arr[59], $list_data_arr[60], $list_data_arr[61], $list_data_arr[62], 1]);
            if (!$reckon_list_tbl) {
                $arr = array(
                    "code" => "200",
                    "msg" => "预算单生成失败",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        } else {
            $reckon_list_tbl = DB::insert('INSERT INTO hh_order_reckon_list(order_id,order_personnel,is_available) VALUES (?,?,?)',
                [$order_id, $order_personnel, 1]);
            if (!$reckon_list_tbl) {
                $arr = array(
                    "code" => "200",
                    "msg" => "预算单生成失败",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        }
        //结算
        //查看结算单是否存在
        $sel_reckon_list = DB::select('SELECT id FROM hh_order_actual_list WHERE order_id = ?',
            [$order_id]);
        if ($sel_reckon_list) {
            $arr = array(
                "code" => "200",
                "msg" => "预算单生成成功，结算单已存在",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        if ($list_data_exist) {
            $reckon_list_tbl = DB::insert('INSERT INTO hh_order_actual_list(order_id,order_personnel,foreman_price,service1,service2,service3,service4,service5,service6,service7,service8,
service9,service10,service11,service12,service13,service14,service15,service16,service17,service18,service19,service20,service21,service22,service23,service24,service25,service26,
service27,service28,service29,service30,service31,service32,service33,service34,service35,service36,service37,service38,service39,service40,service41,service42,service43,service44,
service45,service46,service47,service48,service49,service50,service51,service52,service53,service54,service55,service56,service57,service58,service59,service60,service61,service62,
service63,is_available) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                [$order_id, $order_personnel, $list_data_arr[63], $list_data_arr[0], $list_data_arr[1], $list_data_arr[2], $list_data_arr[3], $list_data_arr[4], $list_data_arr[5], $list_data_arr[6],
                    $list_data_arr[7], $list_data_arr[8], $list_data_arr[9], $list_data_arr[10], $list_data_arr[11], $list_data_arr[12], $list_data_arr[13], $list_data_arr[14],
                    $list_data_arr[15], $list_data_arr[16], $list_data_arr[17], $list_data_arr[18], $list_data_arr[19], $list_data_arr[20], $list_data_arr[21], $list_data_arr[22],
                    $list_data_arr[23], $list_data_arr[24], $list_data_arr[25], $list_data_arr[26], $list_data_arr[27], $list_data_arr[28], $list_data_arr[29], $list_data_arr[30],
                    $list_data_arr[31], $list_data_arr[32], $list_data_arr[33], $list_data_arr[34], $list_data_arr[35], $list_data_arr[36], $list_data_arr[37], $list_data_arr[38],
                    $list_data_arr[39], $list_data_arr[40], $list_data_arr[41], $list_data_arr[42], $list_data_arr[43], $list_data_arr[44], $list_data_arr[45], $list_data_arr[46],
                    $list_data_arr[47], $list_data_arr[48], $list_data_arr[49], $list_data_arr[50], $list_data_arr[51], $list_data_arr[52], $list_data_arr[53], $list_data_arr[54],
                    $list_data_arr[55], $list_data_arr[56], $list_data_arr[57], $list_data_arr[58], $list_data_arr[59], $list_data_arr[60], $list_data_arr[61], $list_data_arr[62], 1]);
            if (!$reckon_list_tbl) {
                //清除预算单
                $del_reckon_list_tbl = DB::delete('DELETE FROM hh_order_reckon_list WHERE order_id = ?',
                    [$order_id]);
                $arr = array(
                    "code" => "000",
                    "msg" => "结算单生成失败",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        } else {
            $actual_list_tbl = DB::insert('INSERT INTO hh_order_actual_list(order_id,order_personnel,is_available) VALUES (?,?,?)',
                [$order_id, $order_personnel, 1]);
            if (!$actual_list_tbl) {
                //清除预算单
                $del_reckon_list_tbl = DB::delete('DELETE FROM hh_order_reckon_list WHERE order_id = ?',
                    [$order_id]);
                $arr = array(
                    "code" => "200",
                    "msg" => "结算单生成失败",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        }
        $changeOrder_status = DB::update('update hh_order set order_status = 4 where order_id=?', [$order_id]);
        $ins_status_time = DB::insert('INSERT INTO hh_order_status_time (order_id, order_status) VALUES (?,?)',
            [$order_id, 4]);
        if ($list_data_exist) {
            //计算工价
            $gz = $list_data_arr[63] * $sel_shop_price[0]->foreman_price;
            $zg = ($list_data_arr[0] * $sel_shop_price[0]->service1);
            $zg += ($list_data_arr[1] * $sel_shop_price[0]->service2);
            $zg += ($list_data_arr[2] * $sel_shop_price[0]->service3);
            $zg += ($list_data_arr[3] * $sel_shop_price[0]->service4);
            $zg += ($list_data_arr[4] * $sel_shop_price[0]->service5);
            $zg += ($list_data_arr[5] * $sel_shop_price[0]->service6);
            $zg += ($list_data_arr[6] * $sel_shop_price[0]->service7);
            $zg += ($list_data_arr[7] * $sel_shop_price[0]->service8);
            $zg += ($list_data_arr[8] * $sel_shop_price[0]->service9);
            $zg += ($list_data_arr[9] * $sel_shop_price[0]->service10);
            $zg += ($list_data_arr[10] * $sel_shop_price[0]->service11);
            $zg += ($list_data_arr[11] * $sel_shop_price[0]->service12);
            $zg += ($list_data_arr[12] * $sel_shop_price[0]->service13);
            $zg += ($list_data_arr[13] * $sel_shop_price[0]->service14);
            $zg += ($list_data_arr[14] * $sel_shop_price[0]->service15);
            $zg += ($list_data_arr[15] * $sel_shop_price[0]->service16);
            $sdg = ($list_data_arr[16] * $sel_shop_price[0]->service17);
            $sdg += ($list_data_arr[17] * $sel_shop_price[0]->service18);
            $wg = ($list_data_arr[18] * $sel_shop_price[0]->service19);
            $wg += ($list_data_arr[19] * $sel_shop_price[0]->service20);
            $wg += ($list_data_arr[20] * $sel_shop_price[0]->service21);
            $wg += ($list_data_arr[21] * $sel_shop_price[0]->service22);
            $wg += ($list_data_arr[22] * $sel_shop_price[0]->service23);
            $wg += ($list_data_arr[23] * $sel_shop_price[0]->service24);
            $wg += ($list_data_arr[24] * $sel_shop_price[0]->service25);
            $wg += ($list_data_arr[25] * $sel_shop_price[0]->service26);
            $wg += ($list_data_arr[26] * $sel_shop_price[0]->service27);
            $wg += ($list_data_arr[27] * $sel_shop_price[0]->service28);
            $wg += ($list_data_arr[28] * $sel_shop_price[0]->service29);
            $wg += ($list_data_arr[29] * $sel_shop_price[0]->service30);
            $wg += ($list_data_arr[30] * $sel_shop_price[0]->service31);
            $wg += ($list_data_arr[31] * $sel_shop_price[0]->service32);
            $wg += ($list_data_arr[32] * $sel_shop_price[0]->service33);
            $wg += ($list_data_arr[33] * $sel_shop_price[0]->service34);
            $wg += ($list_data_arr[34] * $sel_shop_price[0]->service35);
            $wg += ($list_data_arr[35] * $sel_shop_price[0]->service36);
            $wg += ($list_data_arr[36] * $sel_shop_price[0]->service37);
            $wg += ($list_data_arr[37] * $sel_shop_price[0]->service38);
            $wg += ($list_data_arr[38] * $sel_shop_price[0]->service39);
            $wg += ($list_data_arr[39] * $sel_shop_price[0]->service40);
            $wg += ($list_data_arr[40] * $sel_shop_price[0]->service41);
            $mg = ($list_data_arr[41] * $sel_shop_price[0]->service42);
            $mg += ($list_data_arr[42] * $sel_shop_price[0]->service43);
            $mg += ($list_data_arr[43] * $sel_shop_price[0]->service44);
            $mg += ($list_data_arr[44] * $sel_shop_price[0]->service45);
            $mg += ($list_data_arr[45] * $sel_shop_price[0]->service46);
            $mg += ($list_data_arr[46] * $sel_shop_price[0]->service47);
            $mg += ($list_data_arr[47] * $sel_shop_price[0]->service48);
            $mg += ($list_data_arr[48] * $sel_shop_price[0]->service49);
            $mg += ($list_data_arr[49] * $sel_shop_price[0]->service50);
            $mg += ($list_data_arr[50] * $sel_shop_price[0]->service51);
            $mg += ($list_data_arr[51] * $sel_shop_price[0]->service52);
            $mg += ($list_data_arr[52] * $sel_shop_price[0]->service53);
            $yqg = ($list_data_arr[53] * $sel_shop_price[0]->service54);
            $yqg += ($list_data_arr[54] * $sel_shop_price[0]->service55);
            $yqg += ($list_data_arr[55] * $sel_shop_price[0]->service56);
            $yqg += ($list_data_arr[56] * $sel_shop_price[0]->service57);
            $yqg += ($list_data_arr[57] * $sel_shop_price[0]->service58);
            $yqg += ($list_data_arr[58] * $sel_shop_price[0]->service59);
            $yqg += ($list_data_arr[59] * $sel_shop_price[0]->service60);
            $yqg += ($list_data_arr[60] * $sel_shop_price[0]->service61);
            $yqg += ($list_data_arr[61] * $sel_shop_price[0]->service62);
            $yqg += ($list_data_arr[62] * $sel_shop_price[0]->service63);
            $sum = $gz + $zg + $sdg + $wg + $mg + $yqg;
            $sum_next = $gz + $zg + $sdg;
            //添加订单id
            /* 返回当前的毫秒时间戳(16位) */
            $mtime = explode(' ', microtime());
            $mtime[0] = ($mtime[0] + 1) * 1000000;
            $str1 = (string)$mtime[1];
            $str2 = substr((string)$mtime[0], 1);
            $str = $str1 . $str2 . rand(1000, 9999);
            $pay_id = $str;
            //添加预支付订单
            $ins_order_pay = DB::insert('INSERT INTO hh_order_pay(order_id,reckon_amount,actual_finish_amount,actual_next_amount,order_pay_step) VALUES (?,?,?,?,?)',
                [$order_id, $sum, 0, $sum_next, 1]);
            $ins_order_pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)',
                [$order_id, $pay_id, 1, 18, $gz, 1]);
            $ins_order_pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)',
                [$order_id, $pay_id, 11, 18, $zg, 1]);
            $ins_order_pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)',
                [$order_id, $pay_id, 2, 18, $sdg, 1]);
        }
        $arr = array(
            "code" => "000",
            "msg" => "生成成功",
            "data" => ""
        );
        return $callback . "(" . HHJson($arr) . ")";
    }

    public function getActualDataAndReckonData()
    {
        $shop_id = rq('shop_id');
        $callback = rq('callback');
        $sel_worker_service = DB::select('SELECT * FROM hh_worker_service_view');
        if ($sel_worker_service) {
            $arr = array(
                "code" => "000",
                "msg" => "获取成功",
                "data" => $sel_worker_service
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "200",
                "msg" => "获取失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //获取预算单结算单字段(分类)
    public function getActualDataAndReckonDataList()
    {
        $shop_id = rq('shop_id');
        $callback = rq('callback');
        $sel_work_item_unit = DB::select('SELECT unit FROM hh_workerprice_list');
        $sel_shop_price = DB::select('SELECT * FROM hh_shop_price WHERE shop_id = ?', [$shop_id]);
        $sel_worker_service1 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['杂工', '基础改造']);
        $sel_worker_service2 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['水电工', '水电改造']);
        $sel_worker_service3 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['瓦工', '贴砖']);
        $sel_worker_service4 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['瓦工', '砌墙']);
        $sel_worker_service5 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['瓦工', '粉刷']);
        $sel_worker_service6 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['木工', '家具制作（免漆板）']);
        $sel_worker_service7 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['木工', '吊顶、隔墙']);
        $sel_worker_service8 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['木工', '杂项']);
        $sel_worker_service9 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['油漆工', '天花墙面乳胶漆']);
        $sel_worker_service0 = DB::select('SELECT id,servicename,unit,cost FROM hh_worker_service_view WHERE category = ? AND serviceitem = ?', ['油漆工', '木质油漆工程']);
        if ($sel_work_item_unit && $sel_shop_price && $sel_worker_service1 && $sel_worker_service2 && $sel_worker_service3 && $sel_worker_service4 && $sel_worker_service5 && $sel_worker_service6 && $sel_worker_service7 && $sel_worker_service8 && $sel_worker_service9) {
            $data = array(
                "0" => array(
                    "name" => "工长",
                    "data_list" => $zadata = array(
                        array(
                            "name" => "工长费用",
                            "data_list" => array(array(
                                "id" => "1",
                                "servicename" => "管理费用",
                                "unit" => $sel_work_item_unit[63]->unit,
                                "cost" => $sel_shop_price[0]->foreman_price
                            )))
                    )),
                "1" => array(
                    "name" => "杂工",
                    "data_list" => $zadata = array(
                        array(
                            "name" => "基础改造",
                            "data_list" => $sel_worker_service1)
                    )),
                "2" => array(
                    "name" => "水电工",
                    "data_list" => $zadata = array(
                        array(
                            "name" => "水电改造",
                            "data_list" => $sel_worker_service2)
                    )),
                "3" => array(
                    "name" => "瓦工",
                    "data_list" => $zadata = array(
                        array(
                            "name" => "贴砖",
                            "data_list" => $sel_worker_service3),
                        array(
                            "name" => "砌墙",
                            "data_list" => $sel_worker_service4),
                        array(
                            "name" => "粉刷",
                            "data_list" => $sel_worker_service5)
                    )),
                "4" => array(
                    "name" => "木工",
                    "data_list" => $zadata = array(
                        array(
                            "name" => "家具制作（免漆板）",
                            "data_list" => $sel_worker_service6),
                        array(
                            "name" => "吊顶、隔墙",
                            "data_list" => $sel_worker_service7),
                        array(
                            "name" => "杂项",
                            "data_list" => $sel_worker_service8)
                    )),
                "5" => array(
                    "name" => "油漆工",
                    "data_list" => $zadata = array(
                        array(
                            "name" => "天花墙面乳胶漆",
                            "data_list" => $sel_worker_service9),
                        array(
                            "name" => "木质油漆工程",
                            "data_list" => $sel_worker_service0)
                    )),
            );
            $arr = array(
                "code" => "000",
                "msg" => "获取成功",
                "data" => $data
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "200",
                "msg" => "获取失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //添加预算单数据
    public function addActualDataAndReckonData()
    {
        $order_id = rq('order_id');
        $callback = rq('callback');
        $list_data_json = rq('list_data_json');
        $list_data_arr = json_decode($list_data_json, true);
        $remark = rq('remark');
        //查看订单是否存在
        $sel_order_tbl = DB::select('SELECT * FROM hh_order_reckon_list WHERE order_id = ?',
            [$order_id]);
        if (!$sel_order_tbl) {
            $arr = array(
                "code" => "200",
                "msg" => "预算单未生成",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $sel_order_tbl = DB::select('SELECT * FROM hh_order WHERE order_id = ?',
            [$order_id]);
        if (!$sel_order_tbl) {
            $arr = array(
                "code" => "200",
                "msg" => "订单号错误",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $shop_id = $sel_order_tbl[0]->shop_id;
        $sel_shop_price = DB::select('SELECT * FROM hh_shop_price WHERE shop_id = ?',
            [$shop_id]);
        //判断字段是否为空
        if (count($list_data_arr) < 64) {
            $arr = array(
                "code" => "200",
                "msg" => "缺少数据",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        //获取当前时间转化为mysql时间戳格式
        $timenow = strtotime(date('Y-m-d H:i:s', time()));
        //预算
        $reckon_list_tbl = DB::update('update hh_order_reckon_list set foreman_price = ?,service1 = ?,service2 = ?,service3 = ?,service4 = ?,service5 = ?,service6 = ?,service7 = ?,service8 = ?,service9 = ?,service10 = ?,service11 = ?,service12 = ?,service13 = ?,service14 = ?,service15 = ?,
service16 = ?,service17 = ? ,service18 = ? ,service19 = ? ,service20 = ? ,service21 = ? ,service22 = ? ,service23 = ? ,service24 = ? ,service25 = ? ,service26 = ? ,
service27 = ?,service28 = ? ,service29 = ? ,service30 = ? ,service31 = ? ,service32 = ? ,service33 = ? ,service34 = ? ,service35 = ? ,service36 = ? ,service37 = ? ,
service38 = ?,service39 = ? ,service40 = ? ,service41 = ? ,service42 = ? ,service43 = ? ,service44 = ? ,service45 = ? ,service46 = ? ,service47 = ? ,service48 = ? ,
service49 = ?,service50 = ?,service51 = ? ,service52 = ? ,service53 = ? ,service54 = ? ,service55 = ? ,service56 = ? ,service57 = ? ,service58 = ? ,service59 = ? ,
service60 = ? ,service61 = ? ,service62 = ? ,service63 = ? ,is_available = ? ,remark = ? WHERE order_id = ?',
            [$list_data_arr[63], $list_data_arr[0], $list_data_arr[1], $list_data_arr[2], $list_data_arr[3], $list_data_arr[4], $list_data_arr[5], $list_data_arr[6], $list_data_arr[7],
                $list_data_arr[8], $list_data_arr[9], $list_data_arr[10], $list_data_arr[11], $list_data_arr[12], $list_data_arr[13], $list_data_arr[14], $list_data_arr[15],
                $list_data_arr[16], $list_data_arr[17], $list_data_arr[18], $list_data_arr[19], $list_data_arr[20], $list_data_arr[21], $list_data_arr[22], $list_data_arr[23],
                $list_data_arr[24], $list_data_arr[25], $list_data_arr[26], $list_data_arr[27], $list_data_arr[28], $list_data_arr[29], $list_data_arr[30], $list_data_arr[31],
                $list_data_arr[32], $list_data_arr[33], $list_data_arr[34], $list_data_arr[35], $list_data_arr[36], $list_data_arr[37], $list_data_arr[38], $list_data_arr[39],
                $list_data_arr[40], $list_data_arr[41], $list_data_arr[42], $list_data_arr[43], $list_data_arr[44], $list_data_arr[45], $list_data_arr[46], $list_data_arr[47],
                $list_data_arr[48], $list_data_arr[49], $list_data_arr[50], $list_data_arr[51], $list_data_arr[52], $list_data_arr[53], $list_data_arr[54], $list_data_arr[55],
                $list_data_arr[56], $list_data_arr[57], $list_data_arr[58], $list_data_arr[59], $list_data_arr[60], $list_data_arr[61], $list_data_arr[62], 0, $remark, $order_id]);
        //结算
        $actual_list_tbl = DB::update('UPDATE hh_order_actual_list SET foreman_price = ?, service1 = ? ,service2 = ? ,service3 = ? ,service4 = ? ,
service5 = ? ,service6 = ? ,service7 = ? ,service8 = ? ,service9 = ? ,service10 = ? ,service11 = ? ,service12 = ? ,service13 = ? ,service14 = ? ,service15 = ? ,
service16 = ? ,service17 = ? ,service18 = ? ,service19 = ? ,service20 = ? ,service21 = ? ,service22 = ? ,service23 = ? ,service24 = ? ,service25 = ? ,service26 = ? ,
service27 = ? ,service28 = ? ,service29 = ? ,service30 = ? ,service31 = ? ,service32 = ? ,service33 = ? ,service34 = ? ,service35 = ? ,service36 = ? ,service37 = ? ,
service38 = ? ,service39 = ? ,service40 = ? ,service41 = ? ,service42 = ? ,service43 = ? ,service44 = ? ,service45 = ? ,service46 = ? ,service47 = ? ,service48 = ? ,
service49 = ? ,service50 = ? ,service51 = ? ,service52 = ? ,service53 = ? ,service54 = ? ,service55 = ? ,service56 = ? ,service57 = ? ,service58 = ? ,service59 = ? ,
service60 = ? ,service61 = ? ,service62 = ? ,service63 = ? ,is_available = ? ,remark = ? ,update_time = ? WHERE order_id = ?',
            [$list_data_arr[63], $list_data_arr[0], $list_data_arr[1], $list_data_arr[2], $list_data_arr[3], $list_data_arr[4], $list_data_arr[5], $list_data_arr[6], $list_data_arr[7],
                $list_data_arr[8], $list_data_arr[9], $list_data_arr[10], $list_data_arr[11], $list_data_arr[12], $list_data_arr[13], $list_data_arr[14], $list_data_arr[15],
                $list_data_arr[16], $list_data_arr[17], $list_data_arr[18], $list_data_arr[19], $list_data_arr[20], $list_data_arr[21], $list_data_arr[22], $list_data_arr[23],
                $list_data_arr[24], $list_data_arr[25], $list_data_arr[26], $list_data_arr[27], $list_data_arr[28], $list_data_arr[29], $list_data_arr[30], $list_data_arr[31],
                $list_data_arr[32], $list_data_arr[33], $list_data_arr[34], $list_data_arr[35], $list_data_arr[36], $list_data_arr[37], $list_data_arr[38], $list_data_arr[39],
                $list_data_arr[40], $list_data_arr[41], $list_data_arr[42], $list_data_arr[43], $list_data_arr[44], $list_data_arr[45], $list_data_arr[46], $list_data_arr[47],
                $list_data_arr[48], $list_data_arr[49], $list_data_arr[50], $list_data_arr[51], $list_data_arr[52], $list_data_arr[53], $list_data_arr[54], $list_data_arr[55],
                $list_data_arr[56], $list_data_arr[57], $list_data_arr[58], $list_data_arr[59], $list_data_arr[60], $list_data_arr[61], $list_data_arr[62], 1, $remark, $timenow, $order_id]);

        //计算工价
        $gz = $list_data_arr[63] * $sel_shop_price[0]->foreman_price;
        $zg = ($list_data_arr[0] * $sel_shop_price[0]->service1);
        $zg += ($list_data_arr[1] * $sel_shop_price[0]->service2);
        $zg += ($list_data_arr[2] * $sel_shop_price[0]->service3);
        $zg += ($list_data_arr[3] * $sel_shop_price[0]->service4);
        $zg += ($list_data_arr[4] * $sel_shop_price[0]->service5);
        $zg += ($list_data_arr[5] * $sel_shop_price[0]->service6);
        $zg += ($list_data_arr[6] * $sel_shop_price[0]->service7);
        $zg += ($list_data_arr[7] * $sel_shop_price[0]->service8);
        $zg += ($list_data_arr[8] * $sel_shop_price[0]->service9);
        $zg += ($list_data_arr[9] * $sel_shop_price[0]->service10);
        $zg += ($list_data_arr[10] * $sel_shop_price[0]->service11);
        $zg += ($list_data_arr[11] * $sel_shop_price[0]->service12);
        $zg += ($list_data_arr[12] * $sel_shop_price[0]->service13);
        $zg += ($list_data_arr[13] * $sel_shop_price[0]->service14);
        $zg += ($list_data_arr[14] * $sel_shop_price[0]->service15);
        $zg += ($list_data_arr[15] * $sel_shop_price[0]->service16);
        $sdg = ($list_data_arr[16] * $sel_shop_price[0]->service17);
        $sdg += ($list_data_arr[17] * $sel_shop_price[0]->service18);
        $wg = ($list_data_arr[18] * $sel_shop_price[0]->service19);
        $wg += ($list_data_arr[19] * $sel_shop_price[0]->service20);
        $wg += ($list_data_arr[20] * $sel_shop_price[0]->service21);
        $wg += ($list_data_arr[21] * $sel_shop_price[0]->service22);
        $wg += ($list_data_arr[22] * $sel_shop_price[0]->service23);
        $wg += ($list_data_arr[23] * $sel_shop_price[0]->service24);
        $wg += ($list_data_arr[24] * $sel_shop_price[0]->service25);
        $wg += ($list_data_arr[25] * $sel_shop_price[0]->service26);
        $wg += ($list_data_arr[26] * $sel_shop_price[0]->service27);
        $wg += ($list_data_arr[27] * $sel_shop_price[0]->service28);
        $wg += ($list_data_arr[28] * $sel_shop_price[0]->service29);
        $wg += ($list_data_arr[29] * $sel_shop_price[0]->service30);
        $wg += ($list_data_arr[30] * $sel_shop_price[0]->service31);
        $wg += ($list_data_arr[31] * $sel_shop_price[0]->service32);
        $wg += ($list_data_arr[32] * $sel_shop_price[0]->service33);
        $wg += ($list_data_arr[33] * $sel_shop_price[0]->service34);
        $wg += ($list_data_arr[34] * $sel_shop_price[0]->service35);
        $wg += ($list_data_arr[35] * $sel_shop_price[0]->service36);
        $wg += ($list_data_arr[36] * $sel_shop_price[0]->service37);
        $wg += ($list_data_arr[37] * $sel_shop_price[0]->service38);
        $wg += ($list_data_arr[38] * $sel_shop_price[0]->service39);
        $wg += ($list_data_arr[39] * $sel_shop_price[0]->service40);
        $wg += ($list_data_arr[40] * $sel_shop_price[0]->service41);
        $mg = ($list_data_arr[41] * $sel_shop_price[0]->service42);
        $mg += ($list_data_arr[42] * $sel_shop_price[0]->service43);
        $mg += ($list_data_arr[43] * $sel_shop_price[0]->service44);
        $mg += ($list_data_arr[44] * $sel_shop_price[0]->service45);
        $mg += ($list_data_arr[45] * $sel_shop_price[0]->service46);
        $mg += ($list_data_arr[46] * $sel_shop_price[0]->service47);
        $mg += ($list_data_arr[47] * $sel_shop_price[0]->service48);
        $mg += ($list_data_arr[48] * $sel_shop_price[0]->service49);
        $mg += ($list_data_arr[49] * $sel_shop_price[0]->service50);
        $mg += ($list_data_arr[50] * $sel_shop_price[0]->service51);
        $mg += ($list_data_arr[51] * $sel_shop_price[0]->service52);
        $mg += ($list_data_arr[52] * $sel_shop_price[0]->service53);
        $yqg = ($list_data_arr[53] * $sel_shop_price[0]->service54);
        $yqg += ($list_data_arr[54] * $sel_shop_price[0]->service55);
        $yqg += ($list_data_arr[55] * $sel_shop_price[0]->service56);
        $yqg += ($list_data_arr[56] * $sel_shop_price[0]->service57);
        $yqg += ($list_data_arr[57] * $sel_shop_price[0]->service58);
        $yqg += ($list_data_arr[58] * $sel_shop_price[0]->service59);
        $yqg += ($list_data_arr[59] * $sel_shop_price[0]->service60);
        $yqg += ($list_data_arr[60] * $sel_shop_price[0]->service61);
        $yqg += ($list_data_arr[61] * $sel_shop_price[0]->service62);
        $yqg += ($list_data_arr[62] * $sel_shop_price[0]->service63);
        $sum = $gz + $zg + $sdg + $wg + $mg + $yqg;
        $sum_next = $gz + $zg + $sdg;
        //添加订单id
        /* 返回当前的毫秒时间戳(16位) */
        $mtime = explode(' ', microtime());
        $mtime[0] = ($mtime[0] + 1) * 1000000;
        $str1 = (string)$mtime[1];
        $str2 = substr((string)$mtime[0], 1);
        $str = $str1 . $str2 . rand(100, 999);
        $pay_id = (int)$str;
        //添加预支付订单
        $ins_order_pay = DB::insert('INSERT INTO hh_order_pay(order_id,reckon_amount,actual_finish_amount,actual_next_amount,order_pay_step) VALUES (?,?,?,?,?)',
            [$order_id, $sum, 0, $sum_next, 1]);
        $ins_order_pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)',
            [$order_id, $pay_id, 1, 18, $gz, 1]);
        $ins_order_pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)',
            [$order_id, $pay_id, 11, 18, $zg, 1]);
        $ins_order_pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)',
            [$order_id, $pay_id, 2, 18, $sdg, 1]);
        $arr = array(
            "code" => "000",
            "msg" => "插入成功",
            "data" => ""
        );
        return $callback . "(" . HHJson($arr) . ")";
    }

    //修改结算单数据
    public function updateActualData()
    {
        $order_id = rq('order_id');
        $callback = rq('callback');
        $list_data_json = rq('list_data_json');
        $list_dataArr = array_values(json_decode($list_data_json, true));
        $remark = rq('remark');
        if (!$remark) {
            $remark = '';
        }
        //判断结算单阶段是否更改表字段是否存在
        $sel_order_actual_isclick = DB::select('SELECT * FROM hh_order_actual_isclick WHERE order_id = ?', [$order_id]);
        if (!$sel_order_actual_isclick) {
            //不存在则插入字段
            $ins_order_actual_isclick = DB::insert('INSERT INTO hh_order_actual_isclick(order_id) VALUES (?)', [$order_id]);
        }
        $count = count($list_dataArr);
        $list_data_arr = array();
        $dataCount = 0;
        for ($i = 0; $i < 64; $i++) {
            if ($i < $count) {
                array_push($list_data_arr, $list_dataArr[$i]);
            } else {
                array_push($list_data_arr, $dataCount);
            }
        }


        //查看订单是否存在
        $sel_order_tbl = DB::select('SELECT * FROM hh_order_reckon_list WHERE order_id = ?',
            [$order_id]);
        if (!$sel_order_tbl) {
            $arr = array(
                "code" => "200",
                "msg" => "订单号错误",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }

        $shop_id = DB::SELECT('SELECT shop_id FROM hh_order WHERE order_id = ?', [$order_id]);
        $sel_shop_price = DB::select('SELECT * FROM hh_shop_price WHERE shop_id = ?',
            [$shop_id[0]->shop_id]);
        $yu_price = DB::SELECT('SELECT * FROM hh_order_reckon_list WHERE order_id = ?', [$order_id]);
        //添加订单id
        /* 返回当前的毫秒时间戳(16位) */
        $mtime = explode(' ', microtime());
        $mtime[0] = ($mtime[0] + 1) * 1000000;
        $str1 = (string)$mtime[1];
        $str2 = substr((string)$mtime[0], 1);
        $str = $str1 . $str2 . rand(1000, 9999);
        $pay_id = $str;
        //获取当前时间转化为mysql时间戳格式
        $timenow = strtotime(date('Y-m-d H:i:s', time()));
        //判断订单步骤 控制结算单更改字段
        //获取订单步骤
        $sel_order_step = DB::select('SELECT order_step FROM hh_order WHERE order_id = ?',
            [$order_id]);
        if ($sel_order_step) {
            $order_step = $sel_order_step[0]->order_step;
            switch ($order_step) {
                case 4:
                case 5:
                    //杂工及水电工结算单数据修改
                    $actual_list_tbl = DB::update('update hh_order_actual_list SET service1 = ? ,service2 = ? ,service3 = ? ,service4 = ? ,
service5 = ? ,service6 = ? ,service7 = ? ,service8 = ? ,service9 = ? ,service10 = ? ,service11 = ? ,service12 = ? ,service13 = ? ,service14 = ? ,service15 = ? ,
service16 = ? ,service17 = ? ,service18 = ? ,remark = ? ,update_time = ? ,is_available = 0 WHERE order_id = ?',
                        [$list_data_arr[0], $list_data_arr[1], $list_data_arr[2], $list_data_arr[3], $list_data_arr[4], $list_data_arr[5], $list_data_arr[6], $list_data_arr[7],
                            $list_data_arr[8], $list_data_arr[9], $list_data_arr[10], $list_data_arr[11], $list_data_arr[12], $list_data_arr[13], $list_data_arr[14], $list_data_arr[15],
                            $list_data_arr[16], $list_data_arr[17], $remark, $timenow, $order_id]);
                    $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage1 = ? WHERE order_id = ?', [1, $order_id]);
                    $gz = ($list_data_arr[63] - $yu_price[0]->foreman_price) * $sel_shop_price[0]->foreman_price;
                    $zg = ($list_data_arr[0] - $yu_price[0]->service1) * ($sel_shop_price[0]->service1);
                    $zg += ($list_data_arr[1] - $yu_price[0]->service2) * ($sel_shop_price[0]->service2);
                    $zg += ($list_data_arr[2] - $yu_price[0]->service3) * ($sel_shop_price[0]->service3);
                    $zg += ($list_data_arr[3] - $yu_price[0]->service4) * ($sel_shop_price[0]->service4);
                    $zg += ($list_data_arr[4] - $yu_price[0]->service5) * ($sel_shop_price[0]->service5);
                    $zg += ($list_data_arr[5] - $yu_price[0]->service6) * ($sel_shop_price[0]->service6);
                    $zg += ($list_data_arr[6] - $yu_price[0]->service7) * ($sel_shop_price[0]->service7);
                    $zg += ($list_data_arr[7] - $yu_price[0]->service8) * ($sel_shop_price[0]->service8);
                    $zg += ($list_data_arr[8] - $yu_price[0]->service9) * ($sel_shop_price[0]->service9);
                    $zg += ($list_data_arr[9] - $yu_price[0]->service10) * ($sel_shop_price[0]->service10);
                    $zg += ($list_data_arr[10] - $yu_price[0]->service11) * ($sel_shop_price[0]->service11);
                    $zg += ($list_data_arr[11] - $yu_price[0]->service12) * ($sel_shop_price[0]->service12);
                    $zg += ($list_data_arr[12] - $yu_price[0]->service13) * ($sel_shop_price[0]->service13);
                    $zg += ($list_data_arr[13] - $yu_price[0]->service14) * ($sel_shop_price[0]->service14);
                    $zg += ($list_data_arr[14] - $yu_price[0]->service15) * ($sel_shop_price[0]->service15);
                    $zg += ($list_data_arr[15] - $yu_price[0]->service16) * ($sel_shop_price[0]->service16);
                    $sdg = ($list_data_arr[16] - $yu_price[0]->service17) * ($sel_shop_price[0]->service17);
                    $sdg += ($list_data_arr[17] - $yu_price[0]->service18) * ($sel_shop_price[0]->service18);
                    $sum_next = $zg + $sdg + $gz;

                    $pay_amount1 = $sum_next;

                    $pay_amount2 = ($yu_price[0]->service19 * $sel_shop_price[0]->service19);
                    $pay_amount2 += ($yu_price[0]->service20 * $sel_shop_price[0]->service20);
                    $pay_amount2 += ($yu_price[0]->service21 * $sel_shop_price[0]->service21);
                    $pay_amount2 += ($yu_price[0]->service22 * $sel_shop_price[0]->service22);
                    $pay_amount2 += ($yu_price[0]->service23 * $sel_shop_price[0]->service23);
                    $pay_amount2 += ($yu_price[0]->service24 * $sel_shop_price[0]->service24);
                    $pay_amount2 += ($yu_price[0]->service25 * $sel_shop_price[0]->service25);
                    $pay_amount2 += ($yu_price[0]->service26 * $sel_shop_price[0]->service26);
                    $pay_amount2 += ($yu_price[0]->service27 * $sel_shop_price[0]->service27);
                    $pay_amount2 += ($yu_price[0]->service28 * $sel_shop_price[0]->service28);
                    $pay_amount2 += ($yu_price[0]->service29 * $sel_shop_price[0]->service29);
                    $pay_amount2 += ($yu_price[0]->service30 * $sel_shop_price[0]->service30);
                    $pay_amount2 += ($yu_price[0]->service31 * $sel_shop_price[0]->service31);
                    $pay_amount2 += ($yu_price[0]->service32 * $sel_shop_price[0]->service32);
                    $pay_amount2 += ($yu_price[0]->service33 * $sel_shop_price[0]->service33);
                    $pay_amount2 += ($yu_price[0]->service34 * $sel_shop_price[0]->service34);
                    $pay_amount2 += ($yu_price[0]->service35 * $sel_shop_price[0]->service35);
                    $pay_amount2 += ($yu_price[0]->service36 * $sel_shop_price[0]->service36);
                    $pay_amount2 += ($yu_price[0]->service37 * $sel_shop_price[0]->service37);
                    $pay_amount2 += ($yu_price[0]->service38 * $sel_shop_price[0]->service38);
                    $pay_amount2 += ($yu_price[0]->service39 * $sel_shop_price[0]->service39);
                    $pay_amount2 += ($yu_price[0]->service40 * $sel_shop_price[0]->service40);
                    $pay_amount2 += ($yu_price[0]->service41 * $sel_shop_price[0]->service41);
                    $sum_next += $pay_amount2;

                    $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                    if ($order_pay) {
                        $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                        $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $sum_next, 2, $order_id]);
                        $pay_each1 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 5, $pay_amount1, 1]);
                        $pay_each2 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 3, 5, $pay_amount2, 1]);
                    }
                    break;
                case 8:
                case 9:
                    //瓦工结算单数据修改
                    $actual_list_tbl = DB::update('UPDATE hh_order_actual_list SET service19 = ? ,service20 = ? ,service21 = ? ,service22 = ? ,service23 = ? ,service24 = ? ,service25 = ? ,service26 = ? ,
service27 = ? ,service28 = ? ,service29 = ? ,service30 = ? ,service31 = ? ,service32 = ? ,service33 = ? ,service34 = ? ,service35 = ? ,service36 = ? ,service37 = ? ,
service38 = ? ,service39 = ? ,service40 = ? ,service41 = ? ,remark = ? ,update_time = ? ,is_available = 0 WHERE order_id = ?',
                        [$list_data_arr[18], $list_data_arr[19], $list_data_arr[20], $list_data_arr[21], $list_data_arr[22], $list_data_arr[23],
                            $list_data_arr[24], $list_data_arr[25], $list_data_arr[26], $list_data_arr[27], $list_data_arr[28], $list_data_arr[29], $list_data_arr[30], $list_data_arr[31],
                            $list_data_arr[32], $list_data_arr[33], $list_data_arr[34], $list_data_arr[35], $list_data_arr[36], $list_data_arr[37], $list_data_arr[38], $list_data_arr[39],
                            $list_data_arr[40], $remark, $timenow, $order_id]);
                    $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage2 = ? WHERE order_id = ?', [1, $order_id]);
                    $wg = ($list_data_arr[18] - $yu_price[0]->service19) * ($sel_shop_price[0]->service19);
                    $wg += ($list_data_arr[19] - $yu_price[0]->service20) * ($sel_shop_price[0]->service20);
                    $wg += ($list_data_arr[20] - $yu_price[0]->service21) * ($sel_shop_price[0]->service21);
                    $wg += ($list_data_arr[21] - $yu_price[0]->service22) * ($sel_shop_price[0]->service22);
                    $wg += ($list_data_arr[22] - $yu_price[0]->service23) * ($sel_shop_price[0]->service23);
                    $wg += ($list_data_arr[23] - $yu_price[0]->service24) * ($sel_shop_price[0]->service24);
                    $wg += ($list_data_arr[24] - $yu_price[0]->service25) * ($sel_shop_price[0]->service25);
                    $wg += ($list_data_arr[25] - $yu_price[0]->service26) * ($sel_shop_price[0]->service26);
                    $wg += ($list_data_arr[26] - $yu_price[0]->service27) * ($sel_shop_price[0]->service27);
                    $wg += ($list_data_arr[27] - $yu_price[0]->service28) * ($sel_shop_price[0]->service28);
                    $wg += ($list_data_arr[28] - $yu_price[0]->service29) * ($sel_shop_price[0]->service29);
                    $wg += ($list_data_arr[29] - $yu_price[0]->service30) * ($sel_shop_price[0]->service30);
                    $wg += ($list_data_arr[30] - $yu_price[0]->service31) * ($sel_shop_price[0]->service31);
                    $wg += ($list_data_arr[31] - $yu_price[0]->service32) * ($sel_shop_price[0]->service32);
                    $wg += ($list_data_arr[32] - $yu_price[0]->service33) * ($sel_shop_price[0]->service33);
                    $wg += ($list_data_arr[33] - $yu_price[0]->service34) * ($sel_shop_price[0]->service34);
                    $wg += ($list_data_arr[34] - $yu_price[0]->service35) * ($sel_shop_price[0]->service35);
                    $wg += ($list_data_arr[35] - $yu_price[0]->service36) * ($sel_shop_price[0]->service36);
                    $wg += ($list_data_arr[36] - $yu_price[0]->service37) * ($sel_shop_price[0]->service37);
                    $wg += ($list_data_arr[37] - $yu_price[0]->service38) * ($sel_shop_price[0]->service38);
                    $wg += ($list_data_arr[38] - $yu_price[0]->service39) * ($sel_shop_price[0]->service39);
                    $wg += ($list_data_arr[39] - $yu_price[0]->service40) * ($sel_shop_price[0]->service40);
                    $wg += ($list_data_arr[40] - $yu_price[0]->service41) * ($sel_shop_price[0]->service41);
                    $pay_amount1 = $wg;

                    $pay_amount2 = ($yu_price[0]->service42 * $sel_shop_price[0]->service42);
                    $pay_amount2 += ($yu_price[0]->service43 * $sel_shop_price[0]->service43);
                    $pay_amount2 += ($yu_price[0]->service44 * $sel_shop_price[0]->service44);
                    $pay_amount2 += ($yu_price[0]->service45 * $sel_shop_price[0]->service45);
                    $pay_amount2 += ($yu_price[0]->service46 * $sel_shop_price[0]->service46);
                    $pay_amount2 += ($yu_price[0]->service47 * $sel_shop_price[0]->service47);
                    $pay_amount2 += ($yu_price[0]->service48 * $sel_shop_price[0]->service48);
                    $pay_amount2 += ($yu_price[0]->service49 * $sel_shop_price[0]->service49);
                    $pay_amount2 += ($yu_price[0]->service50 * $sel_shop_price[0]->service50);
                    $pay_amount2 += ($yu_price[0]->service51 * $sel_shop_price[0]->service51);
                    $pay_amount2 += ($yu_price[0]->service52 * $sel_shop_price[0]->service52);
                    $pay_amount2 += ($yu_price[0]->service53 * $sel_shop_price[0]->service53);
                    $wg += $pay_amount2;

                    $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                    if ($order_pay) {
                        $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                        $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $wg, 3, $order_id]);
                        $pay_each1 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 9, $pay_amount1, 1]);
                        $pay_each2 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 4, 9, $pay_amount2, 1]);
                    }
                    break;
                case 12:
                case 13:
                    //木工结算单数据修改
                    $actual_list_tbl = DB::update('UPDATE hh_order_actual_list SET service42 = ? ,service43 = ? ,service44 = ? ,service45 = ? ,service46 = ? ,service47 = ? ,service48 = ? ,
service49 = ? ,service50 = ? ,service51 = ? ,service52 = ? ,service53 = ? ,remark = ? ,update_time = ? ,is_available = 0 WHERE order_id = ?',
                        [$list_data_arr[41], $list_data_arr[42], $list_data_arr[43], $list_data_arr[44], $list_data_arr[45], $list_data_arr[46], $list_data_arr[47],
                            $list_data_arr[48], $list_data_arr[49], $list_data_arr[50], $list_data_arr[51], $list_data_arr[52], $remark, $timenow, $order_id]);
                    $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage3 = ? WHERE order_id = ?', [1, $order_id]);
                    $mg = ($list_data_arr[41] - $yu_price[0]->service42) * ($sel_shop_price[0]->service42);
                    $mg += ($list_data_arr[42] - $yu_price[0]->service43) * ($sel_shop_price[0]->service43);
                    $mg += ($list_data_arr[43] - $yu_price[0]->service44) * ($sel_shop_price[0]->service44);
                    $mg += ($list_data_arr[44] - $yu_price[0]->service45) * ($sel_shop_price[0]->service45);
                    $mg += ($list_data_arr[45] - $yu_price[0]->service46) * ($sel_shop_price[0]->service46);
                    $mg += ($list_data_arr[46] - $yu_price[0]->service47) * ($sel_shop_price[0]->service47);
                    $mg += ($list_data_arr[47] - $yu_price[0]->service48) * ($sel_shop_price[0]->service48);
                    $mg += ($list_data_arr[48] - $yu_price[0]->service49) * ($sel_shop_price[0]->service49);
                    $mg += ($list_data_arr[49] - $yu_price[0]->service50) * ($sel_shop_price[0]->service50);
                    $mg += ($list_data_arr[50] - $yu_price[0]->service51) * ($sel_shop_price[0]->service51);
                    $mg += ($list_data_arr[51] - $yu_price[0]->service52) * ($sel_shop_price[0]->service52);
                    $mg += ($list_data_arr[52] - $yu_price[0]->service53) * ($sel_shop_price[0]->service53);

                    $pay_amount1 = $mg;

                    $pay_amount2 = ($yu_price[0]->service54 * $sel_shop_price[0]->service54);
                    $pay_amount2 += ($yu_price[0]->service55 * $sel_shop_price[0]->service55);
                    $pay_amount2 += ($yu_price[0]->service56 * $sel_shop_price[0]->service56);
                    $pay_amount2 += ($yu_price[0]->service57 * $sel_shop_price[0]->service57);
                    $pay_amount2 += ($yu_price[0]->service58 * $sel_shop_price[0]->service58);
                    $pay_amount2 += ($yu_price[0]->service59 * $sel_shop_price[0]->service59);
                    $pay_amount2 += ($yu_price[0]->service60 * $sel_shop_price[0]->service60);
                    $pay_amount2 += ($yu_price[0]->service61 * $sel_shop_price[0]->service61);
                    $pay_amount2 += ($yu_price[0]->service62 * $sel_shop_price[0]->service62);
                    $pay_amount2 += ($yu_price[0]->service63 * $sel_shop_price[0]->service63);
                    $mg += $pay_amount2;

                    $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                    if ($order_pay) {
                        $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                        $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $mg, 4, $order_id]);
                        $pay_each1 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 13, $pay_amount1, 1]);
                        $pay_each2 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 5, 13, $pay_amount2, 1]);
                    }
                    break;
                case 16:
                case 17:
                    $actual_list_tbl = DB::update('UPDATE hh_order_actual_list SET service54 = ? ,service55 = ? ,service56 = ? ,service57 = ? ,service58 = ? ,service59 = ? ,
service60 = ? ,service61 = ? ,service62 = ? ,service63 = ? ,remark = ? ,update_time = ? ,is_available = 0 WHERE order_id = ?',
                        [$list_data_arr[53], $list_data_arr[54], $list_data_arr[55],
                            $list_data_arr[56], $list_data_arr[57], $list_data_arr[58], $list_data_arr[59], $list_data_arr[60], $list_data_arr[61], $list_data_arr[62], $remark, $timenow, $order_id]);
                    $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage4 = ? WHERE order_id = ?', [1, $order_id]);
                    $yqg = ($list_data_arr[53] - $yu_price[0]->service54) * $sel_shop_price[0]->service54;
                    $yqg += ($list_data_arr[54] - $yu_price[0]->service54) * $sel_shop_price[0]->service55;
                    $yqg += ($list_data_arr[55] - $yu_price[0]->service55) * $sel_shop_price[0]->service56;
                    $yqg += ($list_data_arr[56] - $yu_price[0]->service56) * $sel_shop_price[0]->service57;
                    $yqg += ($list_data_arr[57] - $yu_price[0]->service57) * $sel_shop_price[0]->service58;
                    $yqg += ($list_data_arr[58] - $yu_price[0]->service58) * $sel_shop_price[0]->service59;
                    $yqg += ($list_data_arr[59] - $yu_price[0]->service59) * $sel_shop_price[0]->service60;
                    $yqg += ($list_data_arr[60] - $yu_price[0]->service60) * $sel_shop_price[0]->service61;
                    $yqg += ($list_data_arr[61] - $yu_price[0]->service61) * $sel_shop_price[0]->service62;
                    $yqg += ($list_data_arr[62] - $yu_price[0]->service62) * $sel_shop_price[0]->service63;
                    $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                    if ($order_pay) {
                        $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                        $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $yqg, 5, $order_id]);
                        $pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 17, $yqg, 1]);
                    }
                    break;
                default:
                    $arr = array(
                        "code" => "200",
                        "msg" => "订单当前状态无法修改结算单",
                        "data" => ""
                    );
                    return $callback . "(" . HHJson($arr) . ")";
            }
        }
        if ($actual_list_tbl) {
            $arr = array(
                "code" => "000",
                "msg" => "插入成功",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "200",
                "msg" => "插入失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //获取预算单结算单数据
    public function getActualAndReckonData()
    {
        $order_id = rq('order_id');
        $callback = rq('callback');
        $sel_reckon_list = DB::select('SELECT * FROM hh_order_reckon_list WHERE order_id = ?', [$order_id]);
        $sel_actual_list = DB::select('SELECT * FROM hh_order_actual_list WHERE order_id = ?', [$order_id]);
        if ($sel_reckon_list && $sel_actual_list) {
            $arr = array(
                "code" => "000",
                "msg" => "查询成功",
                "data" => array(
                    "预算单数据" => $sel_reckon_list,
                    "结算单数据" => $sel_actual_list
                )
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "200",
                "msg" => "查询失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //查询预结算单数据和付款信息
    public function searchActualDataAndReckonData()
    {
        $order_id = rq('order_id');
        $order_step_type = rq('order_step_type');
        $callback = rq('callback');
        $total_amount = 0;
        //查看订单是否存在
        $sel_order_tbl = DB::select('SELECT * FROM hh_order WHERE order_id = ?',
            [$order_id]);
        if (!$sel_order_tbl) {
            $arr = array(
                "code" => "200",
                "msg" => "订单号错误",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $shop_id = $sel_order_tbl[0]->shop_id;
        $sel_shop_price = DB::select('SELECT * FROM hh_shop_price WHERE shop_id = ?',
            [$shop_id]);
        $sel_work_item_unit = DB::select('SELECT unit FROM hh_workerprice_list');
        $sel_reckon_list = DB::select('SELECT * FROM hh_order_reckon_list WHERE order_id = ?', [$order_id]);
        $sel_actual_list = DB::select('SELECT * FROM hh_order_actual_list WHERE order_id = ?', [$order_id]);
        if ($sel_reckon_list && $sel_actual_list) {
            //按阶段隐藏结算单数据
            //查询订单阶段
            $sel_order_tbl = DB::select('SELECT order_step FROM hh_order WHERE order_id = ?', [$order_id]);
            if ($sel_order_tbl) $order_step = $sel_order_tbl[0]->order_step;
            //根据订单步骤隐藏结算单数据
            if ($order_step < 17 || $order_step == 18) {
                $sel_actual_list[0]->service63 = 0;
                $sel_actual_list[0]->service62 = 0;
                $sel_actual_list[0]->service61 = 0;
                $sel_actual_list[0]->service60 = 0;
                $sel_actual_list[0]->service59 = 0;
                $sel_actual_list[0]->service58 = 0;
                $sel_actual_list[0]->service57 = 0;
                $sel_actual_list[0]->service56 = 0;
                $sel_actual_list[0]->service55 = 0;
                $sel_actual_list[0]->service54 = 0;
                if ($order_step < 13 || $order_step == 18) {
                    $sel_actual_list[0]->service53 = 0;
                    $sel_actual_list[0]->service52 = 0;
                    $sel_actual_list[0]->service51 = 0;
                    $sel_actual_list[0]->service50 = 0;
                    $sel_actual_list[0]->service49 = 0;
                    $sel_actual_list[0]->service48 = 0;
                    $sel_actual_list[0]->service47 = 0;
                    $sel_actual_list[0]->service46 = 0;
                    $sel_actual_list[0]->service45 = 0;
                    $sel_actual_list[0]->service44 = 0;
                    $sel_actual_list[0]->service43 = 0;
                    $sel_actual_list[0]->service42 = 0;
                    if ($order_step < 9 || $order_step == 18) {
                        $sel_actual_list[0]->service41 = 0;
                        $sel_actual_list[0]->service40 = 0;
                        $sel_actual_list[0]->service39 = 0;
                        $sel_actual_list[0]->service38 = 0;
                        $sel_actual_list[0]->service37 = 0;
                        $sel_actual_list[0]->service36 = 0;
                        $sel_actual_list[0]->service35 = 0;
                        $sel_actual_list[0]->service34 = 0;
                        $sel_actual_list[0]->service33 = 0;
                        $sel_actual_list[0]->service32 = 0;
                        $sel_actual_list[0]->service31 = 0;
                        $sel_actual_list[0]->service30 = 0;
                        $sel_actual_list[0]->service29 = 0;
                        $sel_actual_list[0]->service28 = 0;
                        $sel_actual_list[0]->service27 = 0;
                        $sel_actual_list[0]->service26 = 0;
                        $sel_actual_list[0]->service25 = 0;
                        $sel_actual_list[0]->service24 = 0;
                        $sel_actual_list[0]->service23 = 0;
                        $sel_actual_list[0]->service22 = 0;
                        $sel_actual_list[0]->service21 = 0;
                        $sel_actual_list[0]->service20 = 0;
                        $sel_actual_list[0]->service19 = 0;
                        if ($order_step < 5 || $order_step == 18) {
                            $sel_actual_list[0]->service18 = 0;
                            $sel_actual_list[0]->service17 = 0;
                            $sel_actual_list[0]->service16 = 0;
                            $sel_actual_list[0]->service15 = 0;
                            $sel_actual_list[0]->service14 = 0;
                            $sel_actual_list[0]->service13 = 0;
                            $sel_actual_list[0]->service12 = 0;
                            $sel_actual_list[0]->service11 = 0;
                            $sel_actual_list[0]->service10 = 0;
                            $sel_actual_list[0]->service9 = 0;
                            $sel_actual_list[0]->service8 = 0;
                            $sel_actual_list[0]->service7 = 0;
                            $sel_actual_list[0]->service6 = 0;
                            $sel_actual_list[0]->service5 = 0;
                            $sel_actual_list[0]->service4 = 0;
                            $sel_actual_list[0]->service3 = 0;
                            $sel_actual_list[0]->service2 = 0;
                            $sel_actual_list[0]->service1 = 0;
                        }
                    }
                }
            }
            //工长费用
            $gz = array(
                "工长费用" => array(
                    "管理费用" => array(
                        "单位" => $sel_work_item_unit[63]->unit,
                        "单价/元" => $sel_shop_price[0]->foreman_price,
                        "预计工程量" => $sel_reckon_list[0]->foreman_price,
                        "实际工程量" => $sel_actual_list[0]->foreman_price
                    )
                ),
                "小计/元" => array(
                    "预计工程量" => $sel_reckon_list[0]->foreman_price * $sel_shop_price[0]->foreman_price,
                    "实际工程量" => $sel_actual_list[0]->foreman_price * $sel_shop_price[0]->foreman_price
                ),
                "结转金额/元" => ($sel_actual_list[0]->foreman_price * $sel_shop_price[0]->foreman_price) - ($sel_reckon_list[0]->foreman_price * $sel_shop_price[0]->foreman_price));
            //杂工费用
            $reckon_sum = ($sel_reckon_list[0]->service1 * $sel_shop_price[0]->service1);
            $reckon_sum += ($sel_reckon_list[0]->service2 * $sel_shop_price[0]->service2);
            $reckon_sum += ($sel_reckon_list[0]->service3 * $sel_shop_price[0]->service3);
            $reckon_sum += ($sel_reckon_list[0]->service4 * $sel_shop_price[0]->service4);
            $reckon_sum += ($sel_reckon_list[0]->service5 * $sel_shop_price[0]->service5);
            $reckon_sum += ($sel_reckon_list[0]->service6 * $sel_shop_price[0]->service6);
            $reckon_sum += ($sel_reckon_list[0]->service7 * $sel_shop_price[0]->service7);
            $reckon_sum += ($sel_reckon_list[0]->service8 * $sel_shop_price[0]->service8);
            $reckon_sum += ($sel_reckon_list[0]->service9 * $sel_shop_price[0]->service9);
            $reckon_sum += ($sel_reckon_list[0]->service10 * $sel_shop_price[0]->service10);
            $reckon_sum += ($sel_reckon_list[0]->service11 * $sel_shop_price[0]->service11);
            $reckon_sum += ($sel_reckon_list[0]->service12 * $sel_shop_price[0]->service12);
            $reckon_sum += ($sel_reckon_list[0]->service13 * $sel_shop_price[0]->service13);
            $reckon_sum += ($sel_reckon_list[0]->service14 * $sel_shop_price[0]->service14);
            $reckon_sum += ($sel_reckon_list[0]->service15 * $sel_shop_price[0]->service15);
            $reckon_sum += ($sel_reckon_list[0]->service16 * $sel_shop_price[0]->service16);
            $actual_sum = ($sel_actual_list[0]->service1 * $sel_shop_price[0]->service1);
            $actual_sum += ($sel_actual_list[0]->service2 * $sel_shop_price[0]->service2);
            $actual_sum += ($sel_actual_list[0]->service3 * $sel_shop_price[0]->service3);
            $actual_sum += ($sel_actual_list[0]->service4 * $sel_shop_price[0]->service4);
            $actual_sum += ($sel_actual_list[0]->service5 * $sel_shop_price[0]->service5);
            $actual_sum += ($sel_actual_list[0]->service6 * $sel_shop_price[0]->service6);
            $actual_sum += ($sel_actual_list[0]->service7 * $sel_shop_price[0]->service7);
            $actual_sum += ($sel_actual_list[0]->service8 * $sel_shop_price[0]->service8);
            $actual_sum += ($sel_actual_list[0]->service9 * $sel_shop_price[0]->service9);
            $actual_sum += ($sel_actual_list[0]->service10 * $sel_shop_price[0]->service10);
            $actual_sum += ($sel_actual_list[0]->service11 * $sel_shop_price[0]->service11);
            $actual_sum += ($sel_actual_list[0]->service12 * $sel_shop_price[0]->service12);
            $actual_sum += ($sel_actual_list[0]->service13 * $sel_shop_price[0]->service13);
            $actual_sum += ($sel_actual_list[0]->service14 * $sel_shop_price[0]->service14);
            $actual_sum += ($sel_actual_list[0]->service15 * $sel_shop_price[0]->service15);
            $actual_sum += ($sel_actual_list[0]->service16 * $sel_shop_price[0]->service16);
            $zg = array(
                "基础改造" => array(
                    "原墙、顶面腻子铲除" => array(
                        "单位" => $sel_work_item_unit[0]->unit,
                        "单价/元" => $sel_shop_price[0]->service1,
                        "预计工程量" => $sel_reckon_list[0]->service1,
                        "实际工程量" => $sel_actual_list[0]->service1
                    ),
                    "墙面粉刷层凿除" => array(
                        "单位" => $sel_work_item_unit[1]->unit,
                        "单价/元" => $sel_shop_price[0]->service2,
                        "预计工程量" => $sel_reckon_list[0]->service2,
                        "实际工程量" => $sel_actual_list[0]->service2
                    ),
                    "墙地面旧瓷砖拆除" => array(
                        "单位" => $sel_work_item_unit[2]->unit,
                        "单价/元" => $sel_shop_price[0]->service3,
                        "预计工程量" => $sel_reckon_list[0]->service3,
                        "实际工程量" => $sel_actual_list[0]->service3
                    ),
                    "拆除墙面保温层" => array(
                        "单位" => $sel_work_item_unit[3]->unit,
                        "单价/元" => $sel_shop_price[0]->service4,
                        "预计工程量" => $sel_reckon_list[0]->service4,
                        "实际工程量" => $sel_actual_list[0]->service4
                    ),
                    "门洞移位（左右20cm以内）" => array(
                        "单位" => $sel_work_item_unit[4]->unit,
                        "单价/元" => $sel_shop_price[0]->service5,
                        "预计工程量" => $sel_reckon_list[0]->service5,
                        "实际工程量" => $sel_actual_list[0]->service5
                    ),
                    "12㎝砖墙开门洞" => array(
                        "单位" => $sel_work_item_unit[5]->unit,
                        "单价/元" => $sel_shop_price[0]->service6,
                        "预计工程量" => $sel_reckon_list[0]->service6,
                        "实际工程量" => $sel_actual_list[0]->service6
                    ),
                    "24㎝砖墙开门洞" => array(
                        "单位" => $sel_work_item_unit[6]->unit,
                        "单价/元" => $sel_shop_price[0]->service7,
                        "预计工程量" => $sel_reckon_list[0]->service7,
                        "实际工程量" => $sel_actual_list[0]->service7
                    ),
                    "混泥土24㎝墙切割费" => array(
                        "单位" => $sel_work_item_unit[7]->unit,
                        "单价/元" => $sel_shop_price[0]->service8,
                        "预计工程量" => $sel_reckon_list[0]->service8,
                        "实际工程量" => $sel_actual_list[0]->service8
                    ),
                    "拆除24㎝混泥土墙" => array(
                        "单位" => $sel_work_item_unit[8]->unit,
                        "单价/元" => $sel_shop_price[0]->service9,
                        "预计工程量" => $sel_reckon_list[0]->service9,
                        "实际工程量" => $sel_actual_list[0]->service9
                    ),
                    "砖墙凿除半墙" => array(
                        "单位" => $sel_work_item_unit[9]->unit,
                        "单价/元" => $sel_shop_price[0]->service10,
                        "预计工程量" => $sel_reckon_list[0]->service10,
                        "实际工程量" => $sel_actual_list[0]->service10
                    ),
                    "混泥土墙凿除半墙" => array(
                        "单位" => $sel_work_item_unit[10]->unit,
                        "单价/元" => $sel_shop_price[0]->service11,
                        "预计工程量" => $sel_reckon_list[0]->service11,
                        "实际工程量" => $sel_actual_list[0]->service11
                    ),
                    "拆除12㎝砖墙" => array(
                        "单位" => $sel_work_item_unit[11]->unit,
                        "单价/元" => $sel_shop_price[0]->service12,
                        "预计工程量" => $sel_reckon_list[0]->service12,
                        "实际工程量" => $sel_actual_list[0]->service12
                    ),
                    "拆除12㎝混泥土墙" => array(
                        "单位" => $sel_work_item_unit[12]->unit,
                        "单价/元" => $sel_shop_price[0]->service13,
                        "预计工程量" => $sel_reckon_list[0]->service13,
                        "实际工程量" => $sel_actual_list[0]->service13
                    ),
                    "拆除24㎝砖墙" => array(
                        "单位" => $sel_work_item_unit[13]->unit,
                        "单价/元" => $sel_shop_price[0]->service14,
                        "预计工程量" => $sel_reckon_list[0]->service14,
                        "实际工程量" => $sel_actual_list[0]->service14
                    ),
                    "拆除混泥土飘窗台" => array(
                        "单位" => $sel_work_item_unit[14]->unit,
                        "单价/元" => $sel_shop_price[0]->service15,
                        "预计工程量" => $sel_reckon_list[0]->service15,
                        "实际工程量" => $sel_actual_list[0]->service15
                    ),
                    "垃圾清运" => array(
                        "单位" => $sel_work_item_unit[15]->unit,
                        "单价/元" => $sel_shop_price[0]->service16,
                        "预计工程量" => $sel_reckon_list[0]->service16,
                        "实际工程量" => $sel_actual_list[0]->service16
                    ),
                ),
                "小计/元" => array(
                    "预计工程量" => $reckon_sum,
                    "实际工程量" => $actual_sum
                ),
                "结转金额/元" => $actual_sum - $reckon_sum);
            //水电工费用
            $reckon_sum = ($sel_reckon_list[0]->service17 * $sel_shop_price[0]->service17);
            $reckon_sum += ($sel_reckon_list[0]->service18 * $sel_shop_price[0]->service18);
            $actual_sum = ($sel_actual_list[0]->service17 * $sel_shop_price[0]->service17);
            $actual_sum += ($sel_actual_list[0]->service18 * $sel_shop_price[0]->service18);
            $sdg = array(
                "水电改造" => array(
                    "局部改造" => array(
                        "单位" => $sel_work_item_unit[16]->unit,
                        "单价/元" => $sel_shop_price[0]->service17,
                        "预计工程量" => $sel_reckon_list[0]->service17,
                        "实际工程量" => $sel_actual_list[0]->service17
                    ),
                    "全部换线" => array(
                        "单位" => $sel_work_item_unit[17]->unit,
                        "单价/元" => $sel_shop_price[0]->service18,
                        "预计工程量" => $sel_reckon_list[0]->service18,
                        "实际工程量" => $sel_actual_list[0]->service18
                    ),
                ),
                "小计/元" => array(
                    "预计工程量" => $reckon_sum,
                    "实际工程量" => $actual_sum
                ),
                "结转金额/元" => $actual_sum - $reckon_sum);
            //瓦工费用
            $reckon_sum = ($sel_reckon_list[0]->service19 * $sel_shop_price[0]->service19);
            $reckon_sum += ($sel_reckon_list[0]->service20 * $sel_shop_price[0]->service20);
            $reckon_sum += ($sel_reckon_list[0]->service21 * $sel_shop_price[0]->service21);
            $reckon_sum += ($sel_reckon_list[0]->service22 * $sel_shop_price[0]->service22);
            $reckon_sum += ($sel_reckon_list[0]->service23 * $sel_shop_price[0]->service23);
            $reckon_sum += ($sel_reckon_list[0]->service24 * $sel_shop_price[0]->service24);
            $reckon_sum += ($sel_reckon_list[0]->service25 * $sel_shop_price[0]->service25);
            $reckon_sum += ($sel_reckon_list[0]->service26 * $sel_shop_price[0]->service26);
            $reckon_sum += ($sel_reckon_list[0]->service27 * $sel_shop_price[0]->service27);
            $reckon_sum += ($sel_reckon_list[0]->service28 * $sel_shop_price[0]->service28);
            $reckon_sum += ($sel_reckon_list[0]->service29 * $sel_shop_price[0]->service29);
            $reckon_sum += ($sel_reckon_list[0]->service30 * $sel_shop_price[0]->service30);
            $reckon_sum += ($sel_reckon_list[0]->service31 * $sel_shop_price[0]->service31);
            $reckon_sum += ($sel_reckon_list[0]->service32 * $sel_shop_price[0]->service32);
            $reckon_sum += ($sel_reckon_list[0]->service33 * $sel_shop_price[0]->service33);
            $reckon_sum += ($sel_reckon_list[0]->service34 * $sel_shop_price[0]->service34);
            $reckon_sum += ($sel_reckon_list[0]->service35 * $sel_shop_price[0]->service35);
            $reckon_sum += ($sel_reckon_list[0]->service36 * $sel_shop_price[0]->service36);
            $reckon_sum += ($sel_reckon_list[0]->service37 * $sel_shop_price[0]->service37);
            $reckon_sum += ($sel_reckon_list[0]->service38 * $sel_shop_price[0]->service38);
            $reckon_sum += ($sel_reckon_list[0]->service39 * $sel_shop_price[0]->service39);
            $reckon_sum += ($sel_reckon_list[0]->service40 * $sel_shop_price[0]->service40);
            $reckon_sum += ($sel_reckon_list[0]->service41 * $sel_shop_price[0]->service41);
            $actual_sum = ($sel_actual_list[0]->service19 * $sel_shop_price[0]->service19);
            $actual_sum += ($sel_actual_list[0]->service20 * $sel_shop_price[0]->service20);
            $actual_sum += ($sel_actual_list[0]->service21 * $sel_shop_price[0]->service21);
            $actual_sum += ($sel_actual_list[0]->service22 * $sel_shop_price[0]->service22);
            $actual_sum += ($sel_actual_list[0]->service23 * $sel_shop_price[0]->service23);
            $actual_sum += ($sel_actual_list[0]->service24 * $sel_shop_price[0]->service24);
            $actual_sum += ($sel_actual_list[0]->service25 * $sel_shop_price[0]->service25);
            $actual_sum += ($sel_actual_list[0]->service26 * $sel_shop_price[0]->service26);
            $actual_sum += ($sel_actual_list[0]->service27 * $sel_shop_price[0]->service27);
            $actual_sum += ($sel_actual_list[0]->service28 * $sel_shop_price[0]->service28);
            $actual_sum += ($sel_actual_list[0]->service29 * $sel_shop_price[0]->service29);
            $actual_sum += ($sel_actual_list[0]->service30 * $sel_shop_price[0]->service30);
            $actual_sum += ($sel_actual_list[0]->service31 * $sel_shop_price[0]->service31);
            $actual_sum += ($sel_actual_list[0]->service32 * $sel_shop_price[0]->service32);
            $actual_sum += ($sel_actual_list[0]->service33 * $sel_shop_price[0]->service33);
            $actual_sum += ($sel_actual_list[0]->service34 * $sel_shop_price[0]->service34);
            $actual_sum += ($sel_actual_list[0]->service35 * $sel_shop_price[0]->service35);
            $actual_sum += ($sel_actual_list[0]->service36 * $sel_shop_price[0]->service36);
            $actual_sum += ($sel_actual_list[0]->service37 * $sel_shop_price[0]->service37);
            $actual_sum += ($sel_actual_list[0]->service38 * $sel_shop_price[0]->service38);
            $actual_sum += ($sel_actual_list[0]->service39 * $sel_shop_price[0]->service39);
            $actual_sum += ($sel_actual_list[0]->service40 * $sel_shop_price[0]->service40);
            $actual_sum += ($sel_actual_list[0]->service41 * $sel_shop_price[0]->service41);
            $wg = array(
                "贴砖" => array(
                    "常规瓷砖方正贴" => array(
                        "单位" => $sel_work_item_unit[18]->unit,
                        "单价/元" => $sel_shop_price[0]->service19,
                        "预计工程量" => $sel_reckon_list[0]->service19,
                        "实际工程量" => $sel_actual_list[0]->service19
                    ),
                    "常规瓷砖菱形贴" => array(
                        "单位" => $sel_work_item_unit[19]->unit,
                        "单价/元" => $sel_shop_price[0]->service20,
                        "预计工程量" => $sel_reckon_list[0]->service20,
                        "实际工程量" => $sel_actual_list[0]->service20
                    ),
                    "条形木纹砖" => array(
                        "单位" => $sel_work_item_unit[20]->unit,
                        "单价/元" => $sel_shop_price[0]->service21,
                        "预计工程量" => $sel_reckon_list[0]->service21,
                        "实际工程量" => $sel_actual_list[0]->service21
                    ),
                    "瓷砖走边线" => array(
                        "单位" => $sel_work_item_unit[21]->unit,
                        "单价/元" => $sel_shop_price[0]->service22,
                        "预计工程量" => $sel_reckon_list[0]->service22,
                        "实际工程量" => $sel_actual_list[0]->service22
                    ),
                    "加工砖上墙方正贴" => array(
                        "单位" => $sel_work_item_unit[22]->unit,
                        "单价/元" => $sel_shop_price[0]->service23,
                        "预计工程量" => $sel_reckon_list[0]->service23,
                        "实际工程量" => $sel_actual_list[0]->service23
                    ),
                    "加工砖上墙菱形贴" => array(
                        "单位" => $sel_work_item_unit[23]->unit,
                        "单价/元" => $sel_shop_price[0]->service24,
                        "预计工程量" => $sel_reckon_list[0]->service24,
                        "实际工程量" => $sel_actual_list[0]->service24
                    ),
                    "普通马赛克铺贴" => array(
                        "单位" => $sel_work_item_unit[24]->unit,
                        "单价/元" => $sel_shop_price[0]->service25,
                        "预计工程量" => $sel_reckon_list[0]->service25,
                        "实际工程量" => $sel_actual_list[0]->service25
                    ),
                    "门槛石、挡水条" => array(
                        "单位" => $sel_work_item_unit[25]->unit,
                        "单价/元" => $sel_shop_price[0]->service26,
                        "预计工程量" => $sel_reckon_list[0]->service26,
                        "实际工程量" => $sel_actual_list[0]->service26
                    ),
                    "踢脚线铺贴" => array(
                        "单位" => $sel_work_item_unit[26]->unit,
                        "单价/元" => $sel_shop_price[0]->service27,
                        "预计工程量" => $sel_reckon_list[0]->service27,
                        "实际工程量" => $sel_actual_list[0]->service27
                    )
                ),
                "砌墙" => array(
                    "砖砌12cm墙（单面粉刷）" => array(
                        "单位" => $sel_work_item_unit[27]->unit,
                        "单价/元" => $sel_shop_price[0]->service28,
                        "预计工程量" => $sel_reckon_list[0]->service28,
                        "实际工程量" => $sel_actual_list[0]->service28
                    ),
                    "砖砌12cm墙（双面粉刷）" => array(
                        "单位" => $sel_work_item_unit[28]->unit,
                        "单价/元" => $sel_shop_price[0]->service29,
                        "预计工程量" => $sel_reckon_list[0]->service29,
                        "实际工程量" => $sel_actual_list[0]->service29
                    ),
                    "砖砌24cm墙（单面粉刷）" => array(
                        "单位" => $sel_work_item_unit[29]->unit,
                        "单价/元" => $sel_shop_price[0]->service30,
                        "预计工程量" => $sel_reckon_list[0]->service30,
                        "实际工程量" => $sel_actual_list[0]->service30
                    ),
                    "砖砌24cm墙（双面粉刷）" => array(
                        "单位" => $sel_work_item_unit[30]->unit,
                        "单价/元" => $sel_shop_price[0]->service31,
                        "预计工程量" => $sel_reckon_list[0]->service31,
                        "实际工程量" => $sel_actual_list[0]->service31
                    ),
                    "封下水管（单管）" => array(
                        "单位" => $sel_work_item_unit[31]->unit,
                        "单价/元" => $sel_shop_price[0]->service32,
                        "预计工程量" => $sel_reckon_list[0]->service32,
                        "实际工程量" => $sel_actual_list[0]->service32
                    ),
                    "封下水管（双管）" => array(
                        "单位" => $sel_work_item_unit[32]->unit,
                        "单价/元" => $sel_shop_price[0]->service33,
                        "预计工程量" => $sel_reckon_list[0]->service33,
                        "实际工程量" => $sel_actual_list[0]->service33
                    )
                ),
                "粉刷" => array(
                    "粉线槽" => array(
                        "单位" => $sel_work_item_unit[33]->unit,
                        "单价/元" => $sel_shop_price[0]->service34,
                        "预计工程量" => $sel_reckon_list[0]->service34,
                        "实际工程量" => $sel_actual_list[0]->service34
                    ),
                    "墙面粉刷（单面计算）" => array(
                        "单位" => $sel_work_item_unit[34]->unit,
                        "单价/元" => $sel_shop_price[0]->service35,
                        "预计工程量" => $sel_reckon_list[0]->service35,
                        "实际工程量" => $sel_actual_list[0]->service35
                    ),
                    "地面找平（5cm以内）" => array(
                        "单位" => $sel_work_item_unit[35]->unit,
                        "单价/元" => $sel_shop_price[0]->service36,
                        "预计工程量" => $sel_reckon_list[0]->service36,
                        "实际工程量" => $sel_actual_list[0]->service36
                    ),
                    "地面现浇（30mm以内）" => array(
                        "单位" => $sel_work_item_unit[36]->unit,
                        "单价/元" => $sel_shop_price[0]->service37,
                        "预计工程量" => $sel_reckon_list[0]->service37,
                        "实际工程量" => $sel_actual_list[0]->service37
                    ),
                    "刷防水涂料" => array(
                        "单位" => $sel_work_item_unit[37]->unit,
                        "单价/元" => $sel_shop_price[0]->service38,
                        "预计工程量" => $sel_reckon_list[0]->service38,
                        "实际工程量" => $sel_actual_list[0]->service38
                    ),
                    "墙面拉毛处理" => array(
                        "单位" => $sel_work_item_unit[38]->unit,
                        "单价/元" => $sel_shop_price[0]->service39,
                        "预计工程量" => $sel_reckon_list[0]->service39,
                        "实际工程量" => $sel_actual_list[0]->service39
                    ),
                    "修补门边" => array(
                        "单位" => $sel_work_item_unit[39]->unit,
                        "单价/元" => $sel_shop_price[0]->service40,
                        "预计工程量" => $sel_reckon_list[0]->service40,
                        "实际工程量" => $sel_actual_list[0]->service40
                    ),
                    "粉刷门框" => array(
                        "单位" => $sel_work_item_unit[40]->unit,
                        "单价/元" => $sel_shop_price[0]->service41,
                        "预计工程量" => $sel_reckon_list[0]->service41,
                        "实际工程量" => $sel_actual_list[0]->service41
                    )
                ),
                "小计/元" => array(
                    "预计工程量" => $reckon_sum,
                    "实际工程量" => $actual_sum
                ),
                "结转金额/元" => $actual_sum - $reckon_sum);
            //木工费用
            $reckon_sum = ($sel_reckon_list[0]->service42 * $sel_shop_price[0]->service42);
            $reckon_sum += ($sel_reckon_list[0]->service43 * $sel_shop_price[0]->service43);
            $reckon_sum += ($sel_reckon_list[0]->service44 * $sel_shop_price[0]->service44);
            $reckon_sum += ($sel_reckon_list[0]->service45 * $sel_shop_price[0]->service45);
            $reckon_sum += ($sel_reckon_list[0]->service46 * $sel_shop_price[0]->service46);
            $reckon_sum += ($sel_reckon_list[0]->service47 * $sel_shop_price[0]->service47);
            $reckon_sum += ($sel_reckon_list[0]->service48 * $sel_shop_price[0]->service48);
            $reckon_sum += ($sel_reckon_list[0]->service49 * $sel_shop_price[0]->service49);
            $reckon_sum += ($sel_reckon_list[0]->service50 * $sel_shop_price[0]->service50);
            $reckon_sum += ($sel_reckon_list[0]->service51 * $sel_shop_price[0]->service51);
            $reckon_sum += ($sel_reckon_list[0]->service52 * $sel_shop_price[0]->service52);
            $reckon_sum += ($sel_reckon_list[0]->service53 * $sel_shop_price[0]->service53);
            $actual_sum = ($sel_actual_list[0]->service42 * $sel_shop_price[0]->service42);
            $actual_sum += ($sel_actual_list[0]->service43 * $sel_shop_price[0]->service43);
            $actual_sum += ($sel_actual_list[0]->service44 * $sel_shop_price[0]->service44);
            $actual_sum += ($sel_actual_list[0]->service45 * $sel_shop_price[0]->service45);
            $actual_sum += ($sel_actual_list[0]->service46 * $sel_shop_price[0]->service46);
            $actual_sum += ($sel_actual_list[0]->service47 * $sel_shop_price[0]->service47);
            $actual_sum += ($sel_actual_list[0]->service48 * $sel_shop_price[0]->service48);
            $actual_sum += ($sel_actual_list[0]->service49 * $sel_shop_price[0]->service49);
            $actual_sum += ($sel_actual_list[0]->service50 * $sel_shop_price[0]->service50);
            $actual_sum += ($sel_actual_list[0]->service51 * $sel_shop_price[0]->service51);
            $actual_sum += ($sel_actual_list[0]->service52 * $sel_shop_price[0]->service52);
            $actual_sum += ($sel_actual_list[0]->service53 * $sel_shop_price[0]->service53);
            $mg = array(
                "家具制作（免漆板）" => array(
                    "高柜制作" => array(
                        "单位" => $sel_work_item_unit[41]->unit,
                        "单价/元" => $sel_shop_price[0]->service42,
                        "预计工程量" => $sel_reckon_list[0]->service42,
                        "实际工程量" => $sel_actual_list[0]->service42
                    ),
                    "矮柜制作" => array(
                        "单位" => $sel_work_item_unit[42]->unit,
                        "单价/元" => $sel_shop_price[0]->service43,
                        "预计工程量" => $sel_reckon_list[0]->service43,
                        "实际工程量" => $sel_actual_list[0]->service43
                    ),
                    "抽屉制作" => array(
                        "单位" => $sel_work_item_unit[43]->unit,
                        "单价/元" => $sel_shop_price[0]->service44,
                        "预计工程量" => $sel_reckon_list[0]->service44,
                        "实际工程量" => $sel_actual_list[0]->service44
                    ),
                    "门板制作" => array(
                        "单位" => $sel_work_item_unit[44]->unit,
                        "单价/元" => $sel_shop_price[0]->service45,
                        "预计工程量" => $sel_reckon_list[0]->service45,
                        "实际工程量" => $sel_actual_list[0]->service45
                    ),
                    "榻榻米制作" => array(
                        "单位" => $sel_work_item_unit[45]->unit,
                        "单价/元" => $sel_shop_price[0]->service46,
                        "预计工程量" => $sel_reckon_list[0]->service46,
                        "实际工程量" => $sel_actual_list[0]->service46
                    )
                ),
                "吊顶、隔墙" => array(
                    "木龙骨或轻钢龙骨混搭" => array(
                        "单位" => $sel_work_item_unit[46]->unit,
                        "单价/元" => $sel_shop_price[0]->service47,
                        "预计工程量" => $sel_reckon_list[0]->service47,
                        "实际工程量" => $sel_actual_list[0]->service47
                    ),
                    "轻钢龙骨" => array(
                        "单位" => $sel_work_item_unit[47]->unit,
                        "单价/元" => $sel_shop_price[0]->service48,
                        "预计工程量" => $sel_reckon_list[0]->service48,
                        "实际工程量" => $sel_actual_list[0]->service48
                    )
                ),
                "杂项" => array(
                    "门套基础（加门边）" => array(
                        "单位" => $sel_work_item_unit[48]->unit,
                        "单价/元" => $sel_shop_price[0]->service49,
                        "预计工程量" => $sel_reckon_list[0]->service49,
                        "实际工程量" => $sel_actual_list[0]->service49
                    ),
                    "降门头、封柜顶" => array(
                        "单位" => $sel_work_item_unit[49]->unit,
                        "单价/元" => $sel_shop_price[0]->service50,
                        "预计工程量" => $sel_reckon_list[0]->service50,
                        "实际工程量" => $sel_actual_list[0]->service50
                    ),
                    "窗帘套制作" => array(
                        "单位" => $sel_work_item_unit[50]->unit,
                        "单价/元" => $sel_shop_price[0]->service51,
                        "预计工程量" => $sel_reckon_list[0]->service51,
                        "实际工程量" => $sel_actual_list[0]->service51
                    ),
                    "电视背景制作" => array(
                        "单位" => $sel_work_item_unit[51]->unit,
                        "单价/元" => $sel_shop_price[0]->service52,
                        "预计工程量" => $sel_reckon_list[0]->service52,
                        "实际工程量" => $sel_actual_list[0]->service52
                    ),
                    "踢脚线安装" => array(
                        "单位" => $sel_work_item_unit[52]->unit,
                        "单价/元" => $sel_shop_price[0]->service53,
                        "预计工程量" => $sel_reckon_list[0]->service53,
                        "实际工程量" => $sel_actual_list[0]->service53
                    )
                ),
                "小计/元" => array(
                    "预计工程量" => $reckon_sum,
                    "实际工程量" => $actual_sum
                ),
                "结转金额/元" => $actual_sum - $reckon_sum);
            //油漆工费用
            $reckon_sum = ($sel_reckon_list[0]->service54 * $sel_shop_price[0]->service54);
            $reckon_sum += ($sel_reckon_list[0]->service55 * $sel_shop_price[0]->service55);
            $reckon_sum += ($sel_reckon_list[0]->service56 * $sel_shop_price[0]->service56);
            $reckon_sum += ($sel_reckon_list[0]->service57 * $sel_shop_price[0]->service57);
            $reckon_sum += ($sel_reckon_list[0]->service58 * $sel_shop_price[0]->service58);
            $reckon_sum += ($sel_reckon_list[0]->service59 * $sel_shop_price[0]->service59);
            $reckon_sum += ($sel_reckon_list[0]->service60 * $sel_shop_price[0]->service60);
            $reckon_sum += ($sel_reckon_list[0]->service61 * $sel_shop_price[0]->service61);
            $reckon_sum += ($sel_reckon_list[0]->service62 * $sel_shop_price[0]->service62);
            $reckon_sum += ($sel_reckon_list[0]->service63 * $sel_shop_price[0]->service63);
            $actual_sum = ($sel_actual_list[0]->service54 * $sel_shop_price[0]->service54);
            $actual_sum += ($sel_actual_list[0]->service55 * $sel_shop_price[0]->service55);
            $actual_sum += ($sel_actual_list[0]->service56 * $sel_shop_price[0]->service56);
            $actual_sum += ($sel_actual_list[0]->service57 * $sel_shop_price[0]->service57);
            $actual_sum += ($sel_actual_list[0]->service58 * $sel_shop_price[0]->service58);
            $actual_sum += ($sel_actual_list[0]->service59 * $sel_shop_price[0]->service59);
            $actual_sum += ($sel_actual_list[0]->service60 * $sel_shop_price[0]->service60);
            $actual_sum += ($sel_actual_list[0]->service61 * $sel_shop_price[0]->service61);
            $actual_sum += ($sel_actual_list[0]->service62 * $sel_shop_price[0]->service62);
            $actual_sum += ($sel_actual_list[0]->service63 * $sel_shop_price[0]->service63);
            $yqg = array(
                "天花墙面乳胶漆" => array(
                    "原顶天花墙面乳胶漆" => array(
                        "单位" => $sel_work_item_unit[53]->unit,
                        "单价/元" => $sel_shop_price[0]->service54,
                        "预计工程量" => $sel_reckon_list[0]->service54,
                        "实际工程量" => $sel_actual_list[0]->service54
                    ),
                    "石膏板天花乳胶漆" => array(
                        "单位" => $sel_work_item_unit[54]->unit,
                        "单价/元" => $sel_shop_price[0]->service55,
                        "预计工程量" => $sel_reckon_list[0]->service55,
                        "实际工程量" => $sel_actual_list[0]->service55
                    ),
                    "天花墙面喷乳胶漆" => array(
                        "单位" => $sel_work_item_unit[55]->unit,
                        "单价/元" => $sel_shop_price[0]->service56,
                        "预计工程量" => $sel_reckon_list[0]->service56,
                        "实际工程量" => $sel_actual_list[0]->service56
                    ),
                    "铺纤维网" => array(
                        "单位" => $sel_work_item_unit[56]->unit,
                        "单价/元" => $sel_shop_price[0]->service57,
                        "预计工程量" => $sel_reckon_list[0]->service57,
                        "实际工程量" => $sel_actual_list[0]->service57
                    ),
                ),
                "木制油漆工程" => array(
                    "木质硝基清漆" => array(
                        "单位" => $sel_work_item_unit[57]->unit,
                        "单价/元" => $sel_shop_price[0]->service58,
                        "预计工程量" => $sel_reckon_list[0]->service58,
                        "实际工程量" => $sel_actual_list[0]->service58
                    ),
                    "木制有色硝基透明漆" => array(
                        "单位" => $sel_work_item_unit[58]->unit,
                        "单价/元" => $sel_shop_price[0]->service59,
                        "预计工程量" => $sel_reckon_list[0]->service59,
                        "实际工程量" => $sel_actual_list[0]->service59
                    ),
                    "木制硝基白色显木纹" => array(
                        "单位" => $sel_work_item_unit[59]->unit,
                        "单价/元" => $sel_shop_price[0]->service60,
                        "预计工程量" => $sel_reckon_list[0]->service60,
                        "实际工程量" => $sel_actual_list[0]->service60
                    ),
                    "木制透明PU漆" => array(
                        "单位" => $sel_work_item_unit[60]->unit,
                        "单价/元" => $sel_shop_price[0]->service61,
                        "预计工程量" => $sel_reckon_list[0]->service61,
                        "实际工程量" => $sel_actual_list[0]->service61
                    ),
                    "装饰实木线条喷刷清漆" => array(
                        "单位" => $sel_work_item_unit[61]->unit,
                        "单价/元" => $sel_shop_price[0]->service62,
                        "预计工程量" => $sel_reckon_list[0]->service62,
                        "实际工程量" => $sel_actual_list[0]->service62
                    ),
                    "装饰实木线条喷刷索色" => array(
                        "单位" => $sel_work_item_unit[62]->unit,
                        "单价/元" => $sel_shop_price[0]->service63,
                        "预计工程量" => $sel_reckon_list[0]->service63,
                        "实际工程量" => $sel_actual_list[0]->service63
                    )
                ),
                "小计/元" => array(
                    "预计工程量" => $reckon_sum,
                    "实际工程量" => $actual_sum
                ),
                "结转金额/元" => $actual_sum - $reckon_sum);
            if ($order_step_type) {
                $order_step_in_pay = $order_step_type;
                $order_step_in_pay_flag = false;
            } else {
                //根据当前状态判断付款订单状态
                //TODO 18:工长、杂工、水电工预支付
                //TODO 5:上阶段结转金额与瓦工预支付
                //TODO 9:上阶段结转金额与木工预支付
                //TODO 13:上阶段结转金额与油漆工预支付
                //TODO 17:上阶段结转金额
                $order_step_in_pay_flag = true;
                $order_step_in_pay = $order_step;
                if ($order_step < 5) {
                    $order_step_in_pay = 18;
                } else if ($order_step < 9) {
                    $order_step_in_pay = 5;
                } else if ($order_step < 13) {
                    $order_step_in_pay = 9;
                } else if ($order_step < 17) {
                    $order_step_in_pay = 13;
                }
            }
            //当前付款阶段
            $pay_arr = array();
            if ($order_step_in_pay_flag) {
                $sle_order_pay_each = DB::select('SELECT * FROM hh_order_pay_each_view WHERE order_id = ? AND pay_status = ?',
                    [$order_id, 1]);
                $sle_order_pay_each_count = DB::select('SELECT count(id) AS count_id FROM hh_order_pay_each_view WHERE order_id = ? AND pay_status = ?',
                    [$order_id, 1]);
                if (!$sle_order_pay_each) {
                    $sle_order_pay_each = DB::select('SELECT * FROM hh_order_pay_each_view WHERE order_id = ? AND order_step = ?',
                        [$order_id, $order_step_in_pay]);
                    $sle_order_pay_each_count = DB::select('SELECT count(id) AS count_id FROM hh_order_pay_each_view WHERE order_id = ? AND order_step = ?',
                        [$order_id, $order_step_in_pay]);
                }
            } else {
                $sle_order_pay_each = DB::select('SELECT * FROM hh_order_pay_each_view WHERE order_id = ? AND order_step = ?',
                    [$order_id, $order_step_in_pay]);
                $sle_order_pay_each_count = DB::select('SELECT count(id) AS count_id FROM hh_order_pay_each_view WHERE order_id = ? AND order_step = ?',
                    [$order_id, $order_step_in_pay]);
            }
            $pay_type = 0;
            $i = 0;
            if ($sle_order_pay_each) {
                $num = $sle_order_pay_each_count[0]->count_id;
                $i = 0;
                while ($i < $num) {
                    if ($sle_order_pay_each[$i]->pay_status == 3) {
                        $pay_name = "已付款";
                    } else if ($sle_order_pay_each[$i]->pay_status == 1) {
                        $pay_type = 1;
                        $pay_name = "待付款";
                    }
                    $order_step_count = "";
                    if ($sle_order_pay_each[$i]->order_pay_step == "结转金额") {
                        switch ($sle_order_pay_each[$i]->order_step) {
                            case 5:
                                $order_step_count = "杂工、水电工";
                                break;
                            case 9:
                                $order_step_count = "瓦工";
                                break;
                            case 13:
                                $order_step_count = "木工";
                                break;
                            case 17:
                                $order_step_count = "油漆工";
                                break;
                        }
                    }
                    $pay_arr[$i] = array(
                        "付款项目" => $order_step_count . $sle_order_pay_each[$i]->order_pay_step,
                        "工费/元" => $sle_order_pay_each[$i]->pay_amount,
                        "状态" => $pay_name
                    );
                    $total_amount += $sle_order_pay_each[$i]->pay_amount;
                    $i++;
                }
            } else {
                $pay_arr[$i] = array(
                    "付款项目" => "当前无项目",
                    "工费/元" => "0",
                    "状态" => ""
                );
                $i++;
            }
            $pay_arr["总计/元"] = $total_amount;
            //数据
            $arr_list = array(
                "工长" => $gz,
                "杂工" => $zg,
                "水电工" => $sdg,
                "瓦工" => $wg,
                "木工" => $mg,
                "油漆工" => $yqg,
                "需付款" => $pay_arr
            );
            $data = array(
                "data_list" => $arr_list,
                "pay_type" => $pay_type
            );
            $arr = array(
                "code" => "000",
                "data" => $data
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "200",
                "msg" => "查询失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //生成订单纠纷表
    public function generateOrderCandd()
    {
        $order_id = rq('order_id');
        $callback = rq('callback');
        //查询订单中的user_id
        $order_tbl_sel = DB::select('SELECT user_id FROM hh_order WHERE order_id = ?',
            [$order_id]);
        if ($order_tbl_sel) {
            $user_id = $order_tbl_sel[0]->user_id;
            $candd_id = $order_id;
            //查询是否存在未完成的当前订单所生成的投诉纠纷订单
            $order_candd_repeat_tbl = DB::select('SELECT * FROM hh_order WHERE candd_id = ? AND is_finish = ?',
                [$candd_id, 0]);
            if ($order_candd_repeat_tbl) {
                //TODO 节点
                $content = $order_candd_repeat_tbl[0]->content;
                $process_result = $order_candd_repeat_tbl[0]->process_result;
                $arr = array(
                    "code" => "210",
                    "msg" => "订单纠纷生成失败，存在未解决的订单纠纷",
                    "data" => array("content" => $content,
                        "process_result" => $process_result
                    )
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
            $order_candd_tbl = DB::insert('INSERT INTO hh_order_candd(candd_id, order_id, user_id, is_finish) VALUES(?,?,?,?)',
                [$candd_id, $order_id, $user_id, 0]);
            if ($order_candd_tbl) {
                $arr = array(
                    "code" => "000",
                    "msg" => "订单纠纷已生成",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            } else {
                $arr = array(
                    "code" => "207",
                    "msg" => "订单纠纷生成失败",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        } else {
            $arr = array(
                "code" => "206",
                "msg" => "订单号错误",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //插入纠纷数据
    public function intoOrderCandd()
    {
        $candd_id = rq('candd_id');
        $callback = rq('callback');
        $content = rq('content');
        $candd_tbl_into = DB::update('UPDATE hh_order_candd SET content = ? WHERE candd_id = ?',
            [$content, $candd_id]);
        if ($candd_tbl_into) {
            $arr = array(
                "code" => "000",
                "msg" => "成功",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "211",
                "msg" => "失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //插入纠纷解决方式数据
    public function intoOrderCanddResult()
    {
        $candd_id = rq('candd_id');
        $callback = rq('callback');
        $process_result = rq('process_result');
        $candd_tbl_into = DB::update('UPDATE hh_order_candd SET process_result = ? WHERE candd_id = ?',
            [$process_result, $candd_id]);
        if ($candd_tbl_into) {
            $arr = array(
                "code" => "000",
                "msg" => "成功",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "211",
                "msg" => "失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //更改纠纷订单状态为已解决
    public function changeOrderCanddFinish()
    {
        $candd_id = rq('candd_id');
        $callback = rq('callback');
        $candd_tbl_into = DB::update('UPDATE hh_order_candd SET is_finish = ? WHERE candd_id = ?',
            [1, $candd_id]);
        if ($candd_tbl_into) {
            $arr = array(
                "code" => "000",
                "msg" => "成功",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "211",
                "msg" => "失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //查看预算单结算单可修改状态
    public function getActualReckonStatus()
    {
        $order_id = rq('order_id');
        $callback = rq('callback');
        $sel_reckon_tbl = DB::select('SELECT is_available FROM hh_order_reckon_list WHERE order_id = ?',
            [$order_id]);
        if ($sel_reckon_tbl) {
            $reckon_is_available = $sel_reckon_tbl[0]->is_available;
        } else {
            $arr = array(
                "code" => "200",
                "msg" => "预算单不存在",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $sel_actual_tbl = DB::select('SELECT is_available FROM hh_order_actual_list WHERE order_id = ?',
            [$order_id]);
        if ($sel_actual_tbl) {
            $actual_is_available = $sel_actual_tbl[0]->is_available;
        } else {
            $arr = array(
                "code" => "200",
                "msg" => "结算单不存在",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $arr = array(
            "code" => "000",
            "msg" => "查询成功",
            "data" => array(
                "预算单编辑状态" => $reckon_is_available,
                "结算单编辑状态" => $actual_is_available
            )
        );
        return $callback . "(" . HHJson($arr) . ")";
    }

    //生成装修人员订单
    public function generateOrderWorker()
    {
        $order_id = rq('order_id');
        $callback = rq('callback');
        $order_personnel_array = rq('personnel_array');
        if (count($order_personnel_array) > 9) {
            $arr = array(
                "code" => "209",
                "msg" => "订单施工人员过多，请勿超过9位",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $i = 0;
        //遍历数组
        foreach ($order_personnel_array as $pa[$i]) {
            $i++;
        }
        //至多9名员工，不足补空
        while ($i < 10) {
            $pa[$i] = '';
            $i++;
        }
        if (!isOrder_OrderID($order_id)) {
            $arr = array(
                "code" => "206",
                "msg" => "订单号错误",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $order_personnel = $order_id;
        $order_personnel_tbl = DB::insert('INSERT INTO hh_order_personnel(personnel_id, order_id,person1,person2,person3,person4,person5,person6,person7,person8,person9) VALUES(?,?,?,?,?,?,?,?,?,?,?)',
            [$order_personnel, $order_id, $pa[0], $pa[1], $pa[2], $pa[3], $pa[4], $pa[5], $pa[6], $pa[7], $pa[8]]);

        if ($order_personnel_tbl) {
            $arr = array(
                "code" => "000",
                "msg" => "人员表生成成功",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "208",
                "msg" => "人员表生成失败",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //查询装修人员
    public function getDecorationWorker()
    {
        $order_id = rq('order_id');
        $callback = rq('callback');
        $result = DB::select('SELECT * from hh_order_personnel WHERE personnel_id = ? AND order_id = ?', [$order_id, $order_id]);
        if ($result) {
            $order_person = $result[0];
            $personArr = array();

            for ($i = 1; $i <= 9; $i++) {
                $personId = 'person' . $i;
                $user_id = $order_person->$personId;
                if ($user_id) {
                    $user = personal($user_id);
                    array_push($personArr, $user);
                }
            }

            $arr = array(
                "code" => "000",
                "data" => $personArr
            );
            return $callback . "(" . HHJson($arr) . ")";
        } else {
            $arr = array(
                "code" => "208",
                "msg" => "没有装修人员",
                "data" => ""
            );
            return $callback . "(" . HHJson($arr) . ")";
        }


    }

    /*订单进度*/
    //开工上传图片信息和描述
    public function startWork()
    {
        $callback = rq('callback');
        $order_id = rq('order_id'); //订单id
        $order_step_result = DB::SELECT('SELECT order_step FROM hh_order WHERE order_id = ?', [$order_id]);
        if ($order_step_result) {
            $order_step = $order_step_result[0]->order_step;
        } else {
            $arr = array("code" => "200",
                "msg" => "订单不存在"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
        $content = rq('content'); //描述
        $time = rq('time'); //开工时间
        $count = rq('count');  //图片数量
        $files = array();
        if ($count) {
            for ($i = 0; $i < $count; $i++) {
                $filename = "myfile" . $i;
                if (!Request::hasFile($filename)) {
                    $arr = array("code" => "121",
                        "msg" => "没有图片被上传"
                    );
                    return $callback . "(" . HHJson($arr) . ")";
                }
                $myfile = Request::file($filename);
                $files[$i] = $myfile;
            }
        } else {
            $myfile = Request::file('myfile');
            if (!Request::hasFile('myfile')) {
                $arr = array("code" => "121",
                    "msg" => "没有图片被上传"
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
            if (!is_array($myfile)) {
                $files = [$myfile];
            } else {
                $files = $myfile;
            }
        }

        if (!$order_id || !$time || !$content || !$order_step) {
            $arr = array("code" => "112",
                "msg" => "信息输入错误"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }

        //检测图片是否都合法
        $isvalid = true;
        foreach ($files as $file) {
            if (!$file->isValid()) {
                $isvalid = false;
                break;
            }
        }

        if ($isvalid && count($files)) {

            $detail = DB::select('SELECT id from hh_order_detail WHERE order_id = ? AND order_step = ? ', [$order_id, $order_step]);
            if ($detail) {
                $arr = array("code" => "000",
                    "msg" => "重复添加"
                );
                return $callback . "(" . HHJson($arr) . ")";
            } else {

                $case = DB::insert('insert into hh_order_detail(order_id,order_step,img_time,img_content) values(?,?,?,?)', [$order_id, $order_step, $time, $content]);
                $order_detail_id = DB::select('SELECT id from hh_order_detail WHERE order_id = ? AND order_step = ? ', [$order_id, $order_step]);
                if (!$order_detail_id) {
                    $arr = array("code" => "111",
                        "msg" => "添加失败"
                    );
                    return $callback . "(" . HHJson($arr) . ")";
                }
                //写进案例表 待完成案例
                if($order_step==1){
                    $order=DB::select('select area,house_style,order_address,shop_id,room,parlour,toilet,balcony from hh_order_new_view where order_id=?',[$order_id]);
                    if($order){
                        $foreman=DB::select('select shopper_id from hh_shop where shop_id=?',[$order[0]->shop_id]);
                        $foreman_id=$foreman[0]->shopper_id;
                        $housetype=$order[0]->room.'室'.$order[0]->parlour.'厅'.$order[0]->toilet.'卫'.$order[0]->balcony.'阳';
                        $workcase=DB::insert('insert into hh_workcase(case_id,foreman_id,area,housetype,style,timelong,address,type) values(?,?,?,?,?,?,?,?)', [$order_id,$foreman_id,$order[0]->area,$housetype,$order[0]->house_style,$time,$order[0]->order_address,3]);
                    }
                }
                //需要修改一个availlabel字段
                // $step = $order_step;
                if ($order_step == 4 || $order_step == 8 || $order_step == 12 || $order_step == 16) {
                    $available = DB::UPDATE('UPDATE hh_order_actual_list SET is_available = 1 WHERE order_id = ?', [$order_id]);
                }
                $step = $order_step + 1;
                $order_step = DB::update('UPDATE hh_order SET order_step = ? WHERE order_id = ?', [$step, $order_id]);
                //完工状态也写入order_detail
                if ($step == 5 || $step == 9 || $step == 13 || $step == 17) {
                    DB::insert('insert into hh_order_detail(order_id,order_step,img_time) values(?,?,?)', [$order_id, $step, date('Y-m-d H:i:s', time())]);
                }
                $ifinsert = false;
                foreach ($files as $key => $file) {
                    $clientName = $file->getClientOriginalName();//文件原名
                    $entension = $file->getClientOriginalExtension();//扩展名
                    $realPath = $file->getRealPath();   //临时文件的绝对路径
                    $type = $file->getClientMimeType();
                    $size = $file->getClientSize();
                    $filename = date('Ymd') . md5(rand(999, 10000)) . '.' . $entension;
                    $is = $file->move(public_path() . '/uploads/' . substr($filename, 0, 4) . '-' . substr($filename, 4, 2) . '-' . substr($filename, 6, 2), $filename);
                    if ($is) {
                        $path = 'api/public/uploads/' . substr($filename, 0, 4) . '-' . substr($filename, 4, 2) . '-' . substr($filename, 6, 2) . '/' . $filename;
                        $insert = DB::insert('insert into hh_order_detail_img(order_detail_id,img_url) values (?,?)', [$order_detail_id[0]->id, $path]);
                        if ($insert) {
                            $ifinsert = true;
                        } else {
                            $ifinsert = false;
                        }
                    } else {
                        $arr = array("code" => "131",
                            "msg" => "上传失败"
                        );
                        return $callback . "(" . HHJson($arr) . ")";
                    }
                }
                if ($ifinsert) {
                    $arr = array("code" => "000",
                        "msg" => "添加成功",
                        "data" => $step
                    );
                    return $callback . "(" . HHJson($arr) . ")";
                } else {
                    $arr = array("code" => "111",
                        "msg" => "添加失败"
                    );
                    return $callback . "(" . HHJson($arr) . ")";
                }
            }
        } else {
            $arr = array("code" => "132",
                "msg" => "上传的文件无效"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }
    }

    //查看
    public function workDetail()
    {
        $callback = rq('callback');
        $order_id = rq('order_id');
        $order_step = rq('order_step');

        $order = DB::select('SELECT * FROM hh_order_detail WHERE order_id = ? AND order_step = ?', [$order_id, $order_step]);
        if ($order) {
            $order_img_id = $order[0]->id;
            $imgArr = DB::select('SELECT img_url FROM hh_order_detail_img WHERE order_detail_id = ?', [$order_img_id]);
            if ($imgArr) {
                $arr = array("code" => "000",
                    "data" => array("time" => $order[0]->img_time,
                        "content" => $order[0]->img_content,
                        "imgs" => $imgArr
                    )
                );
                return $callback . "(" . HHJson($arr) . ")";

            } else {
                $arr = array("code" => "130",
                    "msg" => "没有数据"
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        } else {
            $arr = array("code" => "132",
                "msg" => "没有该订单"
            );
            return $callback . "(" . HHJson($arr) . ")";
        }

    }

    //进度 编辑或者新增金额结算单
    public function AddOrEditbalance()
    {
        $order_id = rq('order_id');
        $order_personnel = $order_id;
        $callback = rq('callback');
        $list_data_json = rq('list_data_json');
        $list_data_arr = json_decode($list_data_json, true);
        $remark = rq('remark');
        if (!$remark) {
            $remark = '';
        }
        //查看订单是否存在
        $sel_order_tbl = DB::select('SELECT * FROM hh_order_actual_list WHERE order_id = ?', [$order_id]);

        if (!$sel_order_tbl) { //添加
            $reckon_list_tbl = DB::insert('INSERT INTO hh_order_actual_list(order_id,order_personnel,service1,service2,service3,service4,service5,service6,service7,service8,service9,service10,service11,service12,service13,service14,service15,service16,service17,service18,service19,service20,service21,service22,service23,service24,service25,service26,service27,service28,service29,service30,service31,service32,service33,service34,service35,service36,service37,service38,service39,service40,service41,service42,service43,service44,service45,service46,service47,service48,service49,service50,service51,service52,service53,service54,service55,service56,service57,service58,service59,service60,service61,service62,service63,is_available) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                [$order_id, $order_personnel, $list_data_arr[0], $list_data_arr[1], $list_data_arr[2], $list_data_arr[3], $list_data_arr[4], $list_data_arr[5], $list_data_arr[6],
                    $list_data_arr[7], $list_data_arr[8], $list_data_arr[9], $list_data_arr[10], $list_data_arr[11], $list_data_arr[12], $list_data_arr[13], $list_data_arr[14],
                    $list_data_arr[15], $list_data_arr[16], $list_data_arr[17], $list_data_arr[18], $list_data_arr[19], $list_data_arr[20], $list_data_arr[21], $list_data_arr[22],
                    $list_data_arr[23], $list_data_arr[24], $list_data_arr[25], $list_data_arr[26], $list_data_arr[27], $list_data_arr[28], $list_data_arr[29], $list_data_arr[30],
                    $list_data_arr[31], $list_data_arr[32], $list_data_arr[33], $list_data_arr[34], $list_data_arr[35], $list_data_arr[36], $list_data_arr[37], $list_data_arr[38],
                    $list_data_arr[39], $list_data_arr[40], $list_data_arr[41], $list_data_arr[42], $list_data_arr[43], $list_data_arr[44], $list_data_arr[45], $list_data_arr[46],
                    $list_data_arr[47], $list_data_arr[48], $list_data_arr[49], $list_data_arr[50], $list_data_arr[51], $list_data_arr[52], $list_data_arr[53], $list_data_arr[54],
                    $list_data_arr[55], $list_data_arr[56], $list_data_arr[57], $list_data_arr[58], $list_data_arr[59], $list_data_arr[60], $list_data_arr[61], $list_data_arr[62], 1]);

            if ($reckon_list_tbl) {
                $arr = array(
                    "code" => "000",
                    "msg" => "插入成功",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            } else {
                $arr = array(
                    "code" => "200",
                    "msg" => "插入失败",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        } else { //更新
            $shop_id = DB::SELECT('SELECT shop_id FROM hh_order WHERE order_id = ?', [$order_id]);
            $sel_shop_price = DB::select('SELECT * FROM hh_shop_price WHERE shop_id = ?',
                [$shop_id[0]->shop_id]);
            $yu_price = DB::SELECT('SELECT * FROM hh_order_reckon_list WHERE order_id = ?', [$order_id]);
            //获取当前时间转化为mysql时间戳格式
            $timenow = strtotime(date('Y-m-d H:i:s', time()));
            $mtime = explode(' ', microtime());
            $mtime[0] = ($mtime[0] + 1) * 1000000;
            $str1 = (string)$mtime[1];
            $str2 = substr((string)$mtime[0], 1);
            $str = $str1 . $str2 . rand(1000, 9999);
            $pay_id = $str;
            //判断订单步骤 控制结算单更改字段
            //获取订单步骤
            $sel_order_step = DB::select('SELECT order_step FROM hh_order WHERE order_id = ?',
                [$order_id]);
            if ($sel_order_step) {
                $order_step = $sel_order_step[0]->order_step;
                switch ($order_step) {
                    case 4:
                    case 5:
                        //杂工及水电工结算单数据修改
                        $actual_list_tbl = DB::update('update hh_order_actual_list SET service1 = ? ,service2 = ? ,service3 = ? ,service4 = ? ,service5 = ? ,service6 = ? ,service7 = ? ,service8 = ? ,service9 = ? ,service10 = ? ,service11 = ? ,service12 = ? ,service13 = ? ,service14 = ? ,service15 = ? ,service16 = ? ,service17 = ? ,service18 = ? ,remark = ? ,update_time = ? WHERE order_id = ?',
                            [$list_data_arr[0], $list_data_arr[1], $list_data_arr[2], $list_data_arr[3], $list_data_arr[4], $list_data_arr[5], $list_data_arr[6], $list_data_arr[7],
                                $list_data_arr[8], $list_data_arr[9], $list_data_arr[10], $list_data_arr[11], $list_data_arr[12], $list_data_arr[13], $list_data_arr[14], $list_data_arr[15],
                                $list_data_arr[16], $list_data_arr[17], $remark, $timenow, $order_id]);
                        $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage1 = ? WHERE order_id = ?', [1, $order_id]);
                        $gz = ($list_data_arr[63] - $yu_price[0]->foreman_price) * $sel_shop_price[0]->foreman_price;
                        $zg = ($list_data_arr[0] - $yu_price[0]->service1) * ($sel_shop_price[0]->service1);
                        $zg += ($list_data_arr[1] - $yu_price[0]->service2) * ($sel_shop_price[0]->service2);
                        $zg += ($list_data_arr[2] - $yu_price[0]->service3) * ($sel_shop_price[0]->service3);
                        $zg += ($list_data_arr[3] - $yu_price[0]->service4) * ($sel_shop_price[0]->service4);
                        $zg += ($list_data_arr[4] - $yu_price[0]->service5) * ($sel_shop_price[0]->service5);
                        $zg += ($list_data_arr[5] - $yu_price[0]->service6) * ($sel_shop_price[0]->service6);
                        $zg += ($list_data_arr[6] - $yu_price[0]->service7) * ($sel_shop_price[0]->service7);
                        $zg += ($list_data_arr[7] - $yu_price[0]->service8) * ($sel_shop_price[0]->service8);
                        $zg += ($list_data_arr[8] - $yu_price[0]->service9) * ($sel_shop_price[0]->service9);
                        $zg += ($list_data_arr[9] - $yu_price[0]->service10) * ($sel_shop_price[0]->service10);
                        $zg += ($list_data_arr[10] - $yu_price[0]->service11) * ($sel_shop_price[0]->service11);
                        $zg += ($list_data_arr[11] - $yu_price[0]->service12) * ($sel_shop_price[0]->service12);
                        $zg += ($list_data_arr[12] - $yu_price[0]->service13) * ($sel_shop_price[0]->service13);
                        $zg += ($list_data_arr[13] - $yu_price[0]->service14) * ($sel_shop_price[0]->service14);
                        $zg += ($list_data_arr[14] - $yu_price[0]->service15) * ($sel_shop_price[0]->service15);
                        $zg += ($list_data_arr[15] - $yu_price[0]->service16) * ($sel_shop_price[0]->service16);
                        $sdg = ($list_data_arr[16] - $yu_price[0]->service17) * ($sel_shop_price[0]->service17);
                        $sdg += ($list_data_arr[17] - $yu_price[0]->service18) * ($sel_shop_price[0]->service18);
                        $sum_next = $zg + $sdg + $gz;

                        $pay_amount1 = $sum_next;

                        $pay_amount2 = ($yu_price[0]->service19 * $sel_shop_price[0]->service19);
                        $pay_amount2 += ($yu_price[0]->service20 * $sel_shop_price[0]->service20);
                        $pay_amount2 += ($yu_price[0]->service21 * $sel_shop_price[0]->service21);
                        $pay_amount2 += ($yu_price[0]->service22 * $sel_shop_price[0]->service22);
                        $pay_amount2 += ($yu_price[0]->service23 * $sel_shop_price[0]->service23);
                        $pay_amount2 += ($yu_price[0]->service24 * $sel_shop_price[0]->service24);
                        $pay_amount2 += ($yu_price[0]->service25 * $sel_shop_price[0]->service25);
                        $pay_amount2 += ($yu_price[0]->service26 * $sel_shop_price[0]->service26);
                        $pay_amount2 += ($yu_price[0]->service27 * $sel_shop_price[0]->service27);
                        $pay_amount2 += ($yu_price[0]->service28 * $sel_shop_price[0]->service28);
                        $pay_amount2 += ($yu_price[0]->service29 * $sel_shop_price[0]->service29);
                        $pay_amount2 += ($yu_price[0]->service30 * $sel_shop_price[0]->service30);
                        $pay_amount2 += ($yu_price[0]->service31 * $sel_shop_price[0]->service31);
                        $pay_amount2 += ($yu_price[0]->service32 * $sel_shop_price[0]->service32);
                        $pay_amount2 += ($yu_price[0]->service33 * $sel_shop_price[0]->service33);
                        $pay_amount2 += ($yu_price[0]->service34 * $sel_shop_price[0]->service34);
                        $pay_amount2 += ($yu_price[0]->service35 * $sel_shop_price[0]->service35);
                        $pay_amount2 += ($yu_price[0]->service36 * $sel_shop_price[0]->service36);
                        $pay_amount2 += ($yu_price[0]->service37 * $sel_shop_price[0]->service37);
                        $pay_amount2 += ($yu_price[0]->service38 * $sel_shop_price[0]->service38);
                        $pay_amount2 += ($yu_price[0]->service39 * $sel_shop_price[0]->service39);
                        $pay_amount2 += ($yu_price[0]->service40 * $sel_shop_price[0]->service40);
                        $pay_amount2 += ($yu_price[0]->service41 * $sel_shop_price[0]->service41);
                        $sum_next += $pay_amount2;

                        $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                        if ($order_pay) {
                            $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                            $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $sum_next, 2, $order_id]);
                            $pay_each1 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 5, $pay_amount1, 1]);
                            $pay_each2 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 3, 5, $pay_amount2, 1]);
                        }
                        break;
                    case 8:
                    case 9:
                        //瓦工结算单数据修改
                        $actual_list_tbl = DB::update('UPDATE hh_order_actual_list SET service19 = ? ,service20 = ? ,service21 = ? ,service22 = ? ,service23 = ? ,service24 = ? ,service25 = ? ,service26 = ? ,service27 = ? ,service28 = ? ,service29 = ? ,service30 = ? ,service31 = ? ,service32 = ? ,service33 = ? ,service34 = ? ,service35 = ? ,service36 = ? ,service37 = ? ,service38 = ? ,service39 = ? ,service40 = ? ,service41 = ? ,remark = ? ,update_time = ? WHERE order_id = ?',
                            [$list_data_arr[18], $list_data_arr[19], $list_data_arr[20], $list_data_arr[21], $list_data_arr[22], $list_data_arr[23],
                                $list_data_arr[24], $list_data_arr[25], $list_data_arr[26], $list_data_arr[27], $list_data_arr[28], $list_data_arr[29], $list_data_arr[30], $list_data_arr[31],
                                $list_data_arr[32], $list_data_arr[33], $list_data_arr[34], $list_data_arr[35], $list_data_arr[36], $list_data_arr[37], $list_data_arr[38], $list_data_arr[39],
                                $list_data_arr[40], $remark, $timenow, $order_id]);
                        $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage2 = ? WHERE order_id = ?', [1, $order_id]);
                        $wg = ($list_data_arr[18] - $yu_price[0]->service19) * ($sel_shop_price[0]->service19);
                        $wg += ($list_data_arr[19] - $yu_price[0]->service20) * ($sel_shop_price[0]->service20);
                        $wg += ($list_data_arr[20] - $yu_price[0]->service21) * ($sel_shop_price[0]->service21);
                        $wg += ($list_data_arr[21] - $yu_price[0]->service22) * ($sel_shop_price[0]->service22);
                        $wg += ($list_data_arr[22] - $yu_price[0]->service23) * ($sel_shop_price[0]->service23);
                        $wg += ($list_data_arr[23] - $yu_price[0]->service24) * ($sel_shop_price[0]->service24);
                        $wg += ($list_data_arr[24] - $yu_price[0]->service25) * ($sel_shop_price[0]->service25);
                        $wg += ($list_data_arr[25] - $yu_price[0]->service26) * ($sel_shop_price[0]->service26);
                        $wg += ($list_data_arr[26] - $yu_price[0]->service27) * ($sel_shop_price[0]->service27);
                        $wg += ($list_data_arr[27] - $yu_price[0]->service28) * ($sel_shop_price[0]->service28);
                        $wg += ($list_data_arr[28] - $yu_price[0]->service29) * ($sel_shop_price[0]->service29);
                        $wg += ($list_data_arr[29] - $yu_price[0]->service30) * ($sel_shop_price[0]->service30);
                        $wg += ($list_data_arr[30] - $yu_price[0]->service31) * ($sel_shop_price[0]->service31);
                        $wg += ($list_data_arr[31] - $yu_price[0]->service32) * ($sel_shop_price[0]->service32);
                        $wg += ($list_data_arr[32] - $yu_price[0]->service33) * ($sel_shop_price[0]->service33);
                        $wg += ($list_data_arr[33] - $yu_price[0]->service34) * ($sel_shop_price[0]->service34);
                        $wg += ($list_data_arr[34] - $yu_price[0]->service35) * ($sel_shop_price[0]->service35);
                        $wg += ($list_data_arr[35] - $yu_price[0]->service36) * ($sel_shop_price[0]->service36);
                        $wg += ($list_data_arr[36] - $yu_price[0]->service37) * ($sel_shop_price[0]->service37);
                        $wg += ($list_data_arr[37] - $yu_price[0]->service38) * ($sel_shop_price[0]->service38);
                        $wg += ($list_data_arr[38] - $yu_price[0]->service39) * ($sel_shop_price[0]->service39);
                        $wg += ($list_data_arr[39] - $yu_price[0]->service40) * ($sel_shop_price[0]->service40);
                        $wg += ($list_data_arr[40] - $yu_price[0]->service41) * ($sel_shop_price[0]->service41);
                        $pay_amount1 = $wg;

                        $pay_amount2 = ($yu_price[0]->service42 * $sel_shop_price[0]->service42);
                        $pay_amount2 += ($yu_price[0]->service43 * $sel_shop_price[0]->service43);
                        $pay_amount2 += ($yu_price[0]->service44 * $sel_shop_price[0]->service44);
                        $pay_amount2 += ($yu_price[0]->service45 * $sel_shop_price[0]->service45);
                        $pay_amount2 += ($yu_price[0]->service46 * $sel_shop_price[0]->service46);
                        $pay_amount2 += ($yu_price[0]->service47 * $sel_shop_price[0]->service47);
                        $pay_amount2 += ($yu_price[0]->service48 * $sel_shop_price[0]->service48);
                        $pay_amount2 += ($yu_price[0]->service49 * $sel_shop_price[0]->service49);
                        $pay_amount2 += ($yu_price[0]->service50 * $sel_shop_price[0]->service50);
                        $pay_amount2 += ($yu_price[0]->service51 * $sel_shop_price[0]->service51);
                        $pay_amount2 += ($yu_price[0]->service52 * $sel_shop_price[0]->service52);
                        $pay_amount2 += ($yu_price[0]->service53 * $sel_shop_price[0]->service53);
                        $wg += $pay_amount2;

                        $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                        if ($order_pay) {
                            $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                            $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $wg, 3, $order_id]);
                            $pay_each1 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 9, $pay_amount1, 1]);
                            $pay_each2 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 4, 9, $pay_amount2, 1]);
                        }
                        break;
                    case 12:
                    case 13:
                        //木工结算单数据修改
                        $actual_list_tbl = DB::update('UPDATE hh_order_actual_list SET service42 = ? ,service43 = ? ,service44 = ? ,service45 = ? ,service46 = ? ,service47 = ? ,service48 = ? ,service49 = ? ,service50 = ? ,service51 = ? ,service52 = ? ,service53 = ? ,remark = ? ,update_time = ? WHERE order_id = ?',
                            [$list_data_arr[41], $list_data_arr[42], $list_data_arr[43], $list_data_arr[44], $list_data_arr[45], $list_data_arr[46], $list_data_arr[47],
                                $list_data_arr[48], $list_data_arr[49], $list_data_arr[50], $list_data_arr[51], $list_data_arr[52], $remark, $timenow, $order_id]);
                        $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage3 = ? WHERE order_id = ?', [1, $order_id]);
                        $mg = ($list_data_arr[41] - $yu_price[0]->service42) * ($sel_shop_price[0]->service42);
                        $mg += ($list_data_arr[42] - $yu_price[0]->service43) * ($sel_shop_price[0]->service43);
                        $mg += ($list_data_arr[43] - $yu_price[0]->service44) * ($sel_shop_price[0]->service44);
                        $mg += ($list_data_arr[44] - $yu_price[0]->service45) * ($sel_shop_price[0]->service45);
                        $mg += ($list_data_arr[45] - $yu_price[0]->service46) * ($sel_shop_price[0]->service46);
                        $mg += ($list_data_arr[46] - $yu_price[0]->service47) * ($sel_shop_price[0]->service47);
                        $mg += ($list_data_arr[47] - $yu_price[0]->service48) * ($sel_shop_price[0]->service48);
                        $mg += ($list_data_arr[48] - $yu_price[0]->service49) * ($sel_shop_price[0]->service49);
                        $mg += ($list_data_arr[49] - $yu_price[0]->service50) * ($sel_shop_price[0]->service50);
                        $mg += ($list_data_arr[50] - $yu_price[0]->service51) * ($sel_shop_price[0]->service51);
                        $mg += ($list_data_arr[51] - $yu_price[0]->service52) * ($sel_shop_price[0]->service52);
                        $mg += ($list_data_arr[52] - $yu_price[0]->service53) * ($sel_shop_price[0]->service53);

                        $pay_amount1 = $mg;

                        $pay_amount2 = ($yu_price[0]->service54 * $sel_shop_price[0]->service54);
                        $pay_amount2 += ($yu_price[0]->service55 * $sel_shop_price[0]->service55);
                        $pay_amount2 += ($yu_price[0]->service56 * $sel_shop_price[0]->service56);
                        $pay_amount2 += ($yu_price[0]->service57 * $sel_shop_price[0]->service57);
                        $pay_amount2 += ($yu_price[0]->service58 * $sel_shop_price[0]->service58);
                        $pay_amount2 += ($yu_price[0]->service59 * $sel_shop_price[0]->service59);
                        $pay_amount2 += ($yu_price[0]->service60 * $sel_shop_price[0]->service60);
                        $pay_amount2 += ($yu_price[0]->service61 * $sel_shop_price[0]->service61);
                        $pay_amount2 += ($yu_price[0]->service62 * $sel_shop_price[0]->service62);
                        $pay_amount2 += ($yu_price[0]->service63 * $sel_shop_price[0]->service63);
                        $mg += $pay_amount2;

                        $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                        if ($order_pay) {
                            $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                            $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $mg, 4, $order_id]);
                            $pay_each1 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 13, $pay_amount1, 1]);
                            $pay_each2 = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 5, 13, $pay_amount2, 1]);
                        }
                        break;
                    case 16:
                    case 17:
                        $actual_list_tbl = DB::update('UPDATE hh_order_actual_list SET service54 = ? ,service55 = ? ,service56 = ? ,service57 = ? ,service58 = ? ,service59 = ? ,service60 = ? ,service61 = ? ,service62 = ? ,service63 = ? ,remark = ? ,update_time = ? WHERE order_id = ?',
                            [$list_data_arr[53], $list_data_arr[54], $list_data_arr[55],
                                $list_data_arr[56], $list_data_arr[57], $list_data_arr[58], $list_data_arr[59], $list_data_arr[60], $list_data_arr[61], $list_data_arr[62], $remark, $timenow, $order_id]);
                        $upd_order_actual_isclick = DB::update('UPDATE hh_order_actual_isclick SET stage4 = ? WHERE order_id = ?', [1, $order_id]);
                        $yqg = ($list_data_arr[53] - $yu_price[0]->service54) * $sel_shop_price[0]->service54;
                        $yqg += ($list_data_arr[54] - $yu_price[0]->service54) * $sel_shop_price[0]->service55;
                        $yqg += ($list_data_arr[55] - $yu_price[0]->service55) * $sel_shop_price[0]->service56;
                        $yqg += ($list_data_arr[56] - $yu_price[0]->service56) * $sel_shop_price[0]->service57;
                        $yqg += ($list_data_arr[57] - $yu_price[0]->service57) * $sel_shop_price[0]->service58;
                        $yqg += ($list_data_arr[58] - $yu_price[0]->service58) * $sel_shop_price[0]->service59;
                        $yqg += ($list_data_arr[59] - $yu_price[0]->service59) * $sel_shop_price[0]->service60;
                        $yqg += ($list_data_arr[60] - $yu_price[0]->service60) * $sel_shop_price[0]->service61;
                        $yqg += ($list_data_arr[61] - $yu_price[0]->service61) * $sel_shop_price[0]->service62;
                        $yqg += ($list_data_arr[62] - $yu_price[0]->service62) * $sel_shop_price[0]->service63;
                        $order_pay = DB::SELECT('SELECT actual_finish_amount,actual_next_amount FROM hh_order_pay where order_id = ?', [$order_id]);
                        if ($order_pay) {
                            $new_finish_count = $order_pay[0]->actual_next_amount + $order_pay[0]->actual_finish_amount;
                            $new_finish = DB::update('update hh_order_pay SET actual_finish_amount = ?,actual_next_amount = ?, order_pay_step = ? WHERE order_id = ?', [$new_finish_count, $yqg, 5, $order_id]);
                            $pay_each = DB::insert('INSERT INTO hh_order_pay_each(order_id,pay_id,order_pay_step,order_step,pay_amount,pay_status) VALUES (?,?,?,?,?,?)', [$order_id, $pay_id, 10, 17, $yqg, 1]);

                        }
                        break;
                    default:
                        $arr = array(
                            "code" => "200",
                            "msg" => "订单当前状态无法修改结算单",
                            "data" => ""
                        );
                        return $callback . "(" . HHJson($arr) . ")";
                }
            }
            if ($actual_list_tbl) {
                $arr = array(
                    "code" => "000",
                    "msg" => "更新成功",
                    "data" => ""
                );
                return $callback . "(" . HHJson($arr) . ")";
            } else {
                $arr = array(
                    "code" => "200",
                    "msg" => "更新失败",
                    "data" => $list_data_arr
                );
                return $callback . "(" . HHJson($arr) . ")";
            }
        }
    }

}