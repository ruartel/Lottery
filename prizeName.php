<?php
/**
 * Created by PhpStorm.
 * User: ruartel
 * Date: 1/27/16
 * Time: 10:39 PM
 */
require_once dirname(__FILE__) . '/meekroDB.php';

$tableName = 'p' . $_GET['n'];
$sql = "SELECT * from $tableName limit 1";
$result = DB::query($sql);

$resp = array();
$resp['sponsor'] = $result[0]['sponsor'];
$resp['name'] = $result[0]['PRIZE_NAME'];

echo  json_encode($resp);