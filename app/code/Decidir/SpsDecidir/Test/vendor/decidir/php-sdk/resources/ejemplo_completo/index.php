<?php
include_once dirname(__FILE__)."/../../vendor/autoload.php";
include_once dirname(__FILE__)."/FlatDb.php";

$db = new FlatDb();
if(!file_exists("ordenes.tsv"))
	$db->createTable('ordenes',array("id","status","data","mediodepago","sar","form","gaa","requestkey","publicrequestkey","answerkey"));
$db->openTable('ordenes');

$ord = $db->getRecords(array("id","status","data","mediodepago","sar","form","gaa","requestkey","publicrequestkey","answerkey"));

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
	
	<table id="tablelist" class="full tablesorter">
	<thead>
		<tr>
	    	      <th class="header">Operacion</th>
	    	      <th class="header">Estado</th>
				  <th class="header">Medio de Pago</th>
	    	      <th class="header">SAR</th>
				  <th class="header">Pagar</th>
				  <th class="header">GAA</th>
				  <th class="header">GetByOpId</th>
				  <th class="header">Anular / Devolver</th>	  
		</tr>
	</thead>
	<tbody><?php foreach($ord as $o):?>
	    	<tr>
	      		<td><?php echo $o['id'];?></td>
	      		<td><?php echo $o['status'];?></td>
				<td><?php echo $o['mediodepago']; if($o['mediodepago'] == 0): ?> <a href="mediopago.php?ord=<?php echo $o['id'];?>" class="btn site btn-sm">Agregar datos de Medio de Pago</a><?php endif;?></td>
				<td><?php echo $o['sar']; if($o['sar'] == 0): ?> <a href="sar.php?ord=<?php echo $o['id'];?>" class="btn site btn-sm">Send Authorize Request</a><?php endif;?></td>
				<td><?php echo $o['form']; if($o['form'] == 0): ?> <a href="pagar.php?ord=<?php echo $o['id'];?>" class="btn site btn-sm">Pagar</a><?php endif;?></td>
				<td><?php echo $o['gaa']; if($o['gaa'] == 0): ?> <a href="gaa.php?ord=<?php echo $o['id'];?>" class="btn site btn-sm">Get Authorize Answer</a><?php endif;?></td>
				<td><a href="status.php?ord=<?php echo $o['id'];?>" class="btn site btn-sm">Consultar Estado</a></td>
	       		<td><a href="anular.php?ord=<?php echo $o['id'];?>" class="btn error site btn-sm">Anular</a> &nbsp;&nbsp; <a href="devolver.php?ord=<?php echo $o['id'];?>" class="btn error site btn-sm">Devolver</a></td>
	      	</tr>
			<?php endforeach;?>
	</tbody>
		<tfoot>
	  <tr>
	    <td colspan="7"><a href="create.php" class="btn info">Nuevo</a></td>
	  </tr>
	</tfoot>	
		</table>
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