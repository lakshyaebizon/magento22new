<?php

namespace Mofluid\Mofluidapi2\Helper;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Quote\Model\Quote\Address\ToOrder as ToOrderConverter;
use Magento\Quote\Model\Quote\Address\ToOrderAddress as ToOrderAddressConverter;
use Magento\Quote\Model\Quote\Item\ToOrderItem as ToOrderItemConverter;
use Magento\Quote\Model\Quote\Payment\ToOrderPayment as ToOrderPaymentConverter;
use Magento\Quote\Model\Quote\TotalsCollector;
use Magento\Sales\Api\Data\OrderInterfaceFactory as OrderFactory;
use Magento\Sales\Api\OrderManagementInterface as OrderManagement;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
//use \Magedelight\Stripe\Model\Payment as StripePayment;
use Magento\Search\Model\ResourceModel\Query\CollectionFactory as QueryCollectionFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Cache expire time
     *
     * @var int
     */
    const CACHE_EXPIRY = 300; //in Seconds

    /**
     * @var QueryCollectionFactory
     */
    private $queryCollectionFactory;
    /**
     * @var BuilderInterface
     */
    protected $transactionBuilder;

    /**
     * Helper for product listings
     *
     * @var Listings
     */
    protected $listingHelper;

    /**
     * @var TotalsCollector
     */
    protected $totalsCollector;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var OrderManagement
     */
    protected $orderManagement;

    /**
     * @var \Magento\SalesRule\Model\RuleRepository
     */
    protected $ruleRepository;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var \Magento\Sales\Model\Service\InvoiceService
     */
    protected $_invoiceService;

    /**
     * @var \Magento\Sales\Api\InvoiceRepositoryInterface
     */
    protected $_invoiceRepository;

    /**
     * @var ToOrderConverter
     */
    protected $quoteAddressToOrder;

    /**
     * @var ToOrderAddressConverter
     */
    protected $quoteAddressToOrderAddress;

    /**
     * @var ToOrderItemConverter
     */
    protected $quoteItemToOrderItem;

    /**
     * @var ToOrderPaymentConverter
     */
    protected $quotePaymentToOrderPayment;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Mofluid\Mofluidapi2\Model\Catalog\Product
     */
    protected $mproduct;

    /**
     * @var \Mofluid\Mofluidapi2\Model\Theme
     */
    protected $_theme;

    /**
     * @var \Mofluid\Mofluidapi2\Model\Themeimage
     */
    protected $_themeimage;

    /**
     * @var \Mofluid\Mofluidapi2\Model\Message
     */
    protected $_mmessage;

    /**
     * @var \Mofluid\Mofluidapi2\Model\Themecolor
     */
    protected $_themecolor;

    /**
     * @var \Mofluid\Payment\Model\Index
     */
    protected $_mpayment;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $_cache;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $_currency;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_page;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeconfig;

    /**
     * @var \Magento\Tax\Api\TaxCalculationInterface
     */
    protected $_taxcalculation;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customer;

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $_addressRepository;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $_addressFactory;

    /**
     * @var \Magento\Customer\Model\AddressRegistry
     */
    protected $_address;

    /**
     * @var \Magento\CatalogInventory\Model\StockRegistry
     */
    protected $stock;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_pagefilter;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $_accountManagementInterface;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_session;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var \Magento\Directory\Model\Country
     */
    protected $_country;

    /**
     * @var \Magento\Directory\Helper\Data
     */
    protected $_directory;

    /**
     * @var \Magento\Tax\Api\TaxCalculationInterface
     */
    protected $taxcalculation;

    /**
     * @var \Magento\Directory\Model\Region
     */
    protected $_region;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezone;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlinterface;

    /**
     * @var \Magento\Quote\Model\Quote\Item
     */
    protected $_quoteitem;

    /**
     * @var \Magento\Customer\Model\Address\Form
     */
    protected $_addressform;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_orderData;

    /**
     * @var \Magento\GiftMessage\Model\Message
     */
    protected $_giftMessage;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $_orderSender;

    /**
     * @var \Magedelight\Stripe\Model\Cards
     */
    protected $_stripeId;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var InvoiceSender
     */
    protected $invoiceSender;

    /**
     * Encryption class
     *
     * @var \Magento\Framework\Encryption\Encryptor
     */
    protected $encryptor;

    /**
     * @var \Magedelight\Stripe\Model\CardsFactory
     */
    protected $stripeAccount;
  
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;
  
    /**
     * @var \Magento\Reports\Model\ResourceModel\Product\Sold\CollectionFactory 
     */
    protected $_productSoldCollectionFactory;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $_categoryRepository;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory
     */
     
    protected $_orderItemCollectionFactory;
     
    /**
     * @var \Magento\Customer\Api\Data\RegionInterface
     */ 
    protected $regionInterface; 

    /**
     * @var \Magento\Wishlist\Model\Wishlist
     */ 
    protected $wishlist; 

    /**
     * @var \Magento\Cms\Model\Block
     */ 
    protected $blockData; 

    /**
     * @var \Magento\Cms\Model\Block
     */ 
    protected $proAlertData; 

    /**
     * @var int
     */
    //protected $daysAgo = 30;

    /**
     * Data constructor.
     * @param Listings $listingHelper
     * @param BuilderInterface $transactionBuilder
     * @param OrderFactory $orderFactory
     * @param OrderManagement $orderManagement
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Sales\Model\Service\InvoiceService $invoiceService
     * @param \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository
     * @param ToOrderConverter $quoteAddressToOrder
     * @param ToOrderAddressConverter $quoteAddressToOrderAddress
     * @param ToOrderItemConverter $quoteItemToOrderItem
     * @param ToOrderPaymentConverter $quotePaymentToOrderPayment
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param TotalsCollector $totalsCollector
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mofluid\Mofluidapi2\Model\Catalog\Product $Mproduct
     * @param \Mofluid\Mofluidapi2\Model\Theme $Mtheme
     * @param \Mofluid\Mofluidapi2\Model\Themeimage $Mimage
     * @param \Mofluid\Mofluidapi2\Model\Message $Mmessage
     * @param \Mofluid\Mofluidapi2\Model\Themecolor $Mcolor
     * @param \Mofluid\Payment\Model\Index $Mpayment
     * @param \Magento\Framework\App\CacheInterface $cachedata
     * @param \Magento\Framework\Locale\CurrencyInterface $currency
     * @param \Magento\Cms\Model\Page $pagedata
     * @param \Magento\Catalog\Model\Product $productData
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Tax\Api\TaxCalculationInterface $taxcalculationData
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\CustomerRegistry $customerRegistry
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Customer\Model\AddressFactory $addressFactory
     * @param \Magento\Customer\Model\AddressRegistry $addressRegistry
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     * @param \Magento\CatalogInventory\Model\StockRegistry $stockRegistry
     * @param \Magento\Cms\Model\Template\FilterProvider $pagefilterData
     * @param \Magento\Directory\Helper\Data $directoryData
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagementInterfaceData
     * @param \Magento\Customer\Model\Session $sessionData
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Escaper $escaperData
     * @param \Magento\Directory\Model\Country $country
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProductData
     * @param \Magento\Tax\Api\TaxCalculationInterface $taxcalculation
     * @param \Magento\Directory\Model\Region $region
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Quote\Model\Quote\Item $QuoteItem
     * @param \Magento\Customer\Model\Address\Form $AddressFrom
     * @param \Magento\Sales\Model\Order $orderData
     * @param \Magento\GiftMessage\Model\Message $giftMessage
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
     * @param \Magedelight\Stripe\Model\Cards $stripeId
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param InvoiceSender $invoicesender
     * @param \Magento\Framework\Encryption\Encryptor $encryptor
     * @param \Magedelight\Stripe\Model\CardsFactory $stripeAccount
     * @param \Magento\Wishlist\Model\Wishlist $wishlist
     * @param \Magento\Cms\Model\Block $blockData
     * @param \Magento\ProductAlert\Model\Price $proAlertData
     */
    public function __construct(
        QueryCollectionFactory $queryCollectionFactory,
        Listings $listingHelper,
        BuilderInterface $transactionBuilder,
        OrderFactory $orderFactory,
        OrderManagement $orderManagement,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository,
        ToOrderConverter $quoteAddressToOrder,
        ToOrderAddressConverter $quoteAddressToOrderAddress,
        ToOrderItemConverter $quoteItemToOrderItem,
        ToOrderPaymentConverter $quotePaymentToOrderPayment,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector,
        \Magento\SalesRule\Model\RuleRepository $ruleRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mofluid\Mofluidapi2\Model\Catalog\Product $Mproduct,
        \Mofluid\Mofluidapi2\Model\Theme $Mtheme,
        \Mofluid\Mofluidapi2\Model\Themeimage $Mimage,
        \Mofluid\Mofluidapi2\Model\Message $Mmessage,
        \Mofluid\Mofluidapi2\Model\Themecolor $Mcolor,
        \Mofluid\Payment\Model\Index $Mpayment,
        \Magento\Framework\App\CacheInterface $cachedata,
        \Magento\Framework\Locale\CurrencyInterface $currency,
        \Magento\Cms\Model\Page $pagedata,
        \Magento\Catalog\Model\Product $productData,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Tax\Api\TaxCalculationInterface $taxcalculationData,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Customer\Model\AddressRegistry $addressRegistry,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\CatalogInventory\Model\StockRegistry $stockRegistry,
        \Magento\Cms\Model\Template\FilterProvider $pagefilterData,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\Customer\Api\AccountManagementInterface $accountManagementInterfaceData,
        \Magento\Customer\Model\Session $sessionData,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Escaper $escaperData,
        \Magento\Directory\Model\Country $country,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProductData,
        \Magento\Tax\Api\TaxCalculationInterface $taxcalculation,
        \Magento\Directory\Model\Region $region,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Quote\Model\Quote\Item $QuoteItem,
        \Magento\Customer\Model\Address\Form $AddressFrom,
        \Magento\Sales\Model\Order $orderData,
        \Magento\GiftMessage\Model\Message $giftMessage,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        //\Magedelight\Stripe\Model\Cards $stripeId,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        InvoiceSender $invoicesender,
        \Magento\Framework\Encryption\Encryptor $encryptor,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Reports\Model\ResourceModel\Product\Sold\CollectionFactory $collectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryFactory,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemCollectionFactory,    
        \Magento\Customer\Api\Data\RegionInterface $regionInterface,
        //\Magedelight\Stripe\Model\CardsFactory $stripeAccount,
        \Magento\Wishlist\Model\Wishlist $wishlist,
        \Magento\Wishlist\Model\WishlistFactory $wishlistRepository,
        \Magento\Cms\Model\Block $blockData,
        \Magento\ProductAlert\Model\Price $proAlertData,
        \Magento\Checkout\Model\Cart $cart
    )
    {
		$this->cart  = $cart;
        $this->queryCollectionFactory   = $queryCollectionFactory;
        $this->layoutFactory            = $layoutFactory;
        $this->_productSoldCollectionFactory = $collectionFactory;
        $this->_categoryHelper          = $categoryHelper;
        $this->_categoryFactory         = $categoryFactory;
        $this->_categoryRepository      = $categoryRepository;
        $this->_orderItemCollectionFactory = $orderItemCollectionFactory;
        $this->listingHelper            = $listingHelper;
        $this->mproduct                 = $Mproduct;
        $this->_storeManager            = $storeManager;
        $this->_cache                   = $cachedata;
        $this->_currency                = $currency;
        $this->_page                    = $pagedata;
        $this->_scopeconfig             = $context->getScopeConfig();
        $this->_taxcalculation          = $taxcalculationData;
        $this->customerRegistry         = $customerRegistry;
        $this->customerRepository       = $customerRepository;
        $this->_customer                = $customerFactory;
        $this->stock                    = $stockRegistry;
        $this->_pagefilter              = $pagefilterData;
        $this->taxcalculation           = $taxcalculation;
        $this->_directory               = $directoryData;
        $this->_accountManagementInterface = $accountManagementInterfaceData;
        $this->_session                 = $sessionData;
        $this->_date                    = $date;
        $this->_timezone                = $timezone;
        $this->_country                 = $country;
        $this->_region                  = $region;
        $this->_address                 = $addressRegistry;
        $this->_addressFactory          = $addressFactory;
        $this->_addressRepository       = $addressRepository;
        $this->_theme                   = $Mtheme;
        $this->_themeimage              = $Mimage;
        $this->_mmessage                = $Mmessage;
        $this->_themecolor              = $Mcolor;
        $this->_urlinterface            = $context->getUrlBuilder();
        $this->_mpayment                = $Mpayment;
        $this->_quoteitem               = $QuoteItem;
        $this->_addressform             = $AddressFrom;
        $this->_orderData               = $orderData;
        $this->_giftMessage             = $giftMessage;
        $this->eventManager             = $context->getEventManager();
        $this->orderFactory             = $orderFactory;
        $this->orderManagement          = $orderManagement;
        $this->ruleRepository           = $ruleRepository;
        $this->quoteAddressToOrder      = $quoteAddressToOrder;
        $this->quoteAddressToOrderAddress = $quoteAddressToOrderAddress;
        $this->quoteItemToOrderItem     = $quoteItemToOrderItem;
        $this->quotePaymentToOrderPayment = $quotePaymentToOrderPayment;
        $this->dataObjectHelper         = $dataObjectHelper;
        $this->quoteRepository          = $quoteRepository;
        $this->quoteFactory             = $quoteFactory;
        $this->totalsCollector          = $totalsCollector;
        $this->_orderSender             = $orderSender;
       // $this->_stripeId                = $stripeId;
        $this->transactionBuilder       = $transactionBuilder;
        $this->_orderRepository         = $orderRepository;
        $this->_invoiceService          = $invoiceService;
        $this->_invoiceRepository       = $invoiceRepository;
        $this->invoiceSender            = $invoicesender;
        $this->_resource                = $resource;
        $this->encryptor                = $encryptor;
       // $this->stripeAccount            = $stripeAccount;
        $this->productData              = $productData;
        $this->imageHelper              = $imageHelper;
        $this->_regionInterface         = $regionInterface;
        $this->wishlist                 = $wishlist;
        $this->wishlistRepository       = $wishlistRepository;
        $this->blockData                = $blockData;
        $this->proAlertData             = $proAlertData;

        parent::__construct($context);
    }

    public function ws_storedetails($store, $service, $theme, $currentcurrencycode)
    {
        $storeObj = $this->_storeManager;
        $scopeConfig = $this->_scopeconfig;
        $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $cache = $this->_cache;
        $cache_key = "mofluid_" . $service . "_store" . $store;
        $date = $this->_date;
        $timezone = $this->_timezone;
        $offset = $date->getGmtOffset($timezone);
        $offset_hour = (int)($date->getGmtOffset($timezone) / 3600);
        $offset_min = ($date->getGmtOffset($timezone) % 3600) / 60;
        if ($theme == '') {
            $theme = 'modern';
        }
        $mofluidCms = $this->_themeimage->getCollection()->addFieldToFilter('mofluid_theme_id', 2)->getData();

        $themedata = $this->_theme->getCollection()->addFieldToFilter('mofluid_theme_code', $theme)->getFirstItem();
        $mofluid_theme_id = $themedata->getMofluidThemeId();
        $google_client_id = $themedata->getGoogleIosClientid();
        $google_login = $themedata->getGoogleLogin();
        $cms_pages = $mofluidCms[0]['cms_pages'];
        $about_us = $mofluidCms[0]['about_us'];
        $term_condition = $mofluidCms[0]['term_condition'];
        $privacy_policy = $mofluidCms[0]['privacy_policy'];
        $return_privacy_policy = $mofluidCms[0]['return_privacy_policy'];
        $tax_flag = $themedata->getTaxFlag();
        $mofluid_theme_banner_image_type = $themedata->getMofluidThemeBannerImageType();
        $mofluid_theme_data = [];
        $cache_array = [];
        $res = [
            'store' => array_merge(
                $storeObj->getStore($store)->getData(),
                [
                    'frontname' => $storeObj->getStore($store)->getFrontendName(),
                    'cache_setting' => $cache_array,
                    'logo' => $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC) .
                        'frontend/default/default/' . $scopeConfig->getValue(
                            'design/header/logo_src', \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        ),
                    'banner' => $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC) .
                        'frontend/default/default/images/banner.png',
                    'adminname' => $scopeConfig->getValue(
                        'trans_email/ident_sales/name',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'email' => $scopeConfig->getValue(
                        'trans_email/ident_sales/email',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'checkout' => $scopeConfig->getValue(
                        'trans_email/ident_sales/email',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'google_ios_clientid' => $google_client_id,
                    'google_login_flag' => $google_login,
                    'cms_pages' => $cms_pages,
                    'about_us' => $about_us,
                    'term_condition' => $term_condition,
                    'privacy_policy' => $privacy_policy,
                    'return_privacy_polity' => $return_privacy_policy,
                    'tax_flag' => $tax_flag
                ]
            ),
            'timezone' => [
                'name' => $timezone,
                'offset' => [
                    'value' => $offset,
                    'hour' => $offset_hour,
                    'min' => $offset_min,
                ],
            ],
            'url' => [
                'current' => $this->_urlinterface->getCurrentUrl(),
                'media' => $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA),
                'skin' => $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC),
                'root' => $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB),
                'store' => $this->_urlinterface->getHomeUrl(),
            ],
            'currency' => [
                'base' => [
                    'code' => $storeObj->getStore($store)->getBaseCurrencyCode(),
                    'name' => $this->_currency->getCurrency($storeObj->getStore($store)->getBaseCurrencyCode())->getName(),
                    'symbol' => $this->_currency->getCurrency($storeObj->getStore($store)->getBaseCurrencyCode())->getSymbol(),
                ],
                'current' => [
                    'code' => $storeObj->getStore($store)->getCurrentCurrencyCode(),
                    'name' => $this->_currency->getCurrency($storeObj->getStore($store)->getCurrentCurrencyCode())->getName(),
                    'symbol' => $this->_currency->getCurrency($storeObj->getStore($store)->getCurrentCurrencyCode())->getSymbol(),
                ],
                'allow' => $scopeConfig->getValue(
                    'currency/options/allow',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
            ],
            'configuration' => [
                'show_out_of_stock' => $scopeConfig->getValue(
                    'cataloginventory/options/show_out_of_stock',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ],
        ];

        $mofluidbanners = $this->_themeimage->getCollection()->addFieldToFilter('mofluid_theme_id', $mofluid_theme_id)->addFieldToFilter('mofluid_image_type', 'banner')->setOrder('mofluid_image_sort_order', 'ASC')->getData();

        if ($mofluid_theme_banner_image_type == "1") {
            foreach ($mofluidbanners as $banner_key => $banner_value) {
                $mbanner = '';
                $mbanner = $media_url . $banner_value['mofluid_image_value'];
                $banner_value['mofluid_image_value'] = $mbanner;
                try {
                    $mofluid_image_action = json_decode(base64_decode($banner_value['mofluid_image_action']));
                } catch (\Exception $ex) {
                    echo $ex->getMessage();
                }
                if ($banner_value['mofluid_store_id'] == $store) {
                    $mofluid_theme_banner_data[] = $banner_value;
                } else if ($banner_value['mofluid_store_id'] == 0) {
                    $mofluid_theme_banner_data[] = $banner_value;
                } else {
                    continue;
                }
            }
        } else {
            foreach ($mofluidbanners as $banner_key => $banner_value) {
                $mbanner = '';
                $mbanner = $media_url . $banner_value['mofluid_image_value'];
                $banner_value['mofluid_image_value'] = $mbanner;
                try {
                    $mofluid_image_action = json_decode(base64_decode($banner_value['mofluid_image_action']));
                } catch (\Exception $ex) {
                }
                if ($banner_value['mofluid_image_isdefault'] == '1' && $banner_value['mofluid_store_id'] == $store) {
                    $mofluid_theme_banner_data[] = $banner_value;
                    break;
                } else if ($banner_value['mofluid_image_isdefault'] == '1' && $banner_value['mofluid_store_id'] == 0) {
                    $mofluid_theme_banner_data[] = $banner_value;
                    break;
                } else {
                    continue;
                }
            }
            if (count($mofluid_theme_banner_data) <= 0) {
                $mofluid_theme_banner_data[] = $mofluid_theme_banner_all_data[0]; //$banner_value;
            }
        }
        $mofluid_theme_logo = $this->_themeimage->getCollection()->addFieldToFilter('mofluid_image_type', 'logo')->addFieldToFilter('mofluid_theme_id', $mofluid_theme_id);
        $mofluid_theme_logo_data = $mofluid_theme_logo->getData();
        //echo "<pre>"; print_r($mofluid_theme_logo_data); die('ddd');
        $mlogo = $media_url . $mofluid_theme_logo_data[0]['mofluid_image_value'];
        $mofluid_theme_logo_data[0]['mofluid_image_value'] = $mlogo;
        $mofluid_theme_data["code"] = $theme;
        $mofluid_theme_data["logo"]["image"] = $mofluid_theme_logo_data;
        $mofluid_theme_data["logo"]["alt"] = $scopeConfig->getValue('design/header/logo_alt', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $mofluid_theme_data["banner"]["image"] = $mofluid_theme_banner_data;
        $res["theme"] = $mofluid_theme_data;
        return ($res);

    }

    public function ws_sidecategory($store, $service)
    {
        echo "No category available";
    }

    public function ws_productinfo($store_id, $productid, $currentcurrencycode)
    {
        return $this->mproduct->getCompleteProductInfo($store_id, $productid, $currentcurrencycode);
    }

    /**
     * Initial app data
     *
     * @param string|int $store
     * @param string $service
     * @param string $currency
     * @return array
     */
    public function fetchInitialData($store, $service, $currency)
    {
        $result = [];
        /** @var \Magento\Store\Model\Store $storeObj */
        $storeObj = $this->_storeManager->getStore();
        $rootCatId = $storeObj->getRootCategoryId();
        $blockid = 10; // "Main Promotion" block id for content of site header.
        $result["categories"] = $this->listingHelper->getChildCategories($rootCatId);
        $result["headerData"] = $this->ws_getCmsBlockData($store,$blockid);
        return $result;
    }

    public function rootCategoryData($store, $service)
    {
        $res = [];
        $res["categories"] = $this->ws_category($store, "category");
        return $res;
    }

    public function ws_category($store, $service)
    {
        $storeObj = $this->_storeManager->getStore();
        $cache = $this->_cache;
        $cache_key = "mofluid_" . $service . "_store" . $store;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));

        $res = [];
        try {
            $total = 0;
            $arr = [];
            $storeCategoryId = $storeObj->getRootCategoryId();
            /** @var \Magento\Catalog\Model\Category $cat */
            $cat = $this->listingHelper->getCategory($storeCategoryId);
            /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categories */
            $categories = $cat->getResourceCollection();
            $categories->addAttributeToSelect([
                'name',
                'thumbnail',
                'image',
                'description',
                'store'
            ])->addIdFilter($cat->getChildren());
            try {
                /** @var \Magento\Catalog\Model\Category $category */
                foreach ($categories as $category) {
                    $res[] = array(
                        "id" => $category->getId(),
                        "name" => $category->getName(),
                        "image" => $category->getImageUrl(),
                        "thumbnail" => $category->getImageUrl()
                    );
                    $total = $total + 1;
                }
            } catch (\Exception $ex) {
                $res = $this->ws_subcategory($store, 'subcategory', $storeCategoryId);
            }
            array_push($arr, $cat);
        } catch (\Exception $ex) {
            die($ex->getMessage());
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this::CACHE_EXPIRY);

        return ($res);
    }

    public function ws_subcategory($store_id, $service, $categoryid)
    {
        //$cache = $this->_cache;
        //$cache_key = "mofluid_" . $service . "_store" . $store_id . "_category" . $categoryid;
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->listingHelper->getCategory($categoryid);
        $this->_storeManager->setCurrentStore($store_id);
        $res = [];
        $children = $category->getCategories($categoryid);
        /** @var \Magento\Catalog\Model\Category $subCategory */
        foreach ($children as $subCategory) {
            $res[] = array(
                "id" => $subCategory->getId(),
                "name" => $subCategory->getName(),
                "image" => $subCategory->getImageUrl(),
                "thumbnail" => $category->getImageUrl()
            );
        }
        $result["id"] = $categoryid;
        $result["title"] = $category->getName();
        $result["images"] = $category->getImageUrl();
        $result["thumbnail"] = $category->getImageUrl();
        $result["categories"] = $res;
        return ($result);
    }

    public function ws_products($store_id, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode)
    {
        if ($sortType == null || $sortType == 'null') {
            $sortType = 'name';
        }
        if ($sortOrder == null || $sortOrder == 'null') {
            $sortOrder = 'ASC';
        }
        if ($curr_page == null || $curr_page == 'null') {
            $curr_page = 1;
        }
        if ($page_size == null || $page_size == 'null') {
            $page_size = 10;
        }
        //$cache = $this->_cache;
        $scopeConfig = $this->_scopeconfig;
        $this->_storeManager->setCurrentStore($store_id);
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->_storeManager->getStore();
        $res = [];
        $basecurrencycode = $store->getBaseCurrencyCode();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->listingHelper->getCategoryProducts($categoryid);

        $collection->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')->setOrder('inventory_in_stock', 'desc');
        $collection->addStoreFilter($store_id)->addAttributeToSelect('*')
            ->addAttributeToSort($sortType, $sortOrder)->addAttributeToSort('entity_id','asc');

        //$this->listingHelper->setStockFilter($collection);

        $res["total"] = $collection->getSize();
        $collection->setPage($curr_page, $page_size);

        $res['data'] = $this->listingHelper->getProductData($collection, $basecurrencycode, $currentcurrencycode);
        return ($res);
    }

    public function ws_getFeaturedProducts($currentcurrencycode, $service, $store)
    {
        $res = [];
        $this->_storeManager->setCurrentStore($store);
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->_storeManager->getStore();
        $rootcateid = $store->getRootCategoryId();
        $basecurrencycode = $store->getBaseCurrencyCode();
        $collection = $this->listingHelper->getProductCollection();
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->listingHelper->getCategory($rootcateid);
        $collection->addStoreFilter($store)
            ->addCategoryFilter($category)
            ->addAttributeToFilter('is_featured', 1)
            //->addAttributeToSort('price', \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection::SORT_ORDER_DESC);
            ->addAttributeToSort('entity_id','ASC');
        $this->listingHelper->setStockFilter($collection);

        $collection->setPage(1, 20);

        $res['products_list'] = $this->listingHelper->getProductData(
            $collection, $basecurrencycode, $currentcurrencycode, 'short'
        );

        if (count($res['products_list'])) {
            $res["status"][0] = [
                'Show_Status' => "1"
            ];
        } else {
            $res["status"][0] = [
                'Show_Status' => "0"
            ];
        }

        return $res;
    }

    /*   * **********************get featured products*************** */

    public function ws_getNewProducts($currentcurrencycode, $service, $store, $curr_page, $page_size, $sortType, $sortOrder)
    {
        $res = [];

        if ($sortType == null || $sortType == 'null') {
            $sortType = 'created_at';
        }
        if ($sortOrder == null || $sortOrder == 'null') {
            $sortOrder = 'DESC';
        }
        if ($curr_page == null || $curr_page == 'null') {
            $curr_page = 1;
        }
        if ($page_size == null || $page_size == 'null') {
            $page_size = 20;
        }

        $this->_storeManager->setCurrentStore($store);
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->_storeManager->getStore();
        $basecurrencycode = $store->getBaseCurrencyCode();
        $rootcateid = $store->getRootCategoryId();
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->listingHelper->getCategory($rootcateid);
        $collection = $this->listingHelper->getProductCollection();
        $collection->addStoreFilter($store)
            ->addCategoryFilter($category)
            ->addAttributeToSort($sortType, $sortOrder);

        //if ($sortType != 'name') {
        //    $collection->addAttributeToSort('name', 'ASC');
        //}

        $this->listingHelper->setStockFilter($collection);

        $collection->setPage($curr_page, $page_size);

        $res['products_list'] = $this->listingHelper->getProductData(
            $collection, $basecurrencycode, $currentcurrencycode, 'short'
        );

        if (count($res['products_list'])) {
            $res["status"][0] = [
                'Show_Status' => "1"
            ];
        } else {
            $res["status"][0] = [
                'Show_Status' => "0"
            ];
        }

        return ($res);
    }

    /* ***********************get new products*************** */

    function ws_validatecurrency($store, $service, $currency, $paymentgateway)
    {
        $msg = '';
        $cache = $this->_cache;
        $cache_key = "mofluid_service" . $service . "_store" . $store . "_currency" . $currency . "_paymentmethod" . $paymentgateway;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        if ($paymentgateway == 'secureebs_standard' || $paymentgateway == 'paypal_standard' || $paymentgateway == 'authorizenet' || $paymentgateway == 'authorize' || $paymentgateway == 'moto' || $paymentgateway == 'moneris' || $paymentgateway == 'banorte' || $paymentgateway == 'payucheckout_shared' || $paymentgateway == 'sisowde' || $paymentgateway == 'sisow_ideal') {
            $payment_types['paypal'] = array(
                "0" => 'AUD',
                "1" => 'BRL',
                "2" => 'CAD',
                "3" => 'CZK',
                "4" => 'DKK',
                "5" => 'EUR',
                "6" => 'HKD',
                "7" => 'HUF',
                "8" => 'ILS',
                "9" => 'JPY',
                "10" => 'MYR',
                "11" => 'MXN',
                "12" => 'NOK',
                "13" => 'NZD',
                "14" => 'PHP',
                "15" => 'PLN',
                "16" => 'GBP',
                "17" => 'RUB',
                "18" => 'SGD',
                "19" => 'SEK',
                "20" => 'CHF',
                "21" => 'TWD',
                "22" => 'TRY',
                "23" => 'THB',
                "24" => 'USD'
            );
            $payment_types['paypal_standard'] = $payment_types['paypal'];
            $payment_types['authorizenet'] = array(
                "0" => 'GBP',
                "1" => 'USD',
                "2" => 'EUR',
                "3" => 'AUD'
            );
            $payment_types['secureebs_standard'] = array(
                "0" => 'INR'
            );
            $payment_types['moto'] = array(
                "0" => 'INR'
            );
            $payment_types['moneris'] = array(
                "0" => 'USD'
            );
            $payment_types['banorte'] = array(
                "0" => 'MXN'
            );
            $payment_types['payucheckout_shared'] = array(
                "0" => 'INR'
            );
            $payment_types['sisowde'] = array(
                "0" => 'EUR'
            );
            $payment_types['sisow_ideal'] = array(
                "0" => 'EUR'
            );
            $size_of_array = sizeof($payment_types[$paymentgateway]);
            if ($size_of_array > 0) {
                if (in_array($currency, $payment_types[$paymentgateway]))
                    $status = "1";
                else {
                    $msg = "Currency Code " . $currency . " is not supported with this Payment Type. Please Select different Payment Mode.";
                    $status = "0";
                }
            }
        } else
            $status = "1";
        $res["status"] = $status;
        $res["msg"] = $msg;
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this::CACHE_EXPIRY);
        return $res;
    }

    public function ws_createuser($store, $service, $firstname, $lastname, $email, $password)
    {
        $firstname = base64_decode($firstname);
        $lastname = base64_decode($lastname);
        $password = base64_decode($password);
        // base64_decode($password);
        $res = [];
        $websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
        $cust = $this->_customer->create();
        $res["firstname"] = $firstname;
        $res["lastname"] = $lastname;
        $res["email"] = $email;
        $res["password"] = $password;
        $res["status"] = 0;
        $res["id"] = 0;
        $cust->setWebsiteId($websiteId)->loadByEmail($email);
        if ($cust->getId()) {
            $res["id"] = $cust->getId();
            $res["status"] = 0;
        } else {
            try {
                /** @var \Magento\Store\Model\Store $storeModel */
                $storeModel = $this->_storeManager->getStore($store);
                $customer = $this->_customer->create();
                $customer->setWebsiteId($websiteId);
                $customer->setStore($storeModel);
                // If new, save customer information
                $customer->setWebsiteId($websiteId)->setFirstname($firstname)->setLastname($lastname)->setEmail($email)->setPassword($password)->save();
                $customer->sendNewAccountEmail($type = 'registered', $backUrl = '', $store);
                $res["id"] = $customer->getId();
                $res["status"] = 1;
                $res["stripecustid"] = '0';
                $stripeData = $this->stripeData($customer->getId())->getData();
                if (count($stripeData) > 0) {
                    $res["stripecustid"] = $stripeData[0]['stripe_customer_id'];
                }
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }
        return $res;
    }

    public function stripeData($customer_id)
    {
        $collection = $this->_stripeId->getCollection();
        return $collection->addFieldToFilter('customer_id', $customer_id);
    }

    public function ws_productdetail($store_id, $service, $productid, $currentcurrencycode)
    {
        $storeObj = $this->_storeManager;
        $cache = $this->_cache;
        $scopeConfig = $this->_scopeconfig;
        $taxcalculation = $this->_taxcalculation;
        $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store_id);
        $custom_attr = [];
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->listingHelper->getProduct($productid);
        $attributes = $product->getAttributes();
        $stock = $this->stock->getStockItem($product->getId());

        $custom_attr_count = 0;
        foreach ($attributes as $attribute) {
            if ($attribute->getIsVisibleOnFront()) {
                $attributeCode = $attribute->getAttributeCode();
                $label = $attribute->getFrontend()->getLabel($product);
                $value = $attribute->getFrontend()->getValue($product);
                $custom_attr["data"][$custom_attr_count]["attr_code"] = $attributeCode;
                $custom_attr["data"][$custom_attr_count]["attr_label"] = $label;
                $custom_attr["data"][$custom_attr_count]["attr_value"] = $value;
                ++$custom_attr_count;
            }
        }

        $custom_attr["total"] = $custom_attr_count;
        $res = [];

        $mofluid_all_product_images = [];
        $mofluid_non_def_images = [];
        $mofluid_product = $product;
        $mofluid_baseimage = $media_url . 'catalog/product' . $mofluid_product->getImage();

        foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
            $mofluid_imagecame = $mofluid_image->getUrl();
            if ($mofluid_baseimage == $mofluid_imagecame) {
                $mofluid_all_product_images[] = $mofluid_image->getUrl();
            } else {
                $mofluid_non_def_images[] = $mofluid_image->getUrl();
            }
        }
        $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
        //get base currency from magento
        $basecurrencycode = $storeObj->getStore($store_id)->getBaseCurrencyCode();
        $a = $product;
        $store = $storeObj->getStore($store_id);
        $taxClassId = $product->getTaxClassId();
        $percent = $taxcalculation->getDefaultCalculatedRate($taxClassId, null, $store);//->getRate($request->setProductClassId($taxClassId));

        $b = (($percent) / 100) * ($product->getFinalPrice());
        $all_custom_option_array = [];
        $attVal = $product->getOptions();
        $optStr = "";
        $inc = 0;
        $has_custom_option = 0;
        foreach ($attVal as $optionKey => $optionVal) {

            $has_custom_option = 1;
            $all_custom_option_array[$inc]['custom_option_name'] = $optionVal->getTitle();
            $all_custom_option_array[$inc]['custom_option_id'] = $optionVal->getId();
            $all_custom_option_array[$inc]['custom_option_is_required'] = $optionVal->getIsRequire();
            $all_custom_option_array[$inc]['custom_option_type'] = $optionVal->getType();
            $all_custom_option_array[$inc]['sort_order'] = $optionVal->getSortOrder();
            $all_custom_option_array[$inc]['all'] = $optionVal->getData();
            if ($all_custom_option_array[$inc]['all']['default_price_type'] == "percent") {
                $all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice() * round($all_custom_option_array[$inc]['all']['price'] * 10, 2) / 10) / 100), 2);
                //$all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice()*$all_custom_option_array[$inc]['all']['price'])/100),2);
            } else {
                $all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'], 2);
            }

            $all_custom_option_array[$inc]['all']['price'] = str_replace(",", "", $all_custom_option_array[$inc]['all']['price']);
            $all_custom_option_array[$inc]['all']['price'] = strval(round($this->listingHelper->convertCurrency($all_custom_option_array[$inc]['all']['price'], $basecurrencycode, $currentcurrencycode), 2));

            $all_custom_option_array[$inc]['custom_option_value_array'];
            $inner_inc = 0;
            foreach ($optionVal->getValues() as $valuesKey => $valuesVal) {
                $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id'] = $valuesVal->getId();
                $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $valuesVal->getTitle();

                $defaultcustomprice = str_replace(",", "", ($valuesVal->getPrice()));
                $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = strval(round($this->listingHelper->convertCurrency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));

                //$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format($valuesVal->getPrice(),2);
                $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $valuesVal->getPriceType();
                $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku'] = $valuesVal->getSku();
                $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $valuesVal->getSortOrder();
                if ($valuesVal->getPriceType() == "percent") {

                    $defaultcustomprice = str_replace(",", "", ($product->getFinalPrice()));
                    $customproductprice = strval(round($this->listingHelper->convertCurrency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",", "", round((floatval($customproductprice) * floatval(round($valuesVal->getPrice(), 1)) / 100), 2));
                    //$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format((($product->getPrice()*$valuesVal->getPrice())/100),2);
                }
                $inner_inc++;
            }
            $inc++;
        }

        $res["id"] = $product->getId();
        $res["sku"] = $product->getSku();
        $res["name"] = $product->getName();
        $res["category"] = $product->getCategoryIds(); //'category';
        $res["image"] = $mofluid_all_product_images;
        $res["url"] = $product->getProductUrl();
        $res["description"] = $product->getDescription();
        $res["shortdes"] = $product->getShortDescription();
        $res["quantity"] = $stock->getQty();
        $res["visibility"] = $product->isVisibleInSiteVisibility(); //getVisibility();
        $res["type"] = $product->getTypeID();
        $res["weight"] = $product->getWeight();
        $res["status"] = $product->getStatus();

        //convert price from base currency to current currency
        $res["currencysymbol"] = $this->_currency->getCurrency($currentcurrencycode)->getSymbol();


        $defaultprice = str_replace(",", "", ($product->getPrice()));
        $discountprice = str_replace(",", "", number_format($product->getFinalPrice(), 2));
        //  $discountprice = str_replace(",","",($product->getFinalPrice()));

        $res["discount"] = strval(round($this->listingHelper->convertCurrency($discountprice, $basecurrencycode, $currentcurrencycode), 2));


        $defaultshipping = $scopeConfig->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $res["shipping"] = strval(round($this->listingHelper->convertCurrency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));

        $defaultsprice = str_replace(",", "", ($product->getSpecialprice()));


        // Get the Special Price
        $specialprice = $product->getSpecialPrice();
        // Get the Special Price FROM date
        $specialPriceFromDate = $product->getSpecialFromDate();
        // Get the Special Price TO date
        $specialPriceToDate = $product->getSpecialToDate();
        // Get Current date
        $today = time();

        if ($specialprice) {
            if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                $specialprice = strval(round($this->listingHelper->convertCurrency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
            } else {
                $specialprice = 0;
            }
        } else {
            $specialprice = 0;
        }


        if (floatval($discountprice)) {
            if (floatval($discountprice) < floatval($defaultprice)) {
                $defaultprice = floatval($discountprice);
            }
        }

        /*Added by Mofluid team to resolve spcl price issue in 1.17*/
        $defaultprice = number_format($product->getPrice(), 2, '.', '');
        $specialprice = number_format($product->getFinalPrice(), 2, '.', '');
        if ($defaultprice == $specialprice)
            $specialprice = number_format(0, 2, '.', '');


        $res["price"] = number_format($this->listingHelper->convertCurrency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
        $res["sprice"] = number_format($this->listingHelper->convertCurrency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
        $res["tax"] = number_format($b, 2);
        $tax_type = $scopeConfig->getValue('tax/calculation/price_includes_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $res["tax_type"] = $tax_type;

        $res["has_custom_option"] = $has_custom_option;
        if ($has_custom_option) {
            $res["custom_option"] = $all_custom_option_array;
        }
        $res["custom_attribute"] = $custom_attr;
        return ($res);
    }

    /**
     * Get product details (Simple & configurable product)
     *
     * @param int|string $store_id
     * @param string $service
     * @param int|string $productid
     * @param string $currentcurrencycode
     * @return product description array
     */
   
	public function ws_productdetailDescription($store_id, $service, $productid, $currentcurrencycode){
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$scopeConfig = $this->_scopeconfig;
		$taxcalculation = $this->_taxcalculation;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$storeObj->getStore()->setCurrentStore($store_id);
		$custom_attr = array();
		$product = $this->listingHelper->getProduct($productid);
		$attributes = $product->getAttributes();
		$stock = $this->stock->getStockItem($product->getId());
		$custom_attr_count = 0;
		foreach($attributes as $attribute){
			if ($attribute->getIsVisibleOnFront()){
				$attributeCode = $attribute->getAttributeCode();
				$label = $attribute->getFrontend()->getLabel($product);
				$value = $attribute->getFrontend()->getValue($product);
				$custom_attr["data"][$custom_attr_count]["attr_code"] = $attributeCode;
				$custom_attr["data"][$custom_attr_count]["attr_label"] = $label;
				$custom_attr["data"][$custom_attr_count]["attr_value"] = $value;
				++$custom_attr_count;
				}
			}
		$custom_attr["total"] = $custom_attr_count;
		$res = array();
		$mofluid_all_product_images = array();
		$mofluid_non_def_images = array();
		$mofluid_product = $product;
		$mofluid_baseimage = $media_url . 'catalog/product' . $mofluid_product->getImage();
		$product_thumbimage = $media_url . 'catalog/product' . $mofluid_product->getThumbnail();

		foreach($mofluid_product->getMediaGalleryImages() as $mofluid_image){
			$mofluid_imagecame = $mofluid_image->getUrl();
			if ($mofluid_baseimage == $mofluid_imagecame){
				$mofluid_all_product_images[] = $mofluid_image->getUrl();
				}else{
				$mofluid_non_def_images[] = $mofluid_image->getUrl();
				}
			}

		$mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
		// get base currency from magento

		$basecurrencycode = $storeObj->getStore($store_id)->getBaseCurrencyCode();
		$a = $product;
		$store = $storeObj->getStore($store_id);
		$taxClassId = $product->getTaxClassId();
		$percent = $taxcalculation->getDefaultCalculatedRate($taxClassId, null, $store); //->getRate($request->setProductClassId($taxClassId));
		$b = (($percent) / 100) * ($product->getFinalPrice());
		$all_custom_option_array = array();
		$attVal = $product->getOptions();
		$optStr = "";
		$inc = 0;
		$has_custom_option = 0;
	
		foreach($attVal as $optionKey => $optionVal){
			$has_custom_option = 1;
			$all_custom_option_array[$inc]['custom_option_name'] = $optionVal->getTitle();
			$all_custom_option_array[$inc]['custom_option_id'] = $optionVal->getId();
			$all_custom_option_array[$inc]['custom_option_is_required'] = $optionVal->getIsRequire();
			$all_custom_option_array[$inc]['custom_option_type'] = $optionVal->getType();
			$all_custom_option_array[$inc]['sort_order'] = $optionVal->getSortOrder();
			$all_custom_option_array[$inc]['all'] = $optionVal->getData();
			if ($all_custom_option_array[$inc]['all']['default_price_type'] == "percent"){
				$all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice() * round($all_custom_option_array[$inc]['all']['price'] * 10, 2) / 10) / 100) , 2);
			}else{
				$all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'], 2);
			}
			$all_custom_option_array[$inc]['all']['price'] = str_replace(",", "", $all_custom_option_array[$inc]['all']['price']);
			$all_custom_option_array[$inc]['all']['price'] = strval(round($this->convert_currency($all_custom_option_array[$inc]['all']['price'], $basecurrencycode, $currentcurrencycode) , 2));
			//$all_custom_option_array[$inc]['custom_option_value_array'];
			$inner_inc = 0;
			foreach($optionVal->getValues() as $valuesKey => $valuesVal){
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id'] = $valuesVal->getId();
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $valuesVal->getTitle();
				$defaultcustomprice = str_replace(",", "", ($valuesVal->getPrice()));
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode) , 2));
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $valuesVal->getPriceType();
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku'] = $valuesVal->getSku();
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $valuesVal->getSortOrder();
				if ($valuesVal->getPriceType() == "percent"){
					$defaultcustomprice = str_replace(",", "", ($product->getFinalPrice()));
					$customproductprice = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode) , 2));
					$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",", "", round((floatval($customproductprice) * floatval(round($valuesVal->getPrice() , 1)) / 100) , 2));
					}
				$inner_inc++;
				}
			$inc++;
			}
	   
		$config_option = array();
		$res["id"] 			= $product->getId();
		$res["sku"] 		= $product->getSku();
		$res["name"] 		= $product->getName();
		$res["category"] 	= $product->getCategoryIds(); //'category';
		$res["img"] 		= $product_thumbimage;
		$res["url"] 		= $product->getProductUrl();
		//$res["description"] = strip_tags($product->getDescription());
		$res["description"] = $product->getDescription();
		$res["shortdes"] 	= $product->getShortDescription();
		$res["quantity"] 	= (string)$stock->getQty();
		$res["manage_stock"]= $stock->getManageStock() ? 1 : 0;
		$res["is_in_stock"] = $stock->getIsInStock() ? 1 : 0;
		$res["visibility"] 	= $product->isVisibleInSiteVisibility(); //getVisibility();
		$res["type"] 		= $product->getTypeID();
		$res["weight"] 		= $product->getWeight();
		$res["status"] 		= $product->getStatus();

		//convert price from base currency to current currency
		$res["currencysymbol"] = $this->_currency->getCurrency($currentcurrencycode)->getSymbol();
		$defaultprice = str_replace(",", "", ($product->getPrice()));
		$discountprice = str_replace(",", "", number_format($product->getFinalPrice(), 2));
		//  $discountprice = str_replace(",","",($product->getFinalPrice()));
		$res["discount"] = strval(round($this->listingHelper->convertCurrency($discountprice, $basecurrencycode, $currentcurrencycode), 2));
		$defaultshipping = $scopeConfig->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$res["shipping"] = strval(round($this->listingHelper->convertCurrency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));
		$defaultsprice = str_replace(",", "", ($product->getSpecialprice()));
		// Get the Special Price
		$specialprice = $product->getSpecialPrice();
		// Get the Special Price FROM date
		$specialPriceFromDate = $product->getSpecialFromDate();
		// Get the Special Price TO date
		$specialPriceToDate = $product->getSpecialToDate();
		// Get Current date
		$today = time();
		if ($specialprice) {
			if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
				$specialprice = strval(round($this->listingHelper->convertCurrency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
			} else {
				$specialprice = 0;
			}
		} else {
			$specialprice = 0;
		}
		if (floatval($discountprice)) {
			if (floatval($discountprice) < floatval($defaultprice)) {
				$defaultprice = floatval($discountprice);
			}
		}
		/*Added by Mofluid team to resolve spcl price issue in 1.17*/
		$defaultprice = number_format($product->getPrice(), 2, '.', '');
		$specialprice = number_format($product->getFinalPrice(), 2, '.', '');
		if ($defaultprice == $specialprice)
			$specialprice = number_format(0, 2, '.', '');

		$res["price"] = number_format($this->listingHelper->convertCurrency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
		$res["sprice"] = number_format($this->listingHelper->convertCurrency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
		$res["tax"] = number_format($b, 2);
		$tax_type = $scopeConfig->getValue('tax/calculation/price_includes_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$res["tax_type"] = $tax_type;
		$res["has_custom_option"] = $has_custom_option;
	
		if ($has_custom_option){
			$res["custom_option"] = $all_custom_option_array;
		}
	
		$res["custom_attribute"] = $custom_attr;
		$res["config_option"] = $config_option;
	
		/******** Block starts for Configureable products ************/
	
		if (($product->getTypeID() == "configurable")){
			$_configurableInstance = $product->getTypeInstance(true);
			$productAttributeOptions = $_configurableInstance->getConfigurableAttributesAsArray($product);
			foreach($productAttributeOptions as $productAttribute){
				$config_option[] = $productAttribute['label'];
			}
			$res1 = $this->getConfigurableProductData($product, $currentcurrencycode, $defaultsprice, $basecurrencycode, $store_id);
			$res = array_merge($res, $res1);
			$simple_collection = $_configurableInstance->getUsedProducts($product);
			$qty = 0;
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
			foreach($simple_collection as $product1){
				$qty = $qty + ($StockState->getStockQty($product1->getId()));
			}
			$res['quantity'] = (string)$qty;
			if(floatval($res["sprice"]) > floatval($res["price"]) && floatval($res["price"]) == 0){
				$res["price"] = $res["sprice"];
				$res["sprice"] = 0;
				$res["sprice"] = number_format($res["sprice"],2);
			}
		}
		/******** Block ends for Configureable products ************/ 

	$res['config_option'] = $config_option;
	return ($res);
}

    /**
     * Get configurable product details 
     *
     * @param obj. $_product
     * @param string $currentcurrencycode
     * @param int|string $defaultsprice
     * @param string $basecurrencycode
     * @param int|string $store
     *
     * @return Configureable associated product data array
     */

	public function getConfigurableProductData($_product,$currentcurrencycode,$defaultsprice,$basecurrencycode,$store){
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$locale = $this->_currency;
		$scopeConfig = $this->_scopeconfig;
		$taxcalculation = $this->_taxcalculation;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$storeObj->getStore()->setCurrentStore($store);
		$configurable_count = 0;
		$_configurableInstance = $_product->getTypeInstance(true);
		$productAttributeOptions = $_configurableInstance->getConfigurableAttributes($_product);
		 $simple_collection = $_configurableInstance->getUsedProducts($_product);
		$relation_count               = 0;
	$configurable_relation = array();
	foreach ($simple_collection as $product) {
		$data = array();
		
		$configurable_count = 0;
		$total = Count($productAttributeOptions);
	foreach ($productAttributeOptions as $attribute) {
			
			$currentcurrencycode; 
			$productAttribute                                              = $attribute->getProductAttribute();
			$productAttributeId                                            = $productAttribute->getId();
			$attributeValue                                                = $product->getData($productAttribute->getAttributeCode());
			$attributeLabel                                                = $product->getData($productAttribute->getValue());
				
			$config_option_attribute =	$this->ws_get_configurable_option_attributes($attributeValue, $attribute->getLabel(), $_product->getId(), $currentcurrencycode,$store);
			$data[$attribute->getLabel()]= $config_option_attribute ;
			$configurable_array1[$configurable_count]["data"]  = $config_option_attribute ;

			try {
				$configurable_curr_arr = (array) $configurable_array1[$configurable_count]["data"];
				if (isset($configurable_relation[$relation_count])) {
					$configurable_relation[$relation_count] = $configurable_relation[$relation_count] . ',' . str_replace(',', '', str_replace(' ','', $configurable_curr_arr["label"]));
				} else {
					$configurable_relation[$relation_count] = str_replace(',', '', str_replace(' ','', $configurable_curr_arr["label"]));
				}
			}
			catch (Exception $err) {
				echo 'Error : ' . $err->getMessage();
			}
			$configurable_count++;
	} 
	   $relation_count++;  
	$res = array('config_relation'=>$configurable_relation);
	$stockdat                   = $this->stock->getStockItem($product->getId()); 
	$configurable_array[][$product->getId()] = array(
					"is_required"    		=> $productAttribute->getIsRequired(),
					"sku"  					=> $product->getSku(),
					"name"          		=> $product->getName(),
					"spclprice"    			=> 0,
					"price"        			=>number_format($product->getPrice(), 3),
					"currencysymbol" 		=> $locale->getCurrency($currentcurrencycode)->getSymbol(),
					"created_date"  		=> $product->getCreatedAt(),
					"is_in_stock" 			=> $stockdat->getIsInStock()?1:0,
					"stock_quantity" 		=> $stockdat->getQty(),
					"type"					=> $product->getTypeID(),
					"shipping"   			=> $this->_scopeconfig->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
					"Total_config" 			=> $total,
					"data"          		=> $data

			);
	}
 $res['config_attributes']=$configurable_array;
 return ($res);              
}

/************* 
	Func. Name 	: ws_get_configurable_option_attributes
	Description	: 1. Mapping configurable products option
*************/ 	
    
	function ws_get_configurable_option_attributes($selectedValue, $label, $productid, $currentcurrencycode,$store){
		$storeObj = $this->_storeManager;     
		$basecurrencycode = $storeObj->getStore()->getBaseCurrencyCode();       
		$product_data            = $this->listingHelper->getProduct($productid);
		$configurable			 = $product_data->getTypeInstance(true);
		$productAttributeOptions = $configurable->getConfigurableAttributesAsArray($product_data);
		$simple_collection       = $configurable->getUsedProductCollection($product_data);
		$attributeOptions        = array();
		$count                   = 0;
		$colors  =  array( 'aliceblue'=>'F0F8FF', 'antiquewhite'=>'FAEBD7', 'aqua'=>'00FFFF', 'aquamarine'=>'7FFFD4',
		'azure'=>'F0FFFF', 'beige'=>'F5F5DC', 'bisque'=>'FFE4C4', 'black'=>'000000', 'blanchedalmond '=>'FFEBCD',
		'blue'=>'0000FF', 'blueviolet'=>'8A2BE2', 'brown'=>'A52A2A', 'burlywood'=>'DEB887', 'cadetblue'=>'5F9EA0',
		'chartreuse'=>'7FFF00', 'chocolate'=>'D2691E', 'coral'=>'FF7F50', 'cornflowerblue'=>'6495ED', 'cornsilk'=>'FFF8DC',
		'crimson'=>'DC143C', 'cyan'=>'00FFFF', 'darkblue'=>'00008B', 'darkcyan'=>'008B8B', 'darkgoldenrod'=>'B8860B',
		'darkgray'=>'A9A9A9', 'darkgreen'=>'006400', 'darkgrey'=>'A9A9A9', 'darkkhaki'=>'BDB76B', 'darkmagenta'=>'8B008B',
		'darkolivegreen'=>'556B2F', 'darkorange'=>'FF8C00', 'darkorchid'=>'9932CC', 'darkred'=>'8B0000', 'darksalmon'=>'E9967A',
		'darkseagreen'=>'8FBC8F', 'darkslateblue'=>'483D8B', 'darkslategray'=>'2F4F4F', 'darkslategrey'=>'2F4F4F',
		'darkturquoise'=>'00CED1', 'darkviolet'=>'9400D3', 'deeppink'=>'FF1493', 'deepskyblue'=>'00BFFF', 'dimgray'=>'696969',
		'dimgrey'=>'696969', 'dodgerblue'=>'1E90FF', 'firebrick'=>'B22222', 'floralwhite'=>'FFFAF0', 'forestgreen'=>'228B22',
		'fuchsia'=>'FF00FF', 'gainsboro'=>'DCDCDC', 'ghostwhite'=>'F8F8FF', 'gold'=>'FFD700', 'goldenrod'=>'DAA520',
		'gray'=>'808080', 'green'=>'008000', 'greenyellow'=>'ADFF2F', 'grey'=>'808080', 'honeydew'=>'F0FFF0', 'hotpink'=>'FF69B4',
		'indianred'=>'CD5C5C', 'indigo'=>'4B0082', 'ivory'=>'FFFFF0', 'khaki'=>'F0E68C', 'lavender'=>'E6E6FA',
		'lavenderblush'=>'FFF0F5', 'lawngreen'=>'7CFC00', 'lemonchiffon'=>'FFFACD', 'lightblue'=>'ADD8E6', 'lightcoral'=>'F08080',
		'lightcyan'=>'E0FFFF', 'lightgoldenrodyellow'=>'FAFAD2', 'lightgray'=>'D3D3D3', 'lightgreen'=>'90EE90',
		'lightgrey'=>'D3D3D3', 'lightpink'=>'FFB6C1', 'lightsalmon'=>'FFA07A', 'lightseagreen'=>'20B2AA',
		'lightskyblue'=>'87CEFA', 'lightslategray'=>'778899', 'lightslategrey'=>'778899', 'lightsteelblue'=>'B0C4DE',
		'lightyellow'=>'FFFFE0', 'lime'=>'00FF00', 'limegreen'=>'32CD32', 'linen'=>'FAF0E6', 'magenta'=>'FF00FF',
		'maroon'=>'800000', 'mediumaquamarine'=>'66CDAA', 'mediumblue'=>'0000CD', 'mediumorchid'=>'BA55D3',
		'mediumpurple'=>'9370D0', 'mediumseagreen'=>'3CB371', 'mediumslateblue'=>'7B68EE', 'mediumspringgreen'=>'00FA9A',
		'mediumturquoise'=>'48D1CC', 'mediumvioletred'=>'C71585', 'midnightblue'=>'191970', 'mintcream'=>'F5FFFA',
		'mistyrose'=>'FFE4E1', 'moccasin'=>'FFE4B5', 'navajowhite'=>'FFDEAD', 'navy'=>'000080', 'oldlace'=>'FDF5E6',
		'olive'=>'808000', 'olivedrab'=>'6B8E23', 'orange'=>'FFA500', 'orangered'=>'FF4500', 'orchid'=>'DA70D6',
		'palegoldenrod'=>'EEE8AA', 'palegreen'=>'98FB98', 'paleturquoise'=>'AFEEEE', 'palevioletred'=>'DB7093',
		'papayawhip'=>'FFEFD5', 'peachpuff'=>'FFDAB9', 'peru'=>'CD853F', 'pink'=>'FFC0CB', 'plum'=>'DDA0DD',
		'powderblue'=>'B0E0E6', 'purple'=>'800080', 'red'=>'FF0000', 'rosybrown'=>'BC8F8F', 'royalblue'=>'4169E1',
		'saddlebrown'=>'8B4513', 'salmon'=>'FA8072', 'sandybrown'=>'F4A460', 'seagreen'=>'2E8B57', 'seashell'=>'FFF5EE',
		'sienna'=>'A0522D', 'silver'=>'C0C0C0', 'skyblue'=>'87CEEB', 'slateblue'=>'6A5ACD', 'slategray'=>'708090',
		'slategrey'=>'708090', 'snow'=>'FFFAFA', 'springgreen'=>'00FF7F', 'steelblue'=>'4682B4', 'tan'=>'D2B48C',
		'teal'=>'008080', 'thistle'=>'D8BFD8', 'tomato'=>'FF6347', 'turquoise'=>'40E0D0', 'violet'=>'EE82EE', 'wheat'=>'F5DEB3',
		'white'=>'FFFFFF', 'whitesmoke'=>'F5F5F5', 'yellow'=>'FFFF00', 'charcoal'=>'36454F', 'yellowgreen'=>'9ACD32'); 
		foreach ($productAttributeOptions as $productAttribute) {
			$count = 0;
			foreach ($productAttribute['values'] as $attribute) {
				$attributeOptions[$productAttribute['label']][$attribute['value_index']]["value_index"]                = $attribute['value_index'];
				$attributeOptions[$productAttribute['label']][$attribute['value_index']]["label"]                      = $attribute['label'];
				$attributeOptions[$productAttribute['label']][$attribute['value_index']]["attribute_id"]      = $productAttribute['attribute_id'];
			   // $defaultprice                                                                             = str_replace(",", "", ($attribute['pricing_value']));
		
				/*if ($attribute['is_percent'] == 1) {
						$defaultproductprice                                                                      = str_replace(",", "", ($product_data->getFinalPrice()));
						$productprice                                                                             = strval(round($this->convert_currency($defaultproductprice, $basecurrencycode, $currentcurrencycode), 2));
						$attributeOptions[$productAttribute['label']][$attribute['value_index']]["pricing_value"] = str_replace(",", "", round(((floatval($productprice) * floatval($attribute['pricing_value'])) / 100), 2));
			
				}*/
				  if($productAttribute['label'] == 'Color'){  
			
					$cname = strtolower($attribute['label']);
					if(isset($colors[$cname])){
						$attributeOptions[$productAttribute['label']][$attribute['value_index']]["color_code"]                 = '#'.$colors[$cname];
					}else{ 
						$attributeOptions[$productAttribute['label']][$attribute['value_index']]["color_code"]             =  '#00FFFF';
					}	
				}
				$count++;
			}
		}
		return ($attributeOptions[$label][$selectedValue]);
	}

/************* 
	Func. Name 	: get_configurable_products_image
*************/ 	
    function get_configurable_products_image($productid, $currentcurrencycode)
    {
        $storeObj = $this->_storeManager;
        $cache = $this->_cache;
        $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $cache_key = "mofluid_configurable_products_productidimg" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        try {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->listingHelper->getProduct($product);
            if ($product->getTypeID() == "configurable") {
                /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable */
                $configurable = $product->getTypeInstance();
                $productAttributeOptions = $configurable->getConfigurableAttributes($product);
                $simple_collection = $configurable->getUsedProductCollection($product);
                $configurable_array_selection = [];
                $configurable_array = [];
                $configurable_count = 0;
                $relation_count = 0;
                //load data for children
                foreach ($simple_collection as $product) {
                    $configurable_count = 0;
                    foreach ($productAttributeOptions as $attribute) {
                        $configurable_array[$configurable_count]["id"] = $product->getId();

                        $configurable_array[$configurable_count]["name"] = $product->getName();
                        $configurable_array[$configurable_count]["image"] = $media_url . 'catalog/product' . $product->getImage();
                        $defaultsplprice = str_replace(",", "", number_format($product->getSpecialprice(), 2));

                        $configurable_count++;
                    }
                    $relation_count++;
                    $configurable_array_selection[] = $configurable_array;
                }
                //load data for parent
                $mofluid_all_product_images = [];
                $mofluid_non_def_images = [];
                $mofluid_baseimage = $media_url . 'catalog/product' . $product->getImage();

                foreach ($product->getMediaGalleryImages() as $mofluid_image) {
                    $mofluid_imagecame = $mofluid_image->getUrl();
                    if ($mofluid_baseimage == $mofluid_imagecame) {
                        $mofluid_all_product_images[] = $mofluid_image->getUrl();
                    } else {
                        $mofluid_non_def_images[] = $mofluid_image->getUrl();
                    }
                }
                $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
                $parent_all_custom_option_array = [];
                $parent_attVal = $product->getOptions();
                $parent_optStr = "";
                $parent_inc = 0;
                $has_custom_option = 0;
                foreach ($parent_attVal as $parent_optionKey => $parent_optionVal) {
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'];
                    $parent_inner_inc = 0;
                    $has_custom_option = 1;
                    $parent_inc++;
                }
                $configurable_product_parent["id"] = $product->getId();
                $configurable_product_parent["name"] = $product->getName();
                $configurable_product_parent["image"] = $mofluid_all_product_images;
                $defaultprice = str_replace(",", "", ($product->getFinalPrice()));
                $defaultsprice = str_replace(",", "", ($product->getSpecialprice()));
                $configurable_array_selection["parent"] = $configurable_product_parent;
                $configurable_array_selection["size"] = sizeof($configurable_array_selection);
                //$custom_attr["total"] = $custom_attr_count;
                $cache->save(json_encode($configurable_array_selection), $cache_key, array(
                    "mofluid"
                ), $this::CACHE_EXPIRY);
                return $configurable_array_selection;
            } else
                return "Product Id " . $productid . " is not a Configurable Product";
        } catch (\Exception $ex) {
            return "Error";
        }
    }

    /* =====================get CMS Pages================== */

    public function getallCMSPages($store, $pageId)
    {
        $page_data = [];
        $page = $this->_page->load($pageId);
        $pagehelper = $this->_pagefilter;
        $page_data["title"] = $page->getTitle();
        $page_data["content"] = $pagehelper->getBlockFilter()->setStoreId($store)->filter($page->getContent());
        return ($page_data);
    }

    public function ws_currency($store_id, $service)
    {
        $storeObj = $this->_storeManager;
        $cache = $this->_cache;
        $locale = $this->_currency;
        $cache_key = "mofluid_currency_store" . $store_id;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        $res = [];
        $res["currentcurrency"] = $storeObj->getStore($store_id)->getCurrentCurrencyCode();
        $res["basecurrency"] = $storeObj->getStore($store_id)->getBaseCurrencyCode();
        $res["currentsymbol"] = $locale->getCurrency($res["currentcurrency"])->getSymbol();
        $res["basesymbol"] = $locale->getCurrency($res["basecurrency"])->getSymbol();
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this::CACHE_EXPIRY);
        return ($res);
    }

    public function ws_setaddress($store, $service, $customerId, $Jaddress, $user_mail, $saveaction)
    {
        //----------------------------------------------------------------------
        if ($customerId == "notlogin") {
            $result = [];
            $result['billaddress'] = 1;
            $result['shippaddress'] = 1;
        } else {
            $websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
            $customer = $this->customerRepository->get($user_mail, $websiteId);
            $Jaddress = str_replace(" ", "+", $Jaddress);
            $address = json_decode(base64_decode($Jaddress));
            $billAdd = $address->billing;
            $shippAdd = $address->shipping;
            $result = [];
            $result['billaddress'] = 0;
            $result['shippaddress'] = 0;
            $_bill_address = array(
                'firstname' => $billAdd->firstname,
                'lastname' => $billAdd->lastname,
                'street' => array(
                    '0' => $billAdd->street
                ),
                'city' => $billAdd->city,
                'region_id' => '',
                'region' => $billAdd->region,
                'postcode' => $billAdd->postcode,
                'country_id' => $billAdd->country,
                'telephone' => $billAdd->phone
            );
            $_shipp_address = array(
                'firstname' => $shippAdd->firstname,
                'lastname' => $shippAdd->lastname,
                'street' => array(
                    '0' => $shippAdd->street
                ),
                'city' => $shippAdd->city,
                'region_id' => '',
                'region' => $shippAdd->region,
                'postcode' => $shippAdd->postcode,
                'country_id' => $shippAdd->country,
                'telephone' => $shippAdd->phone
            );
            if ($saveaction == 1 || $saveaction == "1") {
                $billAddress = $this->_address;
                $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');

                $shippAddress = $this->_address;
                $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
            } else {
                $billAddress = $this->_address;
                $shippAddress = $this->_address;
                if ($defaultBillingId = $customer->getDefaultBilling()) {
                    $billAddress->load($defaultBillingId);
                    $billAddress->addData($_bill_address);
                } else {
                    $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
                }
                if ($defaultShippingId = $customer->getDefaultShipping()) {
                    $shippAddress->load($defaultShippingId);
                    $shippAddress->addData($_shipp_address);
                } else {
                    $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
                }
            }

            try {

                if (count($billAdd) > 0) {
                    if ($billAddress->save())
                        $result['billaddress'] = 1;
                }
                if (count($shippAdd) > 0) {
                    if ($shippAddress->save())
                        $result['shippaddress'] = 1;
                }
            } catch (\Exception $ex) {
                //Zend_Debug::dump($ex->getMessage());
            }
        }
        return $result;

        //---------------------------------------------------------------------
    }

    public function ws_checkout($store, $service, $theme, $currentcurrencycode)
    {
        $scopeConfig = $this->_scopeconfig;
        $res = [];
        $checkout_type = $scopeConfig->getValue('checkout/options/guest_checkout', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $res['checkout'] = $checkout_type;
        return $res;

    }

    public function ws_search(
        $store_id, $service, $search_data, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode
    ) {
        $res = [];
        if ($sortType == null || $sortType == 'null') {
            $sortType = 'relevance';
        }
        if ($sortOrder == null || $sortOrder == 'null') {
            $sortOrder = 'DESC';
        }
        if ($curr_page == null || $curr_page == 'null') {
            $curr_page = 1;
        }
        if ($page_size == null || $page_size == 'null') {
            $page_size = 10;
        }
        $search_data = base64_decode($search_data);

        $this->_storeManager->setCurrentStore($store_id);
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->_storeManager->getStore();
        $basecurrencycode = $store->getBaseCurrencyCode();

        $collection = $this->listingHelper->getQueryCollection($search_data, $store); // Adding category ID optional
        //$this->listingHelper->setStockFilter($collection);

        if ($sortType != 'relevance') {
            $collection->getSelect()->order('search_result.' . \Magento\Framework\Search\Adapter\Mysql\TemporaryStorage::FIELD_SCORE . ' ' . 'DESC');
            $collection->addAttributeToSort($sortType, $sortOrder);
        } else {
            $collection->getSelect()->order('search_result.' . \Magento\Framework\Search\Adapter\Mysql\TemporaryStorage::FIELD_SCORE . ' ' . 'DESC');
        }

        //$collection->setOrder($sortType, $sortOrder);

        $res["total"] = $collection->getSize();
        if (($curr_page - 1) * $page_size < $collection->getSize()) {
            $collection->setPage($curr_page, $page_size);

            $res['data'] = $this->listingHelper->getProductData($collection, $basecurrencycode, $currentcurrencycode);
        } else {
            $res['data'] = [];
        }

        //-------------------- finding popular search terms ---------------------//
        $pop_suggested = [];
        $suggested_count = 0;
        $indexes = @unserialize($this->scopeConfig->getValue('searchautocomplete/general/index'));
        $pop_suggested_limit =  $indexes['magento_search_query']['limit'];
        $suggested_terms = $this->queryCollectionFactory->create()
                                                        ->setQueryFilter($search_data)
                                                        ->addFieldToFilter('query_text', ['neq' => $search_data])
                                                        ->addStoreFilter($store_id)
                                                        ->setOrder('popularity')
                                                        ->distinct(true)->setPageSize($pop_suggested_limit);

        foreach($suggested_terms as $term)
        {
            $redirect_url = $term['redirect'];
            //if($redirect_url == '' || $redirect_url == null)
            //$redirect_url = $this->_storeManager->getStore($store_id)->getBaseUrl().'catalogsearch/result/?q='.str_replace(" ","+",$term['query_text']);
            
            $sku = $term['query_text'].".SBY";
            $pro_id = $this->productData->getIdBySku($sku);
            if(!$pro_id)
            $pro_id = $this->productData->getIdBySku($term['query_text']);
            
            $pop_suggested[$suggested_count]['query_text']   = $term['query_text'];
            $pop_suggested[$suggested_count]['num_results']  = $term['num_results'];
            $pop_suggested[$suggested_count]['redirect']     = $redirect_url;
            $pop_suggested[$suggested_count]['popularity']   = $term['popularity'];
            
            if($pro_id)
            {
              try 
              { 
               $pop_suggested[$suggested_count]['product_id']      = $pro_id;
               $product = $this->listingHelper->getProduct($pro_id);
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

               $stock = $this->stock->getStockItem($product->getId());
               $data = [
                    "id" => $product->getId(),
                    "name" => $product->getName(),
                    "imageurl" => $thumbnail,
                    "sku" => $product->getSku(),
                    "type" => $product->getTypeID(),
                    "spclprice" => number_format(
                        $this->listingHelper->convertCurrency($specialPrice, $basecurrencycode, $currentcurrencycode),
                        2, '.', ''
                    ),
                    "currencysymbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
                    "price" => number_format(
                        $this->listingHelper->convertCurrency($defaultPrice, $basecurrencycode, $currentcurrencycode),
                        2, '.', ''
                    ),
                    "created_date" => $product->getCreatedAt(),
                    "is_in_stock" => $stock->getIsInStock(),
                    "hasoptions" => $product->hasCustomOptions(),
                    "stock_quantity" => $stock->getQty(),
               ];
             }
             catch(Exception $e)
             {
               $pop_suggested[$suggested_count]['product_id']      = '';
               $data = ''; 
             }
               $pop_suggested[$suggested_count]['product_details'] = $data;
            }
            else
            {
               $pop_suggested[$suggested_count]['product_id']      = '';
               $pop_suggested[$suggested_count]['product_details'] = '';
            }
            
            $suggested_count++;
        }
        //-----------------------------------------------------------------------//
        $res['pop_suggestion'] = $pop_suggested;

        return ($res);
    }

    public function ws_productdetailImage($store_id, $service, $productid, $currentcurrencycode)
    {
        $storeObj = $this->_storeManager;
        $cache = $this->_cache;
        $storeObj->getStore()->setCurrentStore($store_id);
        $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $cache_key = "mofluid_" . $service . "_store" . $store_id . "_productid_img" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));

        $product = $this->listingHelper->getProduct($productid);
        $res = [];

        $mofluid_all_product_images = [];
        $mofluid_non_def_images = [];
        $mofluid_product = $product;
        $mofluid_baseimage = $media_url . $mofluid_product->getImage();

        foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
            $mofluid_imagecame = $mofluid_image->getUrl();
            if ($mofluid_baseimage == $mofluid_imagecame) {
                $mofluid_all_product_images[] = $mofluid_image->getUrl();
            } else {
                $mofluid_non_def_images[] = $mofluid_image->getUrl();
            }
        }
        $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
        $res["id"] = $product->getId();
        $res["image"] = $mofluid_all_product_images;
        $res["status"] = $product->getStatus();
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this::CACHE_EXPIRY);
        return ($res);
    }

    public function ws_verifyLogin($store, $service, $username, $password)
    {
        $customerObj = $this->_customer->create();
        $websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
        $res = [];
        $res["username"] = $username;
        $res["password"] = base64_decode($password);
        $login_status = 1;
        $login_customer = $customerObj->setWebsiteId($websiteId);
        $login_customer->loadByEmail($username);
        if ($login_customer->getId()) {
            try {
                $login_customer_result = $login_customer->validatePassword(base64_decode($password));
                if (!$login_customer_result) {
                    $login_status = 0;
                } else {
                    $login_status = 1;
                    $res["firstname"] = $login_customer->getFirstname();
                    $res["lastname"] = $login_customer->getLastname();
                    $res["id"] = $login_customer->getId();
                    $res["stripecustid"] = '0';
                    $stripeData = $this->stripeData($login_customer->getId())->getData();
                    if (count($stripeData) > 0) {
                        $res["stripecustid"] = $stripeData[0]['stripe_customer_id'];
                    }
                }
            } catch (\Exception $e) {
                $login_status = 0;
            }
        } else {
            $login_status = 0;
        }
        $res["login_status"] = $login_status;
        return $res;
    }

    public function ws_forgotPassword($email = "")
    {
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        $res = [];
        $res["response"] = "error";
        if ($email) {
            $customer = $this->customerRegistry->retrieveByEmail($email, $websiteId);
            if ($customer->getId()) {
                if (!\Zend_Validate::is($email, 'EmailAddress')) {
                    $this->_session->setForgottenEmail($email);
                    $res["response"] = ['Please correct the email address.'];
                }

                try {
                    $this->_accountManagementInterface->initiatePasswordReset(
                        $email,
                        AccountManagement::EMAIL_RESET
                    );
                    $res["response"] = "success";
                } catch (\Exception $exception) {
                    $res["response"] = ['We\'re unable to send the password reset email.'];
                }
            }
        }
        return ($res);
    }

    public function ws_myProfile($cust_id)
    {
        try {
            $customer = $this->customerRegistry->retrieve($cust_id);
            $customerData = $customer->getData();
            if (isset($customerData['created_at'])) {
                $customerData['membersince'] = $this->_date->date("Y-m-d h:i:s A", $customerData['created_at']);
            }
            $shippingAddress = $customer->getDefaultShippingAddress();
        } catch (\Exception $ex2) {
            echo $ex2;
        }
        $shippadd = [];
        $billadd = [];
        try {
            if ($shippingAddress != null) {
                $shippadd = array(
                    "firstname" => $shippingAddress->getFirstname(),
                    "lastname" => $shippingAddress->getLastname(),
                    "company" => $shippingAddress->getCompany(),
                    "street" => $shippingAddress->getStreetFull(),
                    "region" => $shippingAddress->getRegion(),
                    "city" => $shippingAddress->getCity(),
                    "pincode" => $shippingAddress->getPostcode(),
                    "countryid" => $shippingAddress->getCountryId(),
                    "contactno" => $shippingAddress->getTelephone()
                );
            }
            $billingAddress = $customer->getDefaultBillingAddress();
            if ($billingAddress != null) {
                $billadd = array(
                    "firstname" => $billingAddress->getFirstname(),
                    "lastname" => $billingAddress->getLastname(),
                    "company" => $billingAddress->getCompany(),
                    "street" => $billingAddress->getStreetFull(),
                    "region" => $billingAddress->getRegion(),
                    "city" => $billingAddress->getCity(),
                    "pincode" => $billingAddress->getPostcode(),
                    "countryid" => $billingAddress->getCountryId(),
                    "contactno" => $billingAddress->getTelephone()
                );
            }
        } catch (\Exception $ex) {
            echo $ex;
        }
        $res = [];
        $customerData["stripecustid"] = '0';
        $stripeData = $this->stripeData($customer->getId())->getData();
        if (count($stripeData) > 0) {
            $customerData["stripecustid"] = $stripeData[0]['stripe_customer_id'];
        }
        $res = array(
            "CustomerInfo" => $customerData,
            "BillingAddress" => (object)$billadd,
            "ShippingAddress" => (object)$shippadd
        );
        return $res;
    }

    public function ws_mofluidappcountry($mofluid_store)
    {
        $cache = $this->_cache;
        $cache_key = "mofluid_country_store" . $mofluid_store;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        $scopeConfig = $this->_scopeconfig;
        $res = [];
        $country_sort_array = [];
        try {
            $collection = $this->_country->getCollection()->loadByStore($mofluid_store);
            foreach ($collection as $country) {
                $mofluid_country["country_id"] = $country->getId();
                $mofluid_country["country_name"] = $country->getName();
                $mofluid_country_arr[] = $mofluid_country;
                $country_sort_array[] = $country->getName();
            }

            array_multisort($country_sort_array, SORT_ASC, $mofluid_country_arr);
            $res["mofluid_countries"] = $mofluid_country_arr;

            $res["mofluid_default_country"]["country_id"] = $scopeConfig->getValue('general/country/default', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $res;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this::CACHE_EXPIRY);
        return $res;
    }

    public function ws_mofluidappstates($mofluid_store, $countryid)
    {
        $cache = $this->_cache;
        $cache_key = "mofluid_states_store" . $mofluid_store . "_countryid" . $countryid;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));

        $res = [];
        try {
            $collection = $this->_region->getResourceCollection()->addCountryFilter($countryid)->load();
            foreach ($collection as $region) {
                $mofluid_region["region_id"] = $region->getCode();
                $mofluid_region["region_name"] = $region->getDefaultName();
                $res["mofluid_regions"][] = $mofluid_region;
            }
            return $res;
        } catch (\Exception $ex) {

        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this::CACHE_EXPIRY);
        return $res;
    }

    public function ws_changeProfilePassword($custid, $username, $oldpassword, $newpassword, $store)
    {
        $res = [];
        $oldpassword = base64_decode($oldpassword);
        $newpassword = base64_decode($newpassword);
        $validate = 0;
        $websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
        /** @var \Magento\Customer\Model\Customer|\Magento\Customer\Api\Data\CustomerInterface $customer */
        $customer = $this->customerRegistry->retrieve($custid);
        try {
            $login_customer_result = $customer->validatePassword($oldpassword);
            if (!$login_customer_result) {
                $validate = 0;
            } else {
                $validate = 1;
            }

        } catch (\Exception $ex) {

        }
        if ($validate == 1) {
            try {
                $customer->setPassword($newpassword);
                $this->customerRepository->save($customer->getDataModel());
                $res = array(
                    "customerid" => $custid,
                    "oldpassword" => $oldpassword,
                    "newpassword" => $newpassword,
                    "change_status" => 1,
                    "message" => 'Your Password has been Changed Successfully'
                );
            } catch (\Exception $ex) {
                $res = array(
                    "customerid" => $custid,
                    "oldpassword" => $oldpassword,
                    "newpassword" => $newpassword,
                    "change_status" => -1,
                    "message" => 'Error : ' . $ex->getMessage()
                );
            }
        } else {
            $res = array(
                "customerid" => $custid,
                "oldpassword" => $oldpassword,
                "newpassword" => $newpassword,
                "change_status" => 0,
                "message" => 'Incorrect Old Password.'
            );
        }
        return $res;
    }

    public function mofluidUpdateProfile($store, $service, $customerId, $JbillAdd, $JshippAdd, $profile, $billshipflag)
    {
        $billAdd = json_decode(base64_decode($JbillAdd));
        $shippAdd = json_decode(base64_decode($JshippAdd));
        $profile = json_decode(base64_decode($profile));
        $customer = $this->customerRegistry->retrieveByEmail($profile->email);
        $result = [];
        $result['billaddress'] = 0;
        $result['shippaddress'] = 0;
        $result['userprofile'] = 0;

        //check exists email address of users
        if ($customer->getId() && $customer->getId() != $customerId) {
            return $result;
        } else {
            if ($billshipflag == "billingaddress") {
                $region = $this->_region->loadByCode($billAdd->billstate, $billAdd->billcountry);
                if (!$region->getId()) $this->_region->loadByName($billAdd->billstate, $billAdd->billcountry);
                $_bill_address = array(
                    'firstname' => $billAdd->billfname,
                    'lastname' => $billAdd->billlname,
                    'street' => array(
                        '0' => $billAdd->billstreet1,
                        '1' => (isset($billAdd->billstreet2) ? $billAdd->billstreet2 : '')
                    ),
                    'city' => $billAdd->billcity,
                    'region_id' => $region->getId() ?: null,
                    'region' => $region->getName() ?: $billAdd->billstate,
                    'postcode' => $billAdd->billpostcode,
                    'country_id' => $billAdd->billcountry,
                    'telephone' => $billAdd->billphone
                );
                if ($billAddress = $customer->getDefaultBillingAddress()) {
                    $billAddress->addData($_bill_address);
                } else {
                    $billAddress = $this->_addressFactory->create();
                    $billAddress->setData($_bill_address);
                    $billAddress->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
                }
                try {
                    $this->_addressRepository->save($billAddress->getDataModel());
                    $customer->setDefaultBilling($billAddress->getId());
                    if ($billAddress->getId())
                        $result['billaddress'] = 1;
                } catch (\Exception $ex) {
                    $this->_logger->critical($ex->getMessage());
                }
            } else {
                $region = $this->_region->loadByCode($shippAdd->shippstate, $shippAdd->shippcountry);
                if (!$region->getId()) $this->_region->loadByName($shippAdd->shippstate, $shippAdd->shippcountry);
                $_shipp_address = array(
                    'firstname' => $shippAdd->shippfname,
                    'lastname' => $shippAdd->shipplname,
                    'street' => array(
                        '0' => $shippAdd->shippstreet1,
                        '1' => (isset($shippAdd->shippstreet2) ? $shippAdd->shippstreet2 : ''),
                    ),
                    'city' => $shippAdd->shippcity,
                    'region_id' => $region->getId() ?: null,
                    'region' => $region->getName() ?: $shippAdd->shippstate,
                    'postcode' => $shippAdd->shipppostcode,
                    'country_id' => $shippAdd->shippcountry,
                    'telephone' => $shippAdd->shippphone
                );
                if ($shippAddress = $customer->getDefaultShippingAddress()) {
                    $shippAddress->addData($_shipp_address);
                } else {
                    $shippAddress = $this->_addressFactory->create();
                    $shippAddress->setData($_shipp_address);
                    $shippAddress->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
                }
                try {
                    $this->_addressRepository->save($shippAddress->getDataModel());
                    $customer->setDefaultShipping($shippAddress->getId());
                    if ($shippAddress->getId())
                        $result['shippaddress'] = 1;
                } catch (\Exception $ex) {
                    $this->_logger->critical($ex->getMessage());
                }
            }

            return $result;
        }
    }

    public function ws_loginwithsocial($store, $username, $firstname, $lastname)
    {
        $websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
        $res = [];
        $res["username"] = $username;
        $login_status = 1;
        try {
            $login_customer = $this->customerRegistry->retrieveByEmail($username)->setWebsiteId($websiteId);
            if ($login_customer->getId()) {
                $res["firstname"]   = $login_customer->getFirstname();
                $res["lastname"]    = $login_customer->getLastname();
                $res["id"]          = $login_customer->getId();
               
                $res["stripecustid"]= '0';
                $stripeData = $this->stripeData($login_customer->getId())->getData();
                if (count($stripeData) > 0) {
                    $res["stripecustid"] = $stripeData[0]['stripe_customer_id'];
                }

            } else {
                $login_status = 0;
                $res = $this->ws_registerwithsocial($store, $username, $firstname, $lastname);
                if ($res["status"] == 1) {
                    $login_status = 1;
                }
            }
        } catch (\Exception $e) {
            $login_status = 1;
            $res = $this->ws_registerwithsocial($store, $username, $firstname, $lastname);
            if ($res["status"] == 1) {
                $login_status = 1;
            }
        }
        $res["login_status"] = $login_status;
        return $res;
    }

    /* Function call to register user from its Email address */

    public function ws_registerwithsocial($store, $email, $firstname, $lastname)
    {
        $res = [];
        $websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
        // If new, save customer information
        
        $password           = base64_encode(rand(11111111, 99999999));
        $res["email"]       = $email;
        $res["firstname"]   = $firstname;
        $res["lastname"]    = $lastname;
        $res["password"]    = $password;
        $res["status"]      = 1;
        $res["id"]          = 0;

        $cust = $this->_customer->create();
        $cust->setWebsiteId($websiteId)->loadByEmail($email);
        if ($cust->getId()) {
            $res["id"] = $cust->getId();
            $res["status"] = 0;
        } else {
            try {
                /** @var \Magento\Store\Model\Store $storeModel */
                $storeModel     = $this->_storeManager->getStore($store);
                $customer       = $this->_customer->create();
                $customer->setWebsiteId($websiteId);
                $customer->setStore($storeModel);
                // If new, save customer information
                $customer->setWebsiteId($websiteId)->setFirstname($firstname)->setLastname($lastname)->setEmail($email)->setPassword($password)->save();
                $customer->sendNewAccountEmail($type = 'registered', $backUrl = '', $store);
                $res["id"]      = $customer->getId();
                $res["status"]  = 1;
                $res["stripecustid"] = '0';
                $stripeData     = $this->stripeData($customer->getId())->getData();
                if (count($stripeData) > 0) {
                    $res["stripecustid"] = $stripeData[0]['stripe_customer_id'];
                }
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }
        return $res;
    }

    public function ws_productQuantity($product)
    {

        $pqty = [];
        $scopeConfig = $this->_scopeconfig;
        $config_manage_stock = $scopeConfig->getValue('cataloginventory/options/show_out_of_stock', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config_max_sale_qty = $scopeConfig->getValue('cataloginventory/item_options/max_sale_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $product = json_decode($product);
        foreach ($product as $key => $val) {
            try {
                $_product = $this->listingHelper->getProduct($val);
                $stock = $this->stock->getStockItem($_product->getId());

                $stocklevel = (int)$stock->getQty();
                $stock_data = $stock->getData();

                if ($stock_data['use_config_manage_stock'] == 0) {
                    if ($stock_data['manage_stock'] == 0) {
                        if ($stock_data['use_config_max_sale_qty'] == 0) {
                            $pqty[$val] = $stock_data['max_sale_qty'];
                        } else {
                            $pqty[$val] = $config_max_sale_qty;

                        }
                    } else {
                        $pqty[$val] = $stocklevel;
                    }
                } else {

                    if ($config_manage_stock == 0) {
                        $pqty[$val] = $config_max_sale_qty;
                    } else {
                        $pqty[$val] = $stocklevel;
                    }

                }
            } catch (\Exception $ex) {

            }
        }
        return $pqty;
    }

    public function ws_myOrder($cust_id, $curr_page, $page_size, $store, $currency)
    {
        $media_url = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $basecurrencycode = $this->_storeManager->getStore($store)->getBaseCurrencyCode();
        $res = [];
        $totorders = $this->_orderData->getCollection()->addFieldToFilter('customer_id', $cust_id);
        $res["total"] = count($totorders);
        /** @var \Magento\Sales\Model\ResourceModel\Order\Collection $orders */
        $orders = $this->_orderData->getCollection()->addFieldToSelect('*')->addFieldToFilter('customer_id', $cust_id)->setOrder('created_at', 'desc')->setPage($curr_page, $page_size);
        $orderData = [];
        /** @var \Magento\Sales\Model\Order $order */
        foreach ($orders as $order) {

            $shippingAddress = $order->getShippingAddress();
            if (is_object($shippingAddress)) {
                $shippadd = [];
                $flag = 0;
                if (count($orderData) > 0)
                    $flag = 1;
                $shippadd = array(
                    "firstname" => $shippingAddress->getFirstname(),
                    "lastname" => $shippingAddress->getLastname(),
                    "company" => $shippingAddress->getCompany(),
                    "street" => implode(" ", $shippingAddress->getStreet()),
                    "region" => $shippingAddress->getRegion(),
                    "city" => $shippingAddress->getCity(),
                    "pincode" => $shippingAddress->getPostcode(),
                    "countryid" => $shippingAddress->getCountry_id(),
                    "contactno" => $shippingAddress->getTelephone(),
                    "shipmyid" => $flag
                );
            }
            $billingAddress = $order->getBillingAddress();
            if (is_object($billingAddress)) {
                $billadd = array(
                    "firstname" => $billingAddress->getFirstname(),
                    "lastname" => $billingAddress->getLastname(),
                    "company" => $billingAddress->getCompany(),
                    "street" => implode(" ", $billingAddress->getStreet()),
                    "region" => $billingAddress->getRegion(),
                    "city" => $billingAddress->getCity(),
                    "pincode" => $billingAddress->getPostcode(),
                    "countryid" => $billingAddress->getCountry_id(),
                    "contactno" => $billingAddress->getTelephone()
                );
            }
            /** @var \Magento\Sales\Model\Order\Payment\Info $payment */
            $payment = $order->getPayment();
            // print_r($payment->getMethodInstance()->getTitle()); die;

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            try {
                $methodModel = $payment->getMethodInstance();
                $pmethod = $methodModel->getTitle();
                if ($pmethod == null)
                    $pmethod = '';
                else
                    $pmethod = $methodModel->getTitle();
                $payment_result = array(
                    "payment_method_title" => $pmethod,
                    "payment_method_code" => $methodModel->getCode()
                );
                if ($methodModel->getCode() == \Magento\OfflinePayments\Model\Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE) {
                    /** @var \Magento\OfflinePayments\Model\Banktransfer $methodModel */
                    $payment_result["payment_method_description"] = $methodModel->getInstructions();
                }
            } catch (\Exception $ex2) {
                unset($ex2);
            }

            $items = $order->getAllItems();
            $itemcount = count($items);
            $name = [];
            $unitPrice = [];
            $sku = [];
            $ids = [];
            $qty = [];
            $images = [];
            $thumbnailimage = [];
            $test_p = [];
            $itemsExcludingConfigurables = [];
            foreach ($items as $itemId => $item) {
                $name[] = $item->getName();
                if ($item->getOriginalPrice() > 0) {
                    $unitPrice[] = number_format($this->listingHelper->convertCurrency(floatval($item->getPrice()), $basecurrencycode, $currency), 2, '.', '');
                } else {
                    $unitPrice[] = number_format($this->listingHelper->convertCurrency(floatval($item->getPrice()), $basecurrencycode, $currency), 2, '.', '');
                }

                $sku[] = $item->getSku();
                $ids[] = $item->getProductId();
                $qty[] = $item->getQtyOrdered();
                /** @var \Magento\Catalog\Model\Product $products */
                $products = $this->listingHelper->getProduct($item->getProductId());
                $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
                $thumbnailimage[] = $imagehelper->init($products, 'category_page_grid')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
                $images[] = $media_url . '/catalog/product' . $products->getThumbnail();
            }
            $product = [
                "name" => $name,
                "sku" => $sku,
                "id" => $ids,
                "quantity" => $qty,
                "unitprice" => $unitPrice,
                "image" => $images,
                "small_image" => $thumbnailimage,
                "total_item_count" => $itemcount,
                "price_org" => $test_p,
                "price_based_curr" => 1
            ];

            $order_date = $order->getCreatedAt() . '';
            $orderData = array(
                "id" => $order->getId(),
                "order_id" => $order->getRealOrderId(),
                "status" => $order->getStatus(),
                "order_date" => $order_date,
                "grand_total" => number_format($this->listingHelper->convertCurrency(floatval($order->getGrandTotal()), $basecurrencycode, $currency), 2, '.', ''),
                "shipping_address" => $shippadd,
                "billing_address" => $billadd,
                "shipping_message" => $order->getShippingDescription(),
                "shipping_amount" => number_format($this->listingHelper->convertCurrency(floatval($order->getShippingAmount()), $basecurrencycode, $currency), 2, '.', ''),
                "payment_method" => $payment_result,
                "tax_amount" => number_format($this->listingHelper->convertCurrency(floatval($order->getTaxAmount()), $basecurrencycode, $currency), 2, '.', ''),
                "product" => $product,
                "order_currency" => $order->getOrderCurrencyCode(),
                "order_currency_symbol" => $this->_currency->getCurrency($order->getOrderCurrencyCode())->getSymbol(),
                "currency" => $currency,
                "couponUsed" => 0,
                "discount_amount" => floatval(number_format($this->listingHelper->convertCurrency(floatval($order->getDiscountAmount()), $basecurrencycode, $currency), 2, '.', '')) * -1,
            );
            $couponCode = $order->getCouponCode();
            if ($couponCode != "") {
                $orderData["couponUsed"] = 1;
                $orderData["couponCode"] = $couponCode;
            }

            if ($order->getAppliedRuleIds()) {
                $rules = explode(',', $order->getAppliedRuleIds());
                foreach ($rules as $rule) {
                    $rule = $this->ruleRepository->getById($rule);
                    switch ($rule->getCouponType()) {
                        case \Magento\SalesRule\Model\Rule::COUPON_TYPE_NO_COUPON:
                            $orderData['rules'][] = [
                                'name' => $rule->getName(),
                                'coupon' => '',
                            ];
                            break;
                        case \Magento\SalesRule\Model\Rule::COUPON_TYPE_SPECIFIC:
                        case \Magento\SalesRule\Model\Rule::COUPON_TYPE_AUTO:
                            $orderData['rules'][] = [
                                'name' => $rule->getName(),
                                'coupon' => $order->getCouponCode(),
                            ];
                            break;
                    }
                }
            }

            $res["data"][] = $orderData;
        }
        return $res;
    }

    /**
     * Place Order API
     *
     * @param int|string $custid
     * @param $Jproduct
     * @param $store
     * @param $address
     * @param $couponCode
     * @param $is_create_quote
     * @param $transid
     * @param $payment_code
     * @param $shipping_code
     * @param $currency
     * @param $message
     * @param $theme
     * @param $stripeCustId
     * @param $card_id
     * @return array
     */
    public function placeorder($custid, $Jproduct, $store, $address, $couponCode, $is_create_quote, $transid, $payment_code, $shipping_code, $currency, $message, $theme, $stripeCustId, $card_id)
    {
        $orderData = [];
        $res = [];
        $quantity_error = [];
        try {
            $quote_data = $this->prepareQuote($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, 1, 0, 0); // self function
            if ($quote_data["status"] == "error") {
                return $quote_data;
            }
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $this->quoteRepository->get($quote_data['quote_id']);
            $quote->reserveOrderId();
            $quote = $this->setQuoteGiftMessage($quote, $message, $custid); // self function
            $quote = $this->setQuotePayment($quote, $payment_code, $transid); // self function
            $quote->setRemoteIp($this->_remoteAddress->getRemoteAddress());
            /** @var \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order */
            $order = $this->orderFactory->create();
            $addresses = [];
            $quote->setInventoryProcessed(false);
            $quote->collectTotals();
            //$this->quoteRepository->save($quote);     commented this function since it's overriding the current shipping address with default shipping address   
                                                        //when used we use address other than default address for the order.
            //$quote->save();                             //appended for saving the quote.
            
            $this->dataObjectHelper->mergeDataObjects(
                '\Magento\Sales\Api\Data\OrderInterface',
                $order,
                $this->quoteAddressToOrder->convert($quote->getShippingAddress(), $orderData)
            );
            if ($custid) {
                $shippingAddress = $this->quoteAddressToOrderAddress->convert(
                    $quote->getShippingAddress(),
                    [
                        'address_type' => 'shipping',
                        'email' => $quote->getCustomerEmail()
                    ]
                );

                $addresses[] = $shippingAddress;
                $order->setShippingAddress($shippingAddress);
                $order->setShippingMethod($quote->getShippingAddress()->getShippingMethod());
                $billingAddress = $this->quoteAddressToOrderAddress->convert(
                    $quote->getBillingAddress(),
                    [
                        'address_type' => 'billing',
                        'email' => $quote->getCustomerEmail()
                    ]
                );
                $addresses[] = $billingAddress;
            } else {
                $decode_address = json_decode(base64_decode($address));
                $shippingAddress = $this->quoteAddressToOrderAddress->convert(
                    $quote->getShippingAddress(),
                    [
                        'address_type' => 'shipping',
                        'email' => $decode_address->shipping->email
                    ]
                );

                $addresses[] = $shippingAddress;
                $order->setShippingAddress($shippingAddress);
                $order->setShippingMethod($quote->getShippingAddress()->getShippingMethod());
                $billingAddress = $this->quoteAddressToOrderAddress->convert(
                    $quote->getBillingAddress(),
                    [
                        'address_type' => 'billing',
                        'email' => $decode_address->billing->email
                    ]
                );
                $addresses[] = $billingAddress;
            }

            $order->setBillingAddress($billingAddress);
            $order->setAddresses($addresses);
            $order->setRemoteIp($this->_remoteAddress->getRemoteAddress());
            $order->setXForwardedFor($this->_request->getServer('HTTP_X_FORWARDED_FOR'));

            $finalTransactionId = $transid;
            
            $this->quoteRepository->save($quote);   //moved from the line no 2648
            
            $quoteItems = [];
            foreach ($quote->getAllItems() as $quoteItem) {
                /** @var \Magento\Quote\Model\ResourceModel\Quote\Item $quoteItem */
                $quoteItems[$quoteItem->getId()] = $quoteItem;
            }
            $orderItems = [];
            /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
            foreach ($quoteItems as $quoteItem) {
                $parentItem = (isset($orderItems[$quoteItem->getParentItemId()])) ?
                    $orderItems[$quoteItem->getParentItemId()] : null;
                $orderItems[$quoteItem->getId()] =
                    $this->quoteItemToOrderItem->convert($quoteItem, ['parent_item' => $parentItem]);
            }
            $oitems = array_values($orderItems);
            $order->setItems($oitems);
            if ($custid) {
                if ($quote->getCustomer()) {
                    $order->setCustomerId($quote->getCustomer()->getId());
                }
                $order->setQuoteId($quote->getId());
                $order->setCustomerEmail($quote->getCustomerEmail());
                $order->setCustomerFirstname($quote->getCustomerFirstname());
                $order->setCustomerMiddlename($quote->getCustomerMiddlename());
                $order->setCustomerLastname($quote->getCustomerLastname());
            } else {
                $decode_address = json_decode(base64_decode($address));
                $order->setQuoteId($quote->getId());
                $order->setCustomerEmail($decode_address->billing->email);
                $order->setCustomerFirstname($decode_address->billing->firstname);
                $order->setCustomerLastname($decode_address->billing->lastname);
                $order->setCustomerIsGuest(1);
            }

            $this->eventManager->dispatch(
                'sales_model_service_quote_submit_before',
                [
                    'order' => $order,
                    'quote' => $quote
                ]
            );
            try {
                if ($payment_code == \Magento\Paypal\Model\Config::METHOD_EXPRESS) {
                    $order->setPayment($this->quotePaymentToOrderPayment->convert($quote->getPayment()));
                    $this->_eventManager->dispatch('sales_order_place_before', ['order' => $order]);
                    $this->_orderRepository->save($order);
                    $this->_eventManager->dispatch('sales_order_place_after', ['order' => $order]);
                } else {
                    if ($payment_code == StripePayment::CODE) {
                        /** @var \Magento\Quote\Model\Quote\Payment $payment */
                        $payment = $quote->getPayment();
                        $custid = $custid === null ? 0 : $custid;
                        $additionalData = [
                            'md_stripe_card_id' => $this->encryptor->encrypt($card_id),
                            'save_card' => 'true',
                        ];
                        if (empty($stripeCustId)) {
                            $payment->setAdditionalInformation('token_id', $card_id);
                            $stripeKey = $this->getStripeKey();
                            \Stripe\Stripe::setApiKey($stripeKey[0]['payment_method_account_key']);
                            $token = \Stripe\Token::retrieve($card_id);
                            $data = [
                                'method' => $payment_code,
                                'additional_data' => [
                                    'md_stripe_card_id' => 'new',
                                    'save_card' => 'false',
                                    'cc_number' => $token->card->last4,
                                    'cc_type' => $token->card->brand,
                                    'expiration' => $token->card->exp_month,
                                    'expiration_yr' => $token->card->exp_year,
                                    'token_id' => $card_id
                                ]
                            ];
                        } else {
                            if (empty($custid)) {
                                $additionalData['stripe_customer_id'] = $stripeCustId;
                            } else {
                                $customer = $this->customerRegistry->retrieve($custid);
                                $this->_session->setCustomer($customer);
                                $stripeUser = $this->stripeAccount->create();
                                $stripeUser->getResource()->load($stripeUser, $custid, 'customer_id');
                                if ($stripeUser->getId()) {
                                    if ($stripeUser->getStripeCustomerId() != $stripeCustId) {
                                        $stripeUser->setStripeCustomerId($stripeCustId);
                                        $stripeUser->getResource()->save($stripeUser);
                                    }
                                } else {
                                    $stripeUser->setCustomerId($custid);
                                    $stripeUser->setStripeCustomerId($stripeCustId);
                                    $stripeUser->getResource()->save($stripeUser);
                                }
                            }
                            $data = [
                                'method' => $payment_code,
                                'additional_data' => $additionalData,
                            ];
                        }
                        $payment->importData($data);
                        if (!$custid) {
                            $order->unsetData(\Magento\Sales\Api\Data\OrderInterface::CUSTOMER_ID);
                        }
                    }
                    $order->setPayment($this->quotePaymentToOrderPayment->convert($quote->getPayment()));
                    $this->orderManagement->place($order);
                }

                $quote->setIsActive(false);
                $this->eventManager->dispatch(
                    'sales_model_service_quote_submit_success',
                    [
                        'order' => $order,
                        'quote' => $quote
                    ]
                );
            } catch (\Exception $e) {
                $this->eventManager->dispatch(
                    'sales_model_service_quote_submit_failure',
                    [
                        'order' => $order,
                        'quote' => $quote,
                        'exception' => $e
                    ]
                );
                throw $e;
            }

            $quantity_error = $quote_data['qty_flag'] == 1 ? '' : $this->updateQuantityAfterOrder($Jproduct);
            $res["status"] = 1;
            $res["id"] = $order->getId();
            $res["orderid"] = $order->getIncrementId();
            $res["transid"] = $order->getPayment()->getLastTransId() == null ? $transid : $order->getPayment()->getLastTransId();
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"] = $payment_code;
            $res["quantity_error"] = $quantity_error;

            $order->addStatusHistoryComment("Order was placed using Mobile App")->setIsVisibleOnFront(false)->setIsCustomerNotified(false);
            if ($res["orderid"] > 0 &&
                ($payment_code == \Magento\OfflinePayments\Model\Cashondelivery::PAYMENT_METHOD_CASHONDELIVERY_CODE
                    || $payment_code == \Magento\OfflinePayments\Model\Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE
                    || $payment_code == \Magento\Authorizenet\Model\Directpost::METHOD_CODE
                    || $payment_code == \Magento\Payment\Model\Method\Free::PAYMENT_METHOD_FREE_CODE
                    || $payment_code == \Magento\Paypal\Model\Config::METHOD_EXPRESS)) {
                try {
                    $order->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT, true);
                    $order->setStatus($order->getConfig()->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT));
                    $this->quoteRepository->save($quote);
                    $this->_orderRepository->save($order);
                    $res["order_status"] = $order->getStatus();
                } catch (\Exception $e) {
                    $this->_logger->error($e->getMessage() . "\n" . $e->getTraceAsString());
                }
            } else {
                if ($payment_code !== StripePayment::CODE) {
                    $order->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT, true);
                    $order->setStatus($order->getConfig()->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT));
                }
                //$order->setEmailSent(1);
                //$this->_orderSender->send($order);
                $this->quoteRepository->save($quote);
                $this->_orderRepository->save($order);
                $res["order_status"] = $order->getStatus();
            }
        } catch (\Exception $except) {
            $res["status"] = 0;
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"] = $payment_code;
            $this->_logger->critical($except->getMessage() . "\n" . $except->getTraceAsString());
        }

        return $res;
    }

    public function prepareQuote($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, $is_create_quote, $find_shipping, $find_payment)
    {
        $storeObj = $this->_storeManager;
        $scopeConfig = $this->_scopeconfig;
        $Jproduct = str_replace(" ", "+", $Jproduct);
        $orderproduct = json_decode(base64_decode($Jproduct));
        $address = str_replace(" ", "+", $address);
        $address = json_decode(base64_decode($address));
        $config_manage_stock = $scopeConfig->getValue('cataloginventory/options/show_out_of_stock', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config_max_sale_qty = $scopeConfig->getValue('cataloginventory/item_options/max_sale_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $basecurrencycode = $storeObj->getStore($store)->getBaseCurrencyCode();
        try {
            $addressPrefix = null;
            $addresscompany = null;
            if (isset($address->shipping->prefix))
                $addressPrefix = $address->shipping->prefix;
            if (isset($address->shipping->prefix))
                $addresscompany = $address->shipping->company;
            $addressBPrefix = null;
            $addressBcompany = null;
            if (isset($address->billing->prefix))
                $addressBPrefix = $address->billing->prefix;
            if (isset($address->billing->prefix))
                $addressBcompany = $address->billing->company;
            // get billing and shipping address of customer
            $shippingAddress = array(
                //~ 'prefix' => $addressPrefix,
                'firstname' => $address->shipping->firstname,
                'lastname' => $address->shipping->lastname,
                //~ 'company' => $addresscompany,
                'street' => $address->shipping->street,
                'city' => $address->shipping->city,
                'postcode' => $address->shipping->postcode,
                'telephone' => $address->shipping->phone,
                'country_id' => $address->shipping->country,
                'region' => $address->shipping->region
            );
            $billingAddress = array(
                //~ 'prefix' => $addressBPrefix,
                'firstname' => $address->billing->firstname,
                'lastname' => $address->billing->lastname,
                //~ 'company' => $addressBcompany,
                'street' => $address->billing->street,
                'city' => $address->billing->city,
                'postcode' => $address->billing->postcode,
                'telephone' => $address->billing->phone,
                'country_id' => $address->billing->country,
                'region' => $address->billing->region
            );

            //Setting Region ID In case of Country is US
            $billingRegion = $this->_region->loadByCode($address->billing->region, $address->billing->country);
            if ($billingRegion->getId()) {
                $billingAddress["region_id"] = $billingRegion->getId();
            }
            $shippingRegion = $this->_region->loadByCode($address->shipping->region, $address->shipping->country);
            if ($shippingRegion->getId()) {
                $shippingAddress["region_id"] = $shippingRegion->getId();
            }
            $quote = $this->quoteFactory->create();

            if ($custid) {
                $customer = $this->customerRepository->getById($custid);
                if ($customer->getId()) {
                    $quote->assignCustomer($customer);
                }
            } else {
                $quote->setCustomerEmail($address->shipping->email);
            }

//            $quote->setBillingAddress()->create()

            /** @var \Magento\Store\Model\Store $storeobj */
            $storeobj = $this->_storeManager->getStore($store);
            $quote->setStore($storeobj);
            $res = [];
            $stock_counter = 0;
            $flag = 0;
            foreach ($orderproduct as $key => $item) {
                $product_stock = $this->getProductStock($item->id);
                $product = $this->listingHelper->getProduct($item->id);
                try {
                    if ($product_stock->getUseConfigManageStock() == 0) {
                        if ($product_stock->getManageStock() == 0) {
                            if ($product_stock->getUseConfigMaxSaleQty() == 0) {
                                $product_stock_quantity = $product_stock->getMaxSaleQty();
                                $flag = 1;
                            } else {
                                $product_stock_quantity = $config_max_sale_qty;
                                $flag = 1;
                            }
                        } else {
                            $product_stock_quantity = $product_stock->getQty();
                            $flag = 0;
                        }
                    } else {
                        if ($config_manage_stock == 0) {
                            $product_stock_quantity = $config_max_sale_qty;
                            $flag = 1;
                        } else {
                            $product_stock_quantity = $product_stock['qty'];
                            $flag = 0;
                        }

                    }
                } catch (\Exception $ex) {
                }
                $manage_stock = $product_stock->getManageStock();
                $is_in_stock = $product_stock->getIsInStock();
                $res["qty_flag"] = $flag;

                if ($item->quantity > $product_stock_quantity) {
                    $res["status"] = "error";
                    $res["type"] = "quantity";
                    $res["product"][$stock_counter]["id"] = $item->id;
                    $res["product"][$stock_counter]["name"] = $product->getName();
                    $res["product"][$stock_counter]["sku"] = $product->getSku();
                    $res["product"][$stock_counter]["quantity"] = $product_stock_quantity;
                    $stock_counter++;
                }

                $productType = $product->getTypeID();
                $quoteItem = $this->_quoteitem->setProduct($product);

                $quoteItem->setQuote($quote);

                $quoteItem->setQty($item->quantity);
                //~ var_dump($quoteItem->getProduct()->getName());die;
                $optionch = [];
                if (isset($item->options)) {
                    $optionch = (array)$item->options;
                }


                //echo "<pre>"; print_r($item); die('ccc');

                if (!empty($optionch)) {
                    foreach ($item->options as $ckey => $cvalue) {
                        $custom_option_ids_arr[] = $ckey;
                    }

                    $option_ids = implode(",", $custom_option_ids_arr);
                    $quoteItem->addOption(new Varien_Object(array(
                        'product' => $quoteItem->getProduct(),
                        'code' => 'option_ids',
                        'value' => $option_ids
                    )));

                    foreach ($item->options as $ckey => $cvalue) {
                        if (is_array($cvalue)) {
                            $all_ids = implode(",", array_unique($cvalue));
                        } else {
                            $all_ids = $cvalue;
                        }
                        //Handle Custom Option Time depending upon Timezone
                        if (preg_match('/(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]/', $all_ids)) {
                            $currentTimestamp = $this->_date->timestamp(time());
                            $currentDate = date('Y-m-d', $currentTimestamp);
                            $test = new DateTime($currentDate . ' ' . $all_ids);
                            $all_ids = $test->getTimeStamp();
                        }
                        try {
                            $quoteItem->addOption(new Varien_Object(array(
                                'product' => $quoteItem->getProduct(),
                                'code' => 'option_' . $ckey,
                                'value' => $all_ids
                            )));
                        } catch (\Exception $eee) {
                            echo 'Error ' . $eee->getMessage();
                        }
                    } //end inner foreach\

                    //$quote->addItem($quoteItem);
                    $quote->addProduct(
                        $product,
                        intval($item->quantity));

                } //end if
                else {
                    //~ var_dump($quoteItem->getProduct()->getId());die;
                    //  $quote->addItem($quoteItem);
                    $quote->addProduct(
                        $product,
                        intval($item->quantity));
                    continue;
                }
            }

            if ($stock_counter > 0 && $is_create_quote == 1) {
                return $res;
            }
            $addressForm = $this->_addressform;
            $addressForm->setFormCode('customer_address_edit')->setEntityType('customer_address');
            /** @var \Magento\Eav\Model\Attribute $attribute */
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($shippingAddress[$attribute->getAttributeCode()])) {
                    $quote->getShippingAddress()->setData($attribute->getAttributeCode(), $shippingAddress[$attribute->getAttributeCode()]);
                }
            }
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($billingAddress[$attribute->getAttributeCode()])) {
                    $quote->getBillingAddress()->setData($attribute->getAttributeCode(), $billingAddress[$attribute->getAttributeCode()]);
                }
            }
            $quote->setBaseCurrencyCode($basecurrencycode);
            $quote->setQuoteCurrencyCode($currency);
            $this->totalsCollector->collectAddressTotals($quote, $quote->getShippingAddress());
            if ($find_shipping) {
                $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates();
            } else {
                $quote->getShippingAddress()->setShippingMethod($shipping_code)->setCollectShippingRates(true)->collectShippingRates();
            }
            //Check if applied for coupon
            if ($couponCode != '') {
                $quote->setCouponCode($couponCode);
                $coupon_status = 1;
            } else {
                $coupon_status = 0;
            }

            $quote->setTotalsCollectedFlag(false);
            $quote->collectTotals();
            $totals = $quote->getTotals();

            try {
                $test = $quote->getShippingAddress();
                $shipping_tax_amount = number_format($this->_directory->currencyConvert($test->getShippingTaxAmount(), $basecurrencycode, $currency), 2, ".", "");
            } catch (\Exception $ex) {
                $shipping_tax_amount = 0;
            }
            $shipping_methods = [];
            if ($find_shipping) {
                $shipping = $quote->getShippingAddress()->getGroupedAllShippingRates();
                $index = 0;
                $shipping_dropdown_option = '';
                foreach ($shipping as $shipping_method_id => $shipping_method) {
                    foreach ($shipping_method as $current_shipping_method) {
                        if ($current_shipping_method->getCarrier() == 'quotationshipping') {
                            continue;
                        }
                        $shipping_methods[$index]["id"] = $shipping_method_id;
                        $shipping_methods[$index]["code"] = str_replace(" ", "%20", $current_shipping_method->getCode());
                        $shipping_methods[$index]["method_title"] = $current_shipping_method->getMethodTitle();
                        $shipping_methods[$index]["carrier_title"] = $current_shipping_method->getCarrierTitle();
                        $shipping_methods[$index]["carrier"] = $current_shipping_method->getCarrier();
                        $shipping_methods[$index]["price"] = $this->_directory->currencyConvert($current_shipping_method->getPrice(), $basecurrencycode, $currency);
                        $shipping_methods[$index]["description"] = $current_shipping_method->getMethodDescription();
                        $shipping_methods[$index]["error_message"] = $current_shipping_method->getErrorMessage();
                        $shipping_methods[$index]["address_id"] = $current_shipping_method->getAddressId();
                        $shipping_methods[$index]["created_at"] = $current_shipping_method->getCreatedAt();
                        $shipping_methods[$index]["updated_at"] = $current_shipping_method->getUpdatedAt();
                        $shipping_option_title = $shipping_methods[$index]["carrier_title"];
                        if ($shipping_methods[$index]["method_title"]) {
                            $shipping_option_title .= ' (' . $shipping_methods[$index]["method_title"] . ')';
                        }
                        if ($shipping_methods[$index]["price"]) {
                            $shipping_option_title .= ' + ' . $this->_currency->getCurrency($currency)->getSymbol() . number_format($shipping_methods[$index]["price"], 2);
                        }
                        $shipping_dropdown_option .= '<option id="' . $shipping_methods[$index]["id"] . '" value="' . $shipping_methods[$index]["code"] . '" price="' . $shipping_methods[$index]["price"] . '" description="' . $shipping_methods[$index]["description"] . '">' . $shipping_option_title . '</option>';
                        $index++;
                    }
                }
                $res["available_shipping_method"] = base64_encode($shipping_dropdown_option);
            }
            $dis = 0;


            //Find Applied Tax
            if (isset($totals['tax']) && $totals['tax']->getValue()) {
                $tax_amount = number_format($this->_directory->currencyConvert($totals['tax']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $tax_amount = 0;
            }
            if (isset($totals['shipping']) && $totals['shipping']->getValue()) {
                $shipping_amount = number_format($this->_directory->currencyConvert($totals['shipping']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $shipping_amount = 0;
            }
            if ($shipping_tax_amount) {
                $shipping_amount += $shipping_tax_amount;
            }
            if ($quote->getAppliedRuleIds()) {
                $rules = explode(',', $quote->getAppliedRuleIds());
                foreach ($rules as $rule) {
                    $rule = $this->ruleRepository->getById($rule);
                    switch ($rule->getCouponType()) {
                        case \Magento\SalesRule\Model\Rule::COUPON_TYPE_NO_COUPON:
                            $res['rules'][] = [
                                'name' => $rule->getName(),
                                'coupon' => '',
                            ];
                            break;
                        case \Magento\SalesRule\Model\Rule::COUPON_TYPE_SPECIFIC:
                        case \Magento\SalesRule\Model\Rule::COUPON_TYPE_AUTO:
                            $res['rules'][] = [
                                'name' => $rule->getName(),
                                'coupon' => $quote->getCouponCode(),
                            ];
                            break;
                    }
                }
            }

            $coupon_discountvalue = isset($totals['discount']) ? $totals['discount']->getValue() : "";

            //Find Applied Discount
            if ($coupon_discountvalue != '' && $coupon_discountvalue > 0) {
                $coupon_status = 1;
                $coupon_discount = number_format($this->_directory->currencyConvert($coupon_discountvalue, $basecurrencycode, $currency), 2, ".", "");
            } else {
                $coupon_discount = 0;
                $coupon_status = 0;
            }

            $grandTotal = number_format($this->_directory->currencyConvert($totals['grand_total']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            $res["coupon_discount"] = $coupon_discount;
            $res["coupon_status"] = $coupon_status;
            $res["tax_amount"] = $tax_amount;
            $res["total_amount"] = $grandTotal;
            $res["currency"] = $currency;
            $res["status"] = "success";
            $res["shipping_amount"] = $shipping_amount;
            $res["shipping_method"] = $shipping_methods;

            if ($find_payment) {
                $res["payment_method"] = $this->ws_getpaymentmethod(); // to return payment method when find_payment == 1;
            }

            if ($is_create_quote == 1) {
                $this->quoteRepository->save($quote);
                $res["quote_id"] = $quote->getId();
            }
            return $res;
        } catch (\Exception $ex) {
            $res["coupon_discount"] = 0;
            $res["coupon_status"] = 0;
            $res["tax_amount"] = 0;
            $res["total_amount"] = 0;
            $res["currency"] = $currency;
            $res["status"] = "error";
            $res["type"] = $ex->getMessage();
            $res["shipping_amount"] = isset($shipping_amount) ? $shipping_amount : 0;
            $res["shipping_method"] = isset($shipping_methods) ? $shipping_methods : 0;
            if ($find_payment)
                $res["payment_method"] = $this->ws_getpaymentmethod(); // to return payment method when find_payment == 1;
            $this->_logger->critical($ex->getMessage() . "\n" . $ex->getTraceAsString());
            return $res;
        }
    }

    public function getProductStock($product_id)
    {
        $stock_data = [];
        $stock_product = $this->stock->getStockItem($product_id);
        return $stock_product;
    }

    public function ws_getpaymentmethod()
    {
        $mofluid_pay_data = $this->_mpayment->getCollection()->addFieldToFilter('payment_method_status', 1)->getData();

        foreach ($mofluid_pay_data as $key => $mofluid_pay_datas) {
            $mofluid_pay_data[$key]['payment_method_id'] = $mofluid_pay_datas['id'];
            unset($mofluid_pay_data[$key]['id']);
        }

        return ($mofluid_pay_data);
    }

    /**
     * @param \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote $quote
     * @param $message
     * @param $custid
     * @return \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote
     */
    public function setQuoteGiftMessage(\Magento\Quote\Api\Data\CartInterface $quote, $message, $custid)
    {
        $message_id = [];
        $message = json_decode($message, true);
        if (!empty($message)) {
            foreach ($message as $key => $value) {
                $giftMessage = $this->_giftMessage;
                $giftMessage->setCustomerId($custid);
                $giftMessage->setSender($value["sender"]);
                $giftMessage->setRecipient($value["receiver"]);
                $giftMessage->setMessage($value["message"]);
                $giftObj = $giftMessage->save();
                $message_id["msg_id"][] = $giftObj->getId();
                $message_id["prod_id"][] = $value["product_id"];
                $quote->setGiftMessageId($giftObj->getId());
                $this->quoteRepository->save($quote);
            }
        }
        return $quote;
    }

    /**
     * @param \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote $quote
     * @param string $pmethod
     * @param int $transid
     * @return \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote
     */
    public function setQuotePayment(\Magento\Quote\Api\Data\CartInterface $quote, $pmethod, $transid)
    {
        $quotePayment = $quote->getPayment();
        $quotePayment->setMethod($pmethod)->setIsTransactionClosed(1)->setTransactionAdditionalInfo(\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS, array(
            'TransactionID' => $transid
        ));
        $quotePayment->setCustomerPaymentId($transid);
        $quote->setPayment($quotePayment);
        return $quote;
    }

    public function updateQuantityAfterOrder($Jproduct)
    {
        $error = [];
        $Jproduct = str_replace(" ", "+", $Jproduct);

        $orderproduct = json_decode(base64_decode($Jproduct));
        try {
            foreach ($orderproduct as $key => $item) {
                $productId = $item->id;
                $orderQty = $item->quantity;
                //get total quantity
                $totalqty = (int)$this->stock->getStockItem($productId)->getQty();
                //calculate new quantity
                $newqty = $totalqty - $orderQty;
                //update new quantity
                try {
                    /** @var \Magento\Catalog\Model\Product $product */
                    $product = $this->listingHelper->getProduct($productId);
                    $product->setStockData([
                        'is_in_stock' => $newqty ? 1 : 0, //Stock Availability
                        'qty' => $newqty //qty
                    ]);
                    $this->listingHelper->saveProduct($product);
                } catch (\Exception $ee) {
                    $error[] = $ee->getMessage();
                }
            }
        } catch (\Exception $ex) {
            $error[] = $ex->getMessage();
        }
        return $error;
    }

    public function getStripeKey()
    {
        $mofluid_pay_data = $this->_mpayment->getCollection()->addFieldToFilter('payment_method_status', 1)->addFieldToFilter('payment_method_code', 'md_stripe_cards')->getData();
        return $mofluid_pay_data;
    }

    /*********************
     * updateOrder API
     *********************/

    /**
     * Update order
     *
     * @param int|string $store
     * @param int|string $custid
     * @param string $orderid
     * @param string $payment_code
     * @param int|string $transid
     * @param string $status
     * @return array
     */
    public function updateOrder($store, $custid, $orderid, $payment_code, $transid, $status)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->orderFactory->create();
        $order->loadByIncrementId($orderid);
        $payment = $order->getPayment();
        $finalTransactionId = $transid;
        if ($status == "success") {
            try {
                if ($payment_code == \Magento\Paypal\Model\Config::METHOD_EXPRESS) {
                    /** @var \Magento\Sales\Model\Order\Payment $payment */
                    $payment->setMethod($payment_code);
                    $credentials = $this->getPaypalRestAuth();
                    $paypalAuth = new \PayPal\Auth\OAuthTokenCredential(
                        $credentials[0]['payment_method_account_id'],
                        $credentials[0]['payment_method_account_key']
                    );
                    $token = new \PayPal\Rest\ApiContext($paypalAuth);
                    if ($credentials[0]['payment_method_mode']) {
                        $token->setConfig(['mode' => 'live']);
                    }
                    try {
                        $paypalDetails = \PayPal\Api\Payment::get($transid, $token);
                        $transactions = $paypalDetails->getTransactions();
                        $transaction = end($transactions);
                        $payer = $paypalDetails->getPayer();
                        if ($payer->getPayerInfo()) {
                            $additionalInfo = [
                                \Magento\Paypal\Model\Express\Checkout::PAYMENT_INFO_TRANSPORT_PAYER_ID =>
                                    $paypalDetails->getPayer()->getPayerInfo()->getPayerId(),
                                \Magento\Paypal\Model\Info::PAYPAL_PAYER_ID =>
                                    $paypalDetails->getPayer()->getPayerInfo()->getPayerId(),
                                \Magento\Paypal\Model\Info::PAYPAL_PAYER_EMAIL =>
                                    $paypalDetails->getPayer()->getPayerInfo()->getEmail(),
                                \Magento\Paypal\Model\Info::PAYPAL_PAYER_STATUS =>
                                    $paypalDetails->getPayer()->getStatus(),
                            ];
                        } else {
                            $funding = $payer->getFundingInstruments()[0];
                            if ($funding->getCreditCardToken()) {
                                $additionalInfo = [
                                    \Magento\Paypal\Model\Express\Checkout::PAYMENT_INFO_TRANSPORT_PAYER_ID =>
                                        $payer->getFundingInstruments()[0]->getCreditCardToken()->getPayerId(),
                                    \Magento\Paypal\Model\Info::PAYPAL_PAYER_ID =>
                                        $payer->getFundingInstruments()[0]->getCreditCardToken()->getPayerId(),
                                    \Magento\Paypal\Model\Info::PAYPAL_PAYER_EMAIL =>
                                        'N\A',
                                    \Magento\Paypal\Model\Info::PAYPAL_PAYER_STATUS =>
                                        'N\A',
                                ];
                            } else {
                                $additionalInfo = [
                                    \Magento\Paypal\Model\Express\Checkout::PAYMENT_INFO_TRANSPORT_PAYER_ID =>
                                        'N\A',
                                    \Magento\Paypal\Model\Info::PAYPAL_PAYER_ID =>
                                        'N\A',
                                    \Magento\Paypal\Model\Info::PAYPAL_PAYER_EMAIL =>
                                        'N\A',
                                    \Magento\Paypal\Model\Info::PAYPAL_PAYER_STATUS =>
                                        'N\A',
                                ];
                            }
                        }
                        array_merge($additionalInfo, [
                            \Magento\Paypal\Model\Info::PAYPAL_ADDRESS_STATUS =>
                                'N\A',
                            \Magento\Paypal\Model\Info::PAYPAL_PROTECTION_ELIGIBILITY =>
                                $transaction->getRelatedResources()[0]->getSale()->getProtectionEligibility() ?: 'N\A',
                            \Magento\Paypal\Model\Info::PAYMENT_STATUS_GLOBAL =>
                                $transaction->getRelatedResources()[0]->getSale()->getState(),
                            \Magento\Paypal\Model\Info::PENDING_REASON_GLOBAL =>
                                $transaction->getRelatedResources()[0]->getSale()->getReasonCode() ?: 'N\A',
                        ]);
                        $payment->setAdditionalInformation($additionalInfo);
                        $finalTransactionId = $transaction->getRelatedResources()[0]->getSale()->getId();
                    } catch (\Exception $ex) {
                        $payment->setAdditionalInformation(
                            \Magento\Paypal\Model\Express\Checkout::PAYMENT_INFO_TRANSPORT_PAYER_ID,
                            $transid
                        );
                    }
                    $additinalD = [
                        'method_title' => $payment_code,
                    ];

                    $trans = $this->transactionBuilder;
                    $transaction = $trans->setPayment($order->getPayment())
                        ->setOrder($order)
                        ->setTransactionId($finalTransactionId)
                        ->setAdditionalInformation([
                            \Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array)$additinalD
                        ])
                        ->setFailSafe(true)
                        //build method creates the transaction and returns the object
                        ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);
                    $order->getPayment()->addTransactionCommentsToOrder(
                        $transaction,
                        'Paypal Express Checkout Payment '
                    );
                }

                $payment->setAmountOrdered($order->getTotalDue());
                $payment->setBaseAmountOrdered($order->getBaseTotalDue());
                $payment->setAmountPaid($order->getTotalDue());
                $payment->setBaseAmountPaid($order->getBaseTotalDue());
                $payment->setBaseAmountPaidOnline($order->getBaseTotalDue());
                $payment->setAmountAuthorized($order->getTotalDue());
                $payment->setBaseAmountAuthorized($order->getBaseTotalDue());
                $payment->setShippingAmount($order->getShippingAmount());
                $payment->setBaseShippingAmount($order->getBaseShippingAmount());
                $payment->setShippingCaptured($order->getShippingAmount());
                $payment->setBaseShippingCaptured($order->getBaseShippingAmount());
                $order->setTotalDue(0);
                $order->setBaseTotalDue(0);

                /** @var \Magento\Payment\Model\MethodInterface $methodInstance */
                $methodInstance = $payment->getMethodInstance();
                $methodInstance->setStore($order->getStoreId());
                $orderState = \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT;
                $orderStatus = $methodInstance->getConfigData('order_status');
                $isCustomerNotified = $order->getCustomerNoteNotify();
                $orderState = $order->getState() ? $order->getState() : $orderState;
                $orderStatus = $order->getStatus() ? $order->getStatus() : $orderStatus;
                $isCustomerNotified = $isCustomerNotified ?: $order->getCustomerNoteNotify();

                if (!array_key_exists($orderStatus, $order->getConfig()->getStateStatuses($orderState))) {
                    $orderStatus = $order->getConfig()->getStateDefaultStatus($orderState);
                }

                // add message if order was put into review during authorization or capture
                $message = $order->getCustomerNote();
                $originalOrderState = $order->getState();
                $originalOrderStatus = $order->getStatus();

                switch (true) {
                    case ($message && ($originalOrderState == \Magento\Sales\Model\Order::STATE_PAYMENT_REVIEW)):
                        $order->addStatusToHistory($originalOrderStatus, $message, $isCustomerNotified);
                        break;
                    case ($message):
                    case ($originalOrderState && $message):
                    case ($originalOrderState != $orderState):
                    case ($originalOrderStatus != $orderStatus):
                        $order->setState($orderState)
                            ->setStatus($orderStatus)
                            ->addStatusHistoryComment($message)
                            ->setIsCustomerNotified($isCustomerNotified);
                        break;
                    default:
                        break;
                }

                $this->_eventManager->dispatch('sales_order_payment_place_start', ['payment' => $payment]);

                $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING)
                    ->setStatus($order->getConfig()->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_PROCESSING));
                /********code to generate order invoice *******/
                if($order->canInvoice()) {
                    $invoice = $this->_invoiceService->prepareInvoice($order);
                    $invoice->register();
                    if ($payment_code == StripePayment::CODE|| $payment_code == \Magento\Paypal\Model\Config::METHOD_EXPRESS) {
                        $invoice->setTransactionId($transid);
                        $invoice->setState(\Magento\Sales\Model\Order\Invoice::STATE_PAID);
                    }
                    $order->setTotalPaid($invoice->getGrandTotal());
                    $order->setBaseTotalPaid($invoice->getBaseGrandTotal());
                    $this->_invoiceRepository->save($invoice);
                }

                $this->_eventManager->dispatch('sales_order_payment_place_end', ['payment' => $payment]);
                $order->setEmailSent(1);
                $this->_orderRepository->save($order);
                $this->_orderSender->send($order);
				/*********code ends for order invoice ********/
                $res = [
                    'status' => 1,
                    'id' => $order->getId(),
                    'orderid' => $order->getIncrementId(),
                    'transid' => $order->getPayment()->getLastTransId() == null ? $transid : $order->getPayment()->getLastTransId(),
                    'shipping_method' => $order->getShippingMethod(),
                    'payment_method' => $payment_code,
                    'quantity_error' => [],
                    'order_status' => $order->getStatus(),
                ];
            } catch (\Exception $e) {
                $order->cancel();
                $this->_orderRepository->save($order);
                $res = [
                    'status' => 0,
                    'shipping_method' => $order->getShippingMethod(),
                    'payment_method' => $payment_code,
                    'order_status' => $order->getStatus(),
                ];
                $this->_logger->error($e->getMessage() . "\n" . $e->getTraceAsString());
            }
        } else {
            $order->cancel();
            $this->_orderRepository->save($order);
            $res = [
                'status' => 0,
                'shipping_method' => $order->getShippingMethod(),
                'payment_method' => $payment_code,
                'order_status' => $order->getStatus(),
            ];
        }
        return $res;
    }

    public function getPaypalRestAuth()
    {
        $mofluid_pay_data = $this->_mpayment->getCollection()->addFieldToFilter('payment_method_code', 'paypal_rest_api')->getData();
        return $mofluid_pay_data;
    }

    function ws_mofluid_reorder($store, $service, $jproduct, $orderId, $currentcurrencycode)
    {
        $storeObj = $this->_storeManager;
        $scopeConfig = $this->_scopeconfig;
        $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $productids = json_decode($jproduct);
        $count = 0;
        $res = [];
        $order = $this->_orderData->loadByIncrementId($orderId);
        #get all items
        $items = $order->getAllItems();
        #loop for all order items
        foreach ($items as $itemId => $product) {
            $stock = $this->stock->getStockItem($product->getProductId());
            $current_product_id = $product->getProductId();
            $current_product_index = $itemId;
            $has_custom_option = 0;
            $custom_attr = [];
            /** @var \Magento\Catalog\Model\Product $current_product */
            $current_product = $this->listingHelper->getProduct($current_product_id);
            $mofluid_all_product_images = [];
            $mofluid_non_def_images = [];
            $mofluid_baseimage = $media_url . 'catalog/product' . $current_product->getImage();
            foreach ($current_product->getMediaGalleryImages() as $mofluid_image) {
                $mofluid_imagecame = $mofluid_image->getUrl();
                if ($mofluid_baseimage == $mofluid_imagecame) {
                    $mofluid_all_product_images[] = $mofluid_image->getUrl();
                } else {
                    $mofluid_non_def_images[] = $mofluid_image->getUrl();
                }
            }
            $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);

            $basecurrencycode = $storeObj->getStore($store)->getBaseCurrencyCode();
            $res[$count]["id"] = $current_product->getId();
            $res[$count]["sku"] = $current_product->getSku();
            $res[$count]["name"] = $current_product->getName();
            $res[$count]["category"] = $current_product->getCategoryIds();
            $res[$count]["image"] = $mofluid_all_product_images[0];
            $res[$count]["url"] = $current_product->getProductUrl();
            $res[$count]["description"]["full"] = base64_encode($current_product->getDescription());
            $res[$count]["description"]["short"] = base64_encode($current_product->getShortDescription());
            $res[$count]["quantity"]["available"] = $stock->getQty();
            $res[$count]["quantity"]["order"] = $product->getQtyOrdered();
            $res[$count]["visibility"] = $current_product->isVisibleInSiteVisibility(); //getVisibility();
            $res[$count]["type"] = $current_product->getTypeID();
            $res[$count]["weight"] = $current_product->getWeight();
            $res[$count]["status"] = $current_product->getStatus();
            //convert price from base currency to current currency
            $res[$count]["currencysymbol"] = $this->_currency->getCurrency($currentcurrencycode)->getSymbol();
            $defaultprice = str_replace(",", "", ($product->getPrice()));
            $res[$count]["price"] = strval(round($this->listingHelper->convertCurrency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
            $discountprice = str_replace(",", "", ($product->getFinalPrice()));
            $res[$count]["discount"] = strval(round($this->listingHelper->convertCurrency($discountprice, $basecurrencycode, $currentcurrencycode), 2));
            $defaultshipping = $scopeConfig->getValue(
                'carriers/flatrate/price',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $res[$count]["shipping"] = strval(round($this->listingHelper->convertCurrency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));
            $defaultsprice = str_replace(",", "", ($product->getSpecialprice()));
            // Get the Special Price
            $specialprice = $current_product->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate = $current_product->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate = $current_product->getSpecialToDate();
            // Get Current date
            $today = time();
            if ($specialprice) {
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                    $specialprice = strval(round($this->listingHelper->convertCurrency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }
            $current_product_options = [];
            $res[$count]["sprice"] = $specialprice;
            $has_custom_option = 0;
            /*   foreach ($product->getProductOptions() as $opt) {
                $has_custom_option       = 1;
                $current_product_options = $opt['options'];
                if (!$current_product_options) {
                    foreach ($opt as $opt_key => $opt_val) {
                        $current_product_options[$opt_val['option_id']] = $opt_val['option_value'];
                    }
                }
                break;
            } //foreach  */
            $res[$count]["has_custom_option"] = $has_custom_option;
            if ($has_custom_option == 1) {
                $res[$count]["custom_option"] = $current_product_options;
            }
            $res[$count]["custom_attribute"] = $custom_attr;
            $count++;
        }
        return ($res);
    }

    function ws_filter(
        $store_id, $service, $categoryid, $curr_page, $page_size,
        $sortType, $sortOrder, $currentCurrencyCode, $filterdata
    )
    {
        $res = [];
        if ($sortType == null || $sortType == 'null') {
            $sortType = 'name';
        }
        if ($sortOrder == null || $sortOrder == 'null') {
            $sortOrder = 'ASC';
        }
        if ($curr_page == null || $curr_page == 'null') {
            $curr_page = 1;
        }
        if ($page_size == null || $page_size == 'null') {
            $page_size = 10;
        }
        $this->_storeManager->setCurrentStore($store_id);
        $baseCurrencyCode = $this->_storeManager->getStore()->getBaseCurrencyCode();

        $collection = $this->listingHelper->getFilterCollection($categoryid, $filterdata);
        $collection->addAttributeToSort($sortType, $sortOrder);

        $this->listingHelper->setStockFilter($collection);

        $collection->setPage($curr_page, $page_size);
        $category = $this->listingHelper->getCategory($categoryid);
        $res["category_name"] = $category->getName();
        $res["total"] = $collection->getSize();
        $res["data"] = $this->listingHelper->getProductData($collection, $baseCurrencyCode, $currentCurrencyCode);
        $this->_logger->debug(var_export($res, 1));
        return $res;
    }

    /*======================== Get Filter Product Collection =======================*/

    function ws_newfilter(
        $store_id, $service, $categoryid, $curr_page, $page_size,
        $sortType, $sortOrder, $currentCurrencyCode, $filterdata
    )
    {
        $res = [];
        if ($sortType == null || $sortType == 'null') {
            $sortType = 'name';
        }
        if ($sortOrder == null || $sortOrder == 'null') {
            $sortOrder = 'ASC';
        }
        if ($curr_page == null || $curr_page == 'null') {
            $curr_page = 1;
        }
        if ($page_size == null || $page_size == 'null') {
            $page_size = 10;
        }
        $product_listing = 'listing';
        $this->_storeManager->setCurrentStore($store_id);
        $baseCurrencyCode = $this->_storeManager->getStore()->getBaseCurrencyCode();
        $collection = $this->listingHelper->getNewFilters($categoryid, $store_id, $product_listing);

        //For showing out of stock products at last
        $collection->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')->setOrder('inventory_in_stock', 'desc');

        $collection->addStoreFilter($store_id)->addAttributeToSort($sortType, $sortOrder)->addAttributeToSort('entity_id','asc');

        $collection->setPage($curr_page, $page_size);
        $category = $this->listingHelper->getCategory($categoryid);
        $res["category_name"] = $category->getName();
        $res["total"] = $collection->getSize();
        $res["data"] = $this->listingHelper->getProductData($collection, $baseCurrencyCode, $currentCurrencyCode);
        return $res;
    }

    /*===============================================================================*/

    /*===================== OnsaleProduct Webservice ============ */
    public function ws_onSale($store,$currentcurrencycode,$sortType,$sortOrder,$page_size,$curr_page)
    {
       $res = [];

        if ($sortType == null || $sortType == 'null') {
            $sortType = 'created_at';
        }
        if ($sortOrder == null || $sortOrder == 'null') {
            $sortOrder = 'DESC';
        }
        if ($curr_page == null || $curr_page == 'null') {
            $curr_page = 1;
        }
        if ($page_size == null || $page_size == 'null') {
            $page_size = 20;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $start_date = $objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime')->date(null, '0:0:0');
        $end_date = $objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime')->date(null, '23:59:59');
        $this->_storeManager->setCurrentStore($store);
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->_storeManager->getStore();
        $basecurrencycode = $store->getBaseCurrencyCode();
        $rootcateid = $store->getRootCategoryId();
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->listingHelper->getCategory($rootcateid);
        $collection = $this->listingHelper->getProductCollection();
        $collection->addStoreFilter($store)
            ->addCategoryFilter($category)
            ->addAttributeToFilter(
                'special_from_date',
                ['date' => true, 'to' => $end_date], 'left'
            )->addAttributeToFilter(
                'special_to_date', ['or' => [0 => ['date' => true,
                                                   'from' => $start_date],
                                             1 => ['is' => new \Zend_Db_Expr(
                                                 'null'
                                             )],]], 'left'
            )->addAttributeToSort($sortType,$sortOrder);

        $this->listingHelper->setStockFilter($collection);

        $collection->setPage($curr_page, $page_size);

        $res['products_list'] = $this->listingHelper->getProductData(
            $collection, $basecurrencycode, $currentcurrencycode, 'short'
        );

        if (count($res['products_list'])) {
            $res["status"][0] = [
                'Show_Status' => "1"
            ];
        } else {
            $res["status"][0] = [
                'Show_Status' => "0"
            ];
        }
        return ($res);
    }
   /*============================================================= */
  
   /*======================= Top Categories ===================== */
    public function ws_whatTrending($cat_count,$daysAgo) {
       
        if ($cat_count == null || $cat_count == 'null') {
            $cat_count = 4;
        }
        if ($daysAgo == null || $daysAgo == 'null') {
            $daysAgo = 30;
        }
       
        $res = [];
        $counter = 0;
        $cardColors = [
         "#F44336",
         "#2979FF",
         "#00C853",
         "#F50057",
         "#FF3D00",
         "#00BFA5",
         "#7C4DFF",
         "#AA00FF",
         "#536DFE"
        ];
        $storeId = $this->_storeManager->getStore()->getId();
        $storeId = $storeId ? $storeId : 1;
        $orderItems = $this->_orderItemCollectionFactory->create();
        $salesOrderTableName = $orderItems->getResource()->getTable('sales_order');
        $categoryProductIndexTableName = $orderItems->getResource()->getTable('catalog_category_product_index');
        $categoryFlatStoreTableName = $orderItems->getResource()->getTable("catalog_category_flat_store_${storeId}");
        $salesOrderSubquery = new \Zend_Db_Expr("(select status, entity_id, created_at from ${salesOrderTableName})");
        $categoryProductIndexSubquery = new \Zend_Db_Expr("(select product_id, category_id from ${categoryProductIndexTableName})");
        $categoryFlatStoreSubquery = new \Zend_Db_Expr("(select entity_id, level, children_count, include_in_menu, is_active, url_path from ${categoryFlatStoreTableName})");
        $mainSubquery = new \Zend_Db_Expr( '('. $orderItems->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([
                'category_id' => 'category_product_index.category_id',
                'category_url_path' => 'category_flat.url_path',
                'total_qty_ordered' => 'SUM(main_table.qty_ordered)',
                'day_ordered' => 'DATE(sales_order.created_at)'
            ])
            ->joinLeft(
                ['sales_order' => $salesOrderSubquery],
                'sales_order.entity_id = main_table.order_id',
                []
            )
            ->joinLeft(
                ['category_product_index' => $categoryProductIndexSubquery],
                'category_product_index.product_id = main_table.product_id',
                []
            )
            ->joinRight(
                ['category_flat' => $categoryFlatStoreSubquery],
                'category_flat.entity_id = category_product_index.category_id && category_flat.level > 0 && category_flat.children_count = 0 && category_flat.include_in_menu = 1 && category_flat.is_active = 1 && url_path IS NOT NULL',
                []
            )
            ->where("sales_order.created_at between NOW() - interval {$daysAgo} day and NOW() && category_id is not null")
            ->group(['day_ordered', 'category_id'])
                  ->order(['category_id', 'day_ordered DESC']) . ')');

        $orderItems->getSelect()
            ->reset()
            ->from(
                ['result' => $mainSubquery],
                [
                    'result.category_id',
                    'CONCAT(\'{\', GROUP_CONCAT(\'"\', result.day_ordered, \'": \', result.total_qty_ordered), \'}\') as sale_qtys'
                ]
            )
            ->group(['result.category_id'])
            ->limit($cat_count);
        $response = $orderItems->getData();

        for($i = 0; $i < count($response); $i++) {
            $salesQtys = json_decode($response[$i]['sale_qtys'], true);
            $salesQtys = array_values($this->_fillEmptyDates($salesQtys,$daysAgo));
            $response[$i]['sale_qtys'] = $salesQtys;
            $response[$i]['rzscore'] = $this->rollingZScore($salesQtys, [$salesQtys[0]]);
        }

        usort($response, function($a, $b){
            return $a['rzscore'] < $b['rzscore'];
        });

        foreach($response as $category)
        {
            $color_counter = $counter % 9;
            $cat = $this->listingHelper->getCategory($category['category_id']);
            $res['category_list'][$counter]['id'] = $category['category_id'];
            $res['category_list'][$counter]['name'] = $cat->getName();
            $res['category_list'][$counter]['url'] = $cat->getUrl();
            $res['category_list'][$counter]['image'] = $cat->getImageUrl();
            $res['category_list'][$counter]['color'] = $cardColors[$color_counter];
            $counter++;
        } 
        
        if (count($res)) {
            $res["status"][0] = [
                'Show_Status' => "1"
            ];
        } else {
            $res["status"][0] = [
                'Show_Status' => "0"
            ];
        }
        
        return ($res);
   }

   public function _fillEmptyDates($arr,$daysAgo) {
        $endDate = new \DateTime(date('Y-m-d', strtotime("tomorrow")));
        $beginDate = new \DateTime(date('Y-m-d', strtotime("-{$daysAgo} days")));
        $dateInterval = new \DateInterval('P1D');
        $datePeriod = new \DatePeriod($beginDate, $dateInterval, $endDate);

        foreach($datePeriod as $day) {
            $date = $day->format('Y-m-d');
            if(!isset($arr[$date])) {
                $arr[$date] = 0;
            }
        }
        krsort($arr);
        return $arr;
    }
  
    public function rollingZScore($data, $observedWindow, $decay = 0.9) {
        $avg = $data[0];
        $squaredAverage = pow($data[0], 2);

        $_addToHistory = function($point, $average, $sqAverage, $decay) {
            $average = $average * $decay + $point * (1 - $decay);
            $sqAverage = $sqAverage * $decay + (pow($point, 2)) * (1 - $decay);
            return [
                "average" => $average,
                "sqAverage" => $sqAverage
            ];
        };

        $zscore = function($average, $sqAverage, $value) {
            $std = sqrt($sqAverage - pow($average, 2));
            if($std == 0) {
                return $value - $average;
            }

            return ($value - $average) / $std;
        };

        for($i = 1; $i < count($data); $i++) {
            $history = $_addToHistory($data[$i], $avg, $squaredAverage, $decay);
            $avg = $history["average"];
            $squaredAverage = $history["sqAverage"];
        }

        $trends = [];

        foreach($observedWindow as $point) {
            $trends[] = $zscore($avg, $squaredAverage, $point);
            $history = $_addToHistory($point, $avg, $squaredAverage, $decay);
                   $avg = $history["average"];
            $squaredAverage = $history["sqAverage"];
        }

        $score = count($trends) > 0 ? array_sum($trends) / count($trends) : 0;

        return $score;
    }

   /*=========================================================== */
    /*====================== stripe payment ======================  */

    public function ws_getcategoryfilter($store, $categoryid)
    {
        $this->_storeManager->setCurrentStore($store);

        $categoryid = $categoryid == -1 ? $this->_storeManager->getStore()->getRootCategoryId() : $categoryid;

        return $this->listingHelper->getFilters($categoryid);
    }

    /*=========================================================== */
    /*====================== New Category Filter Collection ======================  */

    public function ws_getNewcategoryfilter($store, $categoryid)
    {
        return $this->listingHelper->getNewFilters($categoryid,$store);
    }


    /*====================== stripe payment End ======================  */
    /*====================== stripe payment card ======================  */

    public function getProductStock1($store_id, $service, $product_id)
    {
        $res = [];
        $i = 0;
        $product = explode(",", $product_id);
        foreach ($product as $productkey => $productvalue) {
            $stock_data = [];
            $stock_product = $this->stock->getStockItem($productvalue);
            $stock_data = $stock_product->getData();
            $res[$i] = array("Product id" => $stock_data['product_id'],
                "Quantity" => $stock_data['qty'],
            );
            $i++;
        }
        return $res;
    }

    /*====================== stripe payment card End ======================  */
    /*====================== stripe customer create ======================  */

    function mofluid_register_push($store, $deviceid, $pushtoken, $platform, $appname, $description)
    {
        $res = [];
        $connection = $this->_resource->getConnection();
        $mofluid_push = $connection->getTableName('sm7h_mofluidpush');
        //print_r($mofluid_push); die;
        try {
            $readresult = "SELECT * FROM  " . $mofluid_push . " WHERE device_id = '" . $deviceid . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'";
            $row = $connection->fetchAll($readresult);
            $readresult2 = "SELECT * FROM  " . $mofluid_push . " WHERE device_id = '" . $pushtoken . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'";
            $row2 = $connection->fetchAll($readresult2);    //print_r($row); die;
            if (count($row) > 0 && $row[0]['device_id']) {//print_r($row); die;
                $connection->query("DELETE FROM  " . $mofluid_push . " WHERE device_id = '" . $deviceid . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'");
                $connection->query("INSERT INTO " . $mofluid_push . "(mofluidadmin_id, device_id, push_token_id, platform, app_name, description) 
            		VALUES (1,'" . $deviceid . "','" . $pushtoken . "','" . $platform . "','" . $appname . "','" . $description . "')");
                $res = array(
                    "status" => "update",
                    "deviceid" => $deviceid,
                    "pushtoken" => $pushtoken,
                    "message" => "Update token for the existing device id."
                ); //print_r($res); die;
            } else if (count($row) > 0 && $row2[0]["push_token_id"]) {
                $connection->query("DELETE FROM  " . $mofluid_push . " WHERE push_token_id = '" . $pushtoken . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'");
                $connection->query("INSERT INTO " . $mofluid_push . " (mofluidadmin_id, device_id, push_token_id, platform, app_name, description) 
            		VALUES (1,'" . $deviceid . "','" . $pushtoken . "','" . $platform . "','" . $appname . "','" . $description . "')");
                $res = array(
                    "status" => "update",
                    "deviceid" => $deviceid,
                    "pushtoken" => $pushtoken,
                    "message" => "Update Device for the existing token id."
                );
            } else {
                $connection->query("INSERT INTO " . $mofluid_push . " (mofluidadmin_id, device_id, push_token_id, platform, app_name, description) 
            		VALUES (1,'" . $deviceid . "','" . $pushtoken . "','" . $platform . "','" . $appname . "','" . $description . "')");
                $res = array(
                    "status" => "register",
                    "deviceid" => $deviceid,
                    "pushtoken" => $pushtoken,
                    "message" => "register device id with new token."
                );
                //print_r($res); die;
            }
        } catch (\Exception $ex) {
            $res = array(
                "status" => "error",
                "deviceid" => $deviceid,
                "pushtoken" => $pushtoken,
                "message" => $ex->getMessage()
            );
        }
        return $res;
    }

    /*======================  stripe customer create  End ======================  */
    /*====================== stripe payment Update ======================  */

    //~ public function ws_retrieveCustomerStripe($customer_id)
    //~ {
        //~ $stripeData = $this->getStripeKey();
        //~ $apiKey = $stripeData[0]['payment_method_account_key'];
        //~ try {
            //~ $customer = \Stripe\Customer::retrieve($customer_id, $apiKey);
            //~ return ($customer);
        //~ } catch (\Exception $e) {
            //~ return $e;
        //~ }

    //~ }

    //~ public function ws_createCardStripe($customer_id, $token_id)
    //~ {
        //~ $stripeData = $this->getStripeKey();
        //~ $apiKey = $stripeData[0]['payment_method_account_key'];
        //~ \Stripe\Stripe::setApiKey($stripeData[0]['payment_method_account_key']);
        //~ try {
            //~ $customer = \Stripe\Customer::retrieve($customer_id, $apiKey);

            //~ if ($customer) {
                //~ $customer = $customer->sources->create(array("source" => $token_id));
            //~ }
            //~ return $customer;
        //~ } catch (\Stripe\Error\Card $e) {
            //~ // return $e;
            //~ $body = $e->getJsonBody();
            //~ $err = $body['error'];
            //~ return $err;
        //~ }
    //~ }

    //~ public function stripecustomercreate($mofluid_Custid, $token_id, $email, $name)
    //~ {


        //~ $stripeData = $this->getStripeKey();

        //~ if ($mofluid_Custid != 0) {
            //~ $customerData = $this->customerRepository->getById($mofluid_Custid);
        //~ }

        //~ \Stripe\Stripe::setApiKey($stripeData[0]['payment_method_account_key']);

        //~ $data = [];
        //~ if (!empty($name) && $name != null)
            //~ $data['description'] = $name;
        //~ if (!empty($email) && $email != null)
            //~ $data['email'] = $email;
        //~ if (!empty($token_id) && $token_id != null)
            //~ $data['source'] = $token_id;
        //~ try {
            //~ $customer = \Stripe\Customer::create($data);
            //~ $stripeCus = json_decode(json_encode($customer));

            //~ if ($mofluid_Custid != 0 && $stripeCus->id != null && !empty($stripeCus->id)) {
     //           $customerData->setMdStripeCustomerId($stripeCus->id);
     //           $customerData->save();
                //~ $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                //~ $model = $objectManager->create('Magedelight\Stripe\Model\Cards');
                //~ $data = ['customer_id' => $mofluid_Custid, 'stripe_customer_id' => $stripeCus->id];
                //~ $model->setData($data);
                //~ $model->save();
            //~ }

            //~ return ($customer);
        //~ } catch (\Stripe\Error\Card $e) {
            //~ $body = $e->getJsonBody();
            //~ $err = $body['error'];
            //~ return $err;
        //~ }
    //~ }

    //~ public function ws_customerUpdateStripe($customer_id, $description)
    //~ {
        //~ $stripeData = $this->getStripeKey();

        //~ $apiKey = $stripeData[0]['payment_method_account_key'];

        //~ try {
            //~ \Stripe\Stripe::setApiKey($apiKey);

            //~ $customer = \Stripe\Customer::retrieve($customer_id, $apiKey);
            //~ $customer->description = $description;
            //~ //$customer->source = $token;
            //~ $customer->save();
            //~ return $customer;
        //~ } catch (\Exception $e) {
            //~ return $e;
        //~ }
    //~ }
    /*====================== stripe payment  ======================  */

    /**
     * Update user namme
     * @param int|string $store
     * @param int|string $custid
     * @param int|string $firstname
     * @param int|string $lastname
     * @param int|string $store
     */
	public function ws_updateUserName($store,$custid, $firstname, $lastname)
	{
		$res = [];
		try{
			$customer = $this->customerRepository->getById($custid);
			if($firstname != '' || $firstname != null)
			{
			 $firstname = base64_decode($firstname);
			 $customer->setFirstname($firstname);
			}
			if($lastname != '' || $lastname != null)
			{
			 $lastname = base64_decode($lastname);
			 $customer->setLastname($lastname);
			}
			$this->customerRepository->save($customer);
			
			$customer = $this->customerRepository->getById($custid);
			$res['status'] 		= 'success';
			$res['custid'] 		= $custid;
			$res['firstname'] 	= $customer->getFirstname();
			$res['lastname'] 	= $customer->getLastname();
		}catch(\Exception $e){
			$res['status'] 	= 'error';
			$res['message']	=  $e->getMessage();
		}
		return $res;
	}

	/*========================== addNewAddress of Customer ===========================*/

	public function ws_addNewAddress($store,$custid,$address)
	{
	    $res = [];
	    $Address = json_decode(base64_decode($address));
        $region = $this->_region->loadByCode($Address->state, $Address->country);
        if (!$region->getId())
            $this->_region->loadByName($Address->state, $Address->country);
        try{
        $_address = array(
                    'firstname' => $Address->fname,
                    'lastname' => $Address->lname,
                    'street' => array(
                        '0' => $Address->street1,
                        '1' => (isset($Address->street2) ? $Address->street2 : '')
                    ),
                    'city' => $Address->city,
                    'region_id' => $region->getId() ?: null,
                    'region' => $region->getName() ?: $Address->state,
                    'postcode' => $Address->postcode,
                    'country_id' => $Address->country,
                    'telephone' => $Address->phone
        );

        $saveaddress = $this->_addressFactory->create();
        $saveaddress->setData($_address);
        $saveaddress->setCustomerId($custid);

        if($Address->is_defaultbilling == 1 || $Address->is_defaultbilling == 'true')
            $saveaddress->setIsDefaultBilling('1');

        if($Address->is_defaultshipping == 1 || $Address->is_defaultshipping == 'true')
            $saveaddress->setIsDefaultShipping('1');

        $saveaddress->setSaveInAddressBook('1');

        $saveaddress->save();
		$res['status'] 		= 'success';
		$res['custid'] 		= $custid;
        }
        catch (Exception $e) {
            $res['status'] 	= 'error';
			$res['message']	=  $e->getMessage();
			return $res;
        }
        return $res;

	}
	/*================================================================================*/
	/*========================== GetAllAddress of Customer ===========================*/
	public function ws_getAllAddress($store,$custid)
	{
	    $res = [];
	    $customeraddress = [];
	    $i = 0;
	    try{
	        $customer = $this->customerRepository->getById($custid);
	        $defaultbilling  = $customer->getDefaultBilling();
	        $defaultshipping = $customer->getDefaultShipping();
	        $addresses = $customer->getAddresses();
            foreach($addresses as $address)
            {
               //$address = $this->_addressRepository->getById($add->getId());
               //$region = $this->_region->load($address->getRegionId());
               //$countryname = $this->_country->load($address->getCountryId())->getName();
               $streetAdd = $address->getStreet();

               $customeraddress[$i]['id']        = $address->getId();
               $customeraddress[$i]['firstname'] = $address->getFirstName();
               $customeraddress[$i]['middlename']= $address->getMiddleName();
               $customeraddress[$i]['lastname']  = $address->getLastName();
               $customeraddress[$i]['street']    = implode(" ",$streetAdd);
               $customeraddress[$i]['city']      = $address->getCity();
               $customeraddress[$i]['region']    = $address->getRegion()->getRegion();//$region->getName();
               $customeraddress[$i]['region_id'] = $address->getRegionId();
               $customeraddress[$i]['countryid'] = $address->getCountryId();
               $customeraddress[$i]['contactno'] = $address->getTelephone();
               $customeraddress[$i]['pincode']   = $address->getPostcode();
               $customeraddress[$i]['is_defaultbilling']  = 0;
               $customeraddress[$i]['is_defaultshipping'] = 0;
               if($address->getId() == $defaultbilling)
                  $customeraddress[$i]['is_defaultbilling']  = 1;
               if($address->getId() == $defaultshipping)
                  $customeraddress[$i]['is_defaultshipping'] = 1;

               $i++;
            }
           }
        catch(\Exception $e) {
	        $res['error']   = $e->getMessage();
	        $res['address'] = '';
	        return $res;
	       }
        $res["address"] = $customeraddress;
        return $res;
    }
	/*================================================================================*/
	/*========================== deleteAddress of Customer ===========================*/
	public function ws_deleteAddress($store,$addressId,$custid)
	{
	    $res = [];
	    $customeraddress = [];
	    $res['status'] = 1;
	    $i = 0;
	    try
	    {
	       $customer = $this->customerRepository->getById($custid);
	       $addresses = $customer->getAddresses();
	       foreach($addresses as $add)
           {
              $customeraddress[$i] = $add->getId();
              $i++;
           }
           if(in_array($addressId,$customeraddress))
           {
	          $this->_addressRepository->deleteById($addressId);
	       }
	       else
	       {
	          $res['status'] = 0;
	          $res['error'] = 'Address id for the customer id passed does not exist';
	       }
	    }
	    catch(\Exception $e)
	    {
	       $res['status'] = 0;
	       $res['error'] = $e->getMessage();
	       return $res;
	    }
	    return $res;
	}
	/*================================================================================*/
    /*========================== updateAddress of Customer ===========================*/
	public function ws_updateAddress($store,$addressId,$custid,$address)
	{
	    $res = [];
	    $street = [];
	    $customeraddress = [];
	    $i = 0;
	    $res['status'] = 1;
	    $Address = json_decode(base64_decode($address));
	    try{
	       $customer = $this->customerRepository->getById($custid);
	       $addresses = $customer->getAddresses();
	       foreach($addresses as $add)
           {
             $customeraddress[$i] = $add->getId();
             $i++;
           }
           if(in_array($addressId,$customeraddress))
           {
	          $set_address = $this->_addressRepository->getById($addressId);
	          $region = $this->_region->loadByCode($Address->state, $Address->country);
	          if (!$region->getId())
                  $this->_region->loadByName($Address->state, $Address->country);
              $region_id = $region->getId()? $region->getId(): null;
	          $street[0] = $Address->street1;
	          if($Address->street2)
	          $street[1] = $Address->street2;

	          $set_address->setCustomerId($custid)
	                   ->setFirstname($Address->fname)
                	   ->setLastname($Address->lname)
	                   ->setCountryId($Address->country)
	                   ->setPostcode($Address->postcode)
	                   ->setCity($Address->city)
	                   ->setTelephone($Address->phone)
	                   ->setStreet($street);
	         if($region_id)
	         {          
	            $set_address->setRegionId($region_id);
	         }
	         else if(!$region_id && $Address->country == 'US')
	         {
	            $res['status'] = 0;
	            $res['error']  = "Please Make Sure The State Lies Within US Territory";
	            return $res;
	         }  
	         else 
	         {
	            $region_name = $this->_regionInterface->setRegion($Address->state);    
	            $set_address->setRegion($region_name);
	            $set_address->setRegionId(0);
	         }
	            
	         if($Address->is_defaultbilling == 1 || $Address->is_defaultbilling == 'true')
                $set_address->setIsDefaultBilling('1');

             if($Address->is_defaultshipping == 1 || $Address->is_defaultshipping == 'true')
                $set_address->setIsDefaultShipping('1');
	         $this->_addressRepository->save($set_address);
	       }
	       else
	       {
	         $res['status'] = 0;
	         $res['error'] = 'Address id for the customer id passed does not exist';
	       }
	     }
	     catch(\Exception $e) {
	       $res['status'] = 0;
	       $res['error']  = $e->getMessage();
	       return $res;
	     }
	     return $res;
    }
    /*================================================================================*/
    /*=============================== Hot Search Api =================================*/

    public function ws_hotsearches($store)
    {
        $res = [];
        $hot_defaultresult = $this->scopeConfig->getValue('searchautocomplete/popular/default');
        $hot_defaultresult = array_filter(array_map('trim', explode(',', $hot_defaultresult)));
   		$hot_limit = intval($this->scopeConfig->getValue('searchautocomplete/popular/limit'));

   		if (!count($hot_defaultresult)) {
            $hot_ignoredresult = $this->scopeConfig->getValue('searchautocomplete/popular/ignored');
            $ignored = array_filter(array_map('strtolower', array_map('trim', explode(',', $hot_ignoredresult))));

            $collection = $this->queryCollectionFactory->create()
                ->setPopularQueryFilter()
                ->setPageSize($hot_limit);

            /** @var \Magento\Search\Model\Query $query */
            foreach ($collection as $query) {
                $text = $query->getQueryText();
                if (!$text) {
                    //old magento 2
                    $text = $query->getName();
                }
                $isIgnored = false;
                foreach ($ignored as $word) {
                    if (strpos(strtolower($text), $word) !== false) {
                        $isIgnored = true;
                        break;
                    }
                }

                if (!$isIgnored) {
                    $hot_defaultresult[] = $text;
                }
            }
        }

        $hot_defaultresult = array_map('ucfirst', $hot_defaultresult);
        $res["hotsearches"] = $hot_defaultresult;
        return $res;
    }

    /*================================================================================*/
    /*======================== Get Wishlist Item API =================================*/

    public function ws_getWishlist($store,$custid,$currentcurrencycode){
        $res = array();
        $basecurrencycode   = $this->_storeManager->getStore($store)->getBaseCurrencyCode();
        $wish               = $this->wishlist->loadByCustomerId($custid);
        $collection         = $wish->getItemCollection();
        try{
            if($collection->getSize()){
                $res['total']    = $collection->getSize();
                $res['items']    = $this->listingHelper->getWishlistProductData($collection, $basecurrencycode, $currentcurrencycode);
                $res['wishlist'] = $wish->getData();
            }else{
                 $res['total']    = 0;
                 $res['items']    = array();
            }
        }
        catch(\Exception $e) {
           $res['total'] = 0;
           $res['items'] = "";
           $res['error'] = $e->getMessage();
         }

        return $res;
    }

    /*================================================================================*/
    /*======================== API : Add item to wishlist ============================*/
    
    public function ws_addToWishlist($store,$custid,$productid,$currentcurrencycode){
        $res = array();
        $basecurrencycode   = $this->_storeManager->getStore($store)->getBaseCurrencyCode();
        try{
            $wishlist  = $this->wishlist->loadByCustomerId($custid);
            $product   = $this->listingHelper->getProduct($productid);
            $customer  = $this->customerRepository->getById($custid);
            
            if(!$wishlist->getData('wishlist_id')){
                $wishlist = $this->wishlistRepository->create()->loadByCustomerId($custid, true);
            }
            if($product->getId() && $customer->getId()){
                $wishlist->addNewItem($product);
                $wishlist->save();
                $res['status'] = 'success';
                $res['message'] = __('%1$s has been added to your wishlist.', $product->getName());

                // return wishlist details and total count    
                $wishlist        = $this->wishlist->loadByCustomerId($custid);
                $collection      = $wishlist->getItemCollection();
                $res['total']    = $collection->getSize();
                //$res['items']    = $this->listingHelper->getWishlistProductData($collection, $basecurrencycode, $currentcurrencycode);
                //$res['wishlist'] = $wishlist->getData();
            }else{
                $res['status'] = 'error';
                $res['message'] = __('An error occurred while adding item to wishlist.');
            }
        }catch(\Exception $e){
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
        }
        return $res;
    }   

    /*================================================================================*/
    /*======================== API : Remove item from wishlist =======================*/
    
    public function ws_removeFromWishlist($store,$custid,$productid,$currentcurrencycode){
        $res = array();
        $basecurrencycode   = $this->_storeManager->getStore($store)->getBaseCurrencyCode();
        try{
            $removeStatus = 0;
            $wishlist  = $this->wishlist->loadByCustomerId($custid);
            $product   = $this->listingHelper->getProduct($productid);
            $customer  = $this->customerRepository->getById($custid);
            if($product->getId() && $customer->getId()){
                $collection      = $wishlist->getItemCollection();
                foreach ($collection as $item) {
                    if ($item->getProductId() == $productid) {
                        $item->delete();
                        $wishlist->save();
                        $removeStatus = 1;
                        $res['status'] = 'success';
                        $res['message'] = __('%1$s has been removed from your wishlist.', $product->getName());                   
                    }
                }
                if($removeStatus == 1){
                    $wishlist        = $this->wishlist->loadByCustomerId($custid);
                    $collection      = $wishlist->getItemCollection();
                    $res['total']    = $collection->getSize();
                }else{
                    $res['status'] = 'error';
                    $res['message'] = __('Product not found in the wislist.');
                }
            }else{
                $res['status'] = 'error';
                $res['message'] = __('An error occurred while removing item from wishlist.');
            }
        }catch(\Exception $e){
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
        }
        return $res;
    }

    /*================================================================================*/
    /*==================== API : Get CMS Block Data by blockId =======================*/

    public function ws_getCmsBlockData($store,$blockid){
        $res = [];
        try{
            if(!$blockid){
                $blockid = 10; // "Main Promotion" block id for content of site header.
            }
            $block = $this->blockData->load($blockid);
            if($block->getBlockId()){
                $res['status']      = 'success';
                $res["blockid"]     = $block->getBlockId();
                $res["title"]       = $block->getTitle();
                $res["identifier"]  = $block->getIdentifier();
                //$res["content"]     = $block->getContent();
                $res["content"] = $this->_pagefilter->getBlockFilter()->setStoreId($store)->filter($block->getContent());
            }else{
                $res['status']  = 'error';
                $res['message'] = "CMS Block not found of the block given Id.";
            }
        }catch(\Exception $e){
            $res['status']  = 'error';
            $res['message'] = $e->getMessage();
        }
        return ($res);
    }

    /*================================================================================*/
    /*=============== API : Add Product notification for price drop ==================*/

    public function ws_addProductToPriceDropNotification($store,$custid,$productid,$currentcurrencycode)
    {
        $res = array();
        try{
            $product    = $this->listingHelper->getProduct($productid);
            $customer   = $this->customerRepository->getById($custid);
            
            if($product->getId() && $customer->getId()){
                
                $alertModel = $this->proAlertData->setCustomerId($custid)
                                ->setProductId($product->getId())
                                ->setPrice($product->getFinalPrice())
                                ->setWebsiteId($this->_storeManager->getStore($store)->getWebsiteId());
                $alertModel->save();
                
                if($alertModel->getData('alert_price_id')){
                    $res['status']  = 'success';
                    $res['message'] = __('You saved the alert subscription.');
                    $res['data']    = $alertModel->getData();
                }else{
                    $res['status']  = 'error';
                    $res['message'] = __('An error occurred while updating the alert subscription.');
                 }
            
            }else{
                $res['status']  = 'error';
                $res['message'] = __('There are not enough parameters.');
            }
        }catch(\Exception $e){
            $res['status']  = 'error';
            $res['message'] = $e->getMessage();           
        }
        return $res;
    }
    /*================================================================================*/   
    /*============================== Cartsync Api ====================================*/
    public function ws_getAllCartItems($store,$customer_id,$currentcurrencycode)
    {
		$res = [];
		$products = [];
		$count = 0;
		try {
		$websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
        $customer = $this->customerRepository->getById($customer_id, $websiteId);
        $quote = $this->quoteFactory->create()->loadByCustomer($customer);
        if($quote)
        {
	      $res["quote_id"] = $quote->getId();
	      $items=$quote->getAllItems();
          foreach($items as $item)
          {
			  $products[$count] = $this->ws_productdetailDescription($store, 'productdetailDescription', $item->getProductId(), $currentcurrencycode);
			  $products[$count]['parent_item_id'] = $item->getParentItemId();
	          $products[$count]['cart_item_id'] = $item->getId();
	          $products[$count]['cart_quantity'] = $item->getQty();
	          $products[$count]['cart_finalprice'] = $item->getPrice();
	          $count++;
	      }
	      $res["cart_items_count"] = $quote->getItemsCount();
		  $res["cart_items_qty"] = $quote->getItemsQty();
		  $res["cart_total_amount"] = $quote->getSubtotal();   
      
	    }
	    else {
		  echo "no quote is present";
		}
	    
	    }
	    catch(\Exception $e)
	    {
		  $res['status'] = "error";	
		  $res['message'] = $e->getMessage();
		}
		$res["cart_products"] = $products;
		return $res;
	}
    /*================================================================================*/
    /*============================== AddCartProduct Api ====================================*/
    public function ws_addCartProduct($store,$customer_id,$products)
    {
		$res = [];
		try
		{
		 $websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
         $customer = $this->customerRepository->getById($customer_id, $websiteId);
         $quote = $this->quoteFactory->create()->loadByCustomer($customer);	
		 $products = str_replace(" ", "+", $products);
         $orderproduct = json_decode(base64_decode($products),true);
         foreach($orderproduct as $productinfo)
	     {
		   $product = $this->listingHelper->getProduct($productinfo['id']);
		   if($productinfo['type'] == "simple")
		   {
			  $quote->addProduct(
                        $product,
                        intval($productinfo['quantity'])
              );
		   }
		   else if ($productinfo['type'] == "configurable")
		   {
	          $requestInfo = array(
              'product' => $productinfo['id'],
              'selected_configurable_option' => $productinfo['selected_configurable_option'],
              'super_attribute' => $productinfo['super_attribute'],
              'qty' => $productinfo['quantity']
              );
              
              $quote->addProduct(
              $product,
              new \Magento\Framework\DataObject($requestInfo)
              );	   
		    }	
		 }
	     $quote->collectTotals()->save();
         //$this->quoteRepository->save($quote);
         $res['status']="success";
	    }
	    catch(\Exception $e)
	    {
		 echo $e->getMessage();
		 $res['status'] = "error";
		 $res['message'] = $e->getMessage();
		}
        return $res;	
		
    }
    /*================================================================================*/
    /*============================== DeleteCartProduct Api ====================================*/
    public function ws_deleteCartProduct($store,$customer_id,$products)
    {
		
		$res = [];
		$websiteId = $this->_storeManager->getStore($store)->getWebsiteId();
		$customer = $this->customerRepository->getById($customer_id, $websiteId);
        $quote = $this->quoteFactory->create()->loadByCustomer($customer);
		try {
		$quoteItem = $this->_quoteitem->load($products);
		$quoteItem->delete();
		$this->quoteRepository->save($quote);
	    }
	    catch(\Exception $e)
	    {
		   $res['status'] = "error";
		}
		$res['status'] = "success";
		return $res;
    }
    /*================================================================================*/

}
