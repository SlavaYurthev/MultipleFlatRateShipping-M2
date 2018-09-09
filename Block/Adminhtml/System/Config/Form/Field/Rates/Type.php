<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Block\Adminhtml\System\Config\Form\Field\Rates;

class Type extends \Magento\Framework\View\Element\Html\Select {
	public function __construct(
		\Magento\Framework\View\Element\Context $context,
		array $data = []
	){
		parent::__construct($context, $data);
	}
	public function setInputName($value){
		return $this->setName($value);
	}
	public function setInputId($value){
		return $this->setId($value);
	}
	public function getOptions(){
		return [
			['value' => \SY\MultipleFlatRateShipping\Helper\Data::PER_ORDER_TYPE, 'label' => __('Per Order')],
			['value' => \SY\MultipleFlatRateShipping\Helper\Data::PER_ITEM_TYPE, 'label' => __('Per Item')]
		];
	}
}