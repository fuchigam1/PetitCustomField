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
 * ModelName
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfigField';
	
/**
 * PluginName
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
 * constructer
 * 
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		
		$validation = $this->getDefaultValidate();
		$this->validate = $validation['PetitCustomFieldConfigField'];
	}
	
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
	public $validate = array();
	
/**
 * KeyValue で利用するバリデーション内容を取得する
 * - 通常の validate プロパティにコンストラクタでセットしている
 * 
 * @return array
 */
	public function getDefaultValidate() {
		$data = $this->keyValueValidate;
		return $data;
	}
	
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
				// フィールドタイプが wysiwyg の場合はチェックするバリデーション
				'alphaNumericUnderscore' => array(
					'rule'		=> array('alphaNumericUnderscore', 'field_type'),
					'message'	=> '半角英数とアンダースコアで入力してください。',
				),
				array(
					'rule'		 => array('notInList', array('day')),
					'message'	 => 'フィールド名に利用できない文字列です。変更してください。',
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
 * 
 * @param array $check 対象データ
 * @param string $field
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
 * 英数チェックアンダースコア: アンダースコアを許容する
 * フィールドタイプが wysiwyg の場合、フィールド名にハイフンがあると正常に表示されなくなるためのチェック
 * 
 * @param array $check 対象データ
 * @param string $fieldType フィールドタイプ
 * @return	boolean
 */
	public function alphaNumericUnderscore($check, $fieldType) {
		if (!$check[key($check)]) {
			return true;
		}
		if ($this->data[$this->alias][$fieldType] == 'wysiwyg') {
			if (preg_match("/^[a-zA-Z0-9\_]+$/", $check[key($check)])) {
				return true;
			} else {
				return false;
			}
		}
		return true;
	}
	
/**
 * 初期値を取得する
 *
 * @return array
 */
	public function getDefaultValue() {
		$data = $this->keyValueDefaults;
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
	
/**
 * コントロールソースを取得する
 *
 * @param string $field フィールド名
 * @return array
 */
	public function getControlSource($field) {
		switch ($field) {
			case 'field_name':
				$conditions = array(
					$this->alias . '.' . 'key'		=> $this->alias . '.' . $field,
				);
				$controlSources['field_name'] = $this->find('list', array(
					'conditions' => $conditions,
					'fields' => array($this->alias .'.id', $this->alias .'.value'),
					'order' => array('value' => 'ASC'),
				));
				break;
		}
		if (isset($controlSources[$field])) {
			return $controlSources[$field];
		} else {
			return false;
		}
	}
	
}
