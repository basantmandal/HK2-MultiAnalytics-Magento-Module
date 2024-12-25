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

namespace HK2\MultiAnalytics\Block;

use HK2\MultiAnalytics\Helper\Data as MultiAnalytics_Helper;
use Magento\Framework\View\Element\Template\Context;

class Index extends \Magento\Framework\View\Element\Template
{

    /**
     * @var MultiAnalytics_Helper
     */
    private $multiAnalytics_Helper;

    /**
     * Constructor
     *
     * @param Context $context
     * @param MultiAnalytics_Helper $multiAnalytics_Helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        MultiAnalytics_Helper $multiAnalytics_Helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->multiAnalytics_Helper = $multiAnalytics_Helper;
    }

    /**
     * Returns Module Enabled Status
     *
     * @return string
     */
    public function isEnabled()
    {
        return $this->multiAnalytics_Helper->isEnabled();
    }

    /**
     * Returns Is Debug Status
     *
     * @return mixed
     */
    public function isDebugEnabled()
    {
        return $this->multiAnalytics_Helper->isDebugEnabled();
    }

    /**
     * Returns Google Tag ID
     *
     * @return mixed
     */
    public function getGtagID()
    {
        return $this->multiAnalytics_Helper->getGtagID();
    }

    /**
     * Returns Google Tag Manager ID
     *
     * @return mixed
     */
    public function getGtagManagerID()
    {
        return $this->multiAnalytics_Helper->getGtagManagerID();
    }

    /**
     * Returns Google Tag Layer ID
     *
     * @return mixed
     */
    public function getGtagLayerID()
    {
        return $this->multiAnalytics_Helper->getGtagLayerID();
    }

    /**
     * Returns Facebook Domain Verification Code
     *
     * @return mixed
     */
    public function getFacebookDomainVerificationCode()
    {
        return $this->multiAnalytics_Helper->getFacebookDomainVerificationCode();
    }

    /**
     * Returns Facebook Pixel Code
     *
     * @return mixed
     */
    public function getFacebookPixelCode()
    {
        return $this->multiAnalytics_Helper->getFacebookPixelCode();
    }

    /**
     * Returns Login Status
     *
     * @return string
     */
    public function isLoggedIn()
    {
        return $this->multiAnalytics_Helper->isLoggedIn();
    }

    /**
     * Returns Page Type
     *
     * @return string
     */
    public function getPageType()
    {
        return $this->multiAnalytics_Helper->getPageType();
    }

    /**
     * Returns Content Square Page Assigned Name
     *
     * @return false|string
     */
    public function assignPageType()
    {
        return $this->multiAnalytics_Helper->assignPageType();
    }

    /**
     * Returns Store Currency Code
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreCurrencyCode()
    {
        return $this->multiAnalytics_Helper->getStoreCurrencyCode();
    }

    /**
     * Returns Last Order ID
     *
     * @return mixed
     */
    public function getLastOrderID()
    {
        return $this->multiAnalytics_Helper->getLastOrderID();
    }

    /**
     * Returns Last Order In Increment Format
     *
     * @return float|string|null
     */
    public function getLastOrderIncrementID()
    {
        return $this->multiAnalytics_Helper->getLastOrderIncrementID();
    }

    /**
     * Returns Last Order Amount based on Order ID
     *
     * @param [type]  $orderID
     * @return float|null
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLastOrderAmount($orderID)
    {
        return $this->multiAnalytics_Helper->getLastOrderAmount($orderID);
    }
}
