<?php
namespace SR\Trashcan\Plugin\Catalog\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;

class MassDelete
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * MassDelete constructor.
     *
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ResultFactory $resultFactory
     * @param ManagerInterface $messageManager
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        ResultFactory $resultFactory,
        ManagerInterface $messageManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function aroundExecute(
        \Magento\Catalog\Controller\Adminhtml\Product\MassDelete $subject,
        \Closure $proceed
    )
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productDeleted = 0;
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection->getItems() as $product) {
            $product->setStatus(3);
            $this->productRepository->save($product);
            $productDeleted++;
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been deleted.', $productDeleted)
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('catalog/*/index');
    }
}
