<?php

namespace Decidir\AdminPlanesCuotas\Controller\Adminhtml\Interes;

/**
 * Class Edit
 *
 * @description Action para editar un plan de pago.
 *
 */
class Edit extends  \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory
     */
    protected $_planPagoFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\Session
     */
    protected $_session;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry    $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory $planPagoFactory,
        \Decidir\AdminPlanesCuotas\Model\Session $adminplanescuotasSession
    )
    {
        parent::__construct($context);
        $this->_planPagoFactory = $planPagoFactory;
        $this->_coreRegistry   = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->_session = $adminplanescuotasSession;
    }

    /**
     * Add New Row Form page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->_planPagoFactory->create();

        if ($rowId)
        {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getNombre();

            if (!$rowData->getPlanPagoId())
            {
                $this->messageManager->addErrorMessage(__('row data no longer exist.'));
                $this->_redirect('adminplanescuotas/interes/admin');
                return false;
            }
        }
        else
        {
            $this->_redirect('adminplanescuotas/interes/admin');
            return false;
        }

        $this->_session->setPlanPago($rowData);

        $resultPage = $this->resultPageFactory->create();
        $title = $rowId ? __('Editar intereses ').$rowTitle : __('Agregar intereses');

        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Decidir_AdminPlanesCuotas::interes_edit');
    }

}