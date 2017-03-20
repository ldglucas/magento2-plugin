<?php

namespace Decidir\AdminPlanesCuotas\Controller\Adminhtml\Tarjeta;

/**
 * Class Edit
 *
 * @description Action para editar una tarjeta de credito.
 *
 */
class Edit extends  \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\TarjetaFactory
     */
    protected $_tarjetaFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry    $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $tarjetaFactory
    )
    {
        parent::__construct($context);
        $this->_tarjetaFactory = $tarjetaFactory;
        $this->_coreRegistry   = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Add New Row Form page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->_tarjetaFactory->create();

        if ($rowId)
        {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getNombre();

            if (!$rowData->getTarjetaId())
            {
                $this->messageManager->addErrorMessage(__('row data no longer exist.'));
                $this->_redirect('adminplanescuotas/tarjeta/admin');
                return;
            }
        }

        $this->_coreRegistry->register('row_data', $rowData);

        $resultPage = $this->resultPageFactory->create();
        $title = $rowId ? __('Editar tarjeta ').$rowTitle : __('Crear tarjeta');

        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Decidir_AdminPlanesCuotas::tarjetas');
    }

}
