<?php
namespace Decidir\AdminPlanesCuotas\Model;

use Decidir\AdminPlanesCuotas\Api\DescuentoInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Descuento
 *
 * @description Modelo dedicado a la ejecucion de la API REST de magento. Este se encarga de aplicar o descartar un
 *              descuento al total de una compra.
 *
 */
class Descuento implements DescuentoInterface
{
    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var CuotaFactory
     */
    protected $_cuotaFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    protected $_cresource;

    /**
     * Descuento constructor.
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param CuotaFactory $cuotaFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\ResourceConnection $cresource
    ) {
        $this->_cresource = $cresource;
        $this->quoteRepository  = $quoteRepository;
        $this->_cuotaFactory    = $cuotaFactory;
        $this->_checkoutSession = $checkoutSession;

        $this->_checkoutSession->setAplicarDescuento(false);
    }

    /**
     * {@inheritdoc}
     */
    public function set($planPagoId,$cuota)
    {
        $cartId = $this->_checkoutSession->getQuoteId();

        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }
        $quote->getShippingAddress()->setCollectShippingRates(true);

        try
        {
            $detallesCuota = $this->_cuotaFactory->create()->getDetalles($planPagoId,$cuota);

            if(count($detallesCuota->getData()) && $detallesCuota->getDescuento() > 0)
            {
                $descuentoFinal = 0;

                /**
                 * Verifico si el gran total ya tiene aplicado un descuento por cuota anterior, y en caso de ser afirmativo
                 * lo elimino para que se pueda calcular nuevamente.
                 */
                if($quote->getDescuentoCuota() > 0)
                {
                    $quote->setGrandTotal($quote->getGrandTotal() + $quote->getDescuentoCuota());
                    $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $quote->getDescuentoCuota());
                    $quote->setDescuentoCuota(0);
                    $quote->setDescuentoCuotaDescripcion('');
                }

                if($detallesCuota->getTipoDescuento() == \Decidir\AdminPlanesCuotas\Model\Cuota::TIPO_DESCUENTO_PORCENTUAL
                && $detallesCuota->getDescuento() < 100)
                {
                    $descuentoFinal = number_format((($detallesCuota->getDescuento() * $quote->getGrandTotal())/100),2);
                }
                if($detallesCuota->getTipoDescuento() == \Decidir\AdminPlanesCuotas\Model\Cuota::TIPO_DESCUENTO_NOMINAL
                    && $quote->getGrandTotal() > $detallesCuota->getDescuento())
                {
                    $descuentoFinal = $detallesCuota->getDescuento();
                }

                $s = $detallesCuota->getCuota() == 1 ? '' : 's';

                $this->_checkoutSession->setAplicarDescuento(true);
                $quote->setDescuentoCuota($descuentoFinal);
                $quote->setDescuentoCuotaDescripcion("Descuento por pago en {$detallesCuota->getCuota()} cuota$s con {$detallesCuota->getTarjetaNombre()} y {$detallesCuota->getBancoNombre()}");
            }
            else
            {
                $quote->setDescuentoCuota(0);
                $quote->setDescuentoCuotaDescripcion('');
            }

            $this->quoteRepository->save($quote->collectTotals());

        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('No se pudo agregar el descuento de cuota'));
        }

        return $quote->getDescuentoCuotaDescripcion();
    }

    /**
     * @description Resetea cualquier descuento aplicado al carrito por una cuota
     * @return bool
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function reset()
    {
        $cartId = $this->_checkoutSession->getQuoteId();

        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }
        $quote->getShippingAddress()->setCollectShippingRates(true);

        try
        {
            /**
             * Verifico si el gran total ya tiene aplicado un descuento por cuota anterior, y en caso de ser afirmativo
             * lo elimino para que se pueda calcular nuevamente.
             */
            if($quote->getDescuentoCuota() > 0)
            {
                $quote->setGrandTotal($quote->getGrandTotal() + $quote->getDescuentoCuota());
                $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $quote->getDescuentoCuota());
                $quote->setDescuentoCuota(0);
                $quote->setDescuentoCuotaDescripcion('');
            }

            $quote = $quote->collectTotals();

            $this->quoteRepository->save($quote);

        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('No se pudo guardar el descuento'));
        }

        return true;
    }
}
