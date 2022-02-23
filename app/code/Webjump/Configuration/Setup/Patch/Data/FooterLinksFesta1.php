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
class FooterLinksFesta1
    implements DataPatchInterface,
    PatchRevertableInterface
{
    const BLOCK_IDENTIFIER = 'institucional';
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

        $festa = $this->storeRepository->get(CreateWebsites::FESTA_STORE_CODE);
        $festa_en = $this->storeRepository->get(CreateWebsites::FESTA_EN_STORE_CODE);

        $headerNoticeData = [
            'title' => 'institutional',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content' => '<div>
            <h3>Institucional</h3>
            <ul>
            <li><a>Nossas vantagens</a></li>
            <li><a>Lojas físicas</a></li>
            <li><a>Trocas e devoluções</a></li>
            <li><a>Comprar no atacado</a></li>
            </ul>
            </div>',
            'stores' => [$festa->getId()],
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

        ///////// BLOCK ENGLISH
        ///
        $headerNoticeDataEn = [
            'title' => 'institutional',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content' => '<div>
            <h3>Institutional</h3>
            <ul>
            <li><a>Our Advantages</a></li>
            <li><a>Physical Stores</a></li>
            <li><a>Exchange and Returns</a></li>
            <li><a>Bulk Purchases</a></li>
            </ul>
            </div>',
            'stores' => [$festa_en->getId()],
            'is_active' => 1,
        ];
        $headerNoticeBlockEn = $this->blockFactory
            ->create()
            ->load($headerNoticeDataEn['identifier'], 'identifier');

            $headerNoticeBlockEn->setData($headerNoticeDataEn)->save();
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
