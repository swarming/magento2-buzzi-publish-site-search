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
     * @param string $searchQuery
     * @param string $pageUrl
     * @param string $searchType
     * @param \Magento\Customer\Model\Customer|null $customer
     * @param string|null $customerEmail
     * @return mixed[]
     */
    public function getPayload($searchQuery, $pageUrl, $searchType = null, $customer = null, $customerEmail = null)
    {
        $payload = $this->dataBuilderBase->initBaseData(self::EVENT_TYPE);

        $payload['customer'] = $customer ? $this->dataBuilderCustomer->getCustomerData($customer) : ['email' => $customerEmail];
        $payload['search_query'] = $searchQuery;
        $payload['page_url'] = $pageUrl;

        if ($searchType) {
            $payload['search_type'] = $searchType;
        }
        $transport = new DataObject(['payload' => $payload]);
        $this->eventDispatcher->dispatch('buzzi_publish_site_search', ['transport' => $transport]);

        return (array)$transport->getData('payload');
    }
}
