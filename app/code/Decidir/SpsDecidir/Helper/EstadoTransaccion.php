<?php
namespace Decidir\SpsDecidir\Helper;

/**
 * Class EstadoTransaccion
 *
 * @description Helper para traer informacion del estado de cada transaccion sps.
 *
 */
class EstadoTransaccion extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @const
     */
    const OPERACION_OK = 0;

    /**
     * @const Es el estado de la transaccion con el webservice, refiere en cuanto a la conexion exitosa.
     */
    const TRANSACCION_OK = 1;

    const ESTADO_OPERACION_INGRESADA            = 1;
    const ESTADO_OPERACION_A_PROCESAR           = 2;
    const ESTADO_OPERACION_PROCESADA            = 3;
    const ESTADO_OPERACION_AUTORIZADA           = 4;
    const ESTADO_OPERACION_RECHAZADA            = 5;
    const ESTADO_OPERACION_ACREDITADA           = 6;
    const ESTADO_OPERACION_ANULADA              = 7;
    const ESTADO_OPERACION_ANULACION_CONFIRMADA = 8;
    const ESTADO_OPERACION_DEVUELTA             = 9;
    const ESTADO_OPERACION_DEVOLUCION_CONFIRMADA= 10;
    const ESTADO_OPERACION_PRE_AUTORIZADA       = 11;
    const ESTADO_OPERACION_VENCIDA              = 12;
    const ESTADO_OPERACION_ACREDITACION_NO_CERRADA = 13;
    const ESTADO_OPERACION_AUTORIZADA_1         = 14;
    const ESTADO_OPERACION_A_REVERSAR           = 15;
    const ESTADO_OPERACION_A_REGISTRAR_EN_VISA  = 16;
    const ESTADO_OPERACION_VALIDACION_INICIADA_EN_VISA = 17;
    const ESTADO_OPERACION_ENVIADA_VALIDAD_VISA = 18;
    const ESTADO_OPERACION_VALIDADA_OK_VISA     = 19;
    const ESTADO_OPERACION_RECIBIDO_DESDE_VISA  = 20;
    const ESTADO_OPERACION_VALIDADA_NO_OK_VISA  = 21;
    const ESTADO_OPERACION_FACTURA_GENERADA     = 22;
    const ESTADO_OPERACION_FACTURA_NO_GENERADA  = 23;
    const ESTADO_OPERACION_RECHAZADA_NO_AUTENTICADA  = 24;
    const ESTADO_OPERACION_RECHAZADA_DATOS_INVALIDOS = 25;
    const ESTADO_OPERACION_A_REGISTRAR_IDVALIDATOR   = 28;
    const ESTADO_OPERACION_ENVIADA_IDVALIDATOR       = 29;
    const ESTADO_OPERACION_RECHAZADA_NO_VALIDADA     = 32;
    const ESTADO_OPERACION_TIMEOUT_DE_COMPRA    = 38;
    const ESTADO_OPERACION_INGRESADA_DISTRIBUIDA= 50;
    const ESTADO_OPERACION_RECHAZADA_POR_GRUPO  = 51;
    const ESTADO_OPERACION_ANULADA_POR_GRUPO    = 52;

    private $_estadoTransaccion = [
        1 => 'Ingresada',
        2 => 'A procesar',
        3 => 'Procesada',
        4 => 'Autorizada',
        5 => 'Rechazada',
        6 => 'Acreditada',
        7 => 'Anulada',
        8 => 'Anulación Confirmada',
        9 => 'Devuelta',
        10 => 'Devolución Confirmada',
        11 => 'Pre autorizada',
        12 => 'Vencida',
        13 => 'Acreditació no cerrada',
        14 => 'Autorizada*',
        15 => 'A reversar',
        16 => 'A registrar en Visa',
        17 => 'Validación iniciada en Visa',
        18 => 'Enviada a validar en Visa',
        19 => 'Validada OK en Visa',
        20 => 'Recibido desde Visa',
        21 => 'Validada no OK en Visa',
        22 => 'Factura generada',
        23 => 'Factura no generada',
        24 => 'Rechazada no autenticada',
        25 => 'Rechazada datos inválidos',
        28 => 'A registrar en IdValidator',
        29 => 'Enviada a IdValidator',
        32 => 'Rechazada no validada',
        38 => 'Timeout de compra',
        50 => 'Ingresada Distribuida',
        51 => 'Rechazada por grupo',
        52 => 'Anulada por grupo'
    ];

    /**
     *
     * @var array[i][j] donde:
     *                          i = codigo de rechazo
     *                          j = id del medio de pago
     */
    private $_motivosRechazo =
        [
            1=>[
                1=>['descripcion'=>'PEDIR AUTORIZACION','error_usuario'=>'Esta operación requiere autorización. Llame al número que figura en el dorso de su tarjeta para autorizar el pago, e intente nuevamente.'],
                6=>['descripcion'=>'PEDIR AUTORIZACION','error_usuario'=>'Esta operación requiere autorización. Llame al número que figura en el dorso de su tarjeta para autorizar el pago, e intente nuevamente.']
            ],
            76=>[
                1=>['descripcion'=>'LLAMAR AL EMISOR','error_usuario'=>'Esta operación requiere autorización. Llame al número que figura en el dorso de su tarjeta para autorizar el pago, e intente nuevamente.']
            ],
            4=>[
                1=>['descripcion'=>'CAPTURAR TARJETA','error_usuario'=>'El pago ha sido rechazado. Reintente con otro medio de pago.'],
                6=>['descripcion'=>'CAPTURAR TARJETA','error_usuario'=>'El pago ha sido rechazado el pago. Reintente con otro medio de pago.']
            ],
            5=>[
                1=>['descripcion'=>'DENEGADA','error_usuario'=>'El pago ha sido rechazado. Reintente con otro medio de pago.'],
                6=>['descripcion'=>'DENEGADA','error_usuario'=>'El pago ha sido rechazado el pago. Reintente con otro medio de pago.']
            ],
            7=>[
                1=>['descripcion'=>'RETENGA Y LLAME','error_usuario'=>'El pago ha sido rechazado. Reintente con otro medio de pago.']
            ],
            43=>[
                1=>['descripcion'=>'RETENER TARJETA','error_usuario'=>'El pago ha sido rechazado. Reintente con otro medio de pago.']
            ],
            57=>[
                1=>['descripcion'=>'TRANS. NO PERMITIDA','error_usuario'=>'El pago ha sido rechazado. Reintente con otro medio de pago.']
            ],
            91=>[
                1=>['descripcion'=>'EMISOR FUERA LINEA','No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            96=>[
                1=>['descripcion'=>'ERROR EN SISTEMA','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            10003=>[
                1=>['descripcion'=>'Error de time out del socket','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            12=>[
                1=>['descripcion'=>'TRANSAC. INVALIDA','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            10001=>[
                1=>['descripcion'=>'ERROR AL RECIBIR DEL MODULO DE COMUNICACIONES','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                6=>['descripcion'=>'ERROR AL RECIBIR DEL MODULO DE COMUNICACIONES','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            45=>[
                1=>['descripcion'=>'NO OPERA EN CUOTAS','error_usuario'=>'La cantidad de cuotas seleccionada es inválida. Intente nuevamente en una sola cuota.']
            ],
            48=>[
                1=>['descripcion'=>'EXCEDE MAX. CUOTAS','error_usuario'=>'La cantidad de cuotas seleccionada es inválida. Intente nuevamente con otro plan de cuotas.']
            ],
            54=>[
                1=>['descripcion'=>'TARJETA VENCIDA	La tarjeta de crédito que está utilizando está vencida. Reintente con otro medio de pago.']
            ],
            56=>[
                1=>['descripcion'=>'TARJ. NO HABILITADA','error_usuario'=>'La tarjeta de crédito que está utilizando no está habilitada. Llame para habilitarla e intente nuevamente, o utilice otro medio de pago.']
            ],
            51=>[
                1 =>['descripcion'=>'FONDOS INSUFICIENTES','Fondos Insuficientes. Intente con otro medio de pago.'],
                15=>['descripcion'=>'An error occurred during the user authentication','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                8 =>['descripcion'=>'An error occurred during the user authentication','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                30=>['descripcion'=>'An error occurred during the user authentication','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            61=>[
                1=>['descripcion'=>'EXCEDE LIMITE','Fondos Insuficientes. Intente con otro medio de pago.']
            ],
            14=>[
                1=>['descripcion'=>'TARJETA INVALIDA','Alguno de los datos ingresados no es correcto. Revíselos e intente nuevamente.'],
                6=>['descripcion'=>'TARJETA INVALIDA','Alguno de los datos ingresados no es correcto. Revíselos e intente nuevamente.']
            ],
            39=>[
                1=>['descripcion'=>'Ingreso Manual Incorrecto','Alguno de los datos ingresados no es correcto. Revíselos e intente nuevamente.']
            ],
            3=>[
                1=>['descripcion'=>'COMERCIO INVALIDO','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                6=>['descripcion'=>'COMERCIO INVALIDO','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            13=>[
                1=>['descripcion'=>'MONTO INVALIDO','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            46=>[
                1=>['descripcion'=>'TARJETA NO VIGENTE','Su tarjeta aún no se encuentra vigente. Reintente con otro medio de pago.']
            ],
            34=>[
                15=>['descripcion'=>'The operation failed for financial reasons.','error_usuario'=>'Esta operación requiere autorización. Llame al número que figura en el dorso de su tarjeta para autorizar el pago, e intente nuevamente.'],
                8 =>['descripcion'=>'The operation failed for financial reasons.','error_usuario'=>'Esta operación requiere autorización. Llame al número que figura en el dorso de su tarjeta para autorizar el pago, e intente nuevamente.'],
                30=>['descripcion'=>'The operation failed for financial reasons.','error_usuario'=>'Esta operación requiere autorización. Llame al número que figura en el dorso de su tarjeta para autorizar el pago, e intente nuevamente.']
            ],
            8=>[
                15=>['descripcion'=>'A duplicate object exists.','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                8 =>['descripcion'=>'A duplicate object exists.','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                30=>['descripcion'=>'A duplicate object exists.','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            53=>[
                15=>['descripcion'=>'An unhandled (such as null pointer) exception occurred.','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                8 =>['descripcion'=>'An unhandled (such as null pointer) exception occurred.','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.'],
                30=>['descripcion'=>'An unhandled (such as null pointer) exception occurred.','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
            77=>[
                6=>['descripcion'=>'ERROR PLAN/CUOTAS','error_usuario'=>'La cantidad de cuotas seleccionada es inválida. Intente nuevamente con otro plan de cuotas.']
            ],
            10005=>[
                6=>['descripcion'=>'DATOS DE TRANSACCION INCORRECTOS','error_usuario'=>'Los datos ingresados de su Tarjeta de crédito no son correctos. Revíselos e intente nuevamente']
            ],
            25=>[
                6=>['descripcion'=>'NO EXISTE ORIGINAL','error_usuario'=>'No fue posible procesar su pago en este momento. Intente nuevamente más tarde.']
            ],
        ];

    /**
     * @param $codigo Id del codigo de rechazo
     * @param $tarjeta Id de la tarjeta
     *
     * @return array | bool
     */
    public function getDetalleRechazo($codigo,$tarjeta)
    {
        return isset($this->_motivosRechazo[$codigo][$tarjeta]) ? $this->_motivosRechazo[$codigo][$tarjeta] : false;
    }

    public function getDetalleEstadoTransaccion($key)
    {
        return isset($this->_estadoTransaccion[$key]) ? $this->_estadoTransaccion[$key] : false;
    }

}