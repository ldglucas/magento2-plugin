<?php

namespace Decidir\AdminPlanesCuotas\Controller\Adminhtml\Banco;

/**
 * Class Save
 *
 * @description Action para guardar los datos de un banco.
 *
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Decidir\AdminPlanesCuotas\Model\BancoFactory
     */
    protected $_bancoFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory
     */
    public function __construct(\Magento\Backend\App\Action\Context $context,
                                \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory)
    {
        parent::__construct($context);

        $this->_bancoFactory = $bancoFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data)
        {
            $this->_redirect('adminplanescuotas/banco/admin');
            return;
        }

        try
        {
            $rowData = $this->_bancoFactory->create();
            $rowData->setData($data);

            if (isset($data['id']))
            {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            $this->messageManager->addSuccessMessage(__('La información fue guardada correctamente.'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
        $this->_redirect('adminplanescuotas/banco/admin');
    }

    /**
     * Check Category Map permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Decidir_AdminPlanesCuotas::bancos');
    }
}
