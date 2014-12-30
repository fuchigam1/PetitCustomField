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
 * プチ・カスタムフィールド用設定
 * 
 */
$config['petitCustomField'] = array(
	// フィールドタイプ種別
	'field_type' => array(
		'基本' => array(
			'text' => 'Text',
			'textarea' => 'TextArea',
			'date' => 'Date',
			'datetime' => 'Datetime',
		),
		'選択' => array(
			'select' => 'Select',
			'radio' => 'Radio',
			'checkbox' => 'Checkbox',
			'multiple' => 'MultiCheckbox',
		),
		'コンテンツ' => array(
			'wysiwyg' => 'Wysiwyg Editor',
			'upload' => 'FileUpload',
		),
	),
	// エディターのタイプ
	'editor_tool_type' => array(
			'simple' => 'Simple',
			'normal' => 'Normal',
	),
	// 必須選択
	'required' => array(
		0 => '必須としない',
		1 => '必須とする',
	)
);
