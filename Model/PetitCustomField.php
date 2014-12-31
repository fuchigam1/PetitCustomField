<?php
/**
 * [Model] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomField extends BcPluginAppModel {
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
 *
 * @var array
 */
	public $validate = array(
	);
	
/**
 * 初期値を取得する
 * 初期値は PetitCustomFieldControllerEventListener でフィールド設定から生成している
 * 
 * @return array
 */
	public function getDefaultValue() {
		$data = array(
			'PetitCustomField' => array(
			)
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
		'PetitCustomField' => array(
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
		'PetitCustomField' => array(),
	);
	
/**
 * シリアライズされているデータを復元する
 * 
 * @param array $data
 * @return array
 */
	public function unserializeData($data = array()) {
		foreach ($data as $key => $value) {
			// TODO BcUtil::unserialize を利用するとエラーが発生するため通常のシリアライズを利用する
			if ($judge = @unserialize($value)) {
				$data[$key] = $judge;
			} else {
				$data[$key] = $value;
			}
		}
		return $data;
	}
	
}
