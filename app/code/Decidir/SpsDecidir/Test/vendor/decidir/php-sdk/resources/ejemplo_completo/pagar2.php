<?php
include_once dirname(__FILE__)."/FlatDb.php";
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$operationid = $_GET['ord'];
$answer = $_GET['pa'];

$db = new FlatDb();
$db->openTable('ordenes');

$db->updateRecords(array("form" => 1, "answerkey" => $answer),array("id" => $operationid));
header("Location: index.php");
