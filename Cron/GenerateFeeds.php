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

namespace HK2\MultiAnalytics\Cron;

use HK2\MultiAnalytics\Helper\Data as HK2_Multi_Analytics_Helper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class GenerateFeeds
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var HK2_Multi_Analytics_Helper
     */
    private $hk2_helper;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param HK2_Multi_Analytics_Helper $hk2Helper
     */
    public function __construct(\Psr\Log\LoggerInterface $logger, HK2_Multi_Analytics_Helper $hk2Helper)
    {
        $this->logger = $logger;
        $this->hk2_helper = $hk2Helper;
    }

    /**
     * Execute the cron
     *
     * @return void
     */

    public function execute()
    {
        try {
            $this->hk2_helper->generateXml();
        } catch (NoSuchEntityException $e) {
        } catch (LocalizedException $e) {
            $this->logger->info('HK2 Multi Analytics Cronjob Error' . $e->getMessage());
        }
        $this->logger->info('HK2 Multi Analytics Cronjob GenerateFeeds is executed.');
    }
}
