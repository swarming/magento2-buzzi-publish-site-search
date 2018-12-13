<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */

namespace Buzzi\PublishSiteSearch\Model;

use Buzzi\Publish\Api\PackerInterface;

class Packer implements PackerInterface
{
    /**
     * @var \Buzzi\PublishSiteSearch\Model\DataBuilder
     */
    private $dataBuilder;

    /**
     * @param \Buzzi\PublishSiteSearch\Model\DataBuilder $dataBuilder
     */
    public function __construct(
        \Buzzi\PublishSiteSearch\Model\DataBuilder $dataBuilder
    ) {
        $this->dataBuilder = $dataBuilder;
    }

    /**
     * @param array $inputData
     * @param \Magento\Customer\Model\Customer|null $customer
     * @param string|null $customerEmail
     * @return array|null
     */
    public function pack(array $inputData, $customer = null, $customerEmail = null)
    {
        if (empty($inputData['page_url']) || empty($inputData['search_query'])) {
            throw new \InvalidArgumentException('Page URL and Search Query are required fields.');
        }

        $searchType = !empty($inputData['search_type']) ? $inputData['search_type'] : null;

        return $customer || $customerEmail
            ? $this->dataBuilder->getPayload($inputData['search_query'], $inputData['page_url'], $searchType, $customer, $customerEmail)
            : null;
    }
}
