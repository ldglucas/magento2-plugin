<?php

namespace Decidir\AdminPlanesCuotas\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Banco
 *
 * @description Source model para obtener los option de un select html con bancos
 *
 */
class Banco implements OptionSourceInterface
{
    /**
     * @var \Decidir\AdminPlanesCuotas\Model\BancoFactory
     */
    protected $_bancos;

    /**
     * Banco constructor.
     * @param \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory
     */
    public function __construct(\Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory)
    {
        $this->_bancos = $bancoFactory;
    }

    /**
     * Retorna un las opciones para visualizar el select de bancos.
     * @return array
     */
    public function getOptionArray()
    {
        $options = [];

        $bancos = $this->_bancos->create()->getCollection()->getData();

        foreach($bancos as $_banco)
        {
            $options[$_banco['banco_id']] = $_banco['nombre'];
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