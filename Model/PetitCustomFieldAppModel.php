<?php
/**
 * [Model] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldAppModel extends BcPluginAppModel {
	
/**
 * シリアライズされているデータを復元する
 * 
 * @param array $data
 * @return array
 */
	public function unserializeData($data = array()) {
		foreach ($data as $key => $value) {
			// TODO BcUtil::unserialize を利用するとエラーが発生するため通常のシリアライズを利用する
			if ($judge = @unserialize($value[$this->alias]['value'])) {
				$data[$key][$this->alias]['value'] = $judge;
			}
		}
		return $data;
	}
	
}
