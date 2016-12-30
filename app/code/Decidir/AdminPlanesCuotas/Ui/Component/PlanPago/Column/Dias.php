<?php
namespace Decidir\AdminPlanesCuotas\Ui\Component\PlanPago\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Decidir\AdminPlanesCuotas\Helper\Data;

/**
 * Class Dias
 *
 * @description Renderizacion de los dias en formato legible al usuario por cada fila de la grilla
 *              de planes de pago.
 *
 */
class Dias extends Column
{
    /**
     * @var UrlInterface
     */
    protected $_helper;

    /**
     * Dias constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Data $helper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Data $helper,
        array $components = [],
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as &$item)
            {
                $numeroDias = explode(',',$item[$this->getData('name')]);

                $dias = $this->_helper->getDiasSemana($numeroDias);

                $item[$this->getData('name')] = implode(', ',$dias);
            }
        }

        return $dataSource;
    }
}
