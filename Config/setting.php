<?php
/**
 * [Config] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
/**
 * システムナビ
 */
$config['BcApp.adminNavi.petit_custom_field'] = array(
		'name'		=> 'プチ・カスタムフィールドプラグイン',
		'contents'	=> array(
			array('name' => '設定一覧',
				'url' => array(
					'admin' => true,
					'plugin' => 'petit_custom_field',
					'controller' => 'petit_custom_field_configs',
					'action' => 'index')
			)
	)
);

/**
 * フィールドタイプ種別
 * 
 */
$config['petitCustomField.field_type'] = array(
	'text',
	'textarea',
	'select',
	'radio',
	'date',
	'datetime',
	'checkbox',
	'multicheckbox',
);
$config['petitCustomField.required'] = array(
	0 => '必須としない',
	1 => '必須とする',
);
