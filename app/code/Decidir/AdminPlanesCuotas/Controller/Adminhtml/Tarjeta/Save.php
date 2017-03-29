<?php

namespace Decidir\AdminPlanesCuotas\Controller\Adminhtml\Tarjeta;

/**
 * Class Save
 *
 * @description Action para guardar los datos de una tarjeta de credito.
 *
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Decidir\AdminPlanesCuotas\Model\TarjetaFactory
     */
    protected $_tarjetaFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $tarjetaFactory
     */
    public function __construct(\Magento\Backend\App\Action\Context $context,
                                \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $tarjetaFactory)
    {
        parent::__construct($context);

        $this->_tarjetaFactory = $tarjetaFactory;
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
            $this->_redirect('adminplanescuotas/tarjeta/admin');
            return;
        }

        try
        {
            $rowData = $this->_tarjetaFactory->create();
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
        $this->_redirect('adminplanescuotas/tarjeta/admin');
    }

    /**
     * Check Category Map permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Decidir_AdminPlanesCuotas::tarjetas');
    }
}
