<?php
namespace Mofluid\Mofluidapi2\Helper;

use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\CatalogInventory\Model\StockRegistry;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\NoSuchEntityException;

class Listings extends AbstractHelper
{
    /**
     * Product repository
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Category repository
     *
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * Category collection
     *
     * @var CategoryCollection
     */
    protected $categoryCollection;

    /**
     * Search collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $searchFactory;

    /**
     * Product collection
     *
     * @var ProductCollection
     */
    protected $productCollection;

    /**
     * Stock Item repository
     *
     * @var StockRegistry
     */
    protected $stockRegistry;

    /**
     * Category filters
     *
     * @var \Magento\Catalog\Model\Layer\FilterList
     */
    protected $filterList;

    /**
     * Filter layer resolver
     *
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $resolver;

    /**
     * Image helper
     *
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * Directory helper
     *
     * @var \Magento\Directory\Helper\Data
     */
    protected $directoryHelper;

    /**
     * Stock helper
     *
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $stockHelper;

    /**
     * Currency model
     *
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $currency;

    /**
     * Query factory
     *
     * @var \Magento\Search\Model\QueryFactory
     */
    protected $queryFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $requesting;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository
     */
    protected $_productAttributeRepository;

    /**
     * @var \Mofluid\Mofluidapi2\Helper\Amasty\AttributeFactory
     */
    protected $amstyAttrFactory;

    /**
     * @var \Mofluid\Mofluidapi2\Helper\Amasty\PriceFactory
     */
    protected $amstyPriceFactory;

    /**
     * @var \Mofluid\Mofluidapi2\Helper\Amasty\DecimalFactory
     */
    protected $amstyDecimalFactory;

    /**
     * @var \Mofluid\Mofluidapi2\Helper\Amasty\CategoryFactory
     */
    protected $amstyCategoryFactory;

    /**
     * @var \Magento\Catalog\Model\Layer\Category\FilterableAttributeList
     */
    protected $filterableAttributes;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\ItemFactory
     */
    protected $_filterItemFactory;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder
     */
    protected $_itemDataBuilder;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Listings constructor.
     *
     * @param ProductRepository $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryCollection $categoryCollection
     * @param ProductCollection $searchFactory
     * @param ProductCollection $productCollection
     * @param StockRegistry $stockRegistry
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Catalog\Model\Layer\Resolver $resolver
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Directory\Helper\Data $directoryHelper
     * @param \Magento\CatalogInventory\Helper\Stock $stockHelper
     * @param \Magento\Framework\Locale\CurrencyInterface $currency
     * @param \Magento\Search\Model\QueryFactory $queryFactory
     * @param Amasty\AttributeFactory $amstyAttrFactory
     * @param Amasty\PriceFactory $amstyPriceFactory
     * @param Amasty\DecimalFactory $amstyDecimalFactory
     * @param Amasty\CategoryFactory $amstyCategoryFactory
     * @param \Magento\Catalog\Model\Layer\Category\FilterableAttributeList $filterableAttributes
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Catalog\Model\Product\Attribute\Repository $_productAttributeRepository
     * @param \Magento\Framework\App\Request\Http $requesting
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        ProductRepository $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        CategoryCollection $categoryCollection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $searchFactory,
        ProductCollection $productCollection,
        StockRegistry $stockRegistry,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\Resolver $resolver,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\CatalogInventory\Helper\Stock $stockHelper,
        \Magento\Framework\Locale\CurrencyInterface $currency,
        \Magento\Search\Model\QueryFactory $queryFactory,
        \Mofluid\Mofluidapi2\Helper\Amasty\AttributeFactory $amstyAttrFactory,
        \Mofluid\Mofluidapi2\Helper\Amasty\PriceFactory $amstyPriceFactory,
        \Mofluid\Mofluidapi2\Helper\Amasty\DecimalFactory $amstyDecimalFactory,
        \Mofluid\Mofluidapi2\Helper\Amasty\CategoryFactory $amstyCategoryFactory,
        \Magento\Catalog\Model\Layer\Category\FilterableAttributeList $filterableAttributes,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\Product\Attribute\Repository $_productAttributeRepository,
        \Magento\Framework\App\Request\Http $requesting,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->categoryCollection = $categoryCollection;
        $this->searchFactory = $searchFactory;
        $this->productCollection = $productCollection;
        $this->stockRegistry = $stockRegistry;
        $this->filterList = $filterList;
        $this->resolver = $resolver;
        $this->imageHelper = $imageHelper;
        $this->directoryHelper = $directoryHelper;
        $this->stockHelper = $stockHelper;
        $this->currency = $currency;
        $this->queryFactory = $queryFactory;
        $this->amstyAttrFactory = $amstyAttrFactory;
        $this->amstyPriceFactory = $amstyPriceFactory;
        $this->amstyDecimalFactory = $amstyDecimalFactory;
        $this->amstyCategoryFactory = $amstyCategoryFactory;
        $this->_productAttributeRepository = $_productAttributeRepository;
        $this->filterableAttributes = $filterableAttributes;
        $this->layerResolver = $layerResolver;
        $this->_filterItemFactory = $filterItemFactory;
        $this->_itemDataBuilder = $itemDataBuilder;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get product from id
     *
     * @param int $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProduct($productId)
    {
        return $this->productRepository->getById($productId);
    }

    /**
     * Save a product via the registry
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return void
     */
    public function saveProduct($product)
    {
        $this->productRepository->save($product);
    }

    /**
     * Get category from id
     *
     * @param int $categoryId
     * @return \Magento\Catalog\Api\Data\CategoryInterface
     */
    public function getCategory($categoryId)
    {
        return $this->categoryRepository->get($categoryId);
    }

    /**
     * Get menu categories
     *
     * @param int $categoryId
     * @return array
     */
    public function getChildCategories($categoryId)
    {
        $sortBy = 'position';
        $cat = $this->categoryRepository->get($categoryId);
        $subCats = $this->categoryCollection->create();
        $subCats->addAttributeToSelect('*')
                ->addIdFilter($cat->getChildren())->addOrderField($sortBy);

        $children = [];
        $count = 0;
        /** @var \Magento\Catalog\Model\Category|\Magento\Catalog\Model\ResourceModel\Category $subCat */
        foreach ($subCats as $subCat) {
            if ($subCat->getIsActive()) {
                $children[$count]["id"]   = $subCat->getId();
                $children[$count]["name"] = $subCat->getName();
                $subSubCats = $subCat->getChildrenCategories();
                /******* code added to retrive complete category hierarchy ****/
                /** @var \Magento\Catalog\Model\Category|\Magento\Catalog\Model\ResourceModel\Category $subSubCat */
                if(count($subSubCats) > 0){
                    $children[$count]["children"] = $this->getChildCategories($subCat->getId());
                }
                $count++;
            }
        }

        return $children;
    }

    /**
     * Build an array of filters
     *
     * @param int $categoryId
     * @return array
     */
    public function getFilters($categoryId)
    {
        $layer = $this->resolver->get();
        $layer->setCurrentCategory($categoryId);
        $products = $layer->getProductCollection();
        $this->setStockFilter($products);
        $filters = $this->filterList->getFilters($layer);

        $finalFilters = [];
        $count = 0;

        foreach ($filters as $filter) {
            if ($filter->getItemsCount()) {
                $finalFilters[$count]['code'] = $filter->getRequestVar();
                $finalFilters[$count]['label'] = $filter->getName();
                $finalFilters[$count]['type'] = 'select';
                $attributeCode = [];
                /** @var \Magento\Catalog\Model\Layer\Filter\Item $item */
                foreach ($filter->getItems() as $item) {
                    if ($item->getName() == 'Price') {
                        $priceLabel = $item->getLabel()->getArguments();
                        $finalPriceLabel = $item->getValue();
                        if (isset($priceLabel[0])) {
                            $finalPriceLabel = strip_tags($priceLabel[0]);
                        }
                        if (isset($priceLabel[1])) {
                            if (!isset($priceLabel[0])) {
                                $finalPriceLabel = '';
                            }
                            $finalPriceLabel.= ' - '.strip_tags($priceLabel[1]);
                        }
                        $attributeCode[] = [
                            'label' => $finalPriceLabel,
                            'id' => $item->getValue(),
                            'count' => (string)$item->getCount(),
                        ];
                    } else {
                        $attributeCode[] = [
                            'label' => $item->getLabel(),
                            'id' => $item->getValue(),
                            'count' => (string)$item->getCount(),
                        ];
                    }
                }
                $finalFilters[$count]['values'] = $attributeCode;
                $count++;
            }
        }

        return $finalFilters;
    }

    /**
     * New Function for getting the Available Filters on the category product list
     * Also used for attainging the product collection based on the @param prodcoll
     * @param int $categoryId
     * @param int $storeId
     * @param string $prodcoll Flag for returning product collection
     * @return string[] | \Magento\Catalog\Model\ResourceModel\Product\Collection Filters
     *
     *  Process: First all the requested filters are applied to the product collection of the layer
     *  and then each layered attributes are looped
     *  to calculate the remaining valid filters for the updated product collection
     */
    public function getNewFilters($categoryId, $storeId = null, $prodcoll = null)
    {
        if ($storeId === '' || $storeId === null) {
            $storeId = 1;
        }
        $res = [];
        $this->_storeManager->getStore($storeId);

        // Get the list of all the filterable attributes.
        $filterableList = $this->filterableAttributes->getList();

        $layer = $this->layerResolver->get();
        // Setting the current category to the layer.
        $layer->setCurrentCategory($categoryId);
        $data = [];
        $catData = [];
        // Array used to get the current state records of all the filters.
        $filter = [];
        // Used to manage count of the options of each filterable attribute.
        $init = 0;

        $blockname = '';
        /** @var string[] $params Getting all the params send to this webservice. */
        $params = $this->_request->getParams();

        $activeFilters = [];
        /** @var array $activeFilter Array used to keep the record of the current active applied filters. */
        $activeFilter = [];
        /** @var array $activeFiltersData Used to keep the record of all the data of the current applied filters. */
        $activeFiltersData = [];
        /** @var int $activeCount Counts the number of active filters. */
        $activeCount = 0;
        foreach ($params as $key => $pro) {
            if ($key == 'cat') {
                // Storing the current cat filter if applied in the list of active filters.
                $activeFiltersData[$activeCount]["code"] = 'cat';
                $activeFiltersData[$activeCount]["values"] = $pro;
                $activeCount++;
            }
            // Bypassing the keys which are not filterable attributes.
            if ($key != 'service' && $key != 'store' && $key != 'categoryid' && $key != 'callback'
                && $key != 'cat' && $key != 'sorttype' && $key !='sortorder' && $key !='filterdata'
                && $key !='currentpage' && $key !='pagesize' && $key !='customerid') {
                try
                {
                    /* Getting the attribute respository on the basis of attribute code passed as the parameters
                       as one of the filter to be applied. */
                    $attribute = $this->_productAttributeRepository->get($key);

                    $currentFilterValue = explode(",", $pro);
                    $activeFiltersData[$activeCount]["code"]   = $attribute->getAttributeCode();
                    // Collecting the current applied filters which would be required in the frontend of the app.
                    $activeFiltersData[$activeCount]["values"] = $pro;
                    // This array is used for showing the is_selected option for the attributes in the webservice
                    // output for each attribute.
                    $activeFilters[$attribute->getAttributeCode()] = $currentFilterValue;
                    $activeFilter[$activeCount] = $attribute->getAttributeCode();
                    $activeCount++;

                    if ($attribute->getFrontendInput() == 'price') {
                        // Creating the instance of price attribute class which is placed in the path
                        // Mofluid/Mofluidapi2/Helper/Amasty/Price.php as some modification was required
                        // to keep & check the filters applied.
                        $applyingFiltersPrice = $this->amstyPriceFactory->create(
                            [
                                'filterItemFactory' => $this->_filterItemFactory,
                                'storeManager' => $this->_storeManager,
                                'layer' => $layer,
                                'itemDataBuilder' => $this->_itemDataBuilder,
                                'data' => $data
                            ]
                        );
                        // Applying the selected price attribute to the collection for filtering.
                        $applyingFiltersPrice->setLayer($layer)->setAttributeModel($attribute)->apply($this->_request);
                    } elseif ($attribute->getBackendType() == 'decimal') {
                        /**
                         * Creating the instance of select/multiselect attribute class which is placed
                         * in the path Mofluid/Mofluidapi2/Helper/Amasty/Decimal.php as some modification was required
                         * to keep&check the filters applied.
                         */
                        $applyingFiltersDecimal  = $this->amstyDecimalFactory->create(
                            [
                                'filterItemFactory' => $this->_filterItemFactory,
                                'storeManager' => $this->_storeManager,
                                'layer' => $layer,
                                'itemDataBuilder' => $this->_itemDataBuilder,
                                'data' => $data
                            ]
                        );
                        // Applying the selected decimal attribute to the collection for filtering.
                        $applyingFiltersDecimal->setLayer($layer)->setAttributeModel($attribute)
                                                                 ->apply($this->_request);
                    } else {
                        /**
                         * Creating the instance of select/multiselect attribute class which is placed
                         * in the path Mofluid/Mofluidapi2/Helper/Amasty/Price.php as some modification was required
                         * to keep&check the filters applied.
                         */
                        $applyingFiltersAttr = $this->amstyAttrFactory->create(
                            [
                                'filterItemFactory' => $this->_filterItemFactory,
                                'storeManager' => $this->_storeManager,
                                'layer' => $layer,
                                'itemDataBuilder' => $this->_itemDataBuilder,
                                'data' => $data
                            ]
                        );
                        // Applying the selected attribute to the collection for filtering.
                        $applyingFiltersAttr->setLayer($layer)->setAttributeModel($attribute)->apply($this->_request);
                    }
                }
                catch (NoSuchEntityException $e) {
                    $this->_logger->critical($e->getMessage()."\n".$e->getTraceAsString());
                }
            }

        }

        // Showing the active filters on the webservice output.
        $res["activeFilters"] = $activeFiltersData;
        $filterCategory  = $this->amstyCategoryFactory->create(
            [
                'filterItemFactory' => $this->_filterItemFactory,
                'storeManager' => $this->_storeManager,
                'layer' => $layer,
                'itemDataBuilder' => $this->_itemDataBuilder,
                'data' => $data
            ]
        );

        // Applying the category filter on the collection and also getting the values of the refreshed category filter.
        $filterCategory->setLayer($layer);
        /** @var \Magento\Catalog\Model\Layer\Filter\Item[] $categoryFilters */
        $categoryFilters = $filterCategory->apply($this->_request)->getItems();

        $filterCount = count($categoryFilters);
        $categoryType = 'Select';
        if ($filterCount > 0) {
            if ($filterCategory->getMultiselectValue()) {
                $categoryType = 'Multiselect';
            }
            $filter[$init]["code"]   = 'cat';
            $filter[$init]["type"]   = $categoryType;
            $filter[$init]["label"]  = 'category';

            foreach ($categoryFilters as $option) {
                $catData[] = [
                    'label' => $option->getLabel(),
                    'value' => $option->getValue(),
                    'count' => $option->getCount(),
                    'is_selected' => 0,
                ];
            }
            $filter[$init]["values"] = $catData;
            $init++;
        }

        if ($prodcoll == 'listing') {
            // Returning the final product collection after all the filters
            // have been applied to the ws_newfilter webservice.
            return $layer->getProductCollection();
        }

        // Getting the values refreshed value of each filter attribute from the new product collection
        // in the layer obtained after the current filters are applied.
        foreach ($filterableList as $attribute) {
            $filterData = [];
            $attributeType = 'Select';

            // Getting the value of price filter attribute.
            if ($attribute->getAttributeCode() == 'price') {
                $applyingFiltersPrice = $this->amstyPriceFactory->create(
                    [
                        'filterItemFactory' => $this->_filterItemFactory,
                        'storeManager' => $this->_storeManager,
                        'layer' => $layer,
                        'itemDataBuilder' => $this->_itemDataBuilder,
                        'data' => $data
                    ]
                );
                $applyingFiltersPrice->filterapplied($activeFilter);
                $applyingFiltersPrice->setLayer($layer);
                $filters = $applyingFiltersPrice->setAttributeModel($attribute)->getItems();
            } elseif ($attribute->getBackendType() == 'decimal') {
                // Getting the value of decimal filter attribute.
                $applyingFiltersDecimal  = $this->amstyDecimalFactory->create(
                    [
                        'filterItemFactory' => $this->_filterItemFactory,
                        'storeManager' => $this->_storeManager,
                        'layer' => $layer,
                        'itemDataBuilder' => $this->_itemDataBuilder,
                        'data' => $data
                    ]
                );
                $applyingFiltersDecimal->filterapplied($activeFilter);
                $filters = $applyingFiltersDecimal->setLayer($layer)->setAttributeModel($attribute)->getItems();
            } else {
                $applyingFiltersAttr     = $this->amstyAttrFactory->create(
                    [
                        'filterItemFactory' => $this->_filterItemFactory,
                        'storeManager' => $this->_storeManager,
                        'layer' => $layer,
                        'itemDataBuilder' => $this->_itemDataBuilder,
                        'data' => $data
                    ]
                );
                // Getting the value of select/mulitiselct filte attribute.
                $applyingFiltersAttr->filterapplied($activeFilter);
                $filters = $applyingFiltersAttr->setLayer($layer)->setAttributeModel($attribute)->getItems();
                if ($applyingFiltersAttr->getMultiselectValue()) {
                    $attributeType = 'Multiselect';
                }
            }
            $count = count($filters);
            if ($count > 0) {
                $filter[$init]["id"]    = $attribute->getAttributeId();
                $filter[$init]["code"]  = $attribute->getAttributeCode();
                $filter[$init]["type"]  = $attributeType;
                $filter[$init]["label"] = $attribute->getFrontendLabel();
                $selectedFilter = in_array($attribute->getAttributeCode(), $activeFilter);
                foreach ($filters as $option) {
                    $isSelected = 0;
                    if ($selectedFilter) {
                        if (in_array($option->getValue(), $activeFilters[$attribute->getAttributeCode()])) {
                            $isSelected = 1;
                        }
                    }
                    $filterData[] = [
                        'label' => strip_tags($option->getLabel()),
                        'value' => $option->getValue(),
                        'count' => $option->getCount(),
                        'is_selected' => $isSelected,
                    ];
                }
                $filter[$init]["values"] = $filterData;
                $init++;
            }
        }
        // Passing all the refreshed current filters for the updated collection
        $res["filters"] = $filter;

        return $res;
    }

    /**
     * Get products by filters
     *
     * @param int|string $categoryId
     * @param string $filterData
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getFilterCollection($categoryId, $filterData)
    {
        $filters = json_decode($filterData);
        $layer = $this->resolver->get();
        $layer->setCurrentCategory($categoryId);
        $filterModels = $this->filterList->getFilters($layer);
        $params = $this->_getRequest()->getParams();
        foreach ($filters as $appliedFilter) {
            $values = explode(',', trim($appliedFilter->id, ','));
            $params[$appliedFilter->code] = trim($appliedFilter->id, ',');
            $this->_getRequest()->setParams($params);
            foreach ($filterModels as $filter) {
                if ($filter->getRequestVar() == $appliedFilter->code) {
                    /** @var \Magento\Catalog\Model\Layer\Filter\Item $item */
                    foreach ($filter->getItems() as $item) {
                        if (in_array($item->getValue(), $values)) {
                            $item->getFilter()->apply($this->_getRequest());
                            break;
                        }
                    }
                    break;
                }
            }
        }

        $products = $layer->getProductCollection();

        $products->addAttributeToSelect('*')
                 ->addAttributeToFilter('status', 1)
                 ->addAttributeToFilter('visibility', 4);
        $this->addTypeFilter($products);

        return $products;
    }

    /**
     * @param string $queryText
     * @param \Magento\Store\Api\Data\StoreInterface $store
     * @param int $category
     * @return \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
     */
    public function getQueryCollection($queryText, $store, $category = null)
    {
        $query = $this->queryFactory->create();
        $query->setStoreId($store->getId());
        $query->loadByQueryText($queryText);

        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $search */
        $search = $this->searchFactory->create(['searchRequestName' => 'quick_search_container']);

        $search->addSearchFilter($queryText);

        $search->addAttributeToSelect('*')
               ->setStore($store)
               ->addMinimalPrice()
               ->addFinalPrice()
               ->addTaxPercents()
               ->addStoreFilter()
               ->addAttributeToFilter('status', 1)
               ->addAttributeToFilter('visibility', 4);

        if ($category) {
            /** @var \Magento\Catalog\Model\Category $category */
            $category = $this->getCategory($category);
            $search->addCategoryFilter($category);
        }

        $this->addTypeFilter($search);

        return $search;
    }

    /**
     * Get product collection from category
     *
     * @param int $categoryId
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getCategoryProducts($categoryId)
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->categoryRepository->get($categoryId);
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products */
        $products = $category->getProductCollection();
        $products->addAttributeToSelect('*')
                 ->addAttributeToFilter('status', 1)
                 ->addAttributeToFilter('visibility', 4);
        $this->addTypeFilter($products);
        return $products;
    }

    /**
     * Get generic product collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $products = $this->productCollection->create();
        $products->addAttributeToSelect('*')
                 ->addAttributeToFilter('status', 1)
                 ->addAttributeToFilter('visibility', 4);
        $this->addTypeFilter($products);
        return $products;
    }

    /**
     * Apply the standard product type filter to the product collection.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $products
     */
    public function addTypeFilter(\Magento\Catalog\Model\ResourceModel\Product\Collection $products)
    {
        $products->addAttributeToFilter(
            'type_id',
            [
                'in' => [
                    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    //\Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                    //\Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
                    \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
                    //\Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE,
                ]
            ]
        );
    }

    /**
     * Set stock filter on product collection
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $products
     * @return void
     */
    public function setStockFilter(\Magento\Catalog\Model\ResourceModel\Product\Collection $products)
    {
        $products->setFlag('require_stock_items', true);
        $this->stockHelper->addInStockFilterToCollection($products);
    }

    /**
     * Get the product data array for the API
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $products
     * @param string $baseCurrencyCode
     * @param string $currentCurrencyCode
     * @param string $format
     * @return array
     */
    public function getProductData(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $products,
        $baseCurrencyCode,
        $currentCurrencyCode,
        $format = 'standard'
    ) {
        $data = [];

        /** @var \Magento\Catalog\Model\Product|\Magento\Catalog\Model\Product\Flat $product */
        foreach ($products as $product) {
            $thumbnail = $this->imageHelper->init($product, 'category_page_list')
                                           ->constrainOnly(false)
                                           ->keepAspectRatio(true)
                                           ->keepFrame(false)
                                           ->resize(200)->getUrl();

            $priceInfo = $product->getPriceInfo();
            $defaultPrice = number_format(
                $priceInfo->getPrice('regular_price')->getAmount()->getValue(),
                2,
                '.',
                ''
            );
            $specialPrice = number_format(
                $priceInfo->getPrice('final_price')->getAmount()->getValue(),
                2,
                '.',
                ''
            );

            if ($defaultPrice == $specialPrice) {
                $specialPrice = number_format(0, 2, '.', '');
            }

            $stock = $this->stockRegistry->getStockItem($product->getId());

            if ($format == 'standard') {
                $data[] = [
                    "id" => $product->getId(),
                    "name" => $product->getName(),
                    "imageurl" => $thumbnail,
                    "sku" => $product->getSku(),
                    "type" => $product->getTypeID(),
                    "spclprice" => number_format(
                        $this->convertCurrency($specialPrice, $baseCurrencyCode, $currentCurrencyCode),
                        2, '.', ''
                    ),
                    "currencysymbol" => $this->currency->getCurrency($currentCurrencyCode)->getSymbol(),
                    "price" => number_format(
                        $this->convertCurrency($defaultPrice, $baseCurrencyCode, $currentCurrencyCode),
                        2, '.', ''
                    ),
                    "created_date" => $product->getCreatedAt(),
                    "is_in_stock" => $stock->getIsInStock(),
                    "hasoptions" => $product->hasCustomOptions(),
                    "stock_quantity" => $stock->getQty(),
                    "short_description" => $product->getShortDescription(),
                ];
            } else {
                $data[] = [
                    "id" => $product->getId(),
                    "name" => $product->getName(),
                    "image" => $thumbnail,
                    "type" => $product->getTypeID(),
                    "price" => number_format(
                        $this->convertCurrency($defaultPrice, $baseCurrencyCode, $currentCurrencyCode),
                        2, '.', ''
                    ),
                    "special_price" => number_format(
                        $this->convertCurrency($specialPrice, $baseCurrencyCode, $currentCurrencyCode),
                        2, '.', ''
                    ),
                    "currency_symbol" => $this->currency->getCurrency($currentCurrencyCode)->getSymbol(),
                    "is_stock_status" => $stock->getIsInStock(),
                    "short_description" => $product->getShortDescription(),
                ];
            }
        }

        return $data;
    }

    /**
     * Convert from store currency to app currency
     *
     * @param int|string $price
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convertCurrency($price, $fromCurrency, $toCurrency)
    {
        return $this->directoryHelper->currencyConvert($price, $fromCurrency, $toCurrency);
    }

    /**
     * Get wislist product data for listing from wishlist collection
     *
     * @param \Magento\Wishlist\Model\Wishlist $collection
     * @param string $baseCurrencyCode
     * @param string $currentCurrencyCode
     * @return array
     */
    public function getWishlistProductData($collection, $baseCurrencyCode, $currentCurrencyCode){
        /** @var \Magento\Wishlist\Model\Item $item */
        foreach ($collection as $item){
            $product   = $this->getProduct($item->getProductId());
            $thumbnail = $this->imageHelper->init($product, 'category_page_list')
                                           ->constrainOnly(false)
                                           ->keepAspectRatio(true)
                                           ->keepFrame(false)
                                           ->resize(200)->getUrl();

            $priceInfo     = $product->getPriceInfo();
            $defaultPrice  = number_format(
                $priceInfo->getPrice('regular_price')->getAmount()->getValue(),
                2,
                '.',
                ''
            );
            $specialPrice = number_format(
                $priceInfo->getPrice('final_price')->getAmount()->getValue(),
                2,
                '.',
                ''
            );

            if ($defaultPrice == $specialPrice) {
                $specialPrice = number_format(0, 2, '.', '');
            }

            $stock = $this->stockRegistry->getStockItem($product->getId());

            $data[] = [
                "entity_id" => $product->getId(),
                "name"      => $product->getName(),
                "image_url" => $thumbnail,
                "sku"       => $product->getSku(),
                "type"      => $product->getTypeID(),
                "spclprice" => number_format(
                    $this->convertCurrency($specialPrice, $baseCurrencyCode, $currentCurrencyCode),
                    2, '.', ''
                ),
                "currencysymbol" => $this->currency->getCurrency($currentCurrencyCode)->getSymbol(),
                "price"          => number_format(
                    $this->convertCurrency($defaultPrice, $baseCurrencyCode, $currentCurrencyCode),
                    2, '.', ''
                ),
                "created_date"   => $product->getCreatedAt(),
                "is_in_stock"    => $stock->getIsInStock(),
                "hasoptions"     => $product->hasCustomOptions(),
                "stock_quantity" => $stock->getQty(),
            ];
        }

        return $data;
    }

}