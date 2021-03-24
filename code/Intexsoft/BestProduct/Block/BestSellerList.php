<?php
namespace Intexsoft\BestProduct\Block;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;


class BestSellerList extends Template
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var array
     */
    private $bestsellerBlockCollection;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * BestSellerList constructor.
     * @param Template\Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param BestsellerBlock $bestsellerBlock
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        BestsellerBlock $bestsellerBlock,
        array $data = []
    )
    {
        $this->bestsellerBlockCollection = $bestsellerBlock->getProductCollection();
        //bestseller id list

        $this->productRepository = $productRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getProductCollection() {
        $filters[] = $this->filterBuilder
            ->setField('entity_id')
            ->setConditionType('in')
            ->setValue($this->bestsellerBlockCollection)
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder->addFilters($filters)->create();
        return $this->productRepository->getList($searchCriteria)->getItems();
    }
}
