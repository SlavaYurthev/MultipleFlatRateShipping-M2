<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Helper;

class Rates extends Data {
	protected $_json;
	protected $_regionCollectionFactory;
	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		\Magento\Framework\Serialize\Serializer\Json $json,
		\Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
		array $data = []
	){
		$this->_json = $json;
		$this->_regionCollectionFactory = $regionCollectionFactory;
		parent::__construct($context, $data);
	}
	private function _getRates($storeId = 0){
		return $this->_json->unserialize($this->getConfig('rates', $storeId));
	}
	private function _getCountryRegionsCollection($countryId, $exclude = []){
		$collection = $this->_regionCollectionFactory->create();
		$collection->addCountryFilter($countryId);
		$collection->addFieldToFilter('main_table.region_id', ['nin' => $exclude]);
		$collection->getSelect()->limit(1);
		return $collection;
	}
	public function getRates($storeId = 0){
		$items = [];
		$rates = $this->_getRates($storeId);
		if(is_array($rates) && !empty($rates)){
			foreach ($rates as $rate) {
				$item = new \Magento\Framework\DataObject($rate);
				if(is_array($item->getData('countries')) && !empty($item->getData('countries'))){
					$countries = [];
					foreach ($item->getData('countries') as $country) {
						if(strpos($country, self::COUNTRY_AND_REGION_SEPARATOR) !== false){
							list($countryId, $regionId) = explode(self::COUNTRY_AND_REGION_SEPARATOR, $country);
							$countries[$countryId][$regionId] = true;
							// If all regions are allowed
							if($this->_getCountryRegionsCollection(
									$countryId, 
									array_keys($countries[$countryId])
								)->count() == 0){
								$countries[$countryId] = true;
							}
						}
						else{
							$countries[$country] = true;
						}
					}
					$item->setData('countries', $countries);
				}
				else{
					$item->unsetData('countries');
				}
				$items[] = $item;
			}
		}
		return $items;
	}
}