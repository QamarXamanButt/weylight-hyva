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
 * @package    Bss_Gallery
 * @author     Extension Team
 * @copyright  Copyright (c) 2018-2019 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\Gallery\Model\Category\Source;

/**
 * Class IsActive
 *
 * @package Bss\Gallery\Model\Category\Source
 */
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Bss\Gallery\Model\Category
     */
    protected $category;

    /**
     * Constructor
     *
     * @param \Bss\Gallery\Model\Category $category
     */
    public function __construct(\Bss\Gallery\Model\Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->category->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
