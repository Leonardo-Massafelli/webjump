<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\StoreFactory;
use Magento\Store\Model\WebsiteFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\ResourceModel\Group;
use Magento\Store\Model\ResourceModel\Store;
use Magento\Store\Model\ResourceModel\Website;

class CreateWebsites implements DataPatchInterface
{
    const AUTOMOTIVO_WEBSITE_CODE = 'automotivo';
    const AUTOMOTIVO_GROUP_CODE = 'automotivo';
    const AUTOMOTIVO_STORE_CODE = 'automotivo';
    const AUTOMOTIVO_EN_STORE_CODE = 'automotivo_en';
    const FESTA_WEBSITE_CODE = 'festa';
    const FESTA_GROUP_CODE = 'festa';
    const FESTA_STORE_CODE = 'festa';
    const FESTA_EN_STORE_CODE = 'festa_en';

    private $moduleDataSetup;
    private $websiteFactory;
    private $websiteResourceModel;
    private $groupFactory;
    private $groupResourceModel;
    private $storeFactory;
    private $storeResourceModel;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WebsiteFactory $websiteFactory,
        Website $websiteResourceModel,
        GroupFactory $groupFactory,
        Group $groupResourceModel,
        StoreFactory $storeFactory,
        Store $storeResourceModel
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->websiteFactory = $websiteFactory;
        $this->websiteResourceModel = $websiteResourceModel;
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $groupResourceModel;
        $this->storeFactory = $storeFactory;
        $this->storeResourceModel = $storeResourceModel;
    }

    public static function getDependencies()
    {
        return[];
    }

    public function getAliases()
    {
        return[];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $data = $this->getData();

        foreach ($data as $value) {
            $website = $this->websiteFactory->create();
            $this->websiteResourceModel->load($website, $value['website']['code'], 'code');

            if (!$website->getId()) {
                //creating websites
                $website->setCode($value['website']['code'])
                    ->setName($value['website']['name'])
                    ->setSortOrder($value['website']['sort_order'])
                    ->setDefaultGroupId(0)
                    ->setIsDefault($value['website']['is_default']);

                $this->websiteResourceModel->save($website);

                //creating groups
                $group = $this->groupFactory->create();
                $group->setWebsiteId($website->getId())
                    ->setName($value['group']['name'])
                    ->setRootCategoryId($value['group']['root_category_id'])
                    ->setDefaultStoreId($value['group']['default_store_id'])
                    ->setCode($value['group']['code']);

                $this->groupResourceModel->save($group);

                $this->websiteResourceModel->load($website, $value['website']['code'], 'code');
                $website->setDefaultGroupId($group->getId());
                $this->websiteResourceModel->save($website);

                //creating  storeviews
                $aux = [];
                $i = 0;

                foreach ($value['store'] as $storeIterator) {
                    $store = $this->storeFactory->create();
                    $store->setCode($storeIterator['code'])
                        ->setWebsiteId($website->getId())
                        ->setGroupId($group->getId())
                        ->setName($storeIterator['name'])
                        ->setSortOrder($storeIterator['sort_order'])
                        ->setIsActive($storeIterator['is_active']);

                    $this->storeResourceModel->save($store);

                    $aux[$i] = $store->getId();
                    $i++;
                }

                $this->groupResourceModel->load($group, $value['group']['code'], 'code');
                $group->setDefaultStoreId($aux[0]);
                $this->groupResourceModel->save($group);
            }
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    private function getData():array
    {
        return [
            'automotivo' => [
                'website' => [
                    'code' => self::AUTOMOTIVO_WEBSITE_CODE,
                    'name' => 'Automotivo',
                    'sort_order' => '1',
                    'is_default' => '1'
                ],
                'group' => [
                    'name' => 'Automotivo Store',
                    'root_category_id' => '2',
                    'code' => self::AUTOMOTIVO_GROUP_CODE,
                    'default_store_id' => 0
                ],
                'store' => [
                    'pt' => [
                        'code' => self::AUTOMOTIVO_STORE_CODE,
                        'name' => 'Automotivo',
                        'sort_order' => '1',
                        'is_active' => '1'
                    ],
                    'en' => [
                        'code' => self::AUTOMOTIVO_EN_STORE_CODE,
                        'name' => 'Automotivo en',
                        'sort_order' => '2',
                        'is_active' => '1'
                    ]
                ]
            ],
            'festa' => [
                'website' => [
                    'code' => self::FESTA_WEBSITE_CODE,
                    'name' => 'Festa',
                    'sort_order' => '2',
                    'is_default' => '0'
                ],
                'group' => [
                    'name' => 'Festa Store',
                    'root_category_id' => '2',
                    'code' => self::FESTA_GROUP_CODE,
                    'default_store_id' => 0
                ],
                'store' => [
                    'pt' => [
                        'code' => self::FESTA_STORE_CODE,
                        'name' => 'Festa',
                        'sort_order' => '2',
                        'is_active' => '1'
                    ],
                    'en' => [
                        'code' => self::FESTA_EN_STORE_CODE,
                        'name' => 'Festa en',
                        'sort_order' => '3',
                        'is_active' => '1'
                    ]
                ]
            ]
        ];
    }
}
