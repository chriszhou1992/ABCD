<?php


require_once "classes/Game.php";
ini_set('max_execution_time', 300000);

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;
//done: from 27000 to 40000 with date-released >= 2008
$id = 27000;
while($id !== 60000) {
    $url = "http://www.giantbomb.com/api/game/".$id."/?api_key=cff26a832d7766443856b114c9764df2b8def7d0&format=json";
    $g = new Game(true, $url, $con);
    //$g->printGameInfo();
    $g->insert();
    $id++;
}


/*
require_once "classes/Company.php";
ini_set('max_execution_time', 300000);
$id = 0;
//done: from 0 to 6000 with most fields non-empty
while($id !== 6000) {
    $url = "http://www.giantbomb.com/api/company/3010-".$id."/?api_key=cff26a832d7766443856b114c9764df2b8def7d0&format=json";
    $c = new Company(true, $url);
    //$c->printCompanyInfo();
    $c->insert();
    $id++;
}
 */