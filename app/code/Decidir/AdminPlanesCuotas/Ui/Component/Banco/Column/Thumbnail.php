<?php
namespace Decidir\AdminPlanesCuotas\Ui\Component\Banco\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class Thumbnail
 *
 * @description Clase renderizadora de imagenes para cada fila de banco.
 *
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME      = 'thumbnail';
    const ALT_FIELD = 'name';

    /**
     * @var \Decidir\AdminPlanesCuotas\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Decidir\AdminPlanesCuotas\Helper\Data $helper,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        $this->_helper = $helper;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $helper = $this->_helper;

        if (isset($dataSource['data']['items']))
        {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item)
            {
                $imgSrc = $helper->getImageUrl($item['logo_src'],$helper::PATH_BANCOS);

                $item[$fieldName . '_src'] = $imgSrc;
                $item[$fieldName . '_alt'] = $item['nombre'];
                $item[$fieldName . '_orig_src'] = $imgSrc;
            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
