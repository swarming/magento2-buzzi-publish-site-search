<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\PublishSiteSearch\Model;

use Magento\Framework\DataObject;

class DataBuilder
{
    const EVENT_TYPE = 'buzzi.ecommerce.site-search';

    /**
     * @var \Buzzi\Publish\Helper\DataBuilder\Base
     */
    private $dataBuilderBase;

    /**
     * @var \Buzzi\Publish\Helper\DataBuilder\Customer
     */
    private $dataBuilderCustomer;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventDispatcher;

    /**
     * @param \Buzzi\Publish\Helper\DataBuilder\Base $dataBuilderBase
     * @param \Buzzi\Publish\Helper\DataBuilder\Customer $dataBuilderCustomer
     * @param \Magento\Framework\Event\ManagerInterface $eventDispatcher
     */
    public function __construct(
        \Buzzi\Publish\Helper\DataBuilder\Base $dataBuilderBase,
        \Buzzi\Publish\Helper\DataBuilder\Customer $dataBuilderCustomer,
        \Magento\Framework\Event\ManagerInterface $eventDispatcher
    ) {
        $this->dataBuilderBase = $dataBuilderBase;
        $this->dataBuilderCustomer = $dataBuilderCustomer;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param array $searchData
     * @param \Magento\Customer\Model\Customer|null $customer
     * @param string|null $customerEmail
     * @return mixed[]
     */
    public function getPayload(array $searchData, $customer = null, $customerEmail = null)
    {
        $payload = $this->dataBuilderBase->initBaseData(self::EVENT_TYPE);

        $payload['customer'] = $customer ? $this->dataBuilderCustomer->getCustomerData($customer) : ['email' => $customerEmail];
        $payload['search_type'] = $searchData['search_type'];
        $payload['search_query'] = $searchData['search_query'];
        $payload['page_url'] = $searchData['page_url'];

        $transport = new DataObject(['payload' => $payload]);
        $this->eventDispatcher->dispatch('buzzi_publish_site_search', ['transport' => $transport]);

        return (array)$transport->getData('payload');
    }
}
