<?php

namespace Intexsoft\CartRule\Helper;

use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    protected $messageManager;
    private $productCollection;
    private $productAction;
    private $storeManager;

    public function __construct(
        Context $context,
        CollectionFactory $collection,
        ProductAction $action,
        StoreManagerInterface $storeManager
    )
    {
        $this->productCollection = $collection;
        $this->productAction = $action;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function setAttributeData($value)
    {
        try {
            $collection = $this->productCollection->create()->addFieldToFilter('*');
            $storeId = $this->storeManager->getStore()->getId();
            $ids = [];
            $i = 0;
            foreach ($collection as $item) {
                $ids[$i] = $item->getEntityId();
                $i++;
            }
            $this->productAction->updateAttributes($ids, array('cart_total_coast' => 0), $storeId);

        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }
}
