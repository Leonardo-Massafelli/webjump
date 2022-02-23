<?php
/*
 *  @category  	Techflarestudio
 *  @author	Wasalu Duckworth
 *  @copyright Copyright (c) 2021 Techflarestudio, Ltd. 			(https://techflarestudio.com)
 *  @license http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use \Magento\Cms\Model\BlockFactory;
use Magento\Store\Api\StoreRepositoryInterface;


/**
 * Class UpdateBlockData
 * @package Techflarestudio\Content\Setup\Patch\Data
 */
class FooterLinks3
    implements DataPatchInterface,
    PatchRevertableInterface
{
    const BLOCK_IDENTIFIER = 'footer_links_block3';
    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * UpdateBlockData constructor.
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        BlockFactory $blockFactory,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->blockFactory = $blockFactory;
        $this->storeRepository = $storeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {

        $automotivo = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_STORE_CODE);
        $automotivo_en = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE);

        $headerNoticeData = [
            'title' => 'footer links block3',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content' => '<div class="footer-links">
            <h3>Buy</h3>
            <ul>
            <li><a href="#">Request a quote</a></li>
            <li><a href="#">Estimate a payment</a></li>
            <li><a href="#">Trade-in value</a></li>
            <li><a href="#">Leasing</a></li>
            <li><a href="#">Financing</a></li>
            </ul>
            </div>',
            'stores' => [$automotivo_en->getId()],
            'is_active' => 1,
        ];
        $headerNoticeBlock = $this->blockFactory
            ->create()
            ->load($headerNoticeData['identifier'], 'identifier');

        /**
         * Create the block if it does not exists, otherwise update the content
         */
        if (!$headerNoticeBlock->getId()) {
            $headerNoticeBlock->setData($headerNoticeData)->save();
        } else {
            $headerNoticeBlock->setContent($headerNoticeData['content'])->save();
        }


        $headerNoticeDataPT = [
            'title' => 'footer links block3',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content' => '<div class="footer-links">
            <h3>Comprar</h3>
            <ul>
            <li><a href="#">Solicite uma Cotação</a></li>
            <li><a href="#">Estimar um Pagamento</a></li>
            <li><a href="#">Valor de Troca</a></li>
            <li><a href="#">Locação</a></li>
            <li><a href="#">Financiamento</a></li>
            </ul>
            </div>',
            'stores' => [$automotivo->getId()],
            'is_active' => 1,
        ];
        $headerNoticeBlockPT = $this->blockFactory
            ->create()
            ->load($headerNoticeDataPT['identifier'], 'identifier');

            $headerNoticeBlockPT->setData($headerNoticeDataPT)->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        /**
         * No dependencies for this
         */
        return [
            CreateWebsites::class
        ];
    }

    /**
     * Delete the block
     */
    public function revert()
    {
        $headerNoticeBlock = $this->blockFactory
            ->create()
            ->load(self::BLOCK_IDENTIFIER, 'identifier');

        if($headerNoticeBlock->getId()) {
            $headerNoticeBlock->delete();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        /**
         * Aliases are useful if we change the name of the patch until then we do not need any
         */
        return [];
    }
}
