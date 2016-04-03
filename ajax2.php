<?php
/**
 * Created by PhpStorm.
 * User: ruartel
 * Date: 1/25/16
 * Time: 1:41 AM
 */
require_once dirname(__FILE__) . '/meekroDB.php';

$tableName = 'p' . $_GET['n'];
$sql = "SELECT * from $tableName WHERE id=" . $_GET['s'];
$result = DB::query($sql);

//save winner to DB
$row = array();
$row['prize_num']=$_GET['n'];
$row['prize_name']=$result[0]['PRIZE_NAME'];
$row['w_name']=$result[0]['name'];
if(!is_null($result[0]['work_phone'])){
    $phone = $result[0]['work_phone'];
}else{
    $phone = $result[0]['phone'];
}
$row['phone']=$phone;
$row['address']=$result[0]['address'];
$row['serial']=$_GET['s'];

$result1 = DB::insert('winners',$row);

echo json_encode($result);