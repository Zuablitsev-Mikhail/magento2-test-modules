<?php

namespace Intexsoft\CartRule\Block;


use Magento\Framework\App\ObjectManager;

class RuleData
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

    /**
     * @param string $name
     * @return string
     */
    public function _getConditionsSerialized(string $name){
        $val = "";
        foreach ($this->cartRuleCollection as $cartRule) {
            if($cartRule->getName() === $name) $val = $cartRule->getConditionsSerialized();
        }
        return $val;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function _getStatus(string $name){
        $val = false;
        foreach ($this->cartRuleCollection as $cartRule) {
            if($cartRule->getName() === $name) $val = $cartRule->getIsActive();
        }
        return $val;
    }

    /**
     * @param string $name
     * @return float
     */
    public function _getDiscountAmount(string $name){
        $val = 1;
        foreach ($this->cartRuleCollection as $cartRule) {
            if($cartRule->getName() === $name) $val = $cartRule->getDiscountAmount();;
        }
        return $val;
    }
}
