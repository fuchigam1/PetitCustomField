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
		'Blog.BlogPost.beforeFind',
		'Blog.BlogPost.afterFind',
		'Blog.BlogPost.afterSave',
		'Blog.BlogPost.afterDelete',
		'Blog.BlogPost.beforeValidate',
		'Blog.BlogContent.beforeFind',
		'Blog.BlogContent.afterDelete',
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
	
/**
 * モデル初期化：PetitCustomField, PetitCustomFieldConfig
 * 
 * @return void
 */
	public function setup() {
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomField')) {
			$this->PetitCustomFieldModel = ClassRegistry::getObject('PetitCustomField.PetitCustomField');
		} else {
			$this->PetitCustomFieldModel = ClassRegistry::init('PetitCustomField.PetitCustomField');
		}
		$this->PetitCustomFieldModel->Behaviors->KeyValue->KeyValue = $this->PetitCustomFieldModel;
		
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomFieldConfig')) {
			$this->PetitCustomFieldConfigModel = ClassRegistry::getObject('PetitCustomField.PetitCustomFieldConfig');
		} else {
			$this->PetitCustomFieldConfigModel = ClassRegistry::init('PetitCustomField.PetitCustomFieldConfig');
		}
	}
	
/**
 * blogBlogPostBeforeFind
 * 最近の投稿、ブログ記事前後移動を find する際に実行
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function blogBlogPostBeforeFind(CakeEvent $event) {
		if (BcUtil::isAdminSystem()) {
			return $event->data;
		}
		
		$Model = $event->subject();
		// 最近の投稿、ブログ記事前後移動を find する際に実行
		// TODO get_recent_entries に呼ばれる find 判定に、より良い方法があったら改修する
		if(count($event->data[0]['fields']) === 2) {
			if(($event->data[0]['fields'][0] == 'no') && ($event->data[0]['fields'][1] == 'name')) {
				$event->data[0]['fields'][] = 'id';
				$event->data[0]['fields'][] = 'posts_date';
				$event->data[0]['fields'][] = 'blog_category_id';
				$event->data[0]['fields'][] = 'blog_content_id';
				$event->data[0]['recursive'] = 2;
			}
		}
		
		return $event->data;
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
		
		if (BcUtil::isAdminSystem()) {
			switch ($params['action']) {
				case 'admin_index':
					break;

				case 'admin_add':
					break;

				case 'admin_edit':
					$data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
					if ($data) {
						$event->data[0][0][$this->PetitCustomFieldModel->name] = $data;
					}
					break;
					
				case 'admin_preview':
					$data = $this->PetitCustomFieldModel->getSection($params['pass'][1], $this->PetitCustomFieldModel->name);
					if ($data) {
						$event->data[0][0][$this->PetitCustomFieldModel->name] = $data;
					}
					break;

				case 'admin_ajax_copy':
					break;

				default:
					break;
			}
			return;
		}
		
		if (!empty($event->data[0])) {
			foreach ($event->data[0] as $key => $value) {
				// 記事のカスタムフィールドデータを取得
				if (!empty($value['BlogPost'])) {
					// KeyValue 側のモデル情報をリセット
					$this->PetitCustomFieldModel->Behaviors->KeyValue->KeyValue = $this->PetitCustomFieldModel;
					$data = $this->PetitCustomFieldModel->getSection($value['BlogPost']['id'], $this->PetitCustomFieldModel->name);
					if ($data) {
						// カスタムフィールドデータを結合
						$event->data[0][$key][$this->PetitCustomFieldModel->name] = $data;

						$contentId = '';
						// カスタムフィールドの設定情報を取得するため、記事のブログコンテンツIDからカスタムフィールド側のコンテンツIDを取得する
						if (!empty($Model->BlogContent->data)) {
							$contentId = $Model->BlogContent->data['BlogContent']['id'];
						} else {
							$contentId = $value['BlogPost']['blog_content_id'];
						}
						$configData = $this->PetitCustomFieldConfigModel->find('first', array(
							'conditions' => array(
								'PetitCustomFieldConfig.content_id' => $contentId,
								'PetitCustomFieldConfig.model' => 'BlogContent',
							),
							'recursive' => -1,
						));

						if ($configData['PetitCustomFieldConfig']['status']) {
							// PetitCustomFieldConfigMeta::afterFind で KeyValue のモデル情報が PetitCustomFieldConfig に切り替わる
							$fieldConfigField = $this->PetitCustomFieldConfigModel->PetitCustomFieldConfigMeta->find('all', array(
								'conditions' => array(
									'PetitCustomFieldConfigMeta.petit_custom_field_config_id' => $configData['PetitCustomFieldConfig']['id']
								),
								'order'	=> 'PetitCustomFieldConfigMeta.position ASC',
								'recursive' => -1,
							));
							if ($contentId) {
								$defaultFieldValue[$contentId] = Hash::combine($fieldConfigField, '{n}.PetitCustomFieldConfigField.field_name', '{n}.PetitCustomFieldConfigField');
							} else {
								$defaultFieldValue = Hash::combine($fieldConfigField, '{n}.PetitCustomFieldConfigField.field_name', '{n}.PetitCustomFieldConfigField');
							}
							//$this->PetitCustomFieldModel->fieldConfig = $fieldConfigField;
							// カスタムフィールドへの入力データ
							$this->PetitCustomFieldModel->publicFieldData = $data;
							// カスタムフィールドのフィールド別設定データ
							$this->PetitCustomFieldModel->publicFieldConfigData = $defaultFieldValue;
						}
					}
				}
			}
		}
		
	}
	
/**
 * blogBlogPostBeforeValidate
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostBeforeValidate(CakeEvent $event) {
		$Model = $event->subject();
		$this->setup();
		$data = $this->PetitCustomFieldConfigModel->find('first', array(
			'conditions' => array('PetitCustomFieldConfig.content_id' => $Model->BlogContent->id),
			'recursive' => -1
		));
		if (!$data) {
			return true;
		}
		
		if ($data['PetitCustomFieldConfig']['status']) {
			$fieldConfigField = $this->PetitCustomFieldConfigModel->PetitCustomFieldConfigMeta->find('all', array(
				'conditions' => array(
					'PetitCustomFieldConfigMeta.petit_custom_field_config_id' => $data['PetitCustomFieldConfig']['id']
				),
				'order'	=> 'PetitCustomFieldConfigMeta.position ASC',
				'recursive' => -1,
			));
			$this->PetitCustomFieldModel->fieldConfig = $fieldConfigField;
			$this->_setValidate($fieldConfigField);
			// ブログ記事本体にエラーがない場合、beforeValidate で判定しないと、カスタムフィールド側でバリデーションエラーが起きない
			if (!$this->PetitCustomFieldModel->validateSection($Model->data, 'PetitCustomField')) {
				return false;
			}
		}
	}
	
/**
 * バリデーションを設定する
 * 
 * @param array $data 元データ
 */
	protected function _setValidate($data = array()) {
		$validation = array();
		$fieldType = '';
		$fieldName = '';
		
		foreach ($data as $key => $fieldConfig) {
			$fieldType = $fieldConfig['PetitCustomFieldConfigField']['field_type'];
			$fieldName = $fieldConfig['PetitCustomFieldConfigField']['field_name'];
			$fieldRule = array();
			
			// 必須項目のバリデーションルールを設定する
			if (!empty($fieldConfig['PetitCustomFieldConfigField']['required'])) {
				$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('notEmpty'));
				$validation[$fieldName] = $fieldRule;
			}
			
			switch ($fieldType) {
				// フィールドタイプがテキストの場合は、最大文字数制限をチェックする
				case 'text':
					if ($fieldConfig['PetitCustomFieldConfigField']['max_length']) {
						$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('maxLength', array('number' => $fieldConfig['PetitCustomFieldConfigField']['max_length'])));
						$validation[$fieldName] = $fieldRule;
					}
					break;
					
				default:
					break;
			}
			
			// 入力値チェックを設定する
			if (!empty($fieldConfig['PetitCustomFieldConfigField']['validate'])) {
				
				switch ($fieldType) {
					// フィールドタイプがテキストの場合
					case 'text':
						foreach ($fieldConfig['PetitCustomFieldConfigField']['validate'] as $key => $rule) {
							if ($rule == 'HANKAKU_CHECK') {
								$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('alphaNumeric'));
								$validation[$fieldName] = $fieldRule;
							}
							if ($rule == 'NUMERIC_CHECK') {
								$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('numeric'));
								$validation[$fieldName] = $fieldRule;
							}
							if ($rule == 'REGEX_CHECK') {
								$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('regexCheck',
										array('validate_regex_message' => $fieldConfig['PetitCustomFieldConfigField']['validate_regex_message'])));
								$validation[$fieldName] = $fieldRule;
							}
						}
						break;
					// フィールドタイプがテキストエリアの場合
					case 'textarea':
						foreach ($fieldConfig['PetitCustomFieldConfigField']['validate'] as $key => $rule) {
							if ($rule == 'HANKAKU_CHECK') {
								$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('alphaNumeric'));
								$validation[$fieldName] = $fieldRule;
							}
							if ($rule == 'NUMERIC_CHECK') {
								$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('numeric'));
								$validation[$fieldName] = $fieldRule;
							}
							if ($rule == 'REGEX_CHECK') {
								$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('regexCheck',
										array('validate_regex_message' => $fieldConfig['PetitCustomFieldConfigField']['validate_regex_message'])));
								$validation[$fieldName] = $fieldRule;
							}
						}
						break;
					// フィールドタイプがマルチチェックボックスの場合
					case 'multiple':
						foreach ($fieldConfig['PetitCustomFieldConfigField']['validate'] as $key => $rule) {
							if ($rule == 'NONCHECK_CHECK') {
								$fieldRule = Hash::merge($fieldRule, $this->_getValidationRule('notEmpty',
									array('not_empty' => 'multiple', 'not_empty_message' => '必ず1つ以上選択してください。')
								));
								$validation[$fieldName] = $fieldRule;
							}
						}
						break;
						
					default:
						break;
				}
				
			}
		}
		
		$keyValueValidate = array('PetitCustomField' => $validation);
		$this->PetitCustomFieldModel->keyValueValidate = $keyValueValidate;
	}
	
/**
 * 設定可能なバリデーションルールを返す
 * 
 * @param string $rule ルール名
 * @param array $options
 * @return array
 */
	protected function _getValidationRule($rule = '', $options = array()) {
		$_options = array(
			'number' => '',
			'not_empty' => 'notEmpty',
			'not_empty_message' => '必須項目です。',
			'validate_regex_message' => '入力エラーが発生しました。',
		);
		$options = array_merge($_options, $options);
		
		$validation = array(
			'notEmpty' => array(
				'notEmpty' => array(
					'rule' => array($options['not_empty']),
					'message' => $options['not_empty_message'],
					'required' => true,
				),
			),
			'maxLength' => array(
				'maxLength' => array(
					'rule'		=> array('maxLength', $options['number']),
					'message'	=> $options['number'] .'文字以内で入力してください。',
				),
			),
			'alphaNumeric' => array(
				'alphaNumeric' => array(
					'rule' => array('alphaNumeric'),
					'message' => '半角英数で入力してください。',
				),
			),
			'numeric' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => '数値で入力してください。',
				),
			),
			'regexCheck' => array(
				'regexCheck' => array(
					'rule' => array('regexCheck'),
					'message' => $options['validate_regex_message'],
				),
			),
		);
		return $validation[$rule];
	}
	
/**
 * blogBlogPostAfterSave
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostAfterSave(CakeEvent $event) {
		$Model = $event->subject();
		
		// カスタムフィールドの入力データがない場合は save 処理を実施しない
		if (!isset($Model->data['PetitCustomField'])) {
			return;
		}
		
		$created = $event->data[0];
		if ($created) {
			$contentId = $Model->getLastInsertId();
		} else {
			$contentId = $Model->data[$Model->alias]['id'];
		}
		
		if (!$this->throwBlogPost) {
			$this->setup();
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
 * blogBlogContentAfterDelete
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		// ブログ削除時、そのブログが持つプチ・カスタムフィールド設定を削除する
		$this->setup();
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
	
}
