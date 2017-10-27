<?php

namespace Mofluid\Mofluidapi2\Controller\Index;

use \Mofluid\Mofluidapi2\Helper\Data;
use \Magento\Framework\App\Action\Action;

class Index extends Action
{
    /**
     * JSON response builder
     *
     * @var  \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Mofluid API Helper class
     *
     * @var Data
     */
    protected $helper;

    /**
     * Authentication class
     *
     * @var \Mofluid\Mofluidapi2\Model\IndexFactory
     */
    protected $authFactory;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mofluid\Mofluidapi2\Model\IndexFactory $authFactory
     * @param Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mofluid\Mofluidapi2\Model\IndexFactory $authFactory,
        Data $helper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        $this->authFactory = $authFactory;
        parent::__construct($context);
    }

    /**
     * Validate the request
     *
     * @return bool
     */
    public function validate()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $authappid = $request->getHeader('authappid');
        $token = $request->getHeader('token');
        $secretkey = $request->getHeader('secretkey');

        if (empty($authappid) || $authappid == null) {
            return false;
        }

        if (empty($token) || $token == null) {
            return false;
        }

        if (empty($secretkey) || $secretkey == null) {
            return false;
        }

        $mofluidAuth = $this->authFactory->create()->getCollection()
            ->addFieldToFilter('appid', $authappid)
            ->addFieldToFilter('token', $token)
            ->addFieldToFilter('secretkey', $secretkey)->getData();

        if (count($mofluidAuth) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $request = $this->getRequest();
        $service = $request->getParam("service");
        $resultJson = $this->resultJsonFactory->create();

        // get authenticate token and secret key
        if ($service == 'gettoken') {
            $authappid = $request->getParam("authappid");

            if (empty($authappid) || $authappid == null) {
                return $resultJson->setData(['error' => 'Invalid App id']);
            }

            $mofluidAuth = $this->authFactory->create()->getCollection()
                ->addFieldToFilter('appid', $authappid)->getData();

            if (count($mofluidAuth) > 0) {
                $data = [
                    'appid' => $mofluidAuth[0]['appid'],
                    'token' => $mofluidAuth[0]['token'],
                    'secretkey' => $mofluidAuth[0]['secretkey']
                ];
                return $resultJson->setData($data);
            } else {
                $token = openssl_random_pseudo_bytes(16);
                $token = bin2hex($token);
                $secretKey = md5(uniqid($authappid, TRUE));
                $model = $this->authFactory->create();
                $data = ['appid' => $authappid, 'token' => $token, 'secretkey' => $secretKey];
                $model->setData($data);
                $model->getResource()->save($model);
                return $resultJson->setData($data);
            }
        }

        // get authenticate token and secret key end here

         // if (!$this->validate()) {
         //    return $resultJson->setData(['error'=>'unauthorized']);
         // }

        $store = $request->getParam("store");
        if ($store == null || $store == '') {
            $store = 1;
        }

        $categoryid         = $request->getParam("categoryid");
        $filterdataencode   = $request->getParam("filterdata");
        $filterdata         = base64_decode($filterdataencode);
        $pageId             = $request->getParam("pageId");
        $service            = $request->getParam("service");
        $categoryid         = $request->getParam("categoryid");
        $firstname          = $request->getParam("firstname");
        $lastname           = $request->getParam("lastname");
        $email              = $request->getParam("email");
        $password           = $request->getParam("password");
        $oldpassword        = $request->getParam("oldpassword");
        $newpassword        = $request->getParam("newpassword");
        $productid          = $request->getParam("productid");
        $custid             = $request->getParam("customerid");
        $billAdd            = $request->getParam("billaddress");
        $shippAdd           = $request->getParam("shippaddress");
        $pmethod            = $request->getParam("paymentmethod");
        $smethod            = $request->getParam("shipmethod");
        $transid            = $request->getParam("transactionid");
        $product            = $request->getParam("product");
        $shippCharge        = $request->getParam("shippcharge");
        $search_data        = $request->getParam("search_data");
        $username           = $request->getParam("username");
        // Get Requested Data for Push Notification Request
        $deviceid           = $request->getParam("deviceid");
        $pushtoken          = $request->getParam("pushtoken");
        $platform           = $request->getParam("platform");
        $appname            = $request->getParam("appname");
        $description        = $request->getParam("description");
        $profile            = $request->getParam("profile");
        $paymentgateway     = $request->getParam("paymentgateway");
        $couponCode         = $request->getParam("couponCode");
        $orderid            = $request->getParam("orderid");
        $pid                = $request->getParam("pid");
        $products           = $request->getParam("products");
        $address            = $request->getParam("address");
        $country            = $request->getParam("country");
        $grand_amount       = $request->getParam("grandamount");
        $order_sub_amount   = $request->getParam("subtotal_amount");
        $discount_amount    = $request->getParam("discountamount");
        $mofluidpayaction   = $request->getParam("mofluidpayaction");
        $postdata           = $_POST;
        $mofluid_payment_mode = $request->getParam("mofluid_payment_mode");
        $product_id         = $request->getParam("product_id");
        $gift_message       = $request->getParam("message");
        $mofluid_paymentdata = $request->getParam("mofluid_paymentdata");
        $mofluid_ebs_pgdata = $request->getParam("DR");
        $curr_page          = $request->getParam("currentpage");
        $page_size          = $request->getParam("pagesize");
        $sortType           = $request->getParam("sorttype");
        $sortOrder          = $request->getParam("sortorder");
        $saveaction         = $request->getParam("saveaction");
        $mofluid_orderid_unsecure = $request->getParam("mofluid_order_id");
        $currency           = $request->getParam("currency");
        $price              = $request->getParam("price");
        $from               = $request->getParam("from");
        $to                 = $request->getParam("to");
        $is_create_quote    = $request->getParam("is_create_quote");
        $find_shipping      = $request->getParam("find_shipping");
        $find_payment       = $request->getParam("find_payment");
        $messages           = $request->getParam("messages");
        $theme              = $request->getParam("theme");
        $timeslot           = $request->getParam("timeslot");
        $billshipflag       = $request->getParam("shipbillchoice");
        $customer_id        = $request->getParam("customer_id");
        $apiKey             = $request->getParam("apiKey");
        $token_id           = $request->getParam("token_id");
        $card_id            = $request->getParam("card_id");
        $mofluid_Custid     = $request->getParam("mofluid_Custid");
        $discription        = $request->getParam("discription");
        $cat_count          = $request->getParam("cat_count");
        $daysAgo            = $request->getParam("daysago");
        $status				= $request->getParam("status");
        $name               = base64_decode($request->getParam("name"));
        $addressid          = $request->getParam("addressid");
        $blockid            = $request->getParam("blockid");
        $currency           = 'USD';

        if ($service == "sidecategory") {
            $res = $this->helper->ws_sidecategory($store, $service);
        } elseif ($service == "initial") {
            $res = $this->helper->fetchInitialData($store, $service, $currency);
        } elseif ($service == "category") {
            $res = $this->helper->ws_category($store, $service);
        } elseif ($service == "subcategory") {
            $res = $this->helper->ws_subcategory($store, $service, $categoryid);
        } elseif ($service == "products") {
            $res = $this->helper->ws_products(
                $store, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currency
            );
        } elseif ($service == "productdetaildescription") {
            $res = $this->helper->ws_productdetailDescription($store, $service, $productid, $currency);
        } elseif ($service == "getallCMSPages") {
            $res = $this->helper->getallCMSPages($store, $pageId);
        } else if ($service == "get_configurable_product_details_description") {
            $res = $this->helper->get_configurable_products_description($productid, $currency, $store);
        } elseif ($service == "getFeaturedProducts") {
            $res = $this->helper->ws_getFeaturedProducts($currency, $service, $store);
        } else if ($service == "get_configurable_product_details_image") {
            $res = $this->helper->get_configurable_products_image($productid, $currency);
        } elseif ($service == "getNewProducts") {
            $res = $this->helper->ws_getNewProducts(
                $currency, $service, $store, $curr_page, $page_size, $sortType, $sortOrder
            );
        } else if ($service == "convert_currency") {
            $res = $this->helper->convert_currency($price, $from, $to);
        } elseif ($service == "rootcategory") {
            $res = $this->helper->rootCategoryData($store, $service);
        } elseif ($service == "createuser") {
            $res = $this->helper->ws_createuser($store, $service, $firstname, $lastname, $email, $password);
        } elseif ($service == "myprofile") {
            $res = $this->helper->ws_myProfile($custid);
        } elseif ($service == "mofluidUpdateProfile") {
            $res = $this->helper->mofluidUpdateProfile(
                $store, $service, $custid, $billAdd, $shippAdd, $profile, $billshipflag
            );
        } elseif ($service == "changeprofilepassword") {
            $res = $this->helper->ws_changeProfilePassword($custid, $username, $oldpassword, $newpassword, $store);
        } else if ($service == "mofluidappcountry") {
            $res = $this->helper->ws_mofluidappcountry($store);
        } else if ($service == "mofluidappstates") {
            $res = $this->helper->ws_mofluidappstates($store, $country);
        } elseif ($service == "productdetail") {
            $res = $this->helper->ws_productdetail($store, $service, $productid, $currency);
        } elseif ($service == "register_push") {
            $res = $this->helper->mofluid_register_push(
                $store, $deviceid, $pushtoken, $platform, $appname, $description
            );
        } elseif ($service == "getallCMSPages") {
            $res = $this->helper->getallCMSPages($store, $pageId);
        } elseif ($service == "productinfo") {
            $res = $this->helper->ws_productinfo($store, $productid, $currency);
        } elseif ($service == "productdetailimage") {
            $res = $this->helper->ws_productdetailImage($store, $service, $productid, $currency);
        } elseif ($service == "storedetails") {
            $res = $this->helper->ws_storedetails($store, $service, $theme, $currency);
        } elseif ($service == "verifylogin") {
            $res = $this->helper->ws_verifyLogin($store, $service, $username, $password);
        } elseif ($service == "loginwithsocial") {
            $res = $this->helper->ws_loginwithsocial($store, $username, $firstname, $lastname);
        } elseif ($service == "forgotPassword") {
            $res = $this->helper->ws_forgotPassword($email);
        } elseif ($service == "search") {
            $res = $this->helper->ws_search(
                $store, $service, $search_data, $curr_page, $page_size, $sortType, $sortOrder, $currency
            );
        } else if ($service == "getpaymentmethod") {
            $res = $this->helper->ws_getpaymentmethod();
        } elseif ($service == "productQuantity") {
            $res = $this->helper->ws_productQuantity($product);
        } elseif ($service == "checkout") {
            $res = $this->helper->ws_checkout($store, $service, $theme, $currency);
        } elseif ($service == "myorders") {
            $res = $this->helper->ws_myOrder($custid, $curr_page, $page_size, $store, $currency);
        } elseif ($service == "preparequote") {
            $res = $this->helper->prepareQuote(
                $custid, $products, $store, $address, $smethod, $couponCode,
                $currency, $is_create_quote, $find_shipping, $find_payment
            );
        } elseif ($service == "placeorder") {
            $res = $this->helper->placeorder($custid, $products, $store, $address, $couponCode, $is_create_quote, $transid,
            $pmethod, $smethod, $currency, $messages, $theme, $customer_id, $card_id); //$customer_id is for stripe customerid. 
        } else if ($service == "validate_currency") {
            $res = $this->helper->ws_validatecurrency($store, $service, $currency, $paymentgateway);
        } elseif ($service == "setaddress") {
            $res = $this->helper->ws_setaddress($store, $service, $custid, $address, $email, $saveaction);
        } else if ($service == "mofluid_reorder") {
            $res = $this->helper->ws_mofluid_reorder($store, $service, $pid, $orderid, $currency);
        } else if ($service == "filter") {
            $res = $this->helper->ws_filter(
                $store, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currency, $filterdata
            );
        } elseif ($service == "getcategoryfilter") {
            $res = $this->helper->ws_getcategoryfilter($store, $categoryid);
        } else if ($service == "getProductStock1") {
            $res = $this->helper->getProductStock1($store, $service, $product_id);
        } else if ($service == "retrieveCustomerStripe") {
            $res = $this->helper->ws_retrieveCustomerStripe($customer_id);
        } else if ($service == "createCardStripe") {
            $res = $this->helper->ws_createCardStripe($customer_id, $token_id);
        } else if ($service == "customerUpdateStripe") {
            $res = $this->helper->ws_customerUpdateStripe($customer_id, $discription);
        } else if ($service == "stripecustomercreate") {
            $res = $this->helper->stripecustomercreate($mofluid_Custid, $token_id, $email, $name);
        } else if ($service == "stripeData") {
            $res = $this->helper->stripeData($customer_id);
        } else if ($service == "updateOrder") {
            $res = $this->helper->updateOrder($store,$custid, $orderid, $pmethod, $transid, $status);
        } else if ($service == "updateUserName") {
            $res = $this->helper->ws_updateUserName($store,$custid, $firstname, $lastname);
        } else if ($service == "onSale") {
            $res = $this->helper->ws_onSale($store,$currency,$sortType,$sortOrder,$page_size,$curr_page);
        } else if ($service == "whatTrending") {
            $res = $this->helper->ws_whatTrending($cat_count,$daysAgo);
        } else if ($service == "getNewcategoryfilter") {
            $res = $this->helper->ws_getNewcategoryfilter($store,$categoryid);
        } else if ($service == "newfilter") {
            $res = $this->helper->ws_newfilter($store, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currency, $filterdata); 
        } else if ($service == "addNewAddress") {
            $res = $this->helper->ws_addNewAddress($store,$custid,$address);
        } else if ($service == "getAllAddress") {
            $res = $this->helper->ws_getAllAddress($store,$custid);
        } else if ($service == "deleteAddress") {
            $res = $this->helper->ws_deleteAddress($store,$addressid,$custid);
        } else if ($service == "updateAddress") {
            $res = $this->helper->ws_updateAddress($store,$addressid,$custid,$address);
        } else if ($service == "hotsearches") {
            $res = $this->helper->ws_hotsearches($store);
        }
        /********** sprint 3 API's **********/
        else if ($service == "getWishlist") {
            $res = $this->helper->ws_getWishlist($store,$custid,$currency);
        }else if ($service == "addToWishlist") {
            $res = $this->helper->ws_addToWishlist($store,$custid,$productid,$currency);
        }else if ($service == "removeFromWishlist") {
            $res = $this->helper->ws_removeFromWishlist($store,$custid,$productid,$currency);
        }else if ($service == "getCmsBlockData") {
            $res = $this->helper->ws_getCmsBlockData($store,$blockid);
        }else if ($service == "addProductToPriceDropNotification") {
            $res = $this->helper->ws_addProductToPriceDropNotification($store,$custid,$productid,$currency);
        }else if ($service == "getAllCartItems") {
            $res = $this->helper->ws_getAllCartItems($store,$custid,$currency);
        }else if ($service == "addCartProduct") {
            $res = $this->helper->ws_addCartProduct($store,$custid,$products);
        }else if ($service == "deleteCartProduct") {
            $res = $this->helper->ws_deleteCartProduct($store,$custid,$products);
        }

        

        if (isset($res)) {
            if ($request->getParam('callback')) {
                $res['callback'] = $request->getParam('callback');
            }
            return $resultJson->setData($res);
        } else {
            return $this->service404($service);
        }
    }

    /**
     * Response handler when no store is found.
     *
     * @param int|string $store
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function store404($store)
    {
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData(['error' => 'Store 404 Error :  Store ' . $store . ' is not found on your host ']);
    }

    /**
     * Response handler when the service isn't found.
     *
     * @param string $service
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function service404($service)
    {
        $resultJson = $this->resultJsonFactory->create();
        if ($service == "" || $service == null) {
            return $resultJson->setData(
                ['error' => 'Service 404 Error : No service type was passed to the Mofluid API.']
            );
        } else {
            return $resultJson->setData(
                [
                    'error' =>
                        'Service 404 Error : The \'' . $service .
                        '\' Mofluid API web service was not found.'
                ]
            );
        }
    }
}
