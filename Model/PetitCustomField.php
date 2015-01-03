<?php
/**
 * [Model] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
App::uses('PetitCustomField.PetitCustomFieldAppModel', 'Model');
class PetitCustomField extends PetitCustomFieldAppModel {
/**
 * モデル名
 * 
 * @var string
 */
	public $name = 'PetitCustomField';
	
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
 * バリデーション
 * - PetitCustomFieldModelEventListener::_setValidate にて設定する
 * 
 * @var array
 */
	public $validate = array();
	
/**
 * KeyValue で利用するバリデーション
 * - actAs の validate 指定が空の際に、このプロパティ値が利用される
 * - モデル名をキーに指定しているのは、KeyValueBehavior の validateSection への対応のため
 * 
 * @var array
 */
	public $keyValueValidate = array(
		'PetitCustomField' => array(),
	);
	
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
 * - 初期値は PetitCustomFieldControllerEventListener でフィールド設定から生成している
 * 
 * @var array
 */
	public $keyValueDefaults = array(
		'PetitCustomField' => array(),
	);
	
/**
 * 保存データに対するカスタムフィールドの設定情報
 * 
 * @var array
 */
	public $fieldConfig = array();
	
/**
 * カスタムフィールドへの入力データ
 * 
 * @var array
 */
	public $publicFieldData = array();
	
/**
 * カスタムフィールドのフィールド別設定データ
 * 
 * @var array
 */
	public $publicFieldConfigData = array();
	
/**
 * カスタムフィールド設定データ
 * 
 * @var array
 */
	public $publicConfigData = array();
/**
 * beforeSave
 * マルチチェックボックスへの対応：配列で送られた値はシリアライズ化する
 * 
 * @param array $options
 * @return boolean
 */
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		
		$this->data[$this->alias] = $this->autoConvert($this->data[$this->alias]);
		
		// 配列で送られた値はシリアライズ化する
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
 * フィールド設定情報をもとに保存文字列の自動変換処理を行う
 * - 変換指定が有効の際に変換する
 * 
 * @param array $data
 * @return array $data
 */
	public function autoConvert($data = array()) {
		// データをキー名をモデル名とキーに分割し、[Model][key]の形式に変換する
		// $data[key] = PetitCustomField.selectpref
		$detailArray = array();
		$keyArray = preg_split('/\./', $data['key'], 2);
		$detailArray[$keyArray[0]][$keyArray[1]] = $data['value'];
		
		foreach ($this->fieldConfig as $config) {
			$config = $config['PetitCustomFieldConfigField'];
			if ($keyArray[1] == $config['field_name']) {
				if ($config['auto_convert'] == 'CONVERT_HANKAKU') {
					switch ($config['field_type']) {
						case 'text':
							// 全角英数字を半角に変換する処理を行う
							$data['value'] = mb_convert_kana($data['value'], 'a');
							break;
						
						case 'textarea':
							// 全角英数字を半角に変換する処理を行う
							$data['value'] = mb_convert_kana($data['value'], 'a');
							break;
						
						default:
							break;
					}
				}
			}
		}
		return $data;
	}
	
}
