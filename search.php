<?php

$search = $_POST['search'];

require_once 'classes/Query.php';
$query = new Query();
$result = $query->queryDatabase($search);

if(count($result) === 0):
    echo "<div class='alert alert-black alert-dismissable'>
                <button type='button' class='close customClose' data-dismiss='alert' aria-hidden='true'><i class='fa fa-times'></i></button>
                <i class='fa fa-exclamation-triangle customWarning'></i>&nbsp&nbsp
                Sorry, there is no game or company whose name contains <strong class='customStrong'>'&nbsp$search&nbsp'</strong> found.
         </div>";
    return;
endif;


echo "<div class='alert alert-black alert-dismissable'>
        <button type='button' class='close customClose' data-dismiss='alert' aria-hidden='true'><i class='fa fa-times'></i></button>
            <i class='fa fa-info-circle customInfo'></i>&nbsp&nbsp&nbsp";
if(count($result) === 1) {
    echo "There is &nbsp<strong class='customStrong'>".count($result)."</strong>&nbsp game or company whose name contains <strong class='customStrong'>'&nbsp$search&nbsp'</strong> found.";
} else {
    echo "There are &nbsp<strong class='customStrong'>".count($result)."</strong>&nbsp games / companies whose names contain <strong class='customStrong'>&nbsp'&nbsp$search&nbsp'</strong> found.";
}
echo "</div><div class='dismissableHR'><br></div>";


foreach($result as $record):
    $imageSrc = $record['imageURL'];
    echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
    $name = $record['name'];
    $send = str_replace("'", "!-!-!", $name);
    if(isset($record['headquarter'])) {
        echo "<span class='companies' name='$send'>$name</span><hr>";
    } else {
        echo "<span class='games' name='$send'>$name</span><hr>";
    }
    $brief = $record['brief'];
    echo "$brief<hr>";
endforeach;
    