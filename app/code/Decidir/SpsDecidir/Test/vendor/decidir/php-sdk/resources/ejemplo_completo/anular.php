<?php
include_once dirname(__FILE__)."/FlatDb.php";
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$operationid = $_GET['ord'];

$db = new FlatDb();
$db->openTable('ordenes');

$http_header = array('Authorization'=>'PRISMA RV82RVHO5T0O5CZUUTX2FLHU',
 'user_agent' => 'PHPSoapClient');

//datos constantes
define('CURRENCYCODE', 032);
define('MERCHANT', 22067736);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', 'RV82RVHO5T0O5CZUUTX2FLHU');

$connector = new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST);

$anul = new \Decidir\Authorize\Execute\Anulacion(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => $operationid));

try {
	$rta = $connector->Authorize()->execute($anul);
} catch(Exception $e) {
	var_dump($e);die();
}

$db->updateRecords(array("status" => "ANULADA"),array("id" => $operationid));
header("Location: index.php");
