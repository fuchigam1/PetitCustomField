<?php
/**
 * [ModelEventListener] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldModelEventListener extends BcModelEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'Blog.BlogPost.afterFind',
		'Blog.BlogPost.afterSave',
		'Blog.BlogPost.afterDelete',
		'Blog.BlogContent.beforeFind',
		'Blog.BlogContent.afterSave',
		'Blog.BlogContent.afterDelete',
		'PetitCustomField.PetitCustomFieldConfigMeta.afterFind',
	);
	
/**
 * プチ・カスタムフィールドモデル
 * 
 * @var Object
 */
	public $PetitCustomFieldModel = null;
	
/**
 * プチ・カスタムフィールド設定モデル
 * 
 * @var Object
 */
	public $PetitCustomFieldConfigModel = null;
	
/**
 * ブログ記事多重保存の判定
 * 
 * @var boolean
 */
	public $throwBlogPost = false;
	
	public function setup() {
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomField')) {
			$this->PetitCustomFieldModel = ClassRegistry::getObject('PetitCustomField.PetitCustomField');
		} else {
			$this->PetitCustomFieldModel = ClassRegistry::init('PetitCustomField.PetitCustomField');
		}
		$this->PetitCustomFieldModel->Behaviors->KeyValue->KeyValue = $this->PetitCustomFieldModel;
	}
	
/**
 * petitCustomFieldPetitCustomFieldConfigMetaAfterFind
 * カスタムフィールド設定メタ情報取得の際に、カスタムフィールド設定情報も併せて取得する
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function petitCustomFieldPetitCustomFieldConfigMetaAfterFind(CakeEvent $event) {
		$Model = $event->subject();
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomFieldConfigField')) {
			$this->PetitCustomFieldConfigFieldModel = ClassRegistry::getObject('PetitCustomField.PetitCustomFieldConfigField');
		} else {
			$this->PetitCustomFieldConfigFieldModel = ClassRegistry::init('PetitCustomField.PetitCustomFieldConfigField');
		}
		$this->PetitCustomFieldConfigFieldModel->Behaviors->KeyValue->KeyValue = $this->PetitCustomFieldConfigFieldModel;
		
		if ($event->data[0]) {
			foreach ($event->data[0] as $key => $value) {
				// $data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
				// $data = $this->{$this->modelClass}->getSection($foreignId, $this->modelClass);
				// getMax等のfindの際にはモデル名をキーとしたデータが入ってこないため判定
				if (isset($value['PetitCustomFieldConfigMeta'])) {
					$dataField = $this->PetitCustomFieldConfigFieldModel->getSection($value['PetitCustomFieldConfigMeta']['field_foreign_id'], 'PetitCustomFieldConfigField');
					if ($dataField) {
						$_dataField['PetitCustomFieldConfigField'] = $dataField;
						$event->data[0][$key]['PetitCustomFieldConfigField'] = $_dataField['PetitCustomFieldConfigField'];
					}
				}
			}
		}
	}
	
/**
 * blogBlogPostAfterFind
 * ブログ記事取得の際にプチ・カスタムフィールド情報も併せて取得する
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function blogBlogPostAfterFind(CakeEvent $event) {
		$Model = $event->subject();
		$params = Router::getParams();
		$this->setup();
		
		if (isset($params['plugin']) && $params['plugin'] == 'blog') {
			switch ($params['action']) {
				case 'admin_index':
					break;

				case 'admin_add':
					break;

				case 'admin_edit':
					$data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
					$data = $this->PetitCustomFieldModel->unserializeData($data);
					if ($data) {
						$event->data[0][0][$this->PetitCustomFieldModel->name] = $data;
					}
					break;

				case 'admin_ajax_copy':
					break;

				default:
					break;
			}
		}
		
	}
	
/**
 * blogBlogPostAfterSave
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostAfterSave(CakeEvent $event) {
		$Model = $event->subject();
		$created = $event->data[0];
		if ($created) {
			$contentId = $Model->getLastInsertId();
		} else {
			$contentId = $Model->data[$Model->alias]['id'];
		}
		
		//$saveData = $this->generateSaveData($Model, $contentId);
		if (!$this->throwBlogPost) {
			$this->setup();
			// TODO マルチチェックボックスへの対応
			// - 配列で送られた値はシリアライズ化する
			if (isset($Model->data['PetitCustomField'])) {
				foreach ($Model->data['PetitCustomField'] as $key => $value) {
					if (is_array($value)) {
						// BcUtil::serialize(unserialize($data['UserGroup']['default_favorites']));
						$serializeData = serialize($value);
						$Model->data['PetitCustomField'][$key] = $serializeData;
					}
				}
			}
			
			if (!$this->PetitCustomFieldModel->saveSection($contentId, $Model->data, 'PetitCustomField')) {
				$this->log(sprintf('ブログ記事ID：%s のカスタムフィールドの保存に失敗', $contentId));
			}
		}
		// ブログ記事コピー保存時、アイキャッチが入っていると処理が2重に行われるため、1周目で処理通過を判定し、
		// 2周目では保存処理に渡らないようにしている
		$this->throwBlogPost = true;
	}
	
/**
 * blogBlogPostAfterDelete
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ記事削除時、そのブログ記事が持つプチ・カスタムフィールドを削除する
		$this->setup();
		$data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
		if ($data) {
			//resetSection(Model $Model, $foreignKey = null, $section = null, $key = null)
			if (!$this->PetitCustomFieldModel->resetSection($Model->id, $this->PetitCustomFieldModel->name)) {
				$this->log(sprintf('ブログ記事ID：%s のカスタムフィールドの削除に失敗', $Model->id));
			}
		}
	}
	
/**
 * blogBlogContentBeforeFind
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function blogBlogContentBeforeFind(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ設定取得の際にプチ・カスタム設定情報も併せて取得する
		$association = array(
			'PetitCustomFieldConfig' => array(
				'className' => 'PetitCustomField.PetitCustomFieldConfig',
				'foreignKey' => 'content_id',
			)
		);
		$Model->bindModel(array('hasOne' => $association));
	}
	
/**
 * blogBlogContentAfterSave
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentAfterSave(CakeEvent $event) {
		$Model = $event->subject();
		$created = $event->data[0];
		if ($created) {
			$contentId = $Model->getLastInsertId();
			$saveData = $this->generateContentSaveData($Model, $contentId);
			// ブログ設定追加時に設定情報を保存する
			$this->PetitCustomFieldConfigModel->create($saveData);
			if (!$this->PetitCustomFieldConfigModel->save()) {
				$this->log(sprintf('ID：%s のプチ・カスタムフィールド設定の保存に失敗しました。', $Model->data['PetitCustomFieldConfig']['id']));
			}
		}
	}
	
/**
 * blogBlogContentAfterDelete
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ削除時、そのブログが持つプチ・カスタムフィールド設定を削除する
		$this->PetitCustomFieldConfigModel = ClassRegistry::init('PetitCustomField.PetitCustomFieldConfig');
		$data = $this->PetitCustomFieldConfigModel->find('first', array(
			'conditions' => array('PetitCustomFieldConfig.content_id' => $Model->id),
			'recursive' => -1
		));
		if ($data) {
			if (!$this->PetitCustomFieldConfigModel->delete($data['PetitCustomFieldConfig']['id'])) {
				$this->log('ID:' . $data['PetitCustomFieldConfig']['id'] . 'のプチ・カスタムフィールド設定の削除に失敗しました。');
			}
		}		
	}
	
/**
 * 保存するデータの生成
 * 
 * @param Object $Model
 * @param int $contentId
 * @return array
 */
	public function generateSaveData($Model, $contentId) {
		$params = Router::getParams();
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomField')) {
			$this->PetitCustomFieldModel = ClassRegistry::getObject('PetitCustomField.PetitCustomField');
		} else {
			$this->PetitCustomFieldModel = ClassRegistry::init('PetitCustomField.PetitCustomField');
		}
		
		$data = array();
		$modelId = $oldModelId = null;
		if ($Model->alias == 'BlogPost') {
			$modelId = $contentId;
			if(!empty($params['pass'][1])) {
				$oldModelId = $params['pass'][1];
			}
		}
		
		if ($contentId) {
			$data = $this->PetitCustomFieldModel->find('first', array('conditions' => array(
				'PetitCustomField.blog_post_id' => $contentId
			)));
		}
		
		switch ($params['action']) {
			case 'admin_add':
				// 追加時
				if (!empty($Model->data['PetitCustomField'])) {
					$data['PetitCustomField'] = $Model->data['PetitCustomField'];
				}
				$data['PetitCustomField']['blog_post_id'] = $contentId;
				break;
				
			case 'admin_edit':
				// 編集時
				if (!empty($Model->data['PetitCustomField'])) {
					$data['PetitCustomField'] = $Model->data['PetitCustomField'];
				}
				break;
				
			case 'admin_ajax_copy':
				// Ajaxコピー処理時に実行
				// ブログコピー保存時にエラーがなければ保存処理を実行
				if (empty($Model->validationErrors)) {
					$_data = array();
					if ($oldModelId) {
						$_data = $this->PetitCustomFieldModel->find('first', array(
							'conditions' => array(
								'PetitCustomField.blog_post_id' => $oldModelId
							),
							'recursive' => -1
						));
					}
					// XXX もしカスタムフィールド設定の初期データ作成を行ってない事を考慮して判定している
					if ($_data) {
						// コピー元データがある時
						$data['PetitCustomField'] = $_data['PetitCustomField'];
						$data['PetitCustomField']['blog_post_id'] = $contentId;
						unset($data['PetitCustomField']['id']);
					} else {
						// コピー元データがない時
						$data['PetitCustomField']['blog_post_id'] = $modelId;
					}
				}
				break;
				
			default:
				break;
		}
		
		return $data;
	}
	
/**
 * 保存するデータの生成
 * 
 * @param Object $Model
 * @param int $contentId
 * @return array
 */
	public function generateContentSaveData($Model, $contentId) {
		$params = Router::getParams();
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomFieldConfig')) {
			$this->PetitCustomFieldConfigModel = ClassRegistry::getObject('PetitCustomField.PetitCustomFieldConfig');
		} else {
			$this->PetitCustomFieldConfigModel = ClassRegistry::init('PetitCustomField.PetitCustomFieldConfig');
		}
		
		$data = array();
		if ($Model->alias == 'BlogContent') {
			$modelId = $contentId;
			if (isset($params['pass'][0])) {
				$oldModelId = $params['pass'][0];
			}
		}
		
		if ($contentId) {
			$data = $this->PetitCustomFieldConfigModel->find('first', array('conditions' => array(
				'PetitCustomFieldConfig.content_id' => $contentId
			)));
		}
		
		switch ($params['action']) {
			case 'admin_add':
				// 追加時
				if (!empty($Model->data['PetitCustomFieldConfig'])) {
					$data['PetitCustomFieldConfig'] = $Model->data['PetitCustomFieldConfig'];
				}
				$data['PetitCustomFieldConfig']['content_id'] = $contentId;
				$data['PetitCustomFieldConfig']['model'] = $Model->alias;
				break;
				
			case 'admin_edit':
				// 編集時
				$data['PetitCustomFieldConfig'] = array_merge($data['PetitCustomFieldConfig'], $Model->data['PetitCustomFieldConfig']);
				break;
				
			case 'admin_ajax_copy':
				// Ajaxコピー処理時に実行
				// ブログコピー保存時にエラーがなければ保存処理を実行
				if (empty($Model->validationErrors)) {
					$_data = $this->PetitCustomFieldConfigModel->find('first', array(
						'conditions' => array(
							'PetitCustomFieldConfig.content_id' => $oldModelId
						),
						'recursive' => -1
					));
					// XXX もしキーワード設定の初期データ作成を行ってない事を考慮して判定している
					if ($_data) {
						// コピー元データがある時
						$data = Hash::merge($data, $_data);
						$data['PetitCustomFieldConfig']['content_id'] = $contentId;
						unset($data['PetitCustomFieldConfig']['id']);
					} else {
						// コピー元データがない時
						$data['PetitCustomFieldConfig']['content_id'] = $modelId;
					}
				}
				break;
				
			default:
				break;
		}
		
		return $data;
	}
	
}
