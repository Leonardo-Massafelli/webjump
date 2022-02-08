<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class CreateAttributeCategories implements DataPatchInterface
{

    const CATEGORY_ONE = 'category_one';

    const CATEGORY_TWO = 'category_two';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavConfig = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavConfig->addAttribute(Category::ENTITY, self::CATEGORY_ONE, [
            'type' => 'varchar',
            'label' => 'Itens (Festa)',
            'input' => 'text',
            'source' => '',
            'user_defined' => true,
            'visible' => true,
            'default' => '',
            'required' => false,
            'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
            'group' => 'General'
        ]);


        $eavConfig->addAttribute(Category::ENTITY, self::CATEGORY_TWO, [
            'type' => 'varchar',
            'label' => 'Itens (Automotivo)',
            'input' => 'text',
            'source' => '',
            'user_defined' => true,
            'visible' => true,
            'default' => '',
            'required' => false,
            'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
            'group' => 'General'
        ]);

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
