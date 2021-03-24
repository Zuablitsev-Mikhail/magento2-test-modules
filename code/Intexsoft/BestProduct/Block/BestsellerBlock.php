<?php
namespace Intexsoft\BestProduct\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\View\Element\Template;
use Magento\Reports\Model\ResourceModel\Report\Collection\Factory;

class BestsellerBlock extends Template
{
    /**
     * @var Factory
     */
    protected Factory $_resourceFactory;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var mixed
     */
    protected $objectManager;

    /**
     * BestsellerBlock constructor.
     * @param Factory $resourceFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Factory $resourceFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
        $this->_resourceFactory = $resourceFactory;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance()->create("Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable");
    }

    /**
     * @return array
     */
    public function getProductCollection() {
        $resourceCollection = $this->_resourceFactory->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
        $resourceCollection->setPageSize(10);
        $resourceCollection->setOrder('rating_pos', Select::SQL_DESC);
        $collection = [];
        foreach ($resourceCollection as $item) {
            $product = $this->objectManager->getParentIdsByChild($item['product_id']);
            if (isset($product[0]))
                $collection[] = $product[0];
            else $collection[] = $item['product_id'];
        }
        return $collection;
    }
}
