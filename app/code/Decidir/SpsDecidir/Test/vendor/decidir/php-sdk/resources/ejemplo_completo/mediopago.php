<?php
include_once dirname(__FILE__)."/FlatDb.php";

$operationid = $_GET['ord'];

if($_POST) {
	$db = new FlatDb();
	$db->openTable('ordenes');

	$orden = $db->getRecords(array("id","status","data","mediodepago","sar","form","gaa","requestkey","answerkey"),array("id" => $operationid));
	
	$data = json_decode($orden[0]['data'],true);
	
	$medio = $_POST['tipo'];
	if($medio == 26)  {
		$data = array_merge($data, array("mediopago" => array(
			"tipo" => $medio,
			"cantdiasfechavenc" => $_POST['rpcantdiasfechavenc'],
			"cantdiaspago" => $_POST['rpcantdiaspago'],
			"recargo" => $_POST['rprecargo'],
			"fechavto" => $_POST['rpfechavto'],
			"cliente" => $_POST['rpcliente'],
		)));		
	} else if($medio == 25) {
		$data = array_merge($data, array("mediopago" => array(
			"tipo" => $medio,
			"recargo" => $_POST['pfrecargo'],
			"fechavto" => $_POST['pffechavto'],
			"fechavto2" => $_POST['pffechavto2'],
		)));			
	} else if($medio == 41) {
		$data = array_merge($data, array("mediopago" => array(
			"tipo" => $medio,
			"fechavto" => $_POST['pmcfechavto'],
		)));
	} else {
		$data = array_merge($data, array("mediopago" => array(
			"tipo" => $medio,
			"cuotas" => $_POST['cuotas'],
		)));		
	}

	$db->updateRecords(array("data" => json_encode($data),"mediodepago" => 1),array("id" => $operationid));
	
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
	<form id="activeform" method="POST" action="mediopago.php?ord=<?php echo $operationid; ?>" enctype="multipart/form-data">
		<table id="tablelist" class="full tablesorter">
			<tbody>
										  <tr>
				  <td><b>Medio Pago</b></td><td><select name="tipo"><option value="1">VISA</option><option value="6" >AMEX</option><option value="15" >MASTERCARD</option><option value="25" >PAGOFACIL</option><option value="26" >RAPIPAGO</option><option value="41" >PAGOMISCUENTAS</option></select></td>
				  </tr>
				<tr>
				  <td><b>Cuotas</b></td><td><select name="cuotas"><option value="1">1</option><option value="6" >6</option><option value="12" >12</option></select></td>
			    </tr>
				  <td><b>Rapipago - CANTDIASFECHAVENC</b></td><td><input type="text" name="rpcantdiasfechavenc" value="00"></input></td>
				  </tr>
						  <tr>
				  <td><b>Rapipago - CANTDIASPAGO</b></td><td><input type="text" name="rpcantdiaspago" value="20"></input></td>
				  </tr>
						  <tr>
				  <td><b>Rapipago - RECARGO</b></td><td><input type="text" name="rprecargo" value="20.00"></input></td>
				  </tr><tr>
				  <td><b>Rapipago - FECHAVTO</b></td><td><input type="text" name="rpfechavto" value="20160101"></input></td>
				  </tr>
						  <tr>
				  <td><b>Rapipago - CLIENTE</b></td><td><input type="text" name="rpcliente" value="123456"></input></td>
				  </tr>	
						  <tr>
				  <td><b>Pagofacil - RECARGO</b></td><td><input type="text" name="pfrecargo" value="20.00"></input></td>
				  </tr><tr>
				  <td><b>Pagofacil - FECHAVTO</b></td><td><input type="text" name="pffechavto" value="20160101"></input></td>
				  </tr>	
				  <tr>
				  <td><b>Pagofacil - FECHAVTO2</b></td><td><input type="text" name="pffechavto2" value="20160301"></input></td>
				  </tr>		
				<tr>
				  <td><b>PagoMisCuentas - FECHAVTO</b></td><td><input type="text" name="pmcfechavto" value="01012016"></input></td>
				  </tr>				
				</tbody>	
			<tfoot>
			  <tr>
				<td colspan="2"><a href="index.php" class="btn error site">Cancelar</a>&nbsp;&nbsp;&nbsp;<a href="create.php" onclick="$('#activeform').submit();return false;" class="btn site" id="send">Enviar</a></td>
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