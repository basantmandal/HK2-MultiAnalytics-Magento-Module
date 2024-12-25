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

namespace HK2\MultiAnalytics\Controller\Generate;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

use HK2\MultiAnalytics\Helper\Data as HK2_Multi_Analytics_Helper;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */

    protected PageFactory $_pageFactory;

    /**
     * @var HK2_Multi_Analytics_Helper
     */
    private $hk2_helper;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param HK2_Multi_Analytics_Helper $hk2Helper
     */
    public function __construct(Context $context, PageFactory $pageFactory, HK2_Multi_Analytics_Helper $hk2Helper)
    {
        $this->_pageFactory = $pageFactory;
        $this->hk2_helper = $hk2Helper;

        return parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */

    public function execute()
    {
        $pageFactory = $this->_pageFactory->create();
        //        if ($this->hk2_helper->isDebugEnabled()) {
        //            echo '<pre>';
        //            echo 'Feed = '.$this->hk2_helper->generateXml();
        //            echo '</pre>';
        //        } else {
        //            $this->hk2_helper->generateXml();
        //        }
        $pageFactory->getConfig()->getTitle()->set("Google Product RSS Feed File Manual Generate");

        return $pageFactory;
    }
}
