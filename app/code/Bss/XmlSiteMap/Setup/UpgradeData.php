<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_XmlSiteMap
 * @author     Extension Team
 * @copyright  Copyright (c) 2018-2019 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\XmlSiteMap\Setup;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Class UpgradeData
 * @package Bss\XmlSiteMap\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * UpgradeData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $this->addExcludedMetatemplate($eavSetup);

        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'excluded_xml_sitemap',
            [
                'sort_order' => 640,
                'note' => '',
                'group' => 'Search Engine Optimization',
                'type' => 'text',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend' => '',
                'label' => 'Excluded from XML Sitemap',
                'input' => 'select',
                'class' => '',
                'source' => 'Bss\XmlSiteMap\Model\Config\Product\ExcludeXmlSiteMap',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'default' => '0'
            ]
        );
        $idg = $categorySetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Search Engine Optimization');
        $categorySetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            $idg,
            'excluded_xml_sitemap',
            1000
        );

        $setup->endSetup();
    }

    /**
     * @param object $eavSetup
     */
    protected function addExcludedMetatemplate($eavSetup)
    {
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'excluded_xml_sitemap',
            [
                'group' => 'Search Engine Optimization',
                'type' => 'text',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend' => '',
                'label' => 'Excluded from XML Sitemap',
                'input' => 'select',
                'class' => '',
                'source' => 'Bss\XmlSiteMap\Model\Config\Product\ExcludeXmlSiteMap',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '0',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'sort_order' => 640,
                'apply_to' => ''
            ]
        );
    }
}
