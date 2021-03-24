<?php

namespace Intexsoft\CartRule\Block;


use Magento\Framework\App\ObjectManager;

class CheckRuleName
{
    /**
     * @var mixed
     */
    protected $storeManager;
    /**
     * @var mixed
     */
    protected $cartRule;
    /**
     * @var
     */
    protected $cartRuleCollection;
    /**
     * CheckRuleName constructor.
     */
    public function __construct()
    {
        $this->storeManager = ObjectManager::getInstance()->create('\Magento\Store\Model\StoreManagerInterface');
        $this->cartRule = ObjectManager::getInstance()->create('\Magento\SalesRule\Model\RuleFactory');;
        $this->cartRuleCollection = $this->cartRule->create()->getCollection();
    }

    /**
     * @return array
     */
    public function getCatalogPriceRuleProductIds()
    {
        $resultProductIds = [];
        foreach ($this->cartRuleCollection as $cartRule) {
            $resultProductIds[] = $cartRule->getName();//name of rule
        }
        return $resultProductIds;
    }
    public function _getConditionsSerialized(string $name){
        foreach ($this->cartRuleCollection as $cartRule) {
            if($cartRule->getName() === $name) return $cartRule->getConditionsSerialized();
        }
        return null;
    }
    public function _getStatus(string $name){
        foreach ($this->cartRuleCollection as $cartRule) {
            if($cartRule->getName() === $name) return $cartRule->getIsActive();
        }
    }
    public function _getDiscountAmount(string $name){
        foreach ($this->cartRuleCollection as $cartRule) {
            if($cartRule->getName() === $name)
            $resultProductIds[] = $cartRule->getName();//name of rule
        }
    }
}
