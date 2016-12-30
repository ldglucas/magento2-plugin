<?php
include_once dirname(__FILE__)."/FlatDb.php";
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$operationid = $_GET['ord'];

$db = new FlatDb();
$db->openTable('ordenes');

$orden = $db->getRecords(array("id","status","data","mediodepago","sar","form","gaa","requestkey","publicrequestkey","answerkey"),array("id" => $operationid));

$data = json_decode($orden[0]['data'],true);
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment method</title>
	<!-- Requerido por Cybersource, en caso de integrar el servicio de prevención de fraude -->
	<script src="https://h.online-metrix.net/fp/check.js?org_id=1snn5n9w&session_id=decidir_agregador<?php echo $operationid; ?>" type="text/javascript"></script>
  </head>
  <body>
        <form action="checkout.php" method="POST" id="payform">
            <label for="CardNumber">Numero de Tarjeta</label><br>
            <input type="text" class="form-control" id="CardNumber" placeholder="Ej.: 44114411" required="required"> <br>
            <label for="CardHolderName">Nombre</label><br>
            <input type="text" class="form-control" id="CardHolderName" placeholder="Ingrese su nombre" required="required"><br>
            <label for="CardExpirationDate">Vencimiento</label><br>
            <input type="number" class="form-control" id="CardExpirationDate" placeholder="MMAA" required="required"><br>
            <label for="CardSecurityCode">CVC</label><br>
            <input type="number" class="form-control" id="CardSecurityCode" placeholder="Codigo de seguridad" required="required"><br>
            <label for="CardHolderMail">Email</label><br>
            <input type="email" class="form-control" id="CardHolderMail" placeholder="Ingrese su email" required="required"><br>
            <input type="hidden" name="idRequest" id="RequestKey" value="<?php echo $orden[0]['publicrequestkey']; ?>" />
            <button type="submit" class="btn btn-primary col-xs-12">Pagar $AR <?php echo $data['monto']; ?></button>
        </form>
       <script src="https://sandbox.decidir.com/custom/callback/1.1/payment.js"></script>
        <script>
         new Payment().init({
             id: 'payform',
             fieldsId: {
                 CardHolderName:          'CardHolderName',
                 CardHolderMail:          'CardHolderMail',
                 CardNumber:              'CardNumber',
                 CardExpirationDate:      'CardExpirationDate',
                 CardSecurityCode:        'CardSecurityCode',
                 PublicRequestKey:        'RequestKey'

             },
             callback: function (PublicAnswerKey) {
                pa = PublicAnswerKey;
				opid = "<?php echo $operationid; ?>";
                location.href="pagar2.php?pa="+pa.PublicAnswerKey+"&ord="+opid;
             }
         });

        </script>
  </body>
</html>