<?php
/**
 * [Model] PetitCustomFieldConfig
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldConfigMeta extends BcPluginAppModel {
/**
 * モデル名
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfigMeta';
	
/**
 * プラグイン名
 * 
 * @var string
 */
	public $plugin = 'PetitCustomField';
	
/**
 * actsAs
 * 
 * @var array
 */
	public $actsAs = array('BcCache');
	
/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'PetitCustomFieldConfig' => array(
			'className' => 'PetitCustomField.PetitCustomFieldConfig',
			'foreignKey' => 'petit_custom_field_config_id'
		),
	);
	
}
