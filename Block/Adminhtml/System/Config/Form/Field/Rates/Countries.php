<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Block\Adminhtml\System\Config\Form\Field\Rates;

class Countries extends \Magento\Framework\View\Element\Html\Select {
	protected $_countryCollectionFactory;
	public function __construct(
		\Magento\Framework\View\Element\Context $context,
		\Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
		array $data = []
	){
		$this->_countryCollectionFactory = $countryCollectionFactory;
		parent::__construct($context, $data);
	}
	public function setInputName($value){
		return $this->setName($value.'[]');
	}
	public function setInputId($value){
		return $this->setId($value);
	}
	public function getOptions(){
		$options = [];
		$countries = $this->_countryCollectionFactory->create()->addFieldToSelect('*');
		if($countries->count() > 0){
			foreach ($countries as $country) {
				$regions = $country->getLoadedRegionCollection();
				if($regions->count() > 0){
					$values = [];
					foreach ($regions as $region) {
						$values[] = [
							'value' => $country->getCountryId().\SY\MultipleFlatRateShipping\Helper\Data::COUNTRY_AND_REGION_SEPARATOR.$region->getRegionId(),
							'label' => $region->getName()
						];
					}
					$options[] = ['value' => $values, 'label' => $country->getName()];
				}
				else{
					$options[] = ['value' => $country->getCountryId(), 'label' => $country->getName()];
				}
			}
		}
		return $options;
	}
}