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

class Manual extends \Magento\Framework\View\Element\Template
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
     * @return mixed
     */
    public function generateXml()
    {
        return $this->multiAnalytics_Helper->generateXml();
    }
}
