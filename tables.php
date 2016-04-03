<?php
/**
 * Created by PhpStorm.
 * User: ruartel
 * Date: 1/30/16
 * Time: 11:44 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . '/meekroDB.php';


for($i=2; $i < 21; $i++) {
    $inputFileName = 'excel/' . $i . '.xls';

//  Read your Excel workbook
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

//  Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

//  Loop through each row of the worksheet in turn
    for ($row = 2; $row <= $highestRow; $row++){
        //  Read a row of data into an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL,
            TRUE,
            FALSE);
//        print_r($rowData);

        $insertRow = array();
        $insertRow['id'] = $rowData[0][0];
        $insertRow['name'] = $rowData[0][1];
        $insertRow['address'] = $rowData[0][6];
        $insertRow['PRIZE_NAME'] = $rowData[0][8];
        $insertRow['work_phone'] = $rowData[0][10];
        $insertRow['phone'] = $rowData[0][11];
        $insertRow['sponsor'] = $rowData[0][7];

        //  Insert row data array into your database of choice here
        DB::insertIgnore('p' . $i, array(
            'id' => $rowData[0][0],
            'name' => $rowData[0][1],
            'address' => $rowData[0][6],
            'PRIZE_NAME' => $rowData[0][8],
            'work_phone' => $rowData[0][10],
            'phone' => $rowData[0][11],
            'sponsor' => $rowData[0][7]
        ));
    }
}