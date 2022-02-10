<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\CatalogRule\Model\CatalogRuleRepository;
use Magento\Customer\Model\Group;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\CatalogRule\Api\Data\RuleInterfaceFactory;
use Magento\Store\Api\WebsiteRepositoryInterface;

class CreateCatalogRuleParty implements DataPatchInterface
{

    private $moduleDataSetup;
    private $ruleInterfaceFactory;
    private $catalogRuleRepository;
    private $websiteRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        RuleInterfaceFactory $ruleInterfaceFactory,
        CatalogRuleRepository $catalogRuleRepository,
        WebsiteRepositoryInterface $websiteRepository
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->ruleInterfaceFactory = $ruleInterfaceFactory;
        $this->catalogRuleRepository = $catalogRuleRepository;
        $this->websiteRepository = $websiteRepository;
    }

    public static function getDependencies()
    {
        return[
            CreateWebsites::class
        ];
    }

    public function getAliases()
    {
        return[];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $partyId = $this->websiteRepository->get(CreateWebsites::FESTA_WEBSITE_CODE)->getId();

        $catalogRule = $this->ruleInterfaceFactory->create(['setup' => $this->moduleDataSetup]);

        $catalogRule->setName('10% discount for not logged in customers')
            ->setDescription('discount applied for users that are not logged in the website')
            ->setIsActive(1)
            ->setWebsiteIds($partyId)
            ->setCustomerGroupIds(Group::NOT_LOGGED_IN_ID)
            ->setSimpleAction('by_percent')
            ->setDiscountAmount(10)
            ->setStopRulesProcessing(0);
        $this->catalogRuleRepository->save($catalogRule);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}

?>
