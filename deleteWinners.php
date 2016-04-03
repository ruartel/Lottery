<?php
/**
 * Created by PhpStorm.
 * User: ruartel
 * Date: 1/27/16
 * Time: 11:43 PM
 */
require_once dirname(__FILE__) . '/meekroDB.php';

$results = DB::query("SELECT * FROM winners");
foreach($results as $r){
    DB::delete('winners','id=%i', $r['id']);
}
