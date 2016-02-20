<?php
namespace SR\Trashcan\Plugin\Catalog\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

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
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        ResultFactory $resultFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
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
        foreach ($collection->getItems() as $product) {
            $product->setStatus(3)->save();
            $productDeleted++;
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $productDeleted)
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('catalog/*/index');
    }
}
