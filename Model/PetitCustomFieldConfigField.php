<?php
/**
 * [Model] PetitCustomFieldConfig
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldConfigField extends BcPluginAppModel {
/**
 * モデル名
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfigField';
	
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
	public $actsAs = array(
		'BcCache',
		'PetitCustomField.KeyValue',
	);
	
/**
 * HABTM
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'PetitCustomFieldConfig' => array(
			'className' => 'PetitCustomField.PetitCustomFieldConfig',
			'joinTable' => 'petit_custom_field_config_metas',
			'foreignKey' => 'field_foreign_id',
			'associationForeignKey' => 'petit_custom_field_config_id',
			'conditions' => '',
			'order' => '',
			'limit' => '',
			'unique' => true,
			'finderQuery' => '',
			'deleteQuery' => ''
	));
	
/**
 * バリデーション
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			array(
				'rule' => array('notEmpty'),
				'message'	=> 'カスタムフィールド名を入力してください。',
				'required'	=> true
			),
			array(
				'rule'		=> array('maxLength', 255),
				'message'	=> '255文字以内で入力してください。'
			),
		),
		'label_name' => array(
			'notEmpty' => array(
				'rule'		=> array('notEmpty'),
				'message'	=> 'ラベル名を入力してください。',
			),
			'maxLength' => array(
				'rule'		=> array('maxLength', 255),
				'message'	=> '255文字以内で入力してください。'
			),
		),
		'field_name' => array(
			'notEmpty' => array(
				'rule'		=> array('notEmpty'),
				'message'	=> 'フィールド名を入力してください。',
			),
			'maxLength' => array(
				'rule'		=> array('maxLength', 255),
				'message'	=> '255文字以内で入力してください。'
			),
			'alphaNumericPlus' => array(
				'rule'		=> array('alphaNumericPlus'),
				'message'	=> '半角英数で入力してください。',
			),
		),
		'field_type' => array(
			'notEmpty' => array(
				'rule'		=> array('notEmpty'),
				'message'	=> 'フィールドタイプを選択してください。',
			),
		),
	);
	
/**
 * KeyValue で利用するバリデーション
 * - actAs の validate 指定が空の際に、このプロパティ値が利用される
 * - モデル名をキーに指定しているのは、KeyValueBehavior の validateSection への対応のため
 * 
 * @var array
 */
	public $keyValueValidate = array(
		'PetitCustomFieldConfigField' => array(
			'name' => array(
				array(
					'rule' => array('notEmpty'),
					'message'	=> 'カスタムフィールド名を入力してください。',
					'required'	=> true
				),
				array(
					'rule'		=> array('maxLength', 255),
					'message'	=> '255文字以内で入力してください。'
				),
			),
			'label_name' => array(
				'notEmpty' => array(
					'rule'		=> array('notEmpty'),
					'message'	=> 'ラベル名を入力してください。',
				),
				'maxLength' => array(
					'rule'		=> array('maxLength', 255),
					'message'	=> '255文字以内で入力してください。'
				),
			),
			'field_name' => array(
				'notEmpty' => array(
					'rule'		=> array('notEmpty'),
					'message'	=> 'フィールド名を入力してください。',
				),
				'maxLength' => array(
					'rule'		=> array('maxLength', 255),
					'message'	=> '255文字以内で入力してください。'
				),
				'alphaNumericPlus' => array(
					'rule'		=> array('alphaNumericPlus'),
					'message'	=> '半角英数で入力してください。',
				),
			),
			'field_type' => array(
				'notEmpty' => array(
					'rule'		=> array('notEmpty'),
					'message'	=> 'フィールドタイプを選択してください。',
				),
			),
		),
	);
	
/**
 * 初期値を取得する
 *
 * @return array
 */
	public function getDefaultValue() {
		$data = array(
			'PetitCustomFieldConfigField' => array(
				'status'	=> 1,
				'required'	=> 0,
			),
		);
		return $data;
	}
	
/**
 * KeyValue で利用する初期値の指定
 * - actAs の defaults 指定が空の際に、このプロパティ値が利用される
 * 
 * @var array
 */
	public $keyValueDefaults = array(
		'PetitCustomFieldConfigField' => array(
			'status'	=> 1,
			'required'	=> 0,
		),
	);
	
}
