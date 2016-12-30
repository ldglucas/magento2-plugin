<?php
include_once dirname(__FILE__)."/FlatDb.php";
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$operationid = $_GET['ord'];

$db = new FlatDb();
$db->openTable('ordenes');

$orden = $db->getRecords(array("id","status","data","mediodepago","sar","form","gaa","requestkey","answerkey"),array("id" => $operationid));

$data = json_decode($orden[0]['data'],true);


//común a todas los métodos
$http_header = array('Authorization'=>'PRISMA RV82RVHO5T0O5CZUUTX2FLHU',
 'user_agent' => 'PHPSoapClient');

//datos constantes
define('CURRENCYCODE', 032);
define('MERCHANT', 22067736);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', 'RV82RVHO5T0O5CZUUTX2FLHU');

$medio = $data['mediopago']['tipo'];
$data['mediopago']['medio_pago'] = $medio;
unset($data['mediopago']['tipo']);

if($medio == 26)  {
	$medio_pago = new Decidir\Data\Mediopago\Rapipago($data['mediopago']);
} else if($medio == 25) {
	$medio_pago = new Decidir\Data\Mediopago\PagoFacil($data['mediopago']);
} else if($medio == 41) {
	$medio_pago = new Decidir\Data\Mediopago\PagoMisCuentas($data['mediopago']);
} else {
	$medio_pago = new Decidir\Data\Mediopago\TarjetaCredito($data['mediopago']);
}

$cybersource = new Decidir\Data\Cybersource\Retail(
	$data['cs_data'],
	$data['cs_product']
);
$sar_data = new Decidir\Authorize\SendAuthorizeRequest\Data(array("security" => SECURITY, "encoding_method" => ENCODINGMETHOD, "merchant" => MERCHANT, "nro_operacion" => $operationid, "monto" => $data['monto'], "email_cliente" => $data["email_cliente"]));
$sar_data->setMedioPago($medio_pago);
//$sar_data->setCybersourceData($cybersource);

//creo instancia de la SDK
$connector = new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST);

try {
	$rta = $connector->Authorize()->sendAuthorizeRequest($sar_data);
} catch(Exception $e) {
	var_dump($e);die();
	$db->updateRecords(array("status" => "ERROR SAR"),array("id" => $operationid));
	header("Location: index.php");
}

$db->updateRecords(array("sar" => 1, "status" => "AUTORIZACION ENVIADA", "requestkey" => $rta->getRequestKey(), "publicrequestkey" => $rta->getPublicRequestKey()),array("id" => $operationid));
header("Location: index.php");

?>