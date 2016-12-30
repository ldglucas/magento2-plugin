<?php
include_once dirname(__FILE__)."/FlatDb.php";
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$operationid = $_GET['ord'];

define('CURRENCYCODE', 032);
define('MERCHANT', 22067736);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', 'RV82RVHO5T0O5CZUUTX2FLHU');


if($_POST) {
	$db = new FlatDb();
	$db->openTable('ordenes');

	$orden = $db->getRecords(array("id","status","data","mediodepago","sar","form","gaa","requestkey","answerkey"),array("id" => $operationid));
	
	$tipo = $_POST['tipo'];
	$monto = $_POST['monto'];
	
	if($tipo == "total") {
		$anul = new \Decidir\Authorize\Execute\Devolucion\Total(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => $operationid));
	} else {
		$anul = new \Decidir\Authorize\Execute\Devolucion\Parcial(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => $operationid, "monto" => $monto));	
	}
	
	$http_header = array('Authorization'=>'PRISMA RV82RVHO5T0O5CZUUTX2FLHU',
	 'user_agent' => 'PHPSoapClient');

	$connector = new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST);

	try {
		$rta = $connector->Authorize()->execute($anul);
	} catch(Exception $e) {
		var_dump($e);die();
	}

	$db->updateRecords(array("status" => "DEVUELTA"),array("id" => $operationid));
	header("Location: index.php");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Administrador</title>
	<meta name="description" content="kleith web site" />
	<meta name="keywords" content="html, css, js, php" />
        <!-- Le styles -->
<link href="css/css.css" media="screen" rel="stylesheet" type="text/css">

<script type="text/javascript" src="js/js.js"></script>

 <style>
.ui-tooltip {
padding: 10px 20px;
color: black;
background-color:white;
width: 200px;
text-align:center;
border-radius: 20px;
font: bold 14px "Helvetica Neue", Sans-Serif;
text-transform: uppercase;
box-shadow: 0 0 7px black;

}
</style>     
</head>
<body>
<div id="container">
	<div id="content">
		<div class="w-content">
		  <div class="w-section"></div>
<div id="m-status" style="margin-bottom: 300px">

	<div class="block">
	<form id="activeform" method="POST" action="devolver.php?ord=<?php echo $operationid; ?>" enctype="multipart/form-data">
		<table id="tablelist" class="full tablesorter">
			<tbody>
										  <tr>
				  <td><b>Tipo Devolucion</b></td><td><select name="tipo"><option value="total">Total</option><option value="parcial">Parcial</option></select></td>
				  </tr>
					<tr>
				  <td><b>Monto</b></td><td><input type="text" name="monto" value="10.00"></input></td>
				  </tr>				
				</tbody>	
			<tfoot>
			  <tr>
				<td colspan="2"><a href="index.php" class="btn error site">Cancelar</a>&nbsp;&nbsp;&nbsp;<a href="devolver.php" onclick="$('#activeform').submit();return false;" class="btn site" id="send">Enviar</a></td>
			  </tr>
			</tfoot>
		</table>		
	</form>
	</div>
</div> 
		</div>
		<div class="clearfix"></div>
	</div>
	<div id="footer">
	</div>
</div>
</body>
</html>