<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\AttributeSetRepository;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Api\AttributeSetRepositoryInterface;


class CreateAutomotivoAttributeSet implements DataPatchInterface
{

    const AUTOMOTIVO_ATTRIBUTE_NAME = 'Automotivo';


    private $attributeSetFactory;

    private $categorySetupFactory;
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var AttributeSetRepository
     */
    private $attributeSetRepository;

    public function __construct(
        AttributeSetFactory $attributeSetFactory,
        CategorySetupFactory $categorySetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeSetRepository $attributeSetRepository
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->attributeSetRepository = $attributeSetRepository;

    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);

        $attributeSet = $this->attributeSetFactory->create();
        $entityTypeId = $categorySetup->getEntityTypeId(Product::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
        $data = [
            'attribute_set_name' => self::AUTOMOTIVO_ATTRIBUTE_NAME,
            'entity_type_id' => $entityTypeId,
            'sort_order' => 200,
        ];

        $attributeSet->setData($data);
        $attributeSet->validate();
        $attributeSet->save();
        $attributeSet->initFromSkeleton($attributeSetId);
        $attributeSet->save();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }


}

