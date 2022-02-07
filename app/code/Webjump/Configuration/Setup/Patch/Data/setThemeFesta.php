<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Laminas\Filter\ToInt;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Store\Model\ScopeInterface;

class SetThemeFesta implements DataPatchInterface
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

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $writer,
        ThemeProviderInterface $themeProvider
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->writer = $writer;
        $this->themeProvider = $themeProvider;
    }


    public static function getDependencies()
    {
       return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $theme = $this->themeProvider->getThemeByFullPath("frontend/Festa");

        $scopes = [
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            ScopeInterface::SCOPE_STORES
        ];

        foreach ($scopes as $scope){
            $this->writer->save('design/theme/theme_id', $theme->getId(), $scope);
        }


        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
