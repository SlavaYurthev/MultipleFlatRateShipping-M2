<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
	const COUNTRY_AND_REGION_SEPARATOR = '|';
	const PER_ITEM_TYPE = 'I';
	const PER_ORDER_TYPE = 'O';
	public function getConfig($field, $storeId = 0){
		return $this->scopeConfig->getValue('carriers/sy_multiple_flat_rate_shipping/'.$field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
	}
}