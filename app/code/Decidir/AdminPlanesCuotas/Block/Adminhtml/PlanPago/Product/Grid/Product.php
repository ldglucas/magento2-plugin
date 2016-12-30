<?php

namespace Decidir\AdminPlanesCuotas\Block\Adminhtml\PlanPago\Product\Grid;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

/**
 * Class Product
 *
 * @description Bloque para renderizar la grilla de productos que se pueden agregar especialmente
 */
class Product extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\PlanPagoProductoFactory
     */
    protected $_productosAsociadosPlanPago;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Decidir\AdminPlanesCuotas\Model\PlanPagoProductoFactory $productos,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory,
        \Magento\Catalog\Model\Product\Type $type,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_productosAsociadosPlanPago = $productos;
        $this->_setsFactory = $setsFactory;
        $this->_type = $type;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('gridAgregarProductosPlan');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }


    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'item_selected')
        {
            $productDecidir = $this->_getSelectedProducts();

            if ($column->getFilter()->getValue())
            {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productDecidir]);
            }
            elseif (!empty($productDecidir))
            {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productDecidir]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'price'
        )->addStoreFilter(
            $this->getRequest()->getParam('store')
        )->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int)$this->getRequest()->getParam('id', 0),
            'left'
        );
        $this->setCollection($collection);

        if(($productDecidir = $this->_getSelectedProducts()) && !$this->getRequest()->getParam('isAjax') )
        {
            $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productDecidir]);
        }

        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'item_selected',
            [
                'type' => 'checkbox',
                'name' => 'item_selected',
                'values' => $this->_getSelectedProducts(),
                'index' => 'entity_id',
                'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction'
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);

        $this->addColumn('sku' , ['header' => __('SKU'), 'index' => 'sku']);

        $sets = $this->_setsFactory->create()->setEntityTypeFilter(
            $this->_productFactory->create()->getResource()->getTypeId()
        )->load()->toOptionHash();
        $this->addColumn(
            'set_name',
            [
                'header' => __('Attribute Set'),
                'index' => 'attribute_set_id',
                'type' => 'options',
                'options' => $sets,
                'header_css_class' => 'col-attr-name',
                'column_css_class' => 'col-attr-name'
            ]
        );

        $this->addColumn(
            'type',
            [
                'header' => __('Type'),
                'index' => 'type_id',
                'type' => 'options',
                'options' => $this->_type->getOptionArray(),
                'header_css_class' => 'col-type',
                'column_css_class' => 'col-type'
            ]
        );

        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'currency_code' => (string)$this->_scopeConfig->getValue(
                    \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'index' => 'price'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @description Busca los productos que tenga asociado el plan de pagos actual.
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $productos = [];

        if($planId = $this->getRequest()->getParam('id'))
        {
            $productosAsociados = $this->_productosAsociadosPlanPago->create()
                ->getCollection()
                ->addFieldToFilter('plan_pago_id',['eq'=>$planId]);

            if($productosAsociados->getSize())
            {
                foreach($productosAsociados->getData() as $_item)
                {
                    $productos[] = $_item['entity_id_producto'];
                }

            }
        }

        return $productos;
    }
}
