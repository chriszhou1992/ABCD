<?php

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

require_once "classes/Company.php";
ini_set('max_execution_time', 300000);
$id = 0;
//done: from 0 to 6000 with most fields non-empty
while($id !== 12000) {
    $url = "http://www.giantbomb.com/api/company/3010-".$id."/?api_key=cff26a832d7766443856b114c9764df2b8def7d0&format=json";
    $c = new Company(true, $url, $con);
    //$c->printCompanyInfo();
    $c->insert();
    $id++;
}

