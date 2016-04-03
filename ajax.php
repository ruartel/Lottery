<?php
/**
 * Created by PhpStorm.
 * User: ruartel
 * Date: 1/24/16
 * Time: 11:36 PM
 */
require_once dirname(__FILE__) . '/meekroDB.php';

$tableName = 'p' . $_GET['n'];
$sql = "SELECT COUNT(id) as total from $tableName";
$result = DB::query($sql);
// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, 'https://www.random.org/integers/?num=1&min=1&max=' . $result[0]['total'] . '&col=1&base=10&format=plain&rnd=new');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// $output contains the output string
$output = curl_exec($ch);
// close curl resource to free up system resources
curl_close($ch);

echo $output;