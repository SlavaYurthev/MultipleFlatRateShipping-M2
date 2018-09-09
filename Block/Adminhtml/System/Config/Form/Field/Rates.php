<?php
/**
 * Multiple Flat Rate Shipping
 * 
 * @author Slava Yurthev
 */
namespace SY\MultipleFlatRateShipping\Block\Adminhtml\System\Config\Form\Field;

class Rates extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray {
	private $_countriesRenderer;
	private function getCountriesRenderer(){
		if(!$this->_countriesRenderer){
			$this->_countriesRenderer = $this->getLayout()->createBlock(
				'SY\MultipleFlatRateShipping\Block\Adminhtml\System\Config\Form\Field\Rates\Countries',
				'',
				[
					'data' => [
						'is_render_to_js_template' => true
					]
				]
			)
			->setExtraParams('style="min-width:200px" multiple="multiple" size="10"');
		}
		return $this->_countriesRenderer;
	}
	protected function _prepareToRender(){
		$this->addColumn('code', [
				'label' => __('Code'),
				'style'=>'min-width:100px',
				'class' => 'input-text required validate-xml-identifier'
			]);
		$this->addColumn('title', [
				'label' => __('Title'), 
				'style'=>'min-width:100px',
				'class' => 'input-text required'
			]);
		$this->addColumn('price', [
				'label' => __('Price'), 
				'style'=>'min-width:100px',
				'class' => 'input-text required required-number validate-number validate-zero-or-greater'
			]);
		$this->addColumn('type', [
				'label' => __('Type'),
				'renderer' => $this->getLayout()->createBlock(
					'SY\MultipleFlatRateShipping\Block\Adminhtml\System\Config\Form\Field\Rates\Type'
				)->setExtraParams('style="min-width:150px"')
			]);
		$this->addColumn('countries', [
				'label' => __('Countries'), 
				'style'=>'min-width:150px',
				'renderer' => $this->getCountriesRenderer()
			]);
		$this->_addAfter = false;
		$this->_addButtonLabel = __('Add');
	}
	protected function _prepareArrayRow(\Magento\Framework\DataObject $row){
		$options = [];
		$countries = $row->getData('countries');
		if(is_array($countries)){
			foreach ($countries as $country) {
				$key = 'option_' . $this->getCountriesRenderer()->calcOptionHash($country);
				$options[$key] = 'selected="selected"';
			}
		}
		$row->setData('option_extra_attrs', $options);
	}
}