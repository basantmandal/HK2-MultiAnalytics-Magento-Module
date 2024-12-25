<?php

/**
 * Basant Mandal (HK2 - HashTagKitto)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Basant Mandal (HK2 - HashTagKitto) license that is
 * available in this module named LICENSE.txt
 * A copy of license is also avaialble at url - https://www.basantmandal.in/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Basant Mandal (HK2 - HashTagKitto)
 * @package     HK2_MultiAnalytics
 * @copyright   Copyright (c) Basant Mandal (HK2 - HashTagKitto) (https://www.basantmandal.in/) All rights reserved.
 * @license     https://www.basantmandal.in/LICENSE.txt
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

use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Page\Config;

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
     * @var string
     */
    private $folder_name = 'HK2/Analytics/';

    private $file_feed_name = 'feed.xml';
    /**
     * @var httpContext
     */
    private httpContext $_httpContext;

    /**
     * @var Http
     */
    private Http $_httpRequest;

    /**
     * @var Title
     */
    private Title $_pageTitle;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $_storeManager;

    /**
     * @var PriceCurrencyInterface
     */
    private PriceCurrencyInterface $_priceCurrency;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $_scopeConfig;

    /**
     * @var checkoutSession
     */
    private checkoutSession $_checkoutSession;

    /**
     * @var OrderRepository
     */
    private OrderRepository $_orderRepository;

    /**
     * @var ImageFactory
     */
    protected ImageFactory $_imageHelperFactory;

    /**
     * @var Filesystem
     */
    protected Filesystem $_filesystem;

    /**
     * @var Config
     */
    protected Config $_page_config;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_productCollection;

    /**
     * @var File
     */
    protected File $file;

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
     * @param ImageFactory $imageHelperFactory
     * @param Filesystem $filesystem
     * @param Config $pageConfig
     * @param CollectionFactory $productCollection
     * @param File $file
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
        OrderRepository $orderRepository,
        ImageFactory $imageHelperFactory,
        Filesystem $filesystem,
        Config $pageConfig,
        CollectionFactory $productCollection,
        File $file
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_httpContext = $httpContext;
        $this->_httpRequest = $http;
        $this->_pageTitle = $pageTitle;
        $this->_storeManager = $storeManager;
        $this->_priceCurrency = $priceCurrency;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderRepository = $orderRepository;
        $this->_filesystem = $filesystem;
        $this->_page_config = $pageConfig;
        $this->_imageHelperFactory = $imageHelperFactory;
        $this->_productCollection = $productCollection;
        $this->file = $file;
        parent::__construct($context);
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
    public function getPageType(): string
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
    public function getStoreCurrencyCode(): string
    {
        return $this->_storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * Get Store Currency Symbol
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreCurrencySymbol(): string
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

    /**
     * Gets Product Collection
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductCollection()
    {
        /* Get Current Store ID */
        $storeId = $this->getStoreID();
        $collection = false;

        try {
            $collection = $this->_productCollection->create();
            $collection->addAttributeToSelect('*');
            $collection->addStoreFilter($storeId);
            if ($this->isDebugEnabled()) {
                $collection->setPageSize(2);
            }
            $collection->addAttributeToFilter(
                'status',
                \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
            );
        } catch (\Exception $e) {
            if ($this->isDebugEnabled()) {
                echo '<pre> Error at Get Product Collection = ' . $e . '</pre>';
            }
        }

        return $collection;
    }

    /**
     * Gets Product Image
     *
     * @param $product
     * @return mixed
     */
    public function getProductImage($product)
    {
        return $this->_imageHelperFactory->create()->init($product, 'product_small_image')->getUrl();
    }

    /**
     * Get Product Price
     *
     * @param $product
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductPrice($product): string
    {
        return $product->getFinalPrice() . ' ' . $this->getStoreCurrencyCode();
    }

    /**
     * Generates XML
     *
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function generateXml()
    {
        $contents = $this->generate_google_product_feed();
        if ($contents) {
            $storeURL = $this->_storeManager->getStore()->getBaseUrl();
            $storeURL2 = $this->_storeManager->getStore()->getCurrentUrl();
            $_meta_title = $this->_page_config->getTitle()->getShort();
            $_meta_description = (!empty($this->_page_config->getDescription())
                ? $this->make_string_xml_safe($this->_page_config->getDescription())
                : '');
            $storeName = $this->_storeManager->getStore()->getName();
            $title = 'Feed generated for ' . $storeName . ' - ' . $storeURL;
            $xml_content = '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . PHP_EOL;
            $xml_content .= '<channel>' . PHP_EOL;
            $xml_content .= '<title>' . $title . '</title>' . PHP_EOL;
            $xml_content .= '<link>' . $storeURL . '</link>' . PHP_EOL;
            $xml_content .= '<description><![CDATA[ ' . $_meta_description . ']]></description>' . PHP_EOL;
            $xml_content .= $contents . PHP_EOL;
            $xml_content .= '</channel>' . PHP_EOL;
            $xml_content .= '</rss>';
            $media_path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
            $media_path = $media_path . $this->folder_name;

            if ($this->isDebugEnabled()) {
                echo '<pre>';
                echo '<ol>';
                echo '<li> Store URL = ' . $storeURL . '</li>';
                echo '<li> Store URL = ' . $storeURL2 . '</li>';
                echo '<li> Store Meta Title = ' . $_meta_title . '</li>';
                echo '<li> Store Meta Description = ' . $_meta_description . '</li>';
                echo '<li> Store Name = ' . $storeName . '</li>';
                echo '<li> Store Filename = ' . $this->file_feed_name . '</li>';
                echo '</ol>';
                echo '</pre>';
            }

            try {
                $this->file->mkdir($media_path);
                $this->file->write($media_path . $this->file_feed_name, $xml_content);
                $export_file_link = "/pub/media/$this->folder_name$this->file_feed_name";

                return "<a href='$export_file_link' class='action primary' target='_blank'>Click to Download</a>";
            } catch (\Exception $e) {
                if ($this->isDebugEnabled()) {
                    echo '<pre> Error at Generate XML Function = ' . $e . '</pre>';
                }

                return "<a href='#' class='action primary' target='_blank'>Feed Generate Failed</a>";
            }
        } else {
            return "<a href='#' class='action primary' target='_blank'>Feed Generate Failed - No Product </a>";
        }
    }

    /**
     * Generates Item Feed for Google RSS Feed
     *
     * @return false|string|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function generate_google_product_feed()
    {
        $products = false;
        $products_collection = $this->getProductCollection();
        if (!empty($products_collection)) {
            $products = '';
            foreach ($products_collection as $product) {
                $product_sku = $product->getSKU();
                $product_name = (empty($product->getName()) ? $product_sku : $this->make_string_xml_safe($product->getName()));
                $product_description = (empty($product->getDescription())) ? '' : substr(
                    $this->make_string_xml_safe($product->getDescription()),
                    0,
                    500
                );
                $product_link = $product->getProductUrl();
                $product_image = $this->getProductImage($product);
                $product_store_code = $product->getStoreID();
                $product_price = $this->getProductPrice($product);
                $product_availability = ($product->getStatus() == 1) ? 'in stock' : 'out of stock';
                $products .= '<item>' . PHP_EOL;
                $products .= '<g:id>' . $product_sku . '</g:id>' . PHP_EOL;
                $products .= '<g:title>' . $product_name . '</g:title>' . PHP_EOL;
                $products .= '<g:description><![CDATA[' . $product_description . ']]></g:description>' . PHP_EOL;
                $products .= '<g:link><![CDATA[' . $product_link . ']]></g:link>' . PHP_EOL;
                $products .= '<g:image_link><![CDATA[' . $product_image . ']]></g:image_link>' . PHP_EOL;
                $products .= '<g:condition>new</g:condition>' . PHP_EOL;
                $products .= '<g:store_code>' . $product_store_code . '</g:store_code>' . PHP_EOL;
                $products .= '<g:price>' . $product_price . '</g:price>' . PHP_EOL;
                $products .= '<g:availability>' . $product_availability . '</g:availability>' . PHP_EOL;
                $products .= '</item>' . PHP_EOL;
            }
        }

        return $products;
    }

    /**
     * Strips Tags from String
     *
     * @param $string
     * @return string
     */
    public function make_string_xml_safe($string): string
    {
        return !empty($string) ? htmlspecialchars(strip_tags(preg_replace("/[^A-Za-z0-9 ]/", '', $string))) : '';
    }
}
