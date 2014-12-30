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
 *
 * @return array
 */
	public function getDefaultValue() {
		$data = array(
			'PetitCustomField' => array(
				'status'	=> 1,
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
//			'status'	=> 1,
//			'required'	=> 0,
		),
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
