<?php

/**
 * Basant Mandal (HK2 - HashTagKitto)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Basant Mandal (HK2 - HashTagKitto) license that is
 * available in this module named LICENSE.txt
 * A copy of license is also avaialble at url - https://www.hashtagkitto.co.in/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Basant Mandal (HK2 - HashTagKitto)
 * @package     HK2_MultiAnalytics
 * @copyright   Copyright (c) Basant Mandal (HK2 - HashTagKitto) (https://www.hashtagkitto.co.in/) All rights reserved.
 * @license     https://hashtagkitto.co.in/LICENSE.txt
 * @license     LICENSE.txt - Available in this Module Root Folder
 */

declare(strict_types=1);

namespace HK2\MultiAnalytics\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Http\Context as httpContext;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Page\Title;
use Magento\Store\Model\ScopeInterface;

use Magento\Checkout\Model\Session as checkoutSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Model\OrderRepository;

class Data extends AbstractHelper
{
    // Sections & Groups
    private const SECTION01 = 'hk2_multianalytics_section1/';
    private const SECTION01_GROUP01 = 'hk2_multianalytics_section1_group1/';
    private const SECTION01_GROUP02 = 'hk2_multianalytics_section1_group2/';
    private const SECTION01_GROUP03 = 'hk2_multianalytics_section1_group3/';

    // Group 1 Settings
    private const ENABLE_MODULE = self::SECTION01 . self::SECTION01_GROUP01 . 'hk2_analytics_enable';
    private const DEBUG_MODULE = self::SECTION01 . self::SECTION01_GROUP01 . 'hk2_analytics_debug';

    // Group 2 Settings
    private const GTAG_ID = self::SECTION01 . self::SECTION01_GROUP02 . 'hk2_analytics_gtag_id';
    private const GTAG_MANAGER_ID = self::SECTION01 . self::SECTION01_GROUP02 . 'hk2_analytics_gtag_manager_id';
    private const GTAG_DATA_LAYER_ID = self::SECTION01 . self::SECTION01_GROUP02 . 'hk2_analytics_gtag_data_layer_id';

    // Group 3 Settings
    private const FB_DOMAIN_VER_CODE = self::SECTION01 . self::SECTION01_GROUP03 . 'hk2_analytics_fb_domain_veri_code';
    private const FB_PIXEL_CODE = self::SECTION01 . self::SECTION01_GROUP03 . 'hk2_analytics_fb_pixel_code';

    // Content Square Specific
    private const CONTENT_SQUARE_LOGGED_IN = "logged in";
    private const CONTENT_SQUARE_LOGGED_OUT = "logged out";

    // Other Variable Declaration
    /**
     * @var httpContext
     */
    private $_httpContext;

    /**
     * @var Http
     */
    private $_httpRequest;

    /**
     * @var Title
     */
    private $_pageTitle;

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @var PriceCurrencyInterface
     */
    private $_priceCurrency;

    /**
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var checkoutSession
     */
    private $_checkoutSession;

    /**
     * @var OrderRepository
     */
    private $_orderRepository;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param httpContext $httpContext
     * @param Http $http
     * @param Title $pageTitle
     * @param StoreManagerInterface $storeManager
     * @param PriceCurrencyInterface $priceCurrency
     * @param checkoutSession $checkoutSession
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        httpContext $httpContext,
        Http $http,
        Title $pageTitle,
        StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrency,
        checkoutSession $checkoutSession,
        OrderRepository $orderRepository
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
        $this->_httpContext = $httpContext;
        $this->_httpRequest = $http;
        $this->_pageTitle = $pageTitle;
        $this->_storeManager = $storeManager;
        $this->_priceCurrency = $priceCurrency;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderRepository = $orderRepository;
    }

    /**
     * Returns Is Enabled Config Value
     *
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->_scopeConfig->getValue(self::ENABLE_MODULE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns Is Debug Config Value
     *
     * @return mixed
     */
    public function isDebugEnabled()
    {
        return $this->_scopeConfig->getValue(self::DEBUG_MODULE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns Google Tag ID Config Value
     *
     * @return mixed
     */
    public function getGtagID()
    {
        return $this->_scopeConfig->getValue(self::GTAG_ID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns Google Tag Manager ID Config Value
     *
     * @return mixed
     */
    public function getGtagManagerID()
    {
        return $this->_scopeConfig->getValue(self::GTAG_MANAGER_ID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns Google Tag Layer ID Config Value
     *
     * @return mixed
     */
    public function getGtagLayerID()
    {
        return $this->_scopeConfig->getValue(self::GTAG_DATA_LAYER_ID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns Facebook Domain Verification Config Value
     *
     * @return mixed
     */
    public function getFacebookDomainVerificationCode()
    {
        return $this->_scopeConfig->getValue(self::FB_DOMAIN_VER_CODE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns Facebook Pixel Code Config Value
     *
     * @return mixed
     */
    public function getFacebookPixelCode()
    {
        return $this->_scopeConfig->getValue(self::FB_PIXEL_CODE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns Log In Status
     *
     * @return string
     */
    public function isLoggedIn()
    {
        $isLoggedIn = $this->_httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

        return ($isLoggedIn) ? self::CONTENT_SQUARE_LOGGED_IN : self::CONTENT_SQUARE_LOGGED_OUT;
    }

    /**
     * Returns Page Title
     *
     * @return mixed
     */
    public function getPageTitle()
    {
        return $this->_pageTitle->getShort();
    }

    /**
     * Returns Page Type
     *
     * @return string
     */
    public function getPageType()
    {
        return $this->_httpRequest->getFullActionName();
    }

    /**
     * Returns Page Name Assignment for Frontend - ContentSquare Page Naming
     *
     * @return false|string
     */
    public function assignPageType()
    {
        $page_type = $this->getPageType();
        $magento_page_types = [
            'catalog_category_view' => 'Category Page',
            'catalog_product_view' => 'Product Page',
            'catalogsearch_advanced_index' => 'Advanced Search Page',
            'catalogsearch_result_index' => 'Search Result Page',
            'checkout_cart_index' => 'Cart Page',
            'checkout_index_index' => 'Checkout Page',
            'checkout_onepage_success' => 'Confirmation Page',
            'cms_index_index' => 'Home Page',
            'cms_page_view' => 'CMS Page',
            'companylinking_linking_index' => 'Company Linking Page',
            'contact_index_index' => 'Contact Page',
            'customer_account_create' => 'Registration Page',
            'customer_account_edit' => 'Account Edit Page',
            'customer_account_index' => 'My Account Page',
            'customer_account_login' => 'Login Page',
            'customer_address_index' => 'Address Book Page',
            'newsletter_manage_index' => 'Customer Newsletter',
            'quotation_quote_history' => 'My Quote Page',
            'sales_order_history' => 'My Order Page',
            'vault_cards_listaction' => 'Customer Payment Method Page',
            'wishlist_index_index' => 'Wishlist Page',
        ];

        return (array_key_exists($page_type, $magento_page_types)) ? $magento_page_types[$page_type] : false;
    }

    /**
     * Get Store ID
     *
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreID()
    {
        return $this->_storeManager->getStore()->getStoreId();
    }

    /**
     * Get Store Currency Code
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * Get Store Currency Symbol
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreCurrencySymbol()
    {
        $storeID = $this->getStoreID();

        return $this->_priceCurrency->getCurrencySymbol($storeID);
    }

    /**
     * Returns Last Order ID
     *
     * @return mixed
     */
    public function getLastOrderID()
    {
        return $this->_checkoutSession->getData('last_order_id');
    }

    /**
     * Returns Last Order Increment ID
     *
     * @return float|string|null
     */
    public function getLastOrderIncrementID()
    {
        $order = $this->_checkoutSession->getLastRealOrder();

        return $order->getIncrementId();
    }

    /**
     * Returns Session Last Order ID
     *
     * @return mixed
     */
    public function getLastOrderEntityID()
    {
        $order = $this->_checkoutSession->getLastRealOrder();

        return $order->getEntityId();
    }

    /**
     * Returns Order Amount
     *
     * @param $orderID
     * @return float|void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLastOrderAmount($orderID)
    {
        if ($orderID > 0) {
            $orderData = $this->_orderRepository->get($orderID);
            $orderAmount = $orderData->getGrandTotal();

            return (float)$orderAmount;
        }
    }
}
