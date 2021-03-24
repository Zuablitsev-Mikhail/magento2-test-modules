<?php

namespace Intexsoft\CartRule\Block;


use Magento\Backend\Block\Template;

class CheckRuleName
{
    public function getCatalogPriceRuleProductIds()
    {
        $storeManager = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Store\Model\StoreManagerInterface'
        );
        $cartRule = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\SalesRule\Model\RuleFactory'
        );

        $websiteId = $storeManager->getStore()->getWebsiteId();//current Website Id

        $resultProductIds = [];
        $cartRuleCollection = $cartRule->create()->getCollection();
        $cartRuleCollection->addIsActiveFilter(1);//filter for active rules only
        foreach ($cartRuleCollection as $cartRule) {
            $resultProductIds[] = $cartRule->getName();//name of rule
        }
        return $resultProductIds;
    }

}
