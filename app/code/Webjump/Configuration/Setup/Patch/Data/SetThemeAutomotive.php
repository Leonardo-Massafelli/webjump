<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class SetThemeAutomotive implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var WriterInterface
     */
    private $writer;
    /**
     * @var ThemeProviderInterface
     */
    private $themeProvider;
    /**
     * @var WebsiteRepositoryInterface
     */
    private $websiteRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $writer,
        ThemeProviderInterface $themeProvider,
        WebsiteRepositoryInterface $websiteRepository
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->writer = $writer;
        $this->themeProvider = $themeProvider;
        $this->websiteRepository = $websiteRepository;
    }


    public static function getDependencies()
    {
       return [CreateWebsites::class, SetThemeCarbono::class];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $automotiveId = $this->websiteRepository->get(CreateWebsites::AUTOMOTIVO_WEBSITE_CODE)->getId();

        $theme = $this->themeProvider->getThemeByFullPath("frontend/Automotive/principal_theme");

        $scopes = [
            ScopeInterface::SCOPE_WEBSITES
        ];

        foreach ($scopes as $scope){
            $this->writer->save('design/theme/theme_id', $theme->getId(), $scope, $automotiveId);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
