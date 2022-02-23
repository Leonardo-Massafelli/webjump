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
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\WebsiteRepository;
use Webjump\Configuration\Setup\Patch\Data\CreateWebsites;
use \Magento\Config\Model\ResourceModel\Config;
use Magento\Store\Api\StoreRepositoryInterface;

class CmsHomeParty implements DataPatchInterface
{
    CONST IDENTIFIER_NAME = 'home-party';

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
     * @var Config
     */
    private $Config;
    /**
     * @var WebsiteRepository
     */
    private $WebsiteRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param PageFactory $pageFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        PageFactory $pageFactory,
        Config $config,
        WebsiteRepository $websiteRepository,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageFactory = $pageFactory;
        $this->Config = $config;
        $this->WebsiteRepository = $websiteRepository;
        $this->storeRepository = $storeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $party = $this->storeRepository->get(CreateWebsites::FESTA_STORE_CODE)->getId();
        $partyEn = $this->storeRepository->get(CreateWebsites::FESTA_EN_STORE_CODE)->getId();

        $pageData = [
            'title' => 'Home Party', // cms page title
            'page_layout' => '1column', // cms page layout
            'meta_keywords' => '', // cms page meta keywords
            'meta_description' => '', // cms page meta description
            'identifier' => self::IDENTIFIER_NAME, // cms page identifier
            'content_heading' => '', // cms page content heading
            'content' => '<div class="banner"><a href="{{config path="web/unsecure/base_url"}}datascomemorativas/halloween.html"><img src="{{media url="wysiwyg/main-banner.png"}}" alt=""></a></div>
            <div class="flex-cards">
            <div class="festaJunina"><a href="{{config path="web/unsecure/base_url"}}datascomemorativas/festa-junina.html"> <img src="{{media url="wysiwyg/festa_junina.png"}}" alt=""> </a></div>
            <div class="carnaval"><a href="{{config path="web/unsecure/base_url"}}datascomemorativas/carnaval.html"> <img src="{{media url="wysiwyg/carnaval.png"}}" alt=""> </a></div>
            <div class="aniversario"><a href="{{config path="web/unsecure/base_url"}}datascomemorativas/aniversario.html"> <img src="{{media url="wysiwyg/aniversario.png"}}" alt=""> </a></div>
            </div>
            <div class="promo-card"><a href="{{config path="web/unsecure/base_url"}}baloes-bexigas.html"><img src="{{media url="wysiwyg/promotions.png"}}" alt=""></a></div>', // cms page content
            'layout_update_xml' => '', // cms page layout xml
            'url_key' => self::IDENTIFIER_NAME, // cms page url key
            'is_active' => 1, // status enabled or disabled
            'stores' => [$party], // You can set store id single or multiple values in array.
            'sort_order' => 2, // cms page sort order
        ];

        $this->moduleDataSetup->startSetup();
        $scopes = [
            ScopeInterface::SCOPE_STORES
        ];

        $this->pageFactory->create()->setData($pageData)->save();

        foreach ($scopes as $scope) {
            $this->Config->saveConfig('web/default/cms_home_page', self::IDENTIFIER_NAME, $scope, $party);
        }
        $this->moduleDataSetup->endSetup();

        $pageDataEn = [
            'title' => 'Home Party', // cms page title
            'page_layout' => '1column', // cms page layout
            'meta_keywords' => '', // cms page meta keywords
            'meta_description' => '', // cms page meta description
            'identifier' => self::IDENTIFIER_NAME, // cms page identifier
            'content_heading' => '', // cms page content heading
            'content' => '<div class="banner"><a href="{{config path="web/unsecure/base_url"}}commemorative-dates/halloween-en.html"><img src="{{media url="wysiwyg/main-banner.png"}}" alt=""></a></div>
            <div class="flex-cards">
            <div class="festaJunina"><a href="{{config path="web/unsecure/base_url"}}commemorative-dates/feast-of-saint-john.html"> <img src="{{media url="wysiwyg/festa_junina.png"}}" alt=""> </a></div>
            <div class="carnaval"><a href="{{config path="web/unsecure/base_url"}}commemorative-dates/carnival.html"> <img src="{{media url="wysiwyg/carnaval.png"}}" alt=""> </a></div>
            <div class="aniversario"><a href="{{config path="web/unsecure/base_url"}}commemorative-dates/birthday.html"> <img src="{{media url="wysiwyg/aniversario.png"}}" alt=""> </a></div>
            </div>
            <div class="promo-card"><a href="{{config path="web/unsecure/base_url"}}baloons.html"><img src="{{media url="wysiwyg/promotions.png"}}" alt=""></a></div>', // cms page content
            'layout_update_xml' => '', // cms page layout xml
            'url_key' => self::IDENTIFIER_NAME, // cms page url key
            'is_active' => 1, // status enabled or disabled
            'stores' => [$partyEn], // You can set store id single or multiple values in array.
            'sort_order' => 2, // cms page sort order
        ];

        $this->moduleDataSetup->startSetup();
        $scopes = [
            ScopeInterface::SCOPE_STORES
        ];

        $this->pageFactory->create()->setData($pageDataEn)->save();

        foreach ($scopes as $scope) {
            $this->Config->saveConfig('web/default/cms_home_page', self::IDENTIFIER_NAME, $scope, $partyEn);
        }
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
