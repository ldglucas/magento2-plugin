<a name="inicio"></a>
Magento 2 - Módulo Todo Pago
============

Plug in para la integración con gateway de pago <strong>Todo Pago</strong>
- [Consideraciones Generales](#consideracionesgenerales)
- [Instalación](#instalacion)
- [Configuración](#configuracion)
 - [Configuración plug in](#confplugin)
- [Datos adiccionales para prevención de fraude](#cybersource) 
- [Consulta de transacciones](#constrans)
- [Devoluciones](#devoluciones)
- [Tablas de referencia](#tablas)

<a name="consideracionesgenerales"></a>
## Consideraciones Generales
El plug in de pagos de <strong>Todo Pago</strong>, provee a las tiendas Magento de un nuevo m&eacute;todo de pago, integrando la tienda al gateway de pago.
La versión de este plug in esta testeada en PHP 5.4 en adelante y MAGENTO 2

<a name="instalacion"></a>
## Instalación

**Usando Composer**

```
composer require todopago/magento2-plugin
```

**Manualmente**

A.  Descomprimir el archivo magento2-plugin-master.zip. 

B. Copiar todo su contenido en la carpeta `app/code/Primsa/TodoPago`

Luego,

1. Ejecutar los siguientes comandos de configuración de Magento desde la consola
```
php bin/magento module:enable Prisma_TodoPago
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy es_AR #idioma instalado de la tienda.
```
2.	Refrescar el cache de Magento desde 'System -> Cache Management'
3.	Luego ir a 'Stores -> Configuration -> Payment Methods' y configurar desde la pestaña de <strong>Todo Pago</strong>.

Observaci&oacute;n:
Descomentar: <em>extension=php_curl.dll</em>, <em>extension=php_soap.dll</em> y <em>extension=php_openssl.dll</em> del php.ini, ya que para la conexión al gateway se utiliza la clase <em>SoapClient</em> del API de PHP.
<br />
[<sub>Volver a inicio</sub>](#inicio)

<a name="configuracion"></a>
##Configuración

[configuración plug in](#confplugin).
<a name="confplugin"></a>
####Configuración plug in
Para llegar al menu de configuración ir a: <em>Stores -> Configuration</em> y seleccionar Paymenth Methods en el menú izquierdo. Entre los medios de pago aparecerá una solapa con el nombre <strong>Todo Pago</strong>. 

<a name="confplanes"></a>
<br />

[<sub>Volver a inicio</sub>](#inicio)

<a name="cybersource"></a>
## Prevención de Fraude
- [Consideraciones Generales](#cons_generales)
- [Consideraciones para vertical RETAIL](#cons_retail)

<a name="cons_generales"></a>
####Consideraciones Generales (para todas los verticales, por defecto RETAIL)
El plug in, toma valores est&aacute;ndar del framework para validar los datos del comprador. Principalmente se utiliza una instancia de la clase <em>Mage_Sales_Model_Order</em>.
Para acceder a los datos del comprador se utiliza el metodo getBillingAddress() que devuelve un objeto ya instanciado del cual se usan los siguientes m&eacute;todos:

```php
   $order = new Mage_Sales_Model_Order ();
   $order->load($id);
-- Ciudad de Facturación: $order->getBillingAddress()->getCity();
-- País de facturación: $order->getBillingAddress()->getCountry();
-- Identificador de Usuario: $order->getBillingAddress()->getCustomerId();
-- Email del usuario al que se le emite la factura: $order->getBillingAddress()->getEmail();
-- Nombre de usuario el que se le emite la factura: $order->getBillingAddress()->getFirstname();
-- Apellido del usuario al que se le emite la factura: $order->getBillingAddress()->getLastname();
-- Teléfono del usuario al que se le emite la factura: $order->getBillingAddress()->getTelephone();
-- Provincia de la dirección de facturación: $order->getBillingAddress()->getRegion();
-- Domicilio de facturación: $order->getBillingAddress()->getStreet1();
-- Complemento del domicilio. (piso, departamento): $order->getBillingAddress()->getStreet2();
-- Moneda: $order->getBaseCurrencyCode();
-- Total:  $order->getGrandTotal();
-- IP de la pc del comprador: $order->getRemoteIp();
```
Otros de los modelos utlilizados es <em>Customer</em> del cual a trav&eacute;s  del m&eacute;todo <em>getPasswordHash()</em>, se extrae el password del usuario (comprador) y la tabla <em>sales_flat_invoice_grid</em>, donde se consultan las transacciones facturadas al comprador. 
<a name="cons_retail"></a> 
####Consideraciones para vertical RETAIL
Las consideración para el caso de empresas del rubro <strong>RETAIL</strong> son similares a las <em>consideraciones generales</em> con la diferencia de se utiliza el m&eacute;todo getShippingAddress() que devuelve un objeto y del cual se utilizan los siguientes m&eacute;todos;
```php
-- Ciudad de envío de la orden: $order->getShippingAddress()->getCity();
-- País de envío de la orden: $order->getShippingAddress()->getCountry();
-- Mail del destinatario: $order->getShippingAddress()->getEmail();
-- Nombre del destinatario: $order->getShippingAddress()->getFirstname();
-- Apellido del destinatario: $order->getShippingAddress()->getLastname();
-- Número de teléfono del destinatario: $order->getShippingAddress()->getTelephone();
-- Código postal del domicio de envío: $order->getShippingAddress()->getPostcode();
-- Provincia de envío: $order->getShippingAddress()->getRegion();
-- Domicilio de envío: $order->getShippingAddress()->getStreet1();
-- Método de despacho: $order->getShippingDescription();
-- Código de cupón promocional: $order->getCuponCode();
-- Para todo lo referido productos: $order->getItemsCollection();
```
nota: el valor resultante de $order->getItemsCollection(), se usan como referencias para conseguir informaci&oacute;n del modelo catalog/producto - a través de los métodos <strong>getDescription(), getName(), getSku(), getQtyOrdered(), getPrice()</strong>-.

####Muy Importante
<strong>Provincias:</strong> uno de los datos requeridos para prevención común a todos los verticales  es el campo provinicia/state tanto del comprador como del lugar de envío, para tal fin el plug in utiliza el valor del campo región de las tablas de la orden (sales_flat_order_address) a través del getRegion() tanto del <em>billingAddress</em> como del <em>shippingAddress</em>. El formato de estos datos deben ser tal cual la tabla de referencia (tabla provincias). Para simplificar la implementación de la tabla en magento se deja para su implementación la clase Decidir\Decidirpago\Model\System\Config\Source\Csprovincias.php, con el formato requerido. Al final de este documento se muestra un script sql que pude ser tomado de refrencia.
<br />

En caso que la tienda decida no implementar este nuevo atributo o que el valor quede vac&iacute;o el plug in mandara al sistema el mismo n&uacute;mero que devuleve el m&eacute;todo $order->getBillingAddress()->getTelephone(). 
[<sub>Volver a inicio</sub>](#inicio)

<a name="constrans"></a>
## Consulta de Transacciones
El plug in crea un nuevo <strong>tab</strong> para poder consultar <strong>on line</strong> las características de la transacci&oacute;n en el sistema de Todo Pago. Se puede consultar desde 'Sales -> Orders' y elegir la orden correspondiente, si la misma ha sido pagada con TodoPago aparecerá las pestaña 'TodoPago Status'
<br />
[<sub>Volver a inicio</sub>](#inicio)

<a name="devoluciones"></a>
## Devolucinones
Es posible realizar devoluciones o reembolsos mediante el procedimiento habitual de Magento. Para ello dirigirse a una orden, y mediante el menú seleccionar "Invoices" para poder generar una nota de crédito (credit memo) sobre la factura. Allí deberá hacerse click en el botón "Refund" para que la devolución sea online y procesada por Todo Pago.
<br />
[<sub>Volver a inicio</sub>](#inicio)


<a name="tablas"></a>
## Tablas de Referencia
######[Provincias](#p)

<a name="p"></a>
<p>Provincias</p>
<table>
<tr><th>Provincia</th><th>Código</th></tr>
<tr><td>CABA</td><td>C</td></tr>
<tr><td>Buenos Aires</td><td>B</td></tr>
<tr><td>Catamarca</td><td>K</td></tr>
<tr><td>Chaco</td><td>H</td></tr>
<tr><td>Chubut</td><td>U</td></tr>
<tr><td>Córdoba</td><td>X</td></tr>
<tr><td>Corrientes</td><td>W</td></tr>
<tr><td>Entre Ríos</td><td>E</td></tr>
<tr><td>Formosa</td><td>P</td></tr>
<tr><td>Jujuy</td><td>Y</td></tr>
<tr><td>La Pampa</td><td>L</td></tr>
<tr><td>La Rioja</td><td>F</td></tr>
<tr><td>Mendoza</td><td>M</td></tr>
<tr><td>Misiones</td><td>N</td></tr>
<tr><td>Neuquén</td><td>Q</td></tr>
<tr><td>Río Negro</td><td>R</td></tr>
<tr><td>Salta</td><td>A</td></tr>
<tr><td>San Juan</td><td>J</td></tr>
<tr><td>San Luis</td><td>D</td></tr>
<tr><td>Santa Cruz</td><td>Z</td></tr>
<tr><td>Santa Fe</td><td>S</td></tr>
<tr><td>Santiago del Estero</td><td>G</td></tr>
<tr><td>Tierra del Fuego</td><td>V</td></tr>
<tr><td>Tucumán</td><td>T</td></tr>
</table>
[<sub>Volver a inicio</sub>](#inicio)
