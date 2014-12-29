<?php
/**
 * [ControllerEventListener] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldControllerEventListener extends BcControllerEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'initialize',
		'Blog.Blog.beforeRender',
		'Blog.BlogPosts.beforeRender',
	);
	
/**
 * petit_custom_fieldヘルパー
 * 
 * @var PetitCustomFieldHelper
 */
	public $PetitCustomField = null;
	
/**
 * petit_custom_field設定情報
 * 
 * @var array
 */
	public $petitCustomFieldConfigs = array();
	
/**
 * petit_custom_fieldモデル
 * 
 * @var Object
 */
	public $PetitCustomFieldModel = null;
	
/**
 * petit_custom_field設定モデル
 * 
 * @var Object
 */
	public $PetitCustomFieldConfigModel = null;
	
/**
 * petit_custom_fieldフィールド名設定データ
 * 
 * @var array
 */
	public $settingsPetitCustomField = array();
	
/**
 * initialize
 * 
 * @param CakeEvent $event
 */
	public function initialize(CakeEvent $event) {
		$Controller = $event->subject();
		// PetitCustomFieldヘルパーの追加
		$Controller->helpers[] = 'PetitCustomField.PetitCustomField';
		$this->settingsPetitCustomField = Configure::read('petitCustomField');
	}
	
/**
 * blogBeforeRender
 * 
 * @param CakeEvent $event
 */
	public function blogBlogBeforeRender(CakeEvent $event) {
		$Controller = $event->subject();
		// プレビューの際は編集欄の内容を送る
		// 設定値を送る
		$Controller->viewVars['customFieldConfig'] = $this->settingsPetitCustomField;
		if ($Controller->preview) {
			if (!empty($Controller->request->data['PetitCustomField'])) {
				$Controller->viewVars['post']['PetitCustomField'] = $Controller->request->data['PetitCustomField'];
			}
		}
	}
	
/**
 * blogPostsBeforeRender
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostsBeforeRender(CakeEvent $event) {
		$Controller = $event->subject();
		$this->modelInitializer($Controller);
			
		// 設定値を送る
		$Controller->viewVars['customFieldConfig'] = $this->settingsPetitCustomField;

		// ブログ記事編集・追加画面で実行
		// - startup で処理したかったが $Controller->request->data に入れるとそれを全て上書きしてしまうのでダメだった
		if ($Controller->request->params['action'] == 'admin_edit') {
			$Controller->request->data['PetitCustomFieldConfig'] = $this->petitCustomFieldConfigs['PetitCustomFieldConfig'];
			
			if ($this->petitCustomFieldConfigs['PetitCustomFieldConfig']['status']) {
				$this->judgeExistCustomFieldConfig($Controller);
			}
		}
		if ($Controller->request->params['action'] == 'admin_add') {
			if ($Controller->request->data('PetitCustomField') == null) {
				$defalut = $this->PetitCustomFieldModel->getDefaultValue();
				$Controller->request->data['PetitCustomField'] = $defalut['PetitCustomField'];
			}
			$Controller->request->data['PetitCustomFieldConfig'] = $this->petitCustomFieldConfigs['PetitCustomFieldConfig'];
		}
	}
	
/**
 * モデル登録用メソッド
 * 
 * @param Controller $Controller
 */
	public function modelInitializer($Controller) {
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomFieldConfig')) {
			$this->PetitCustomFieldConfigModel = ClassRegistry::getObject('PetitCustomField.PetitCustomFieldConfig');
		} else {
			$this->PetitCustomFieldConfigModel = ClassRegistry::init('PetitCustomField.PetitCustomFieldConfig');
		}
		// $this->petitCustomFieldConfigs = $this->PetitCustomFieldConfigModel->read(null, $Controller->BlogContent->id);
		$this->petitCustomFieldConfigs = $this->PetitCustomFieldConfigModel->find('first', array(
			'conditions' => array('PetitCustomFieldConfig.content_id' => $Controller->BlogContent->id),
			'recurseve' => -1,
		));
		$this->PetitCustomFieldModel = ClassRegistry::init('PetitCustomField.PetitCustomField');
	}
	
/**
 * ブログコンテンツ用のカスタム項目設定の有無を判定する
 * 
 * @param Controller $Controller
 */
	public function judgeExistCustomFieldConfig($Controller) {
		if (!isset($this->settingsPetitCustomField['field_name'][$Controller->BlogContent->id]) ||
			!isset($this->settingsPetitCustomField['status'][$Controller->BlogContent->id]) ||
			!isset($this->settingsPetitCustomField['type_radio'][$Controller->BlogContent->id]) ||
			!isset($this->settingsPetitCustomField['type_select'][$Controller->BlogContent->id])
		) {
			$message = '以下のファイルにて、このブログで利用するカスタム項目設定を定義してください。<br />/PetitCustomField/Config/petit_custom_field_custom.php';
			$Controller->setMessage($message, true);
		}
	}
}
