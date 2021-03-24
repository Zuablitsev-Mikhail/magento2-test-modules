<?php

namespace Intexsoft\CartRule\Block;


use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;

class CartInfo extends Template

{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * Form constructor.
     * @param Context $context
     * @param Session $checkoutSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param int $itemId
     * @return float|int
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getItemsQty($itemId = 0) // 0 for all items
    {
        return $this->getActiveQuoteAddress()->getItemQty($itemId);
    }

    /**
     * @return float
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSubtotal()
    {
        return $this->getActiveQuoteAddress()->getBaseSubtotal(); // or any other type of subtotal like subtotal incl tax etc.
    }

    /**
     * @return Address
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function getActiveQuoteAddress()
    {
        /** @var Quote $quote */
        $quote = $this->checkoutSession->getQuote();
        if ($quote->isVirtual()) {
            return $quote->getBillingAddress();
        }

        return $quote->getShippingAddress();
    }
}
