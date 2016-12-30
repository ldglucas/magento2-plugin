Decidir SDK PHP
=======

Modulo para conexión con gateway de pago DECIDIR

  * [Instalación](#instalación)
  * [Manual de Integración](#manual-de-integración)
  * [Generalidades](#generalidades)
  * [SendAuthorizeRequest](#sendauthorizerequest)
    * [Medios de Pago](#medios-de-pago)
      * [Tarjetas de Crédito](#tarjetas-de-crédito)
      * [Rapipago](#rapipago)
      * [Pago Fácil](#pago-fácil)
      * [Pago mis Cuentas](#pago-mis-cuentas)
    * [Integración con Cybersource](#integración-con-cybersource)
	  * [Parámetros Comunes](#parámetros-comunes)
      * [Retail](#retail)
      * [Travel](#travel)
      * [Ticketing](#ticketing)
      * [Services](#services)
      * [Digital Goods](#digital-goods)
    * [Comercios Agregadores](#comercios-agregadores)
    * [Split de Transacciones](#split-de-transacciones)
      * [Monto Fijo](#monto-fijo)
      * [Porcentaje](#porcentaje)	  
  * [GetAuthorizeAnswer] (#getauthorizeanswer) 
  * [Execute](#execute)
    * [Anulación](#anulación)
    * [Devolución Total](#devolución-total)
    * [Devolución Parcial](#devolución-parcial)
  * [GetByOperationId](#getbyoperationid)
  * [Tablas de referencia](#tablas-de-referencia)
    * [Códigos de Medios de Pago](#códigos-de-medios-de-pago)
    * [Códigos de Estado](#códigos-de-estado)
    * [Provincias](#provincias)
 
 
## Instalación
Se debe descargar la última versión del SDK desde el botón Download ZIP, branch master.		
Una vez descargado y descomprimido, debe incluirse el archivo autoload.php que se encuentra en la carpeta /vendor como librería dentro del proyecto.		
<br />		

También se puede realizar la instalación a través de Composer.<br/>
```composer require decidir/php-sdk```<br/>
E incluir el archivo vendor/autoload.php en el proyecto.<br/>
<br/>
Observación: Descomentar: **extension=php_soap.dll** y **extension=php_openssl.dll** del php.ini, ya que para la conexión al gateway se utiliza la clase SoapClient del API de PHP. 

[Volver al inicio](#decidir-sdk-php)

## Manual de Integración

Se encuentra disponible en Gitbook el **[Manual de Integración Decidir] (https://www.gitbook.com/book/decidir/documentacion/details)** para su consulta online o descarga, donde se detalla el proceso de integración. En el mismo se explican los servicios y operaciones disponibles, con ejemplos de requerimientos y respuestas, aquí sólo se ejemplificará la forma de llamar a los distintos servicios usando la presente SDK.

[Volver al inicio](#decidir-sdk-php)

## Generalidades

Instanciación de la clase `Decidir\Connector`
La misma recibe como parámetros el Header HTTP provisto por Decidir para el comercio y el Endpoint en el que se realizaran las operaciones.

```php
	$http_header = array('Authorization'=>'PRISMA RV82RVHO5T0O5CZUUTX2FLHU');
	$enpoint = Decidir\Connector::DECIDIR_ENDPOINT_TEST; // Opciones disponibles: DECIDIR_ENDPOINT_TEST, DECIDIR_ENDPOINT_PROD

	$connector = new Decidir\Connector($http_header, $endpoint);
```

[Volver al inicio](#decidir-sdk-php)

## SendAuthorizeRequest

La operación SendAuthorizeRequest que forma parte del servicio Authorize, envía el requerimiento inical de autorización para comenzar con el ciclo de pago de la transacción, debe llamarse de la siguiente manera:
Debe crearse una instancia de la clase `Decidir\Authorize\SendAuthorizeRequest\Data`, con lo parámetros de la solicitud

```php
	$sar_data = new Decidir\Authorize\SendAuthorizeRequest\Data( array(
																"security" => 'RV82RVHO5T0O5CZUUTX2FLHU', // Mandatorio. Código provisto por Decidir
																"encoding_method" => 'XML', // Opcional
																"merchant" => 22067736, // Mandatorio. Nro. de Comercio provisto por Decidir
																"nro_operacion" => 123456, // Mandatorio. Nro. de operación único y propio del comercio
																"monto" => 50.00, // Mandatorio. Monto de la operación 
																"email_cliente" => 'ejemplo@misitio.com' // Mandatorio. E-mail del cliente
															));
```

Luego se debe invocar al servicio pasándole como parámetro el objeto anteriormente creado

```php
	$sar_rta = $connector->Authorize()->sendAuthorizeRequest($sar_data);
```
El mismo devolverá una instalcia de la clase `Decidir\Authorize\SendAuthorizeRequest\Response`

```php
	$sar_rta->getStatusMessage(); // Mensaje del estado del requerimiento
	$sar_rta->getStatusCode(); // Código de estado
	$sar_rta->getRequestKey(); // Private Request Key
	$sar_rta->getPublicRequestKey(); // Public Request Key
```

[Volver al inicio](#decidir-sdk-php)

### Medios de Pago

En la operación SendAuthorizeRequest deben enviarse datos adicionales dependiendo de los distintos medios de pago que se utilicen, para ello se proveen clases específicas para cada medio de pago que deben agregarse al objeto de datos.

[Volver al inicio](#decidir-sdk-php)

#### Tarjetas de Crédito

Para utilizar tarjetas de crédito como medio de pago, se debe instanciar un objeto de la clase `Decidir\Data\Mediopago\TarjetaCredito`, con los siguientes parámetros

```php
	$tarjeta_credito = new Decidir\Data\Mediopago\TarjetaCredito( array(
																"medio_pago" =>	1 // Mandatorio. Código del tipo de tarjeta a utilizar. [Tabla Medios de Pago] (#tablas-medios-de-pago)
																"cuotas" => 6 // Mandatorio. Nro. de cuotas en los que se hará la operacion
																"bin" => 450799 // Opcional. Código BIN que identifica al emisor de la tarjeta de crédito. Permite al comercio operar con promociones bancarias.
															));
```

Luego se debe incluir la información del medio de pago en el objeto de datos

```php
	$sar_data->setMedioPago($tarjeta_credito);
```

[Volver al inicio](#decidir-sdk-php)

#### Rapipago

Para utilizar Rapipago como medio de pago, se debe instanciar un objeto de la clase `Decidir\Data\Mediopago\Rapipago`, con los siguientes parámetros

```php
	$rapipago = new Decidir\Data\Mediopago\Rapipago( array(
														"medio_pago" =>	26 // Mandatorio. Código del tipo de tarjeta a utilizar. [Tabla Medios de Pago] (#tablas-medios-de-pago)
														"cantdiasfechavenc" => 10 // Mandatorio. Son los días que existen entre el 1º y 2º vencimiento de la factura. Poner “00” si la factura no tiene 2º vencimiento.
														"cantdiaspago" => 12 // Mandatorio. Son los días después del 1º vencimiento y hasta el que el cliente puede pagar la factura por Rapipago.
														"recargo" => 22.00 // Mandatorio. Recargo por vencimiento del plazo. Es un monto (no un porcentaje).
														"fechavto" => 1601010 // Mandatorio. Fecha de vencimiento para el pago del cupón. Formato AAMMDD
														"cliente" => 12345678 // Mandatorio. Código de cliente provisto por Rapipago al momento de habilitar el comercio.
													));
```

Luego se debe incluir la información del medio de pago en el objeto de datos

```php
	$sar_data->setMedioPago($rapipago);
```

[Volver al inicio](#decidir-sdk-php)

#### Pago Fácil

Para utilizar Pago Fácil como medio de pago, se debe instanciar un objeto de la clase `Decidir\Data\Mediopago\PagoFacil`, con los siguientes parámetros

```php
	$pagofacil = new Decidir\Data\Mediopago\PagoFacil( array(
														"recargo" => 2500 // Mandatorio. Se debe enviar el monto toal para el segundo vencimiento.
														"fechavto" => 1601010 // Mandatorio. Fecha de vencimiento para el pago del cupón. Formato AAMMDD
														"fechavto2" => 1601010 // Mandatorio. Fecha del segundo vencimiento para el pago del cupón. Formato AAMMDD
													));
```

Luego se debe incluir la información del medio de pago en el objeto de datos

```php
	$sar_data->setMedioPago($pagofacil);
```

[Volver al inicio](#decidir-sdk-php)

#### Pago mis Cuentas

Para utilizar Pago Mis Cuentas como medio de pago, se debe instanciar un objeto de la clase `Decidir\Data\Mediopago\PagoMisCuentas`, con los siguientes parámetros

```php
	$pagomiscuentas = new Decidir\Data\Mediopago\PagoMisCuentas( array(
														"fechavto" => 1601010 // Mandatorio. Fecha y hora de vencimiento de la factura. En formato DDMMYY HHMM. Puede omitirse las “horas” y “minutos”, informando solo la fecha con formato DDMMYY.
													));
```

Luego se debe incluir la información del medio de pago en el objeto de datos

```php
	$sar_data->setMedioPago($pagomiscuentas);
```

[Volver al inicio](#decidir-sdk-php)

### Integración con Cybersource

Para utilizar el Servicio de Control de Fraude Cybersource, en la operación SendAuthorizeRequest, deben enviarse datos adicionales sobre la operación de compra que se quiere realizar.
Se han definido cinco verticales de negocio que requieren parámetros específicos, así como también parámetros comunes a todas las verticales.

[Volver al inicio](#decidir-sdk-php)

#### Parámetros Comunes

Los parámetros comunes a todas las verticales deben enviarse junto con los datos específicos de cada uno. A continuación, describiremos los párametros comúnes que se deberan agregar a los datos de cada vertical al momento de instanciar la clase correspondiente.

```php
	$datos_comunes = array(
		'device_fingerprint' => 'dj94utjg93', // Device Fingerprint Id. MANDATORIO.
		'csbtcity'=>'Villa General Belgrano', //Ciudad de facturación, MANDATORIO.
		'csbtcountry'=>'AR', //País de facturación. MANDATORIO. Código ISO. (http://apps.cybersource.com/library/documentation/sbc/quickref/countries_alpha_list.pdf)
		'csbtcustomerid'=>'453458', //Identificador del usuario al que se le emite la factura. MANDATORIO. No puede contener un correo electrónico.
		'csbtipaddress'=>'192.0.0.4', //IP de la PC del comprador. MANDATORIO.
		'csbtemail'=>'decidir@hotmail.com', //Mail del usuario al que se le emite la factura. MANDATORIO.
		'csbtfirstname'=>'Juan' ,//Nombre del usuario al que se le emite la factura. MANDATORIO.
		'csbtlastname'=>'Perez', //Apellido del usuario al que se le emite la factura. MANDATORIO.
		'csbtphonenumber'=>'541160913988', //Teléfono del usuario al que se le emite la factura. No utilizar guiones, puntos o espacios. Incluir código de país. MANDATORIO.
		'csbtpostalcode'=>' C1010AAP', //Código Postal de la dirección de facturación. MANDATORIO.
		'csbtstate'=>'B', //Provincia de la dirección de facturación. MANDATORIO. Ver tabla anexa de provincias.
		'csbtstreet1'=>'Cerrito 740', //Domicilio de facturación (calle y nro). MANDATORIO.
		'csbtstreet2'=>'Piso 8', //Complemento del domicilio. (piso, departamento). NO MANDATORIO.
		'csptcurrency'=>'ARS', //Moneda. MANDATORIO.
		'csptgrandtotalamount'=>'125.38', //Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO. (Ejemplos:$125,38-> 125.38 $12-> 12 o 12.00)
		'csmdd6'=>'Mobile', // Canal de venta. NO MANDATORIO. (Valores posibles: Web, Mobile, Telefonica)
		'csmdd7'=>'', // Fecha registro comprador(num Dias). NO MANDATORIO.
		'csmdd8'=>'Y', //Usuario Guest? (Y/N). En caso de ser Y, el campo CSMDD9 no deberá enviarse. NO MANDATORIO.
		'csmdd9'=>'', //Customer password Hash: criptograma asociado al password del comprador final. NO MANDATORIO.
		'csmdd10'=>'', //Histórica de compras del comprador (Num transacciones). NO MANDATORIO.
		'csmdd11'=>'', //Customer Cell Phone. NO MANDATORIO.
	);
```

[Volver al inicio](#decidir-sdk-php)

#### Retail

Los siguientes parámetros se deben enviar específicamente para la vertical Retail. Además se deben enviar datos específicos de cada producto involucrado en la transacción.

```php
	$datos_retail = array(
		'csstcity'=>'rosario', //Ciudad de enví­o de la orden. MANDATORIO.
		'csstcountry'=>'', //País de envío de la orden. MANDATORIO.
		'csstemail'=>'jose@gmail.com', //Mail del destinatario, MANDATORIO.
		'csstfirstname'=>'Jose', //Nombre del destinatario. MANDATORIO.
		'csstlastname'=>'Perez', //Apellido del destinatario. MANDATORIO.
		'csstphonenumber'=>'541155893737', //Número de teléfono del destinatario. MANDATORIO.
		'csstpostalcode'=>'1414', //Código postal del domicilio de envío. MANDATORIO.
		'csststate'=>'D', //Provincia de envío. MANDATORIO. Son de 1 caracter
		'csststreet1'=>'San Martín 123', //Domicilio de envío. MANDATORIO.
		'csststreet2'=>'San Luis', //Localidad de envío. NO MANDATORIO.
		'csmdd12'=>'',//Shipping DeadLine (Num Dias). NO MADATORIO.
		'csmdd13'=>'',//Método de Despacho. NO MANDATORIO.
		'csmdd14'=>'',//Customer requires Tax Bill ? (Y/N). NO MANDATORIO.
		'csmdd15'=>'',//Customer Loyality Number. NO MANDATORIO. 
		'csmdd16'=>'',//Promotional / Coupon Code. NO MANDATORIO.
	);
	
	//Datos de productos, un array con los diferentes productos involucrados.
	$productos = array(
		array(	// Producto 1
			'csitproductcode'=>'electronic_good', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'LEVJNSL36GN', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'1254.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'1254.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO		
		), 
		array(	// Producto 2
			'csitproductcode'=>'default', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'PENDRIVE 2GB KINGSTON', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'PENDRIVE 2GB', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'KSPDRV2g', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'248.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'248.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO			
		), 
		......... // Otros productos
	);

```

Para incorporar estos datos en el requerimiento inicial, se debe instanciar un objeto de la clase `Decidir\Data\Cybersource\Retail` de la siguiente manera.

```php

	//Se combinan los datos de retail con los comunes
	$datos_cs = array_merge($datos_comunes, $datos_retail);
	
	$cybersource = new Decidir\Data\Cybersource\Retail(
										$datos_cs,	// Datos de la operación
										$productos, // Datos de los productos
	);
	
	//Se agregan los datos el requerimiento inicial
	$sar_data->setCybersourceData($cybersource);
```

[Volver al inicio](#decidir-sdk-php)

#### Travel

Los siguientes parámetros se deben enviar específicamente para la vertical Travel. Además se deben enviar datos específicos de cada pasajero involucrado en la transacción.

```php
	$datos_travel = array(
		'csdmcompleteroute'=>'JFK-SFO:SFO-LAX', //Ruta completa del viaje, ORIG1-DEST1[:ORIG2-DEST2...:ORIGn-DESTn]. MANDATORIO.
		'csdmjourneytypey'=>'round trip', //Tipo de viaje. valores posibles: round trip o one way. MANDATORIO.
		'csdmdeparturedatetime'=>'2011-03-20 11:30pm GMT', /* Fecha y hora del primer tramo del viaje. Utilizar GMT.
									Formato: yyyy-MM-dd hh:mma z donde: 
									hh = hora en formato 12-horas
									a = am o pm
									z = huso horario del vuelo de salida. Por ejemplo: Si la compañía tiene su sede en la ciudad de A, pero el vuelo sale de la ciudad B, z es el horario de la ciudad B al momento de la salida
									MANDATORIO */
		'csadnumberofpassengers'=>'4', //Cantidad total de pasajeros. MANDATORIO.
		'csmdd17'=>'AWHWNV', //Código de Reserva (PNR). MANDATORIO. 
		'csmdd18'=>'N', //3rd Party Booking? (Y/N). MANDATORIO.
		'csmdd19'=>'', //Departure City. NO MANDATORIO.
		'csmdd20'=>'', //Final Destination City. NO MANDATORIO.
		'csmdd21'=>'', //International Flight. NO MANDATORIO.
		'csmdd22'=>'', //Frequent Flyer Number. NO MANDATORIO.
		'csmdd23'=>'', //Class of Service. NO MANDATORIO.
		'csmdd24'=>'', //Day of week of Flight. NO MANDATORIO.
		'csmdd25'=>'', //Week of year of Flight. NO MANDATORIO.
		'csmdd26'=>'', //Airline Code. NO MANDATORIO.
		'csmdd27'=>'', //Code Share. NO MANDATORIO.
	);
	
	//Datos de pasajeros, un array con los diferentes pasajeros involucrados.
	$pasajeros = array(
		array(	// Pasajero 1
			'csitpassengeremail'=>'jperez@hotmail.com', //Email del pasajero. MANDATORIO.
			'csitpassengerfirstname'=>'Juan', //Nombre del pasajero. MANDATORIO.
			'csitpassengerid'=>'11123123', //Número de pasaporte. NO MANDATORIO.
			'csitpassengerlastname'=>'Perez', //Apellido del pasajero. MANDATORIO.
			'csitpassengerphone'=>'541145454545', //Número de teléfono del pasajero. MANDATORIO.
			'csitpassengerstatus'=>'gold', //Clasificación del pasajero dentro de la empresa. MANDATORIO.
			'csitpassengertype'=>'INF', //Tipo de pasajero asociado al precio del pasaje. MANDATORIO.(ADT: Adult,CNN: Child,INF: Infant,YTH: Youth,STU: Student,SCR: Senior Citizen,MIL: Military)
		), 
		array(	// Pasajero 2
			'csitpassengeremail'=>'jperez@hotmail.com', //Email del pasajero. MANDATORIO.
			'csitpassengerfirstname'=>'Juan', //Nombre del pasajero. MANDATORIO.
			'csitpassengerid'=>'12123123', //Número de pasaporte. NO MANDATORIO.
			'csitpassengerlastname'=>'Perez', //Apellido del pasajero. MANDATORIO.
			'csitpassengerphone'=>'541145454546', //Número de teléfono del pasajero. MANDATORIO.
			'csitpassengerstatus'=>'gold', //Clasificación del pasajero dentro de la empresa. MANDATORIO.
			'csitpassengertype'=>'INF', //Tipo de pasajero asociado al precio del pasaje. MANDATORIO.(ADT: Adult,CNN: Child,INF: Infant,YTH: Youth,STU: Student,SCR: Senior Citizen,MIL: Military)
		), 
		......... // Otros pasajeros
	);

```

Para incorporar estos datos en el requerimiento inicial, se debe instanciar un objeto de la clase `Decidir\Data\Cybersource\Travel` de la siguiente manera.

```php

	//Se combinan los datos de travel con los comunes
	$datos_cs = array_merge($datos_comunes, $datos_travel);
	
	$cybersource = new Decidir\Data\Cybersource\Travel(
										$datos_cs,	// Datos de la operación
										$pasajeros, // Datos de los pasajeros
	);
	
	//Se agregan los datos el requerimiento inicial
	$sar_data->setCybersourceData($cybersource);
```

[Volver al inicio](#decidir-sdk-php)

#### Ticketing

Los siguientes parámetros se deben enviar específicamente para la vertical Ticketing. Además se deben enviar datos específicos de cada producto involucrado en la transacción.

```php
	$datos_ticketing = array(
		'csmdd33'=> 15, //Número de días en los que se desarrollara el evento. MANDATORIO
		'csmdd34'=>'Email', //Tipo de envío. MANDATORIO. Valores posibles: Pick up, Email, Smartphone, Other
	);
	
	//Datos de productos, un array con los diferentes productos involucrados.
	$productos = array(
		array(	// Producto 1
			'csitproductcode'=>'electronic_good', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'LEVJNSL36GN', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'1254.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'1254.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO		
		), 
		array(	// Producto 2
			'csitproductcode'=>'default', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'PENDRIVE 2GB KINGSTON', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'PENDRIVE 2GB', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'KSPDRV2g', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'248.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'248.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO			
		), 
		......... // Otros productos
	);

```

Para incorporar estos datos en el requerimiento inicial, se debe instanciar un objeto de la clase `Decidir\Data\Cybersource\Ticketing` de la siguiente manera.

```php

	//Se combinan los datos de ticketing con los comunes
	$datos_cs = array_merge($datos_comunes, $datos_ticketing);
	
	$cybersource = new Decidir\Data\Cybersource\Ticketing(
										$datos_cs,	// Datos de la operación
										$productos, // Datos de los productos
	);
	
	//Se agregan los datos el requerimiento inicial
	$sar_data->setCybersourceData($cybersource);
```

[Volver al inicio](#decidir-sdk-php)

#### Services

Los siguientes parámetros se deben enviar específicamente para la vertical Services. Además se deben enviar datos específicos de cada producto involucrado en la transacción.

```php
	$datos_services = array(
		'csmdd28'=>'Gas', //Tipo de Servicio. MANDATORIO. Valores posibles: Luz, Gas, Telefono, Agua, TV, Cable, Internet, Impuestos.
		'csmdd29'=>'', //Referencia de pago del servicio 1. NO MANDATORIO.
		'csmdd30'=>'', //Referencia de pago del servicio 2. NO MANDATORIO.
		'csmdd31'=>'', //Referencia de pago del servicio 3. NO MANDATORIO.
	);
	
	//Datos de productos, un array con los diferentes productos involucrados.
	$productos = array(
		array(	// Producto 1
			'csitproductcode'=>'electronic_good', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'LEVJNSL36GN', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'1254.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'1254.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO		
		), 
		array(	// Producto 2
			'csitproductcode'=>'default', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'PENDRIVE 2GB KINGSTON', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'PENDRIVE 2GB', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'KSPDRV2g', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'248.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'248.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO			
		), 
		......... // Otros productos
	);

```

Para incorporar estos datos en el requerimiento inicial, se debe instanciar un objeto de la clase `Decidir\Data\Cybersource\Services` de la siguiente manera.

```php

	//Se combinan los datos de services con los comunes
	$datos_cs = array_merge($datos_comunes, $datos_services);
	
	$cybersource = new Decidir\Data\Cybersource\Services(
										$datos_cs,	// Datos de la operación
										$productos, // Datos de los productos
	);
	
	//Se agregan los datos el requerimiento inicial
	$sar_data->setCybersourceData($cybersource);
```

[Volver al inicio](#decidir-sdk-php)

#### Digital Goods

Los siguientes parámetros se deben enviar específicamente para la vertical Digital Goods. Además se deben enviar datos específicos de cada producto involucrado en la transacción.

```php
	$datos_digitalgoods = array(
		'csmdd31'=>'', //Tipo de delivery. MANDATORIO. Valores posibles: WEB Session, Email, SmartPhone
	);
	
	//Datos de productos, un array con los diferentes productos involucrados.
	$productos = array(
		array(	// Producto 1
			'csitproductcode'=>'electronic_good', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'NOTEBOOK L845 SP4304LA DF TOSHIBA', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'LEVJNSL36GN', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'1254.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'1254.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO		
		), 
		array(	// Producto 2
			'csitproductcode'=>'default', //Código de producto. MANDATORIO. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
			'csitproductdescription'=>'PENDRIVE 2GB KINGSTON', //Descripción del producto. MANDATORIO.
			'csitproductname'=>'PENDRIVE 2GB', //Nombre del producto. MANDATORIO.
			'csitproductsku'=>'KSPDRV2g', //Código identificador del producto. MANDATORIO.
			'csittotalamount'=>'248.40', //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el puntos como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.
			'csitquantity'=>'1', //Cantidad del producto. MANDATORIO.
			'csitunitprice'=>'248.40', //Formato Idem CSITTOTALAMOUNT. MANDATORIO			
		), 
		......... // Otros productos
	);

```

Para incorporar estos datos en el requerimiento inicial, se debe instanciar un objeto de la clase `Decidir\Data\Cybersource\DigitalGoods` de la siguiente manera.

```php

	//Se combinan los datos de digitalgoods con los comunes
	$datos_cs = array_merge($datos_comunes, $datos_digitalgoods);
	
	$cybersource = new Decidir\Data\Cybersource\DigitalGoods(
										$datos_cs,	// Datos de la operación
										$productos, // Datos de los productos
	);
	
	//Se agregan los datos el requerimiento inicial
	$sar_data->setCybersourceData($cybersource);
```

[Volver al inicio](#decidir-sdk-php)

### Comercios Agregadores

Se debe añadir al requerimiento inicial del SendAuthorizeRequest los siguientes datos adicionales de VISA para comercios agregadores.

Debe instanciarse un objeto de la clase `Decidir\Data\ComerciosAgregadores` con los parámetros específicos.

```php
	$agregadores = new Decidir\Data\ComerciosAgregadores( array(
														'aindicador'=>0, //Indicador del tipo de documento. Numérico, 1 dígito. Valores posibles(0:cuit, 1:cuil, 2:número único).
														'adocumento'=>'2325xxxxxx9', //Número de CUIT, CUIL o Número Único(en el último caso se debe completar con ceros a la izquierda)
														'afactpagar'=>'c0000234321', //Número de factura a pagar. Alfanumérico de 12 caracteres.
														'afactdevol'=>'c0000234320', // Número de factura de anulación/devolución,
														'anombrecom'=>'jorge/Rufalo',  //Nombre del comercio o nombre y apellido del vendedor. Alfanumérico 20 caracteres. en el caso de nombre y apellido debe estar separado por "/".
														'adomiciliocomercio'=>'Salta', //Dirección del comercio o vendedor. Alfanumérico 20 caracteres.
														'anropuerta'=>'153', //Número de puerta. Alfanumérico 6 caracteres
														'acodpostal'=>'H3509XAP', //Código postal. Alfanumérico de 8 caracteres.
														'arubro'=>'', //Código de actividad (rubro). Alfanumérico de 5 caracteres.
														'acodcanal'=>'', //Código de canal. Alfanumérico de 3 caracteres.
														'acodgeografico'=>'',//Código geográfico del vendedor. Alfanumérico de 5 caracteres.
													));
```

Luego se debe incluir esta información en el objeto de datos

```php
	$sar_data->setAgregadoresData($agregadores);
```

[Volver al inicio](#decidir-sdk-php)

### Split de Transacciones

Se debe añadir al requerimiento inicial del SendAuthorizeRequest los siguientes datos adicionales para realizar el Split de Transacciones.

[Volver al inicio](#decidir-sdk-php)

#### Monto Fijo

Debe instanciarse un objeto de la clase `Decidir\Data\SplitTransacciones\MontoFijo` con los parámetros específicos.

```php
	$split = new Decidir\Data\SplitTransacciones\MontoFijo( array(
														'nrocomercio'=>'12345678'//Número de comercio padre provisto por SPS DECIDIR. Alfanumérico de 8 caracteres.
														'impdist'=>'123.4#12#12.05',//Importe de cada una de las substransacciones. Los importes deben postearse separados por "#".
														'sitedist'=>'00100511#0234803245#00230031',//Número de comercio de cada uno de los subcomercios asociados al comercio padre
														'cuotasdist'=>'01#06#24',//cantidad de cuotas para cada subcomercio. Decimal de 2 dígitos.
														'idmodalidad'=>'S',// indica si la transacción es distribuida. (S= transacción distribuida; N y null = no distribida)
													));
```

Luego se debe incluir esta información en el objeto de datos

```php
	$sar_data->setSplitData($split);
```

[Volver al inicio](#decidir-sdk-php)

#### Porcentaje

Debe instanciarse un objeto de la clase `Decidir\Data\SplitTransacciones\Porcentaje` con los parámetros específicos.

```php
	$split = new Decidir\Data\SplitTransacciones\Porcentaje( array(
														'idmodalidad'=>'S',// indica si la transacción es distribuida. (S= transacción distribuida; N y null = no distribida)
													));
```

Luego se debe incluir esta información en el objeto de datos

```php
	$sar_data->setSplitData($split);
```

[Volver al inicio](#decidir-sdk-php)

## GetAuthorizeAnswer

La operación GetAuthorizeAnswer que forma parte del servicio Authorize, debe llamarse de la siguiente manera:
Debe crearse una instancia de la clase `Decidir\Authorize\GetAuthorizeAnswer\Data`, con lo parámetros de la solicitud

```php
	$gaa_data = new Decidir\Authorize\GetAuthorizeAnswer\Data( array(
																"security" => 'RV82RVHO5T0O5CZUUTX2FLHU', // Mandatorio. Código provisto por Decidir
																"merchant" => 22067736, // Mandatorio. Nro. de Comercio provisto por Decidir
																"requestKey" => '6bb242a3-d8c5-fcd8-57ef-3bb0280b6db4', // Mandatorio. Private Request Key devuelta por el requerimiento SendAuthorizeRequest
																"answerKey" => '77215fe6-f9d5-f1c2-372b-c0065e0c4429', // Mandatorio. Answer Key devuelta por la función callback del formulario de pago.
															));
```

Luego se debe invocar al servicio pasándole como parámetro el objeto anteriormente creado

```php
	$gaa_rta = $connector->Authorize()->getAuthorizeAnswer($gaa_data);
```
El mismo devolverá una instalcia de la clase `Decidir\Authorize\GetAuthorizeAnswer\Response`

```php
	$gaa_rta->getStatusMessage(); // Mensaje del estado del requerimiento
	$gaa_rta->getStatusCode(); // Código de estado
	$gaa_rta->getAuthorizationKey(); // Authorization Key
	$gaa_rta->getPayload(); // Array asociativo <clave=>valor> con información adicional.
```

[Volver al inicio](#decidir-sdk-php)

## Execute

La operación Execute que forma parte del servicio Authorize, permite realizar distintas operaciones sobre una transacción, las cuales se describen a continuación.

[Volver al inicio](#decidir-sdk-php)

### Anulación
Para realizar la anulación de una transacción, debe crearse una instancia de la clase `Decidir\Authorize\Execute\Anulacion`, con lo parámetros de la solicitud

```php
	$anul_data = new Decidir\Authorize\Execute\Anulacion( array(
																"security" => 'RV82RVHO5T0O5CZUUTX2FLHU', // Mandatorio. Código provisto por Decidir
																"merchant" => 22067736, // Mandatorio. Nro. de Comercio provisto por Decidir
																"nro_operacion" => 123456, // Mandatorio. Nro. de Operación.
															));
```

Luego se debe invocar al servicio pasándole como parámetro el objeto anteriormente creado

```php
	$anul_rta = $connector->Authorize()->execute($anul_data);
```
El mismo devolverá una instalcia de la clase `Decidir\Authorize\Execute\Response`

```php
	$anul_rta->getStatusMessage(); // Mensaje del estado del requerimiento
	$anul_rta->getStatusCode(); // Código de estado
	$anul_rta->getAuthorizationKey(); // Authorization Key
	$anul_rta->getPayload(); // Array asociativo <clave=>valor> con información adicional.
```

[Volver al inicio](#decidir-sdk-php)

### Devolución Total
Para realizar la devolución de una transacción, debe crearse una instancia de la clase `Decidir\Authorize\Execute\Devolucion\Total`, con lo parámetros de la solicitud

```php
	$devol_data = new Decidir\Authorize\Execute\Devolucion\Total( array(
																"security" => 'RV82RVHO5T0O5CZUUTX2FLHU', // Mandatorio. Código provisto por Decidir
																"merchant" => 22067736, // Mandatorio. Nro. de Comercio provisto por Decidir
																"nro_operacion" => 123456, // Mandatorio. Nro. de Operación.
															));
```

Luego se debe invocar al servicio pasándole como parámetro el objeto anteriormente creado

```php
	$devol_rta = $connector->Authorize()->execute($devol_data);
```
El mismo devolverá una instalcia de la clase `Decidir\Authorize\Execute\Response`

```php
	$devol_rta->getStatusMessage(); // Mensaje del estado del requerimiento
	$devol_rta->getStatusCode(); // Código de estado
	$devol_rta->getAuthorizationKey(); // Authorization Key
	$devol_rta->getPayload(); // Array asociativo <clave=>valor> con información adicional.
```

[Volver al inicio](#decidir-sdk-php)

### Devolución Parcial
Para realizar la devolución parcial de una transacción, debe crearse una instancia de la clase `Decidir\Authorize\Execute\Devolucion\Parcial`, con lo parámetros de la solicitud

```php
	$devol_data = new Decidir\Authorize\Execute\Devolucion\Parcial( array(
																"security" => 'RV82RVHO5T0O5CZUUTX2FLHU', // Mandatorio. Código provisto por Decidir
																"merchant" => 22067736, // Mandatorio. Nro. de Comercio provisto por Decidir
																"nro_operacion" => 123456, // Mandatorio. Nro. de Operación.
																"monto" => 12.00, // Mandatorio. Monto a devolver.
															));
```

Luego se debe invocar al servicio pasándole como parámetro el objeto anteriormente creado

```php
	$devol_rta = $connector->Authorize()->execute($devol_data);
```
El mismo devolverá una instalcia de la clase `Decidir\Authorize\Execute\Response`

```php
	$devol_rta->getStatusMessage(); // Mensaje del estado del requerimiento
	$devol_rta->getStatusCode(); // Código de estado
	$devol_rta->getAuthorizationKey(); // Authorization Key
	$devol_rta->getPayload(); // Array asociativo <clave=>valor> con información adicional.
```

[Volver al inicio](#decidir-sdk-php)

## GetByOperationId

La operación GetByOperationId que forma parte del servicio Operation, permite consultar el último estado de una transacción de forma online, debe llamarse de la siguiente manera:
Debe crearse una instancia de la clase `Decidir\Operation\GetByOperationId\Data`, con lo parámetros de la solicitud

```php
	$gboi_data = new Decidir\Operation\GetByOperationId\Data( array(
																"idsite" => 22067736, // Mandatorio. Nro. de Comercio provisto por Decidir
																"idtransactionsit" => 123456, // Mandatorio. Nro. de operación único y propio del comercio
															));
```

Luego se debe invocar al servicio pasándole como parámetro el objeto anteriormente creado

```php
	$gboi_rta = $connector->Operation()->getByOperationId($gboi_data);
```
El mismo devolverá una instalcia de la clase `Decidir\Operation\GetByOperationId\Response`

```php
	$gboi_rta->getStatusMessage(); // Mensaje del estado del requerimiento
	$gboi_rta->getStatusCode(); // Código de estado
	$gboi_rta->getEstado_descri(); // Estado de la transacción
	......
```

[Volver al inicio](#decidir-sdk-php)

## Tablas de Referencia

### Códigos de Medios de pago

| MEDIO DE PAGO | NOMBRE |
----------------|--------
| 1 | VISA |
| 6 | AMEX |
| 8 | DINERS |
| 15 | MASTERCARD |
| 20 | MASTERCARD TEST |
| 23 | TARJETA SHOPPING |
| 24 | TARJETA NARANJA |
| 25 | PAGO FACIL |
| 26 | RAPIPAGO |
| 27 | CABAL |
| 29 | ITALCRED |
| 30 | ARGENCARD |
| 31 | VISA DEBITO |
| 34 | COOPEPLUS |
| 36 | ARCASH |
| 37 | NEXO |
| 38 | CREDIMAS |
| 39 | NEVADA |
| 41 | PAGOMISCUENTAS |
| 42 | NATIVA |
| 43 | TARJETA MAS/CENCOSUD |
| 44 | CETELEM |
| 45 | NACIONPYMES |
| 46 | PAYSAFECARD |
| 47 | MONEDERO ONLINE |
| 48 | CAJA DE PAGOS |

[Volver al inicio](#decidir-sdk-php)

### Códigos de Estado

| IdEstado | Descripción |
|----------|-------------|
| 1 | Ingresada | 
| 2 | A procesar | 
| 3 | Procesada | 
| 4 | Autorizada | 
| 5 | Rechazada | 
| 6 | Acreditada | 
| 7 | Anulada | 
| 8 | Anulación Confirmada | 
| 9 | Devuelta | 
| 10 | Devolución Confirmada | 
| 11 | Pre autorizada | 
| 12 | Vencida | 
| 13 | Acreditación no cerrada | 
| 14 | Autorizada * | 
| 15 | A reversar | 
| 16 | A registar en Visa | 
| 17 | Validación iniciada en Visa | 
| 18 | Enviada a validar en Visa | 
| 19 | Validada OK en Visa | 
| 20 | Recibido desde Visa | 
| 21 | Validada no OK en Visa | 
| 22 | Factura generada | 
| 23 | Factura no generada | 
| 24 | Rechazada no autenticada | 
| 25 | Rechazada datos inválidos | 
| 28 | A registrar en IdValidador | 
| 29 | Enviada a IdValidador | 
| 32 | Rechazada no validada | 
| 38 | Timeout de compra | 
| 50 | Ingresada Distribuida | 
| 51 | Rechazada por grupo | 
| 52 | Anulada por grupo | 

[Volver al inicio](#decidir-sdk-php)

### Provincias

| Provincia | Código |
|----------|-------------|
| CABA | C | 
| Buenos Aires | B | 
| Catamarca | K | 
| Chaco | H | 
| Chubut | U | 
| Córdoba | X | 
| Corrientes | W | 
| Entre Ríos | R | 
| Formosa | P | 
| Jujuy | Y | 
| La Pampa | L | 
| La Rioja | F | 
| Mendoza | M | 
| Misiones | N | 
| Neuquén | Q | 
| Río Negro | R | 
| Salta | A | 
| San Juan | J | 
| San Luis | D | 
| Santa Cruz | Z | 
| Santa Fe | S | 
| Santiago del Estero | G | 
| Tierra del Fuego | V | 
| Tucumán | T | 	

[Volver al inicio](#decidir-sdk-php)
