<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\SalesRule\Model\ResourceModel\Rule;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\SalesRule\Model\Rule\Condition\CombineFactory;
use Magento\SalesRule\Model\Rule\Condition\AddressFactory;
use Magento\SalesRule\Model\Rule\Condition\Address;

class CreateCartRule implements DataPatchInterface
{

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        RuleFactory $ruleFactory,
        Rule $rule,
        WebsiteRepositoryInterface $websiteRepository,
        CombineFactory $combineFactory,
        AddressFactory $addressFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->ruleFactory = $ruleFactory;
        $this->rule = $rule;
        $this->websiteRepository = $websiteRepository;
        $this->CombineFactory = $combineFactory;
        $this->addressFactory = $addressFactory;
    }

    public static function getDependencies()
    {
        return[CreateWebsites::class];
    }

    public function getAliases()
    {
        return[];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $automotiveId = $this->websiteRepository->get(CreateWebsites::AUTOMOTIVO_WEBSITE_CODE)->getId();
        $partyId = $this->websiteRepository->get(CreateWebsites::FESTA_WEBSITE_CODE)->getId();

        //Creating the condition to be used in the cart rule
        $conditionAddress = $this->addressFactory->create();

        $conditionAddress->settype(Address::class)
            ->setData('attribute', 'total_qty')
            ->setData('operator', '>=')
            ->setData('value', '5')
            ->setData('is_value_processed', 'false');

        $ruleCondition = $this->CombineFactory->create();
        
        $ruleCondition->setData('attribute', 'null')
            ->setData('operator', 'null')
            ->setData('value', '1')
            ->setData('is_value_processed', 'null')
            ->setData('aggregator', 'all')
            ->setConditions([$conditionAddress]);

        //Creating the cart rule
        $cartrule = $this->ruleFactory->create(['setup' => $this->moduleDataSetup]);
        $cartrule->setName('10% discount when there are 5 products or more in the cart')
            ->setDescription('discount applied in both websites for users that have 5 or more items in the cart')
            ->setIsActive(1)
            ->setConditions($ruleCondition)
            ->setSimpleAction('by_percent')
            ->setDiscountAmount(10)
            ->setWebsiteIds([$automotiveId, $partyId])
            ->setCustomerGroupIds(['0', '1', '2', '3']);
        $this->rule->save($cartrule);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
