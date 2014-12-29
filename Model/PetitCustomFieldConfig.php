<?php
/**
 * [Model] PetitCustomFieldConfig
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldConfig extends BcPluginAppModel {
/**
 * モデル名
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfig';
	
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
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'PetitCustomFieldConfigMeta' => array(
			'className' => 'PetitCustomField.PetitCustomFieldConfigMeta',
			'foreignKey' => 'petit_custom_field_config_id',
			'order' => 'field_foreign_id',
			'dependent' => true,
		),
	);
	
/**
 * HABTM
 * 
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'PetitCustomFieldConfigField' => array(
			'className' => 'PetitCustomField.PetitCustomFieldConfigField',
			'joinTable' => 'petit_custom_field_config_metas',
			'foreignKey' => 'petit_custom_field_config_id',
			'associationForeignKey' => 'field_foreign_id',
			'conditions' => '',
			'order' => '',
			'limit' => '',
			'unique' => true,
			'finderQuery' => '',
			'deleteQuery' => ''
	));
	
/**
 * 初期値を取得する
 *
 * @return array
 */
	public function getDefaultValue() {
		$data = array(
			'PetitCustomFieldConfig' => array(
				'status' => true
			)
		);
		return $data;
	}
	
}
