<?php

namespace Decidir\AdminPlanesCuotas\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\TestFramework\Helper\Eav;

/**
 * Class InstallData
 *
 * @description Instalador de datos para las tablas
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->_eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Prepare database for install
         */
        $setup->startSetup();

        $datosTarjetas = [
            ['sps_tarjeta_id'=>1 ,'nps_tarjeta_id'=>14 ,'logo_src'=>'t_visa.png','nombre'=>'VISA','activo'=>1,'prioridad_orden'=>1],
            ['sps_tarjeta_id'=>6 ,'nps_tarjeta_id'=>1  ,'logo_src'=>'t_amex.png','nombre'=>'AMERICAN EXPRESS','activo'=>1,'prioridad_orden'=>3],
            ['sps_tarjeta_id'=>8 ,'nps_tarjeta_id'=>2  ,'logo_src'=>'t_diners.png','nombre'=>'DINERS','activo'=>1,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>15,'nps_tarjeta_id'=>5  ,'logo_src'=>'t_mastercard.png','nombre'=>'MASTERCARD','activo'=>1,'prioridad_orden'=>2],
            ['sps_tarjeta_id'=>20,'nps_tarjeta_id'=>5  ,'logo_src'=>'t_mastercard.png','nombre'=>'MASTERCARD TEST','activo'=>1,'prioridad_orden'=>2],
            ['sps_tarjeta_id'=>23,'nps_tarjeta_id'=>42 ,'logo_src'=>'t_shopping.png','nombre'=>'TARJETA SHOPPING','activo'=>1,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>24,'nps_tarjeta_id'=>9  ,'logo_src'=>'t_naranja.png','nombre'=>'TARJETA NARANJA','activo'=>1,'prioridad_orden'=>5],
//            ['sps_tarjeta_id'=>25,'nps_tarjeta_id'=>999,'logo_src'=>'','nombre'=>'PAGO FACIL','activo'=>0,'prioridad_orden'=>99],
//            ['sps_tarjeta_id'=>26,'nps_tarjeta_id'=>999,'logo_src'=>'','nombre'=>'RAPIPAGO','activo'=>0,'prioridad_orden'=>99],
            ['sps_tarjeta_id'=>27,'nps_tarjeta_id'=>8  ,'logo_src'=>'t_cabal.png','nombre'=>'CABAL','activo'=>1,'prioridad_orden'=>4],
            ['sps_tarjeta_id'=>29,'nps_tarjeta_id'=>43 ,'logo_src'=>'t_italcred.png','nombre'=>'ITALCRED','activo'=>1,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>30,'nps_tarjeta_id'=>65 ,'logo_src'=>'t_argencard.png','nombre'=>'ARGENCARD','activo'=>1,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>31,'nps_tarjeta_id'=>999,'logo_src'=>'t_visa.png','nombre'=>'VISA DEBITO','activo'=>0,'prioridad_orden'=>1],
            ['sps_tarjeta_id'=>34,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'COOPEPLUS','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>36,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'ARCASH','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>37,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'NEXO','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>38,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'CREDIMAS','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>39,'nps_tarjeta_id'=>21 ,'logo_src'=>'t_nevada.png','nombre'=>'NEVADA','activo'=>0,'prioridad_orden'=>5],
//            ['sps_tarjeta_id'=>41,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'PAGOMISCUENTAS','activo'=>0,'prioridad_orden'=>99],
            ['sps_tarjeta_id'=>42,'nps_tarjeta_id'=>63 ,'logo_src'=>'t_nativa.png','nombre'=>'NATIVA','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>43,'nps_tarjeta_id'=>999,'logo_src'=>'t_cencosud.png','nombre'=>'TARJETA MAS/CENCOSUD','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>44,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'CETELEM','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>45,'nps_tarjeta_id'=>50 ,'logo_src'=>null,'nombre'=>'NACIONPYMES','activo'=>0,'prioridad_orden'=>5],
            ['sps_tarjeta_id'=>46,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'PAYSAFECARD','activo'=>0,'prioridad_orden'=>99],
//            ['sps_tarjeta_id'=>47,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'MONEDERO ONLINE','activo'=>0,'prioridad_orden'=>99],
            ['sps_tarjeta_id'=>48,'nps_tarjeta_id'=>999,'logo_src'=>null,'nombre'=>'CAJA DE PAGOS','activo'=>0,'prioridad_orden'=>99]
        ];

        $setup->getConnection()
            ->insertArray($setup->getTable('decidir_tarjeta'),
                ['sps_tarjeta_id', 'nps_tarjeta_id','logo_src','nombre','activo','prioridad_orden'], $datosTarjetas);

        $datosBancos = [
            ['nombre'=>'AHORA 12'                             ,'activo'=>1,'logo_src'=>'b_ahora12.png','prioridad_orden'=>1],
            ['nombre'=>'BANCO DE GALICIA Y BUENOS AIRES S.A.' ,'activo'=>1,'logo_src'=>'b_galicia.png','prioridad_orden'=>2],
            ['nombre'=>'BANCO DE LA NACIÓN ARGENTINA'         ,'activo'=>1,'logo_src'=>'b_nacion.png','prioridad_orden'=>3],
            ['nombre'=>'BANCO DE LA PROVINCIA DE BUENOS AIRES','activo'=>1,'logo_src'=>'b_provincia.png','prioridad_orden'=>4],
            ['nombre'=>'STANDARD BANK ARGENTINA S.A. - ICBC'  ,'activo'=>1,'logo_src'=>'b_icbc.png','prioridad_orden'=>5],
            ['nombre'=>'CITIBANK N.A.'                        ,'activo'=>1,'logo_src'=>'b_citibank.png','prioridad_orden'=>6],
            ['nombre'=>'BBVA BANCO FRANCÉS S.A.'              ,'activo'=>1,'logo_src'=>'b_frances.png','prioridad_orden'=>7],
            ['nombre'=>'BANCO SANTANDER RIO S.A.'             ,'activo'=>1,'logo_src'=>'b_santander.png','prioridad_orden'=>8],
            ['nombre'=>'HSBC BANK ARGENTINA S.A.'             ,'activo'=>1,'logo_src'=>'b_hsbc.png','prioridad_orden'=>9],
            ['nombre'=>'BANCO CIUDAD'                         ,'activo'=>1,'logo_src'=>'b_ciudad.png','prioridad_orden'=>10],
            ['nombre'=>'BANCO SUPERVIELLE S.A.'               ,'activo'=>1,'logo_src'=>'b_supervielle.png','prioridad_orden'=>11],
            ['nombre'=>'BANCO MACRO S.A.'                     ,'activo'=>1,'logo_src'=>'b_macro.png','prioridad_orden'=>12],
            ['nombre'=>'BANCO PATAGONIA S.A.'                 ,'activo'=>1,'logo_src'=>'b_patagonia.png','prioridad_orden'=>13],
            ['nombre'=>'BANCO HIPOTECARIO S.A.'               ,'activo'=>1,'logo_src'=>'b_hipotecario.png','prioridad_orden'=>14],
            ['nombre'=>'BANCO COMAFI S.A.'                    ,'activo'=>1,'logo_src'=>'b_comafi.png','prioridad_orden'=>15],
            ['nombre'=>'BANCO CREDICOOP'                      ,'activo'=>1,'logo_src'=>'b_credicoop.png','prioridad_orden'=>16],
            ['nombre'=>'BANCO INDUSTRIAL'                     ,'activo'=>1,'logo_src'=>'b_bind.png','prioridad_orden'=>17]
        ];

        $setup->getConnection()
            ->insertArray($setup->getTable('decidir_banco'),
                ['nombre','activo','logo_src','prioridad_orden'], $datosBancos);

        $setup->endSetup();
    }
}
