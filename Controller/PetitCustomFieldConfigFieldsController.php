<?php
/**
 * [Controller] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
App::uses('PetitCustomFieldApp', 'PetitCustomField.Controller');
class PetitCustomFieldConfigFieldsController extends PetitCustomFieldAppController {
/**
 * コントローラー名
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfigFields';
	
/**
 * モデル
 * 
 * @var array
 */
	public $uses = array('PetitCustomField.PetitCustomFieldConfigField');
	
/**
 * ぱんくずナビ
 *
 * @var string
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'プチ・カスタムフィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_configs', 'action' => 'index')),
	);
	
/**
 * 管理画面タイトル
 *
 * @var string
 */
	public $adminTitle = 'フィールド設定';
	
/**
 * beforeFilter
 *
 * @return	void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->setup();
	}
	
	public function setup() {
		$this->PetitCustomFieldConfigField->Behaviors->KeyValue->KeyValue = $this->PetitCustomFieldConfigField;
	}
	
/**
 * [ADMIN] プチ・ブログカスタムフィールド設定一覧
 * 
 * @return void
 */
	public function admin_index($id = null) {
		$this->pageTitle = $this->adminTitle . '一覧';
		$this->search = 'petit_custom_field_configs_index';
		$this->help = 'petit_custom_field_configs_index';
		
		$max = $this->PetitCustomFieldConfigField->getMax('foreign_id');
		
		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions('PetitCustomFieldConfigField', array('default' => $default));
		
		$conditions = $this->_createAdminIndexConditions($this->request->data);
		$conditions = array_merge($conditions, array('PetitCustomFieldConfigField.key' => 'PetitCustomFieldConfigField.name',));
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num']
		);
		$datas = $this->paginate('PetitCustomFieldConfigField');

		$this->set('datas', $datas);
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $id = foreign_key
 * @return void
 */
	public function admin_edit($configId = null, $foreignId = null) {
		$this->pageTitle = $this->adminTitle . '編集';
		$deletable = true;
		
		if (!$configId || !$foreignId) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		$this->crumbs[] = array('name' => 'フィールドメタ設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
		
		if (empty($this->request->data)) {
			// $data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
			$data = $this->{$this->modelClass}->getSection($foreignId, $this->modelClass);
			if ($data) {
				$this->request->data = array($this->modelClass => $data);
			}
		} else {
			// validateSection(Model $Model, $data, $section = null)
			if ($this->PetitCustomFieldConfigField->validateSection($this->request->data, 'PetitCustomFieldConfigField')) {
				//if (!$this->PetitCustomFieldModel->saveSection($contentId, $Model->data, 'PetitCustomField'))
				if ($this->PetitCustomFieldConfigField->saveSection($foreignId, $this->request->data, 'PetitCustomFieldConfigField')) {
					$message = '「'. $this->request->data['PetitCustomFieldConfigField']['name'] .'」の更新が完了しました。';
					$this->setMessage($message);
					$this->redirect(array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
				} else {
					$this->setMessage('入力エラーです。内容を修正して下さい。', true);
				}
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$this->set('configId', $configId);
		$this->set('foreignId', $foreignId);
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->set('deletable', $deletable);
		$this->render('form');
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $configId
 * @return void
 */
	public function admin_add($configId = null) {
		$this->pageTitle = $this->adminTitle . '追加';
		$this->crumbs[] = array('name' => 'カスタムフィールドメタ設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
		$deletable = false;
		$foreignId = $this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->getMax('field_foreign_id') + 1;
		
		if (empty($this->request->data)) {
			if (!$configId) {
				$this->setMessage('無効な処理です。', true);
				$this->redirect(array('action' => 'index'));
			}
			// defaultValues(Model $Model, $section = null, $key = null)
			$this->request->data = $this->PetitCustomFieldConfigField->defaultValues();
		} else {
			//if (!$this->PetitCustomFieldModel->saveSection($contentId, $Model->data, 'PetitCustomField'))
			if ($this->PetitCustomFieldConfigField->saveSection($foreignId, $this->request->data, 'PetitCustomFieldConfigField')) {
				// リンクテーブルにデータを追加する
				$saveData = array(
					'PetitCustomFieldConfigMeta' => array(
						'petit_custom_field_config_id' => $configId,
						'field_foreign_id'	=> $foreignId,
					),
				);
				$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->create($saveData);
				$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->save($saveData);
				
				$message = '「'. $this->request->data['PetitCustomFieldConfigField']['name'] .'」の追加が完了しました。';
				$this->setMessage($message);
				$this->redirect(array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$this->set('configId', $configId);
		$this->set('foreignId', $foreignId);
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->set('deletable', $deletable);
		$this->render('form');
	}
	
/**
 * [ADMIN] 削除
 * 
 * @param int $configId
 * @param int $foreignId
 * @return void
 */
	public function admin_delete($configId = null, $foreignId = null) {
		if (!$configId || !$foreignId) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		// $data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
		// 削除前にメッセージ用にカスタムフィールドを取得する
		$data = $this->PetitCustomFieldConfigField->getSection($foreignId, 'PetitCustomFieldConfigField');
		
		// resetSection(Model $Model, $foreignKey = null, $section = null, $key = null)
		if ($this->PetitCustomFieldConfigField->resetSection($foreignId)) {
			$message = '「' . $data['PetitCustomFieldConfigField']['name'] . '」を削除しました。';
			$this->setMessage($message);
			$this->redirect(array('action' => 'index', $configId));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index', $configId));
	}
	
/**
 * 一覧用の検索条件を生成する
 *
 * @param array $data
 * @return array $conditions
 */
	protected function _createAdminIndexConditions($data) {	
		$conditions = array();
		$blogContentId = '';
		
		if (isset($data['PetitCustomFieldConfigField']['content_id'])) {
			$blogContentId = $data['PetitCustomFieldConfigField']['content_id'];
		}
		
		unset($data['_Token']);
		unset($data['PetitCustomFieldConfigField']['content_id']);
		
		// 条件指定のないフィールドを解除
		if (!empty($data['PetitCustomFieldConfigField'])) {
			foreach ($data['PetitCustomFieldConfigField'] as $key => $value) {
				if ($value === '') {
					unset($data['PetitCustomFieldConfigField'][$key]);
				}
			}
			if ($data['PetitCustomFieldConfigField']) {
				$conditions = $this->postConditions($data);
			}
		}
		
		if ($blogContentId) {
			$conditions = array(
				'PetitCustomFieldConfigField.content_id' => $blogContentId
			);
		}
		
		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}
	
}
