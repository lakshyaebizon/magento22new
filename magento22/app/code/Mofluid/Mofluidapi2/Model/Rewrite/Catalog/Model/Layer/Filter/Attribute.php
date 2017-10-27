<?php
namespace Mofluid\Mofluidapi2\Model\Rewrite\Catalog\Model\Layer\Filter;

class Attribute extends \Magento\Catalog\Model\Layer\Filter\Attribute {
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $filter = $request->getParam($this->_requestVar);
        if (is_array($filter)) {
            return $this;
        }
        $text = $this->getOptionText($filter);
        if ($filter && is_array($text) && count($text)) {
            $this->_getResource()->applyFilterToCollection($this, explode(',',$filter));
            foreach ($text as $label) {
                $this->getLayer()->getState()->addFilter($this->_createItem($label, $filter));
            }
            $this->_items = [];
        } elseif ($filter && strlen($text)) {
            $this->_getResource()->applyFilterToCollection($this, $filter);
            $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
            $this->_items = [];
        }
        return $this;
    }
}