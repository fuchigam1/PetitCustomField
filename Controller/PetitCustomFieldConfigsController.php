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
class PetitCustomFieldConfigsController extends PetitCustomFieldAppController {
/**
 * ControllerName
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfigs';
	
/**
 * Model
 * 
 * @var array
 */
	public $uses = array('PetitCustomField.PetitCustomFieldConfig', 'PetitCustomField.PetitCustomField');
	
/**
 * ぱんくずナビ
 *
 * @var string
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'プチ・カスタムフィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_configs', 'action' => 'index'))
	);
	
/**
 * 管理画面タイトル
 *
 * @var string
 */
	public $adminTitle = 'プチ・カスタムフィールド設定';
	
/**
 * beforeFilter
 *
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
/**
 * [ADMIN] プチ・カスタムフィールド設定一覧
 * 
 */
	public function admin_index() {
		$this->pageTitle = $this->adminTitle . '一覧';
		$this->search = 'petit_custom_field_configs_index';
		$this->help = 'petit_custom_field_configs_index';
		
		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions('PetitCustomFieldConfig', array('default' => $default));
		
		$conditions = $this->_createAdminIndexConditions($this->request->data);
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num']
		);
		
		$this->set('datas', $this->paginate('PetitCustomFieldConfig'));
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 */
	public function admin_edit($id = null) {
		$this->pageTitle = $this->adminTitle . '編集';
		
		parent::admin_edit($id);
	}
	
/**
 * [ADMIN] 削除
 *
 * @param int $id
 */
	public function admin_delete($id = null) {
		parent::admin_delete($id);
	}
	
/**
 * 各ブログ別のプチ・カスタムフィールド設定データを作成する
 * - プチ・カスタムフィールド設定データがないブログ用のデータのみ作成する
 * 
 */
	public function admin_first() {
		$this->pageTitle = $this->adminTitle . 'データ作成';
		
		if ($this->request->data) {
			$count = 0;
			if ($this->blogContentDatas) {
				foreach ($this->blogContentDatas as $key => $blog) {
					
					$configData = $this->PetitCustomFieldConfig->findByContentId($key);
					if (!$configData) {
						$this->request->data['PetitCustomFieldConfig']['content_id'] = $key;
						$this->request->data['PetitCustomFieldConfig']['status'] = true;
						$this->request->data['PetitCustomFieldConfig']['model'] = 'BlogContent';
						$this->request->data['PetitCustomFieldConfig']['form_place'] = 'normal';
						$this->PetitCustomFieldConfig->create($this->request->data);
						if (!$this->PetitCustomFieldConfig->save($this->request->data, false)) {
							$this->log(sprintf('ブログID：%s の登録に失敗しました。', $key));
						} else {
							$count++;
						}
					}
					
				}
			}
			$message = sprintf('%s 件のプチ・カスタムフィールド設定を登録しました。', $count);
			$this->setMessage($message);
			$this->redirect(array('controller' => 'petit_custom_field_configs', 'action' => 'index'));
		}
		
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
		
		if (isset($data['PetitCustomFieldConfig']['content_id'])) {
			$blogContentId = $data['PetitCustomFieldConfig']['content_id'];
		}
		
		unset($data['_Token']);
		unset($data['PetitCustomFieldConfig']['content_id']);
		
		// 条件指定のないフィールドを解除
		if (!empty($data['PetitCustomFieldConfig'])) {
			foreach ($data['PetitCustomFieldConfig'] as $key => $value) {
				if ($value === '') {
					unset($data['PetitCustomFieldConfig'][$key]);
				}
			}
			if ($data['PetitCustomFieldConfig']) {
				$conditions = $this->postConditions($data);
			}
		}
		
		if ($blogContentId) {
			$conditions = array(
				'PetitCustomFieldConfig.content_id' => $blogContentId
			);
		}
		
		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}
	
}
