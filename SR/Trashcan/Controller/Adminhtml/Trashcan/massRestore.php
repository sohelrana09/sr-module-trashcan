<?php
namespace SR\Trashcan\Controller\Adminhtml\Trashcan;

use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class massRestore extends \Magento\Catalog\Controller\Adminhtml\Product
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
     * @param Context $context
     * @param Builder $productBuilder
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Builder $productBuilder,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $productBuilder);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productRestored = 0;
        foreach ($collection->getItems() as $product) {
            $product->setStatus(1)->save();
            $productRestored++;
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been restored.', $productRestored)
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('trashcan/trashcan/index');
    }
}