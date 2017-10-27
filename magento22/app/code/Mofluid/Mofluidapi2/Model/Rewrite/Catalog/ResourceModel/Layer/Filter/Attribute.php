<?php
namespace Mofluid\Mofluidapi2\Model\Rewrite\Catalog\ResourceModel\Layer\Filter;

class Attribute extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Attribute {
    public function applyFilterToCollection(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter, $value)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $attribute = $filter->getAttributeModel();
        $connection = $this->getConnection();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
            $connection->quoteInto("{$tableAlias}.value IN (?)", $value),
        ];

        $collection->getSelect()->join(
            [$tableAlias => $this->getMainTable()],
            implode(' AND ', $conditions),
            []
        );

        return $this;
    }
}