<?php
/**
 * Created by PhpStorm.
 * User: ruartel
 * Date: 1/27/16
 * Time: 11:25 PM
 */
require_once dirname(__FILE__) . '/meekroDB.php';

$sql = "SELECT * from winners";
$result = DB::query($sql);
echo json_encode($result);