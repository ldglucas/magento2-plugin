<?php

namespace Decidir\AdminPlanesCuotas\Controller\Adminhtml\Banco;

/**
 * Class Edit
 *
 * @description Action para editar un banco.
 *
 */
class Edit extends  \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\BancoFactory
     */
    protected $_bancoFactory;

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
        \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory
    )
    {
        parent::__construct($context);
        $this->_bancoFactory = $bancoFactory;
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
        $rowData = $this->_bancoFactory->create();

        if ($rowId)
        {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getNombre();

            if (!$rowData->getBancoId())
            {
                $this->messageManager->addErrorMessage(__('row data no longer exist.'));
                $this->_redirect('adminplanescuotas/banco/admin');
                return;
            }
        }

        $this->_coreRegistry->register('row_data', $rowData);

        $resultPage = $this->resultPageFactory->create();
        $title = $rowId ? __('Editar banco ').$rowTitle : __('Crear banco');

        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Decidir_AdminPlanesCuotas::bancos');
    }

}
