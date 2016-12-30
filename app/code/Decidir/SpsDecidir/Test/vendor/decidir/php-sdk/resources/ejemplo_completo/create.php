<?php
include_once dirname(__FILE__)."/FlatDb.php";

function func1($a,$b){return $a*$b;}

if($_POST) {
	$db = new FlatDb();
	$db->openTable('ordenes');
	
	$operationid = $_POST['operacion'];
	$data = array(
		"monto" => $_POST['monto'],
		"email_cliente" => $_POST['email'],
		
		"cs_data" => array(
			"csstcity" => $_POST['city'],
			"csstcountry" => $_POST['country'],
			"csstemail" => $_POST['email'],
			"csstfirstname" => $_POST['firstname'],
			"csstlastname" => $_POST['lastname'],
			"csstphonenumber" => $_POST['phonenumber'],
			"csstpostalcode" => $_POST['postalcode'],
			"csststate" => $_POST['state'],
			"csststreet1" => $_POST['street1'],
			
			"device_fingerprint" => $_POST['operacion'],
			
			"csbtcity" => $_POST['city'],
			"csbtcountry" => $_POST['country'],
			"csbtemail" => $_POST['email'],
			"csbtfirstname" => $_POST['firstname'],
			"csbtlastname" => $_POST['lastname'],
			"csbtphonenumber" => $_POST['phonenumber'],
			"csbtpostalcode" => $_POST['postalcode'],
			"csbtstate" => $_POST['state'],
			"csbtstreet1" => $_POST['street1'],		
			"csbtcustomerid" => rand(999, 9999),
			"csbtipaddress" => "127.0.0.1",
			"csptcurrency" => "ARS",
			"csptgrandtotalamount" => $_POST['monto'],			
		),
		"cs_product" => array(
			array(
				"csitproductcode" => $_POST['productcode'][0],
				"csitproductdescription" => $_POST['productdescription'][0],
				"csitproductname" => $_POST['productname'][0],
				"csitproductsku" => $_POST['productsku'][0],
				"csittotalamount" => $_POST['quantity'][0]*$_POST['unitprice'][0],
				"csitquantity" => $_POST['quantity'][0],
				"csitunitprice" => $_POST['unitprice'][0],			
			),
			array(
				"csitproductcode" => $_POST['productcode'][1],
				"csitproductdescription" => $_POST['productdescription'][1],
				"csitproductname" => $_POST['productname'][1],
				"csitproductsku" => $_POST['productsku'][1],
				"csittotalamount" => $_POST['quantity'][1]*$_POST['unitprice'][1],
				"csitquantity" => $_POST['quantity'][1],
				"csitunitprice" => $_POST['unitprice'][1],			
			),	
		),
	);
	
	$db->insertRecord(array("id" => $operationid,"status" => "PENDIENTE", "data" => json_encode($data), "mediodepago" => 0, "sar" => 0, "form" => 0, "gaa" => 0, "requestkey" => "", "answerkey" => ""));
	
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
	<form id="activeform" method="POST" action="create.php" enctype="multipart/form-data">
		<table id="tablelist" class="full tablesorter">
			<tbody>
				<tr>
				  <td><b>Operacion</b></td><td><input type="text" name="operacion" value="<?php echo rand(99999,9999999); ?>"></input></td>
				  </tr>
						  <tr>
				  <td><b>Monto</b></td><td><input type="text" name="monto" value="50.00"></input></td>
				  </tr>
						  <tr>
				  <td><b>Email</b></td><td><input type="text" name="email" value="email@example.com"></input></td>
				  </tr>
			
				<tr>
				  <td><b>CS - Ciudad</b></td><td><input type="text" name="city" value="CABA"></input></td>
				</tr>
				<tr>
				  <td><b>CS - Pais</b></td><td><input type="text" name="country" value="AR"></input></td>
				</tr>
				<tr>
				  <td><b>CS - Nombre</b></td><td><input type="text" name="firstname" value="Cosme"></input></td>
				</tr>
				<tr>
				  <td><b>CS - Apellido</b></td><td><input type="text" name="lastname" value="Fulanito"></input></td>
				</tr>
				<tr>
				  <td><b>CS - Telefono</b></td><td><input type="text" name="phonenumber" value="+541145454545"></input></td>
				</tr>
				<tr>
				  <td><b>CS - Codigo Postal</b></td><td><input type="text" name="postalcode" value="C1430"></input></td>
				</tr>
				<tr>
				  <td><b>CS - Provincia</b></td><td><input type="text" name="state" value="C"></input></td>
				</tr>
				<tr>
				  <td><b>CS - Domicilio</b></td><td><input type="text" name="street1" value="Calle Falsa 123"></input></td>
				</tr>	
				
				<tr>
				  <td><b>CS - Producto 1 - ProductCode</b></td><td><input type="text" name="productcode[]" value="default"></input></td>
				</tr>				
				<tr>
				  <td><b>CS - Producto 1 - Descripcion</b></td><td><input type="text" name="productdescription[]" value="Producto 1, descripcion lorem ipsum..."></input></td>
				</tr>				
				<tr>
				  <td><b>CS - Producto 1 - Nombre</b></td><td><input type="text" name="productname[]" value="Cosa"></input></td>
				</tr>				
				<tr>
				  <td><b>CS - Producto 1 - SKU</b></td><td><input type="text" name="productsku[]" value="SKUCOSA1"></input></td>
				</tr>	
				<tr>
				  <td><b>CS - Producto 1 - Precio Unitario</b></td><td><input type="text" name="unitprice[]" value="10.00"></input></td>
				</tr>	
				<tr>
				  <td><b>CS - Producto 1 - Cantidad</b></td><td><input type="text" name="quantity[]" value="1"></input></td>
				</tr>

				<tr>
				  <td><b>CS - Producto 2 - ProductCode</b></td><td><input type="text" name="productcode[]" value="default"></input></td>
				</tr>				
				<tr>
				  <td><b>CS - Producto 2 - Descripcion</b></td><td><input type="text" name="productdescription[]" value="Producto 2, descripcion lorem ipsum..."></input></td>
				</tr>				
				<tr>
				  <td><b>CS - Producto 2 - Nombre</b></td><td><input type="text" name="productname[]" value="Cosa 2"></input></td>
				</tr>				
				<tr>
				  <td><b>CS - Producto 2 - SKU</b></td><td><input type="text" name="productsku[]" value="SKUCOSA2"></input></td>
				</tr>	
				<tr>
				  <td><b>CS - Producto 2 - Precio Unitario</b></td><td><input type="text" name="unitprice[]" value="10.00"></input></td>
				</tr>	
				<tr>
				  <td><b>CS - Producto 2 - Cantidad</b></td><td><input type="text" name="quantity[]" value="1"></input></td>
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