<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/17
 * Time: 15:37
 */

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController  extends Controller
{
     /**
      * 订单概述页
      * by 王振
      * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
      */
    public function  index()
    {
        $obj= $this->order(7);
        print_r($obj);
        return view('admin.order.index');
    }

     /**
      * Ajax获取订单概述数据
      */
    public  function  orderSummary()
    {

    }

     /**根据天数获取订单数据
      * @param $day
      * @return mixed
      */
     protected function order($day)
     {
         global $_GPC;
         $day = (int) $day;
         $orderPrice = $this->selectOrderPrice($day);
         $orderPrice['avg'] = (empty($orderPrice['count']) ? 0 : round($orderPrice['price'] / $orderPrice['count'], 1));
         unset($orderPrice['fetchall']);
         return $orderPrice;
     }

     /**
      * @param int $day
      * @return array
      */
     protected function selectOrderPrice($day = 0)
     {
         global $_W;
         $day = (int) $day;
         $uniacid=65;
         if ($day != 0)
         {
             $createtime1 = strtotime(date('Y-m-d', time() - ($day * 3600 * 24)));
             $createtime2 = strtotime(date('Y-m-d', time()));
         }
         else
         {
             $createtime1 = strtotime(date('Y-m-d', time()));
             $createtime2 = strtotime(date('Y-m-d', time() + (3600 * 24)));
         }
        $pdo_res = DB::table('order')->where('uniacid',$uniacid)->get();
         print_r($pdo_res);
         exit;
         $sql = 'select id,price,createtime from ' . tablename('weshop_order') . ' where uniacid = :uniacid and ismr=0 and isparent=0 and (status > 0 or ( status=0 and paytype=3)) and deleted=0 and createtime between :createtime1 and :createtime2';
         $param = array(':uniacid' => $_W['uniacid'], ':createtime1' => $createtime1, ':createtime2' => $createtime2);
         $pdo_res = pdo_fetchall($sql, $param);
         $price = 0;
         foreach ($pdo_res as $arr )
         {
             $price += $arr['price'];
         }
         $result = array('price' => round($price, 1), 'count' => count($pdo_res), 'fetchall' => $pdo_res);
         return $result;
     }
}