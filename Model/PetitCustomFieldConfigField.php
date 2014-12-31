<?php
/**
 * [Model] PetitCustomFieldConfig
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
App::uses('PetitCustomField.PetitCustomFieldAppModel', 'Model');
class PetitCustomFieldConfigField extends PetitCustomFieldAppModel {
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
 * 保存時の foreign_id
 * 
 * @var int
 */
	public $foreignId = null;
	
/**
 * バリデーション
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message'	=> 'カスタムフィールド名を入力してください。',
				'required'	=> true,
			),
			'maxLength' => array(
				'rule'		=> array('maxLength', 255),
				'message'	=> '255文字以内で入力してください。',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => '入力内容は既に使用されています。変更してください。',
			),
		),
		'label_name' => array(
			'notEmpty' => array(
				'rule'		=> array('notEmpty'),
				'message'	=> 'ラベル名を入力してください。',
				'required'	=> true,
			),
			'maxLength' => array(
				'rule'		=> array('maxLength', 255),
				'message'	=> '255文字以内で入力してください。',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => '入力内容は既に使用されています。変更してください。',
			),
		),
		'field_name' => array(
			'notEmpty' => array(
				'rule'		=> array('notEmpty'),
				'message'	=> 'フィールド名を入力してください。',
				'required'	=> true,
			),
			'maxLength' => array(
				'rule'		=> array('maxLength', 255),
				'message'	=> '255文字以内で入力してください。',
			),
			'alphaNumericPlus' => array(
				'rule'		=> array('alphaNumericPlus'),
				'message'	=> '半角英数で入力してください。',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => '入力内容は既に使用されています。変更してください。',
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
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message'	=> 'カスタムフィールド名を入力してください。',
					'required'	=> true,
				),
				'maxLength' => array(
					'rule'		=> array('maxLength', 255),
					'message'	=> '255文字以内で入力してください。',
				),
				'duplicateKeyValue' => array(
					'rule' => array('duplicateKeyValue', 'name'),
					'message' => '入力内容は既に使用されています。変更してください。',
				),
			),
			'label_name' => array(
				'notEmpty' => array(
					'rule'		=> array('notEmpty'),
					'message'	=> 'ラベル名を入力してください。',
					'required'	=> true,
				),
				'maxLength' => array(
					'rule'		=> array('maxLength', 255),
					'message'	=> '255文字以内で入力してください。',
				),
				'duplicateKeyValue' => array(
					'rule' => array('duplicateKeyValue', 'label_name'),
					'message' => '入力内容は既に使用されています。変更してください。',
				),
			),
			'field_name' => array(
				'notEmpty' => array(
					'rule'		=> array('notEmpty'),
					'message'	=> 'フィールド名を入力してください。',
					'required'	=> true,
				),
				'maxLength' => array(
					'rule'		=> array('maxLength', 255),
					'message'	=> '255文字以内で入力してください。',
				),
				'alphaNumericPlus' => array(
					'rule'		=> array('alphaNumericPlus'),
					'message'	=> '半角英数で入力してください。',
				),
				'duplicateKeyValue' => array(
					'rule' => array('duplicateKeyValue', 'field_name'),
					'message' => '入力内容は既に使用されています。変更してください。',
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
 * データの重複チェックを行う
 * @param array $check
 * @return boolean
 */
	public function duplicateKeyValue($check, $field) {
		if (!$this->foreignId) {
			return true;
		}
		
		//$conditions = array($this->alias . '.' . key($check) => $check[key($check)]);
		$conditions = array(
			$this->alias . '.' . 'key'		=> $this->alias . '.' . $field,
			$this->alias . '.' . 'value'	=> $check[key($check)],
			'NOT' => array($this->alias . '.foreign_id' => $this->foreignId),
		);
		$ret = $this->find('first', array(
			'conditions' => $conditions,
			'recursive' => -1,
		));
		if ($ret) {
			return false;
		} else {
			return true;
		}
	}
	
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
	
/**
 * beforeSave
 * マルチチェックボックスへの対応：配列で送られた値はシリアライズ化する
 * 
 * @param array $options
 * @return boolean
 */
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (is_array($this->data[$this->alias]['value'])) {
			$serializeData = serialize($this->data[$this->alias]['value']);
			$this->data[$this->alias]['value'] = $serializeData;
		}
		return true;
	}
	
/**
 * afterFind
 * シリアライズされているデータを復元して返す
 * 
 * @param array $results
 * @param boolean $primary
 */
	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);
		$results = $this->unserializeData($results);
		return $results;
	}
	
}
