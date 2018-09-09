<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Model\Carrier;

use Magento\Shipping\Model\Rate\Result;

class Shipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements \Magento\Shipping\Model\Carrier\CarrierInterface {
	protected $_code = 'sy_multiple_flat_rate_shipping';
	protected $_helper;
	public function __construct(
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
		\Psr\Log\LoggerInterface $logger,
		\Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
		\Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
		\SY\MultipleFlatRateShipping\Helper\Rates $helper,
		array $data = []
	) {
		$this->_helper = $helper;
		$this->_rateResultFactory = $rateResultFactory;
		$this->_rateMethodFactory = $rateMethodFactory;
		parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
	}
	public function getAllowedMethods() {
		return [$this->_code => $this->getConfigData('name')];
	}
	public function collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request) {
		if (!$this->getConfigFlag('active')) {
			return false;
		}
		$result = $this->_rateResultFactory->create();
		$rates = $this->_helper->getRates($request->getStoreId());
		if(is_array($rates) && !empty($rates)){
			foreach ($rates as $rate) {
				$countries = $rate->getData('countries');
				$destCountryId = $request->getDestCountryId();
				if(array_key_exists($destCountryId, $countries)){
					$country = $countries[$destCountryId];
					if((is_bool($country) && $country == true) || 
						(is_array($country) && array_key_exists($request->getDestRegionId(), $country))){
						$method = $this->_rateMethodFactory->create();
						$method->setCarrier($this->_code);
						$method->setCarrierTitle($this->getConfigData('title'));
						$method->setMethod($rate->getCode());
						$method->setMethodTitle($rate->getTitle());
						$amount = $rate->getPrice();
						if($rate->getType() == \SY\MultipleFlatRateShipping\Helper\Data::PER_ITEM_TYPE){
							$amount = $request->getPackageQty()*$amount;
						}
						$method->setPrice($amount);
						$method->setCost($amount);
						$result->append($method);
					}
				}
			}
		}
		return $result;
	}
}