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
			'text' => 'テキスト',
			'textarea' => 'テキストエリア',
			'date' => '日付（年月日）',
			'datetime' => '日時（年月日時間）',
		),
		'選択' => array(
			'select' => 'セレクトボックス',
			'radio' => 'ラジオボタン',
			'checkbox' => 'チェックボックス',
			'multiple' => 'マルチチェックボックス',
			'pref' => '都道府県リスト',
		),
		'コンテンツ' => array(
			'wysiwyg' => 'Wysiwyg Editor',
			//'upload' => 'FileUpload',
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
	),
	'validate' => array(
		'半角英数チェック',
		'数字チェック',
		'チェックボックス未入力チェック',
	),
	'auto_convert' => array(
		'しない',
		'半角変換',
	),
);
