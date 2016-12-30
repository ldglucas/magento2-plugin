<?php
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$http_header = array('Authorization'=>'PRISMA RV82RVHO5T0O5CZUUTX2FLHU',
 'user_agent' => 'PHPSoapClient');

//datos constantes
define('CURRENCYCODE', 032);
define('MERCHANT', 22067736);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', 'RV82RVHO5T0O5CZUUTX2FLHU');

//creo instancia de la SDK
$connector = new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST);


//SendAuthorizeRequest
$medio_pago = new Decidir\Data\Mediopago\TarjetaCredito(array("medio_pago" => 1, "cuotas" => 6));

$sar_data = new Decidir\Authorize\SendAuthorizeRequest\Data(array(
	"security" => SECURITY, 
	"encoding_method" => ENCODINGMETHOD, 
	"merchant" => MERCHANT, 
	"nro_operacion" => 123456, 
	"monto" => 50.00, 
	"email_cliente" => "ejemplo@misitio.com"
));
$sar_data->setMedioPago($medio_pago);

$rta = $connector->Authorize()->sendAuthorizeRequest($sar_data);

echo "<h3>Respuesta SendAuthorizeRequest</h3>"
var_dump($rta);


//GetAuthorizeAnswer
$gaa_data = new \Decidir\Authorize\GetAuthorizeAnswer\Data(array(
	"security" => SECURITY, 
	"merchant" => MERCHANT, 
	"requestKey" => 'cdf96aaf-dd1c-195b-eeee-130a3df96110', 
	"answerKey" => '77215fe6-f9d5-f1c2-372b-c0065e0c4429'
));

$rta = $connector->Authorize()->getAuthorizeAnswer($gaa_data);

echo "<h3>Respuesta GetAuthorizeAnswer</h3>"
var_dump($rta);

//GetOperationById
$gobi_data = new \Decidir\Operation\GetByOperationId\Data(array("idsite" => MERCHANT, "idtransactionsit" => 123456));

$rta = $connector->Operation()->getByOperationId($gobi_data);

echo "<h3>Respuesta GetOperationById</h3>"
var_dump($rta);

//Execute - Anulacion
$anul = new \Decidir\Authorize\Execute\Anulacion(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => 123456));

$rta = $connector->Authorize()->execute($anul);

echo "<h3>Respuesta Execute - Anulacion</h3>"
var_dump($rta);

//Execute - Devolucion Totoal
$devol = new \Decidir\Authorize\Execute\Devolucion\Total(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => 123456));

$rta = $connector->Authorize()->execute($devol);

echo "<h3>Respuesta Execute - Devolucion Total</h3>"
var_dump($rta);

//Execute - Devolucion Parcial
$devol = new \Decidir\Authorize\Execute\Devolucion\Parcial(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => 123456, "monto" => 10.00));

$rta = $connector->Authorize()->execute($devol);

echo "<h3>Respuesta Execute - Devolucion Parcial</h3>"
var_dump($rta);