<?php

namespace Decidir\AdminPlanesCuotas\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Tarjeta
 *
 * @description Source model para obtener los option de un select html con tarjetas
 *
 */
class Tarjeta implements OptionSourceInterface
{
    /**
     * @var \Decidir\AdminPlanesCuotas\Model\TarjetaFactory
     */
    protected $_tarjetas;

    /**
     * Tarjeta constructor.
     * @param \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $tarjetaFactory
     */
    public function __construct(\Decidir\AdminPlanesCuotas\Model\TarjetaFactory $tarjetaFactory)
    {
        $this->_tarjetas = $tarjetaFactory;
    }

    /**
     * Retorna un las opciones para visualizar el select de bancos.
     * @return array
     */
    public function getOptionArray()
    {
        $options = [];

        $tarjetas = $this->_tarjetas->create()->getCollection()->getData();

        foreach($tarjetas as $_tarjeta)
        {
            $options[$_tarjeta['tarjeta_id']] = $_tarjeta['nombre'];
        }

        return $options;
    }

    /**
     * Get Grid row status labels array with empty value for option element.
     *
     * @return array
     */
    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }

    /**
     * Get Grid row type array for option element.
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}