<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Rohan Hapani
 */
declare (strict_types = 1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Webjump\Configuration\Setup\Patch\Data\CreateWebsites;

class CmsHomeFesta implements DataPatchInterface
{
    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param PageFactory $pageFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        PageFactory $pageFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageFactory = $pageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {

        $pageData = [
            'title' => 'Home Party', // cms page title
            'page_layout' => '1column', // cms page layout
            'meta_keywords' => '', // cms page meta keywords
            'meta_description' => '', // cms page meta description
            'identifier' => 'home-party', // cms page identifier
            'content_heading' => '', // cms page content heading
            'content' => '<div class="banner"><a href="{{config path="web/unsecure/base_url"}}catalog/category/view/s/halloween/id/15/"><img src="{{media url="wysiwyg/main-banner.png"}}" alt=""></a></div>
            <div class="flex-cards">
            <div class="festaJunina"><a href="{{config path="web/unsecure/base_url"}}catalog/category/view/s/bexigas-customizadas/id/23/"> <img src="{{media url="wysiwyg/festa_junina.png"}}" alt=""> </a></div>
            <div class="carnaval"><a href="{{config path="web/unsecure/base_url"}}catalog/category/view/s/festa-a-fantasia/id/18/"> <img src="{{media url="wysiwyg/carnaval.png"}}" alt=""> </a></div>
            <div class="aniversario"><a href="{{config path="web/unsecure/base_url"}}catalog/category/view/s/topper/id/24/"> <img src="{{media url="wysiwyg/aniversario.png"}}" alt=""> </a></div>
            </div>
            <div class="promo-card"><a href="{{config path="web/unsecure/base_url"}}catalog/category/view/s/pool-party/id/17/"><img src="{{media url="wysiwyg/promotions.png"}}" alt=""></a></div>', // cms page content
            'layout_update_xml' => '', // cms page layout xml
            'url_key' => 'home-party', // cms page url key
            'is_active' => 1, // status enabled or disabled
            'stores' => [4, 5], // You can set store id single or multiple values in array.
            'sort_order' => 2, // cms page sort order
        ];

        $this->moduleDataSetup->startSetup();
        $this->pageFactory->create()->setData($pageData)->save();
        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [CreateWebsites::class];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}