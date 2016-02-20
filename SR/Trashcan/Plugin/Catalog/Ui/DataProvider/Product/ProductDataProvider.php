<?php
namespace SR\Trashcan\Plugin\Catalog\Ui\DataProvider\Product;

class ProductDataProvider
{
    /**
     * @return AbstractCollection
     */
    public function aroundGetCollection(
        \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider $subject,
        \Closure $proceed
    )
    {
        $collection = $proceed();
        return $collection->addFieldToFilter('status', array('neq' => 3));
    }
}