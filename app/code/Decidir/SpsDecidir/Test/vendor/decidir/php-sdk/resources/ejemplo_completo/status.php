<?php
include_once dirname(__FILE__)."/FlatDb.php";
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$http_header = array('Authorization'=>'PRISMA RV82RVHO5T0O5CZUUTX2FLHU',
 'user_agent' => 'PHPSoapClient');

//datos constantes
define('CURRENCYCODE', 032);
define('MERCHANT', 22067736);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', 'RV82RVHO5T0O5CZUUTX2FLHU');


$operationid = $_GET['ord'];

$db = new FlatDb();
$db->openTable('ordenes');

$orden = $db->getRecords(array("id","status","data","mediodepago","sar","form","gaa","requestkey","answerkey"),array("id" => $operationid));

$data = json_decode($orden[0]['data'],true);

$connector = new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST);

$gaa_data = new \Decidir\Operation\GetByOperationId\Data(array("idsite" => MERCHANT, "idtransactionsit" => $operationid));

try{
	$rta = $connector->Operation()->getByOperationId($gaa_data);
} catch(\Exception $e) {
	var_dump($e);die();
}
var_dump($rta);

?>
<a href="index.php">Volver</a>
