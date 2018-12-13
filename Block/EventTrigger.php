<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\PublishSiteSearch\Block;

use Buzzi\PublishSiteSearch\Model\DataBuilder;

class EventTrigger extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Buzzi\Publish\Model\Config\Events
     */
    private $configEvents;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Buzzi\Publish\Model\Config\Events $configEvents
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Buzzi\Publish\Model\Config\Events $configEvents,
        array $data = []
    ) {
        $this->configEvents = $configEvents;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->configEvents->isEventEnabled(DataBuilder::EVENT_TYPE);
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return DataBuilder::EVENT_TYPE;
    }

    /**
     * @return string
     */
    public function getSearchFormSelector()
    {
        return $this->configEvents->getValue(DataBuilder::EVENT_TYPE, 'search_form_selector');
    }

    /**
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->_urlBuilder->getCurrentUrl();
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return $this->isEnabled() ? parent::_toHtml() : '';
    }
}
