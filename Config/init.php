<?php
/**
 * [Config] PetitCustomField プラグイン用
 * データベース初期化
 */
$this->Plugin->initDb('plugin', 'PetitCustomField');

/**
 * ブログ情報を元にデータを作成する
 *   ・設定データがないブログ用のデータのみ作成する
 * 
 */
	App::uses('BlogContent', 'Blog.Model');
	$BlogContentModel = new BlogContent();
	$blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
	if ($blogContentDatas) {
		CakePlugin::load('PetitCustomField');
		App::uses('PetitCustomFieldConfig', 'PetitCustomField.Model');
		$PetitCustomFieldConfigModel = new PetitCustomFieldConfig();
		foreach ($blogContentDatas as $key => $blog) {
			$petitCustomFieldConfig = $PetitCustomFieldConfigModel->findByContentId($key);
			$savaData = array();
			if(!$petitCustomFieldConfig) {
				$savaData['PetitCustomFieldConfig']['content_id'] = $key;
				$savaData['PetitCustomFieldConfig']['status'] = true;
				$savaData['PetitCustomFieldConfig']['model'] = 'BlogContent';
				$savaData['PetitCustomFieldConfig']['form_place'] = 'normal';
				$PetitCustomFieldConfigModel->create($savaData);
				$PetitCustomFieldConfigModel->save($savaData, array(
					'validate' => false,
					'callbacks' => false
				));
			}
		}
	}
