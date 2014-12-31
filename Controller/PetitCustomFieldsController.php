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
class PetitCustomFieldsController extends PetitCustomFieldAppController {
/**
 * コントローラー名
 * 
 * @var string
 */
	public $name = 'PetitCustomFields';
	
/**
 * モデル
 * 
 * @var array
 */
	public $uses = array('PetitCustomField.PetitCustomField', 'PetitCustomField.PetitCustomFieldConfig');
	
/**
 * ぱんくずナビ
 *
 * @var string
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'プチ・カスタムフィールド管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_fields', 'action' => 'index'))
	);
	
/**
 * 管理画面タイトル
 *
 * @var string
 */
	public $adminTitle = 'プチ・カスタムフィールド';
	
/**
 * beforeFilter
 *
 * @return	void
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
/**
 * [ADMIN] 一覧
 * 
 * @return void
 */
	public function admin_index() {
		$this->pageTitle = $this->adminTitle . '一覧';
		$this->search = 'petit_custom_fields_index';
		$this->help = 'petit_custom_fields_index';
		
		parent::admin_index();
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 */
	public function admin_edit($id = null) {
		if(!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));			
		}
		
		if(empty($this->request->data)) {
			$this->{$this->modelClass}->id = $id;
			$this->request->data = $this->{$this->modelClass}->read();
			$configData = $this->PetitCustomFieldConfig->find('first', array(
				'conditions' => array(
					'PetitCustomFieldConfig.content_id' => $this->request->data[$this->modelClass]['content_id']
				)));
			$this->request->data['PetitCustomFieldConfig'] = $configData['PetitCustomFieldConfig'];
		} else {
			$configData = $this->PetitCustomFieldConfig->find('first', array(
				'conditions' => array(
					'PetitCustomFieldConfig.content_id' => $this->request->data[$this->modelClass]['content_id']
				)));
			$this->request->data['PetitCustomFieldConfig'] = $configData['PetitCustomFieldConfig'];

			$this->{$this->modelClass}->set($this->request->data);
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setMessage('更新が完了しました。');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		
		$this->pageTitle = $this->adminTitle . '編集';
		$this->render('form');
	}
	
/**
 * [ADMIN] 削除
 *
 * @param int $id
 * @return void
 */
	public function admin_delete($id = null) {
		parent::admin_delete($id);
	}
	
/**
 * 一覧用の検索条件を生成する
 *
 * @param array $data
 * @return array $conditions
 */
	protected function _createAdminIndexConditions($data) {
		$conditions = array();
		$name = '';
		$blogContentId = '';
		
		if(isset($data['PetitCustomField']['name'])) {
			$name = $data['PetitCustomField']['name'];
		}
		if(isset($data['PetitCustomField']['content_id'])) {
			$blogContentId = $data['PetitCustomField']['content_id'];
		}
		if(isset($data['PetitCustomField']['status']) && $data['PetitCustomField']['status'] === '') {
			unset($data['PetitCustomField']['status']);
		}
		
		unset($data['_Token']);
		unset($data['PetitCustomField']['name']);
		unset($data['PetitCustomField']['content_id']);
		
		// 条件指定のないフィールドを解除
		foreach($data['PetitCustomField'] as $key => $value) {
			if($value === '') {
				unset($data['PetitCustomField'][$key]);
			}
		}
		
		if($data['PetitCustomField']) {
			$conditions = $this->postConditions($data);
		}
		/*
		if($name) {
			$conditions[] = array(
				'PetitCustomField.name LIKE' => '%'.$name.'%'
			);
		}*/
		// １つの入力指定から複数フィールド検索指定
		if($name) {
			$conditions['or'][] = array(
				'PetitCustomField.name LIKE' => '%'.$name.'%'
			);
		}
		if($blogContentId) {
			$conditions['and'] = array(
				'PetitCustomField.content_id' => $blogContentId
			);
		}
		
		if($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}
	
}
