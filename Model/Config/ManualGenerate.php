<?php

namespace HK2\MultiAnalytics\Model\Config;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class ManualGenerate implements \Magento\Config\Model\Config\CommentInterface
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $_storeManager;

    /**
     * Constructor
     *
     * @param UrlInterface $urlInterface
     * @param StoreManagerInterface $storeManagerInterface
     */
    public function __construct(UrlInterface $urlInterface, StoreManagerInterface $storeManagerInterface)
    {
        $this->_storeManager = $storeManagerInterface;
    }

    /**
     * Get Comment Text
     *
     * @param $elementValue
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCommentText($elementValue)
    {
        $base_url = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $url = $base_url . 'hk2multianalytics/generate';
        $url = '<a href="' . $url . '"target="_blank">' . __('ManualGenerateRSSFeed') . '</a>';

        return $url;
    }
}
