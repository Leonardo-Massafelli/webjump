<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Api\ProductAttributeManagementInterface;
use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class AddAutomotivoAttributeProducts implements DataPatchInterface
{
    const AUTOMOTIVO_AUTO = 'automatico';

    const AUTOMOTIVO_AR_CONDICIONADO = 'ar_condicionado';

    const AUTOMOTIVO_COLOR = 'color';


    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var ProductAttributeManagementInterface
     */
    private $productAttributeManagement;


    /**
     * @var EavSetupFactory $eavSetupFactory ;
     */
    private $eavSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    /**
     * @var Product
     */
    private $product;

    public function __construct(
        ModuleDataSetupInterface            $moduleDataSetup,
        EavSetupFactory                     $eavSetupFactory,
        ProductAttributeManagementInterface $productAttributeManagement,
        AttributeSetFactory                 $attributeSetFactory,
        Product                             $product
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productAttributeManagement = $productAttributeManagement;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->product = $product;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $this->createAttribute($eavSetup);
        $this->moduleDataSetup->getConnection()->endSetup();
    }


    public function createAttribute(EavSetup $eavSetup)
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            static::AUTOMOTIVO_AR_CONDICIONADO,
            [
                'attribute_set' => 'Automotivo',
                'user_defined' => true,
                'type' => 'int',
                'label' => 'Ar condicionado',
                'input' => 'boolean',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true,
            ]
        );

        $attributeSetId = $eavSetup->getAttributeSetId(Product::ENTITY, 'Automotivo'); //pegar via contante
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $sortOrder = 50;
        $this->productAttributeManagement
            ->assign($attributeSetId, $attributeGroupId, static::AUTOMOTIVO_AR_CONDICIONADO, $sortOrder);


        $eavSetup->addAttribute(
            Product::ENTITY,
            static::AUTOMOTIVO_AUTO,
            [
                'attribute_set' => 'Automotivo',
                'user_defined' => true,
                'type' => 'int',
                'label' => 'Automático',
                'input' => 'boolean',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'used_in_product_listing' => true,
                'visible_on_front' => true,
            ]
        );

        $sortOrder = 52;
        $this->productAttributeManagement
            ->assign($attributeSetId, $attributeGroupId, static::AUTOMOTIVO_AUTO, $sortOrder);


        $eavSetup->addAttribute(
            Product::ENTITY,
            static::AUTOMOTIVO_COLOR,
            [
                'attribute_set' => 'Automotivo',
                'user_defined' => true,
                'type' => 'text',
                'label' => 'Cor',
                'input' => 'select',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'used_in_product_listing' => true,
                'system' => false,
                'visible_on_front' => true,
            ]
        );

        $sortOrder = 54;
        $this->productAttributeManagement
            ->assign($attributeSetId, $attributeGroupId, static::AUTOMOTIVO_COLOR, $sortOrder);
    }

    public function getAliases()
    {
        return [

        ];
    }

    public static function getDependencies()
    {
        return [CreateAutomotivoAttributeSet::class];
    }
}
