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

class AddFestaAttributeProducts implements DataPatchInterface
{
    const FESTA_TEMA = 'festa_tema';

    const FESTA_QTD = 'festa_qtd';


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
            static::FESTA_TEMA,
            [
                'attribute_set' => 'Festa',
                'user_defined' => true,
                'type' => 'text',
                'label' => 'Tema',
                'input' => 'text',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'used_in_product_listing' => true,
                'system' => false,
                'visible_on_front' => true,
            ]
        );

        $attributeSetId = $eavSetup->getAttributeSetId(Product::ENTITY, 'Festa'); //pegar via contante
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $sortOrder = 50;
        $this->productAttributeManagement
            ->assign($attributeSetId, $attributeGroupId, static::FESTA_TEMA, $sortOrder);


        $eavSetup->addAttribute(
            Product::ENTITY,
            static::FESTA_QTD,
            [
                'attribute_set' => 'Festa',
                'user_defined' => true,
                'type' => 'text',
                'label' => 'Quantidade por pacote',
                'input' => 'text',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'used_in_product_listing' => true,
                'system' => false,
                'visible_on_front' => true,
            ]
        );

        $sortOrder = 52;
        $this->productAttributeManagement
            ->assign($attributeSetId, $attributeGroupId, static::FESTA_QTD, $sortOrder);
    }

    public function getAliases()
    {
        return [

        ];
    }

    public static function getDependencies()
    {
        return [CreateFestaAttributeSet::class];
    }
}
