<?php
namespace Mofluid\Mofluidapi2\Helper;

use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\CatalogInventory\Model\StockRegistry;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;

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
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \Magento\Framework\Api\Search\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Search\Api\SearchInterface
     */
    protected $search;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory
     */
    private $temporaryStorageFactory;


    protected $requesting;
	
	protected $_productAttributeRepository;
	
	protected $amstyAttrFactory;
	
	protected $amstyPriceFactory;
	
	protected $amstyDecimalFactory;
	
	protected $amstyCategoryFactory;
	
	protected $filterableAttributes;
	
	protected $layerResolver;
	
	protected $_filterItemFactory;
	
	protected $_itemDataBuilder;
	
	protected $_request;


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
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Search\Api\SearchInterface $search
     * @param \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $storageFactory
     * @param Context $context
     */
    public function __construct(
        \Mofluid\Mofluidapi2\Helper\Amasty\AttributeFactory $amstyAttrFactory,
	    \Mofluid\Mofluidapi2\Helper\Amasty\PriceFactory $amstyPriceFactory,
	    \Mofluid\Mofluidapi2\Helper\Amasty\DecimalFactory $amstyDecimalFactory,
	    \Mofluid\Mofluidapi2\Helper\Amasty\CategoryFactory $amstyCategoryFactory,
	    \Magento\Catalog\Model\Layer\Category\FilterableAttributeList $filterableAttributes,
	    \Magento\Catalog\Model\Layer\Resolver $layerResolver,
	    \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
	    \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
	    \Magento\Framework\App\RequestInterface $request,
	    \Magento\Catalog\Model\Product\Attribute\Repository $_productAttributeRepository,
		\Magento\Framework\App\Request\Http $requesting,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigData,
		
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
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Search\Api\SearchInterface $search,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $storageFactory,
        Context $context
    ) {
        $this->amstyAttrFactory = $amstyAttrFactory;
		$this->amstyPriceFactory = $amstyPriceFactory;
		$this->amstyDecimalFactory = $amstyDecimalFactory;
		$this->amstyCategoryFactory = $amstyCategoryFactory;
		$this->requesting = $requesting;
		$this->_productAttributeRepository = $_productAttributeRepository;
		$this->filterableAttributes = $filterableAttributes;
		$this->layerResolver = $layerResolver;
		$this->_filterItemFactory = $filterItemFactory;
        $this->_itemDataBuilder = $itemDataBuilder;
        $this->_request = $request;
        $this->_storeManager = $storeManager;
        $this->_scopeconfig = $scopeConfigData;
		
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
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->search = $search;
        $this->temporaryStorageFactory = $storageFactory;
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
    public function getFilters($categoryId,$store_id = null,$prodcoll = null)
    {
        
	    $applied_count = 0;
       
        if($store_id == '' || $store_id == null)
		 {
		   $store_id = 1;
		 }
		$res = [];
		$storeObj = $this->_storeManager;
		$scopeConfig = $this->_scopeconfig;
		$storeObj->setCurrentStore($store_id);
        
		$filterablelist = $this->filterableAttributes->getList();
		$filterItemFactory = $this->_filterItemFactory;
		$storeManager = $this->_storeManager;
		$itemDataBuilder = $this->_itemDataBuilder;
		$request = $this->_request;
		$layerResolver = $this->layerResolver;
		
		$layer = $layerResolver->get();
		$layer->setCurrentCategory($categoryId);
		$data = [];
		$catdata = [];
		$filter = [];
		$init = 0;
			
		$blockname = '';
		$params = $this->requesting->getParams();
		
		$activeFilters = [];
		$activefil = [];
		$activeCount = 0;
		foreach($params as $key => $pro)
        { 
		   $applyingfiltersattr     = $this->amstyAttrFactory->create(['filterItemFactory' => $filterItemFactory,'storeManager' => $storeManager,'layer' => $layer,'itemDataBuilder' => $itemDataBuilder,'data' => $data]);		
		   $applyingfiltersprice    = $this->amstyPriceFactory->create(['filterItemFactory' => $filterItemFactory,'storeManager' => $storeManager,'layer' => $layer,'itemDataBuilder' => $itemDataBuilder,'data' => $data]);		
		   $applyingfiltersdecimal  = $this->amstyDecimalFactory->create(['filterItemFactory' => $filterItemFactory,'storeManager' => $storeManager,'layer' => $layer,'itemDataBuilder' => $itemDataBuilder,'data' => $data]);		
		   		     
		   if($key != 'service' && $key != 'store' && $key != 'categoryid' && $key != 'callback' && $key != 'cat')
		   {
		       try
		       {
		         $attribute = $this->_productAttributeRepository->get($key);	
				
				 $current_filval = explode(",",$pro); 
			     //$activeFilters[$activeCount]["id"]    = $attribute->getAttributeId();
                 $activeFilters[$activeCount]["code"]  = $current_filval;
                 //$activeFilters[$activeCount]["type"]  = $attribute->getFrontendInput();
                 $activeFilters[$activeCount]["values"] = $pro;
                 $activefil[$activeCount] = $attribute->getAttributeCode();
                 $activeCount++;
             
			     if($attribute->getFrontendInput() == 'price')
			     {
				   $applyingfiltersprice->setLayer($layer)->setAttributeModel($attribute)->apply($request);		
			     }elseif($attribute->getBackendType() == 'decimal')
			     {
				   $applyingfiltersdecimal->setLayer($layer)->setAttributeModel($attribute)->apply($request);		
			     }else
			     { 
				   $applyingfiltersattr->setLayer($layer)->setAttributeModel($attribute)->apply($request);		
			     }
			    }
			    catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
			    
			    }	
		    }		
											   
 		}		
		
		$res["activeFilters"] = $activeFilters;
		$categoryfilters  = $this->amstyCategoryFactory->create(['filterItemFactory' => $filterItemFactory,'storeManager' => $storeManager,'layer' => $layer,'itemDataBuilder' => $itemDataBuilder,'data' => $data]);
		
		$catfilters = $categoryfilters->setLayer($layer)->apply($request)->getItems();		
		
		$countcat = count($catfilters);
		$categoryType = 'Select';                   
        if($countcat > 0)
        {
			if($categoryfilters->getMultiselectValue())
		        {
                   $categoryType = 'Multiselect';			
			    }
	        $filter[$init]["code"]   = 'cat';
            $filter[$init]["type"]   = $categoryType;
            $filter[$init]["label"]  = 'category';     
		
            foreach($catfilters as $option) {
                $catdata [] =  array(
								 'label' => $option->getLabel(),
								 'value' => $option->getValue(),
							     'count' => $option->getCount(),
							     'is_selected' => 0,
						   );
	        }           
            $filter[$init]["values"] = $catdata;
            $init++; 
        }
        
        foreach($filterablelist as $attribute)
		{ 
			$applyingfiltersattr     = $this->amstyAttrFactory->create(['filterItemFactory' => $filterItemFactory,'storeManager' => $storeManager,'layer' => $layer,'itemDataBuilder' => $itemDataBuilder,'data' => $data]);		
		    $applyingfiltersprice    = $this->amstyPriceFactory->create(['filterItemFactory' => $filterItemFactory,'storeManager' => $storeManager,'layer' => $layer,'itemDataBuilder' => $itemDataBuilder,'data' => $data]);		
		    $applyingfiltersdecimal  = $this->amstyDecimalFactory->create(['filterItemFactory' => $filterItemFactory,'storeManager' => $storeManager,'layer' => $layer,'itemDataBuilder' => $itemDataBuilder,'data' => $data]);		
		     
		    $fildata = [];
            $count = 0;
            $attributeType = 'Select';	 
		    
		    if($attribute->getAttributeCode() == 'price'){
				$applyingfiltersprice->filterapplied($activefil);
                $filters = $applyingfiltersprice->setLayer($layer)->setAttributeModel($attribute)->getItems();		
		    }elseif($attribute->getBackendType() == 'decimal'){
		        $applyingfiltersattr->filterapplied($activefil);
		        $filters = $applyingfiltersdecimal->setLayer($layer)->setAttributeModel($attribute)->getItems();		
		    }else{
			    $applyingfiltersattr->filterapplied($activefil);	
		        $filters = $applyingfiltersattr->setLayer($layer)->setAttributeModel($attribute)->getItems();		
		        if($applyingfiltersattr->getMultiselectValue())
		        {
                   $attributeType = 'Multiselect';			
			    }
		    }
     		$count = count($filters);
		    if($count > 0)
		    {
			    $filter[$init]["id"]    = $attribute->getAttributeId();
                $filter[$init]["code"]  = $attribute->getAttributeCode();
                $filter[$init]["type"]  = $attributeType;
                $filter[$init]["value"] = $attribute->getFrontendLabel();     
                $selected_fill = in_array($attribute->getAttributeCode(),$activefil);
                foreach($filters as $option) {
                   $is_selected = 0;
                   if($selected_fill)
                   {
                     if(in_array($option->getValue(),$activeFilters[$attribute->getAttributeCode()]))
                     $is_selected = 1;         
                   }
			       $fildata[] = array(
								    'label' => strip_tags($option->getLabel()),
								    'value' => $option->getValue(),
									'count' => $option->getCount(),
									'is_selected' => $is_selected,
							  );
			    } 
                $filter[$init]["values"] = $fildata;
                $init++;      
		    }
	     }	
	     $res["filters"] = $filter;
		 //echo "<pre>";
		 //print_r($filter);
		
		 //$products = $layer->getProductCollection();

         //foreach($products as $coll)
         //{
         //   echo "<pre>";print_r( $coll->getData());
         //}
         
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
        $products = $this->getCategoryProducts($categoryId);
        if ($filterData != null) {
            //$sizeflag = '1';
            //$colorflag = '1';
            foreach ($filters as $filter) {
                $filterArray = [];
                if ($filter->code == 'cat') {
                    $products->addCategoriesFilter(['in' => $filter->id]);
                } elseif ($filter->code != 'price') {
                    $code = $filter->code;
                    /* Unused but potentially useful.
                    if ($code == 'size') {
                        $sizeflag = '0';
                    }
                    if ($code == 'color') {
                        $colorflag = '2';
                    }
                    */
                    $ids = array_map('intval', explode(',', $filter->id));
                    foreach ($ids as $value) {
                        $filterArray[] = [
                            'attribute' => $code,
                            'finset' => $value
                        ];
                    }
                    $products->addAttributeToFilter($filterArray);
                } else {
                    $filterValueArr = explode('-', $filter->id);
                    $priceArray = [
                        [
                            'attribute' => 'price',
                            [
                                'from'=>$filterValueArr[0],
                                'to'=>$filterValueArr[1]
                            ]
                        ],
                    ];
                    $products->addAttributeToFilter($priceArray);
                }
            }
        }

        return $products;
    }

    /**
     * @param string $queryText
     * @param \Magento\Store\Api\Data\StoreInterface $store
     * @param int $rootCategory
     * @return \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
     */
    public function getQueryCollection($queryText, $store, $rootCategory)
    {
        $query = $this->queryFactory->create();
        $query->setStoreId($store->getId());
        $query->loadByQueryText($queryText);
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->getCategory($rootCategory);
        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $search */
        $search = $this->searchFactory->create();
        $search->addCategoryFilter($category)
            ->addAttributeToSelect('*')
            ->setStore($store)
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter()
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('visibility', 4);

        $this->filterBuilder->setField('search_term');
        $this->filterBuilder->setValue($queryText);
        $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchCriteria->setRequestName('quick_search_container');
        $searchResult = $this->search->search($searchCriteria);
        $temporaryStorage = $this->temporaryStorageFactory->create();

        $table = $temporaryStorage->storeApiDocuments($searchResult->getItems());

        $search->getSelect()->joinInner(
            [
                'search_result_two' => $table->getName(),
            ],
            'e.entity_id = search_result_two.' . TemporaryStorage::FIELD_ENTITY_ID,
            []
        );

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

            if ($product->getTypeID() == \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE) {
                /** @var \Magento\GroupedProduct\Model\Product\Type\Grouped $productType */
                $productType = $product->getTypeInstance(true);
                $associatedProducts = $productType->getAssociatedProducts($product);

                /** @var \Magento\Catalog\Model\Product $associatedProduct */
                $prices = [];
                foreach ($associatedProducts as $associatedProduct) {
                    $prices[] = round(floatval(
                        $this->convertCurrency(
                            $associatedProduct->getFinalPrice(), $baseCurrencyCode, $currentCurrencyCode
                        )), 2);
                }
                sort($prices);
                $defaultPrice = number_format(array_shift($prices), 2, '.', '');
                $specialPrice =  number_format($product->getFinalPrice(), 2, '.', '');
            } else {
                $defaultPrice = number_format($product->getPrice(), 2, '.', '');
                $specialPrice = number_format($product->getFinalPrice(), 2, '.', '');
            }

            if ($product->getTypeID() == 'configurable') {
                $defaultPrice = $specialPrice;
            }

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
}
