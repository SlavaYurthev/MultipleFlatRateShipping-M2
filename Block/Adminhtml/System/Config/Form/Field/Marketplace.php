<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Block\Adminhtml\System\Config\Form\Field;

use \Magento\Framework\Data\Form\Element\AbstractElement;

class Marketplace extends \Magento\Config\Block\System\Config\Form\Field {
	protected function _getElementHtml(AbstractElement $element){
		return '<p style="padding-top:7px"><a href="https://marketplace.magento.com/partner/'
			.$element->getEscapedValue()
			.'" target="_blank">'
			.$element->getEscapedValue()
			.'</a></p>';
	}
}