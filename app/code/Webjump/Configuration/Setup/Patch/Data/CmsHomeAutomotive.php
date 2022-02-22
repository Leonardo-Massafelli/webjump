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
use \Magento\Config\Model\ResourceModel\Config;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;


class CmsHomeAutomotive implements DataPatchInterface
{
    CONST IDENTIFIER_NAME = 'home-automotive';

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
     * @var CollectionFactory
     */
    private $CollectionFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param PageFactory $pageFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        PageFactory $pageFactory,
        Config $config,
        WebsiteRepository $websiteRepository,
        CollectionFactory $collectionFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageFactory = $pageFactory;
        $this->Config = $config;
        $this->WebsiteRepository = $websiteRepository;
        $this->CollectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $pageData = [
            'title' => 'Home Automotive', // cms page title
            'page_layout' => '1column', // cms page layout
            'meta_keywords' => '', // cms page meta keywords
            'meta_description' => '', // cms page meta description
            'identifier' => self::IDENTIFIER_NAME, // cms page identifier
            'content_heading' => '', // cms page content heading
            'content' => '<div class="banner-principal"><a href="#"><img src="{{media url="wysiwyg/banner_principal_full_cut4.png"}}" alt=""></a></div>
            <div class="container">
            <div class="row">
            <div class="car-col"><img src="{{media url="wysiwyg/Grupo_28.png"}}" alt=""></div>
            <div class="car-text-col">
            <div>
            <h2>UNIQUE.</h2>
            <h2>JUST LIKE YOU.</h2>
            </div>
            <button>CUSTOMIZE</button></div>
            </div>
            <div class="row">
            <div class="cards card1-col"><img src="{{media url="wysiwyg/charging.png"}}" alt="">
            <div>
            <p>CHARGING ACCESSORIES</p>
            <button>BUY NOW</button></div>
            </div>
            <div class="cards card2-col"><img src="{{media url="wysiwyg/find_store.png"}}" alt="">
            <div>
            <p>FIND A STORE</p>
            <button>SEARCH</button></div>
            </div>
            </div>
            </div>
            <div class="details-banner"><img src="{{media url="wysiwyg/Agrupar_1.png"}}" alt="">
            <div>
            <p>LITTLE DETAILS THAT MAKES A WHOLE DIFFERENCE</p>
            <button>GET TO KNOW</button></div>
            </div>', // cms page content
            'layout_update_xml' => '', // cms page layout xml
            'url_key' => self::IDENTIFIER_NAME, // cms page url key
            'is_active' => 1, // status enabled or disabled
            'stores' => [2, 3], // You can set store id single or multiple values in array.
            'sort_order' => 2, // cms page sort order
        ];

        $this->moduleDataSetup->startSetup();
        $automotiveId = $this->WebsiteRepository->get(CreateWebsites::AUTOMOTIVO_WEBSITE_CODE)->getId();
        $scopes = [
            ScopeInterface::SCOPE_WEBSITES
        ];

        $this->pageFactory->create()->setData($pageData)->save();

        foreach ($scopes as $scope) {
            $this->Config->saveConfig('web/default/cms_home_page', self::IDENTIFIER_NAME, $scope, $automotiveId);
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
