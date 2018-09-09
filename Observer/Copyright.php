<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Observer;
class Copyright implements \Magento\Framework\Event\ObserverInterface {
	public function execute(\Magento\Framework\Event\Observer $observer){
		$observer->getLayout()->addBlock(
			'Magento\Framework\View\Element\Text', 
			'sy_copyright_multiple_flat_rate_shipping', 
			'sy_copyright'
		)->setData(
			'text',
			'<a href="https://slavayurthev.github.io/magento-2/extensions/multiple-flat-rate-shipping/">Magento 2 Multiple Flat Rate Shipping Extension</a>'
		);
		return $this;
	}
}