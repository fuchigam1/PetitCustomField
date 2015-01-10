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
class PetitCustomFieldConfigMetasController extends PetitCustomFieldAppController {
/**
 * コントローラー名
 * 
 * @var string
 */
	public $name = 'PetitCustomFieldConfigMetas';
	
/**
 * モデル
 * 
 * @var array
 */
	public $uses = array('PetitCustomField.PetitCustomFieldConfigMeta', 'PetitCustomField.PetitCustomFieldConfigField');
	
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
	}
	
/**
 * [ADMIN] プチ・カスタムフィールド設定一覧
 * 
 * @param int $configId
 * @return void
 */
	public function admin_index($configId = null) {
		$this->pageTitle = $this->adminTitle . '一覧';
		$this->help = 'petit_custom_field_metas_index';
		$this->crumbs[] = array('name' => 'フィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
		
		// フィールド一覧の最大件数を取得し、ページネーション件数に設定する
		$max = $this->PetitCustomFieldConfigField->getMax('foreign_id');
		if (!$max) {
			$max = $this->siteConfigs['admin_list_num'];
		}
		
		$default = array(
			'named' => array(
				'num' => $max,
				'sortmode' => 0));
		$this->setViewConditions('PetitCustomFieldConfigMeta', array('default' => $default));
		
		$conditions = $this->_createAdminIndexConditions($this->request->data);
		
		// コンテンツIDで絞り込む
		if ($configId) {
			$conditions = array_merge($conditions, array('petit_custom_field_config_id' => $configId));
		}
		
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $max,
			'order'	=> 'PetitCustomFieldConfigMeta.position ASC',
		);
		$datas = $this->paginate('PetitCustomFieldConfigMeta');
		$this->set('datas', $datas);
		
		$configData = $this->PetitCustomFieldConfigMeta->PetitCustomFieldConfig->find('first', array(
			'conditions' => array('PetitCustomFieldConfig.id' => $configId),
			'recursive' => -1,
		));
		$contentId = $configData['PetitCustomFieldConfig']['content_id'];
		$this->set('contentId', $contentId);
		
		$this->set('configId', $configId);
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));			
		}
		
		if (empty($this->request->data)) {
			$this->{$this->modelClass}->id = $id;
			$this->request->data = $this->{$this->modelClass}->read();
		} else {
			$contentId = $this->request->data['PetitCustomFieldConfig']['content_id'];
			$configData = $this->PetitCustomFieldConfigMeta->PetitCustomFieldConfig->find('first', array(
				'conditions' => array(
					'PetitCustomFieldConfig.content_id' => $contentId,
				),
				'recursive' => -1,
			));
			
			// 次の位置のデータ（最初と最後以外の場合）
			$nextData = $this->PetitCustomFieldConfigMeta->lowerItem($id);
			
			// petit_custom_field_config_id
			$newFieldConfigId = $configData['PetitCustomFieldConfig']['id'];
			$this->request->data[$this->modelClass]['petit_custom_field_config_id'] = $newFieldConfigId;
			$max = $this->{$this->modelClass}->getMax('position', array(
					'PetitCustomFieldConfigMeta.petit_custom_field_config_id' => $newFieldConfigId
			));
			$max = $max + 1;
			$this->request->data[$this->modelClass]['position'] = $max;
			
			$this->{$this->modelClass}->set($this->request->data);
			if ($this->{$this->modelClass}->save($this->request->data)) {
				clearViewCache();
				clearDataCache();
				// 最後のデータの場合は何もしなくてOK
				if ($nextData) {
					if ($nextData['PetitCustomFieldConfigMeta']['position'] == 2) {
						$this->PetitCustomFieldConfigMeta->unbindModel(array('belongsTo' => array('PetitCustomFieldConfig')));
						$this->PetitCustomFieldConfigMeta->updateAll(
							array('PetitCustomFieldConfigMeta.position' => 'PetitCustomFieldConfigMeta.position - 1'),
							array('PetitCustomFieldConfigMeta.petit_custom_field_config_id' => $nextData['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'])
						);
						// 以下、どれもダメだった。。。
						// $this->PetitCustomFieldConfigMeta->moveToBottom($nextData['PetitCustomFieldConfigMeta']['id']);
						// $this->PetitCustomFieldConfigMeta->moveToTop($nextData['PetitCustomFieldConfigMeta']['id']);
						// $this->PetitCustomFieldConfigMeta->insertAt(1, $nextData['PetitCustomFieldConfigMeta']['id']);
						// $this->PetitCustomFieldConfigMeta->moveUp($nextData['PetitCustomFieldConfigMeta']['id']);
					} else {
						$newPosition = $nextData['PetitCustomFieldConfigMeta']['position'] - 1;
						$this->PetitCustomFieldConfigMeta->insertAt($newPosition, $nextData['PetitCustomFieldConfigMeta']['id']);
					}
				}
				
				$this->setMessage('更新が完了しました。');
				$this->redirect(array('action' => 'index', $configData['PetitCustomFieldConfig']['id']));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$configData['PetitCustomFieldConfig'] = $this->request->data['PetitCustomFieldConfig'];
		$this->set('configId', $configData['PetitCustomFieldConfig']['id']);
		$this->set('blogContentDatas', $this->blogContentDatas);
		$this->render('form');
	}
	
/**
 * [ADMIN] 削除
 * 
 * @param int $configId
 * @param int $id
 * @return void
 */
	public function admin_delete($configId = null, $id = null) {
		if (!$configId || !$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		$data = $this->PetitCustomFieldConfigMeta->find('first', array(
			'conditions' => array('PetitCustomFieldConfigMeta.id' => $id),
			'recursive' => -1,
		));
		// $data['PetitCustomFieldConfigMeta']['field_foreign_id']
		
		if ($this->PetitCustomFieldConfigMeta->delete($id)) {
			
			// メタ情報削除時、そのメタ情報が持つカスタムフィールド設定を削除する
			$this->PetitCustomFieldConfigField->Behaviors->KeyValue->KeyValue = $this->PetitCustomFieldConfigField;
			if ($data) {
				//resetSection(Model $Model, $foreignKey = null, $section = null, $key = null)
				if (!$this->PetitCustomFieldConfigField->resetSection($data['PetitCustomFieldConfigMeta']['field_foreign_id'], 'PetitCustomFieldConfigField')) {
					$this->log(sprintf('field_foreign_id：%s のカスタムフィールドの削除に失敗', $data['PetitCustomFieldConfigMeta']['field_foreign_id']));
				}
			}
			
			$message = 'NO.' . $id . 'のデータを削除しました。';
			$this->setMessage($message);
			$this->redirect(array('action' => 'index', $configId));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index', $configId));
	}
	
/**
 * [ADMIN] 削除処理　(ajax)
 * 
 * @param int $configId
 * @param int $id
 * @return void
 */
	public function admin_ajax_delete($configId = null, $id = null) {
		if (!$configId || !$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		// 削除実行
		if ($this->_delete($id)) {
			clearViewCache();
			exit(true);
		}
		exit();
	}
	
/**
 * データを削除する
 * 
 * @param int $id
 * @return boolean 
 */
	protected function _delete($id) {
		// メッセージ用にデータを取得
		$data = $this->PetitCustomFieldConfigMeta->read(null, $id);
		// 削除実行
		if ($this->PetitCustomFieldConfigMeta->delete($id)) {
			
			// メタ情報削除時、そのメタ情報が持つカスタムフィールド設定を削除する
			$this->PetitCustomFieldConfigField->Behaviors->KeyValue->KeyValue = $this->PetitCustomFieldConfigField;
			//resetSection(Model $Model, $foreignKey = null, $section = null, $key = null)
			if (!$this->PetitCustomFieldConfigField->resetSection($data['PetitCustomFieldConfigMeta']['field_foreign_id'], 'PetitCustomFieldConfigField')) {
				$this->log(sprintf('field_foreign_id：%s のカスタムフィールドの削除に失敗', $data['PetitCustomFieldConfigMeta']['field_foreign_id']));
			}
			
			$this->PetitCustomFieldConfigMeta->saveDbLog($data['PetitCustomFieldConfigMeta']['id'] .' を削除しました。');
			return true;
		} else {
			return false;
		}
	}
	
/**
 * [ADMIN] 無効状態にする（AJAX）
 * 
 * @param int $configId
 * @param int $id
 * @return void
 */
	public function admin_ajax_unpublish($configId = null, $id = null) {
		if (!$configId || !$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($configId, $id, false)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
		exit();
	}
	
/**
 * [ADMIN] 有効状態にする（AJAX）
 * 
 * @param int $configId
 * @param int $id
 * @return void
 */
	public function admin_ajax_publish($configId = null, $id = null) {
		if (!$configId || !$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($configId, $id, true)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
		exit();
	}
	
/**
 * ステータスを変更する
 * 
 * @param int $configId
 * @param int $id
 * @param boolean $status
 * @return boolean 
 */
	protected function _changeStatus($configId = null, $id = null, $status = false) {
		$data = $this->{$this->modelClass}->find('first', array(
			'conditions' => array('id' => $id),
			'recursive' => -1
		));
		
		if (ClassRegistry::isKeySet('PetitCustomField.PetitCustomFieldConfigField')) {
			$this->PetitCustomFieldConfigFieldModel = ClassRegistry::getObject('PetitCustomField.PetitCustomFieldConfigField');
		} else {
			$this->PetitCustomFieldConfigFieldModel = ClassRegistry::init('PetitCustomField.PetitCustomFieldConfigField');
		}
		
		$data['PetitCustomFieldConfigField']['status'] = $status;
		if ($status) {
			$data['PetitCustomFieldConfigField']['status'] = '1';
		} else {
			$data['PetitCustomFieldConfigField']['status'] = '0';
		}
		if ($this->PetitCustomFieldConfigFieldModel->saveSection($data['PetitCustomFieldConfigMeta']['field_foreign_id'], $data, 'PetitCustomFieldConfigField')) {
			return true;
		} else {
			return false;
		}
	}
	
/**
 * [ADMIN] 並び順を上げる
 * 
 * @param int $configId 
 * @param int $id
 * @return void
 */
	public function admin_move_up($configId = null, $id = null, $toTop = '') {
		$this->pageTitle = $this->adminTitle .'並び順を繰り上げ';
		
		if (!$id || !$configId) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->PetitCustomFieldConfigMeta->Behaviors->enabled('List')) {
			$moveMethod = 'moveUp';
			if ($toTop) {
				$moveMethod = 'moveToTop';
			}
			if ($this->PetitCustomFieldConfigMeta->{$moveMethod}($id)) {
				if ($toTop) {
					$message = '指定フィールドを最上段へ移動しました。';
				} else {
					$message = $this->pageTitle .'ました。';
				}
				$this->setMessage($message, false, false);
				clearViewCache();
				clearDataCache();
				$this->redirect(array('action' => 'index', $configId));
			} else {
				$this->setMessage('データベース処理中にエラーが発生しました。', true);
			}
		} else {
			$this->setMessage('ListBehaviorが無効のモデルです。', true);
		}
		$this->render(false);
		$this->redirect(array('action' => 'index', $configId));
	}
	
/**
 * [ADMIN] 並び順を下げる
 * 
 * @param int $configId 
 * @param int $id 
 * @return void
 */
	public function admin_move_down($configId = null, $id = null, $toBottom = '') {
		$this->pageTitle = $this->adminTitle .'並び順を繰り下げ';
		
		if (!$id || !$configId) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->PetitCustomFieldConfigMeta->Behaviors->enabled('List')) {
			$moveMethod = 'moveDown';
			if ($toBottom) {
				$moveMethod = 'moveToBottom';
			}
			if ($this->PetitCustomFieldConfigMeta->{$moveMethod}($id)) {
				if ($toBottom) {
					$message = '指定フィールドを最下段へ移動しました。';
				} else {
					$message = $this->pageTitle .'ました。';
				}
				$this->setMessage($message, false, false);
				clearViewCache();
				clearDataCache();
				$this->redirect(array('action' => 'index', $configId));
			} else {
				$this->setMessage('データベース処理中にエラーが発生しました。', true);
			}
		} else {
			$this->setMessage('ListBehaviorが無効のモデルです。', true);
		}
		$this->render(false);
		$this->redirect(array('action' => 'index', $configId));
	}
	
/**
 * [ADMIN] ListBehavior利用中のデータ並び順を割り振る
 * 
 * @return void
 */
	function admin_reposition() {
		if ($this->PetitCustomFieldConfigMeta->Behaviors->enabled('List')) {
			if ($this->PetitCustomFieldConfigMeta->fixListOrder($this->PetitCustomFieldConfigMeta)) {
				$message = $this->modelClass .'データに並び順（position）を割り振りました。';
				$this->setMessage($message, false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('データベース処理中にエラーが発生しました。', true);
			}
		} else {
			$this->setMessage('ListBehaviorが無効のモデルです。', true);
		}
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * 一覧用の検索条件を生成する
 *
 * @param array $data
 * @return array $conditions
 */
	protected function _createAdminIndexConditions($data) {	
		$conditions = array();
		$contentId = '';
		
		if (isset($data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'])) {
			$contentId = $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'];
		}
		
		unset($data['_Token']);
		unset($data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id']);
		
		// 条件指定のないフィールドを解除
		if (!empty($data['PetitCustomFieldConfigMeta'])) {
			foreach ($data['PetitCustomFieldConfigMeta'] as $key => $value) {
				if ($value === '') {
					unset($data['PetitCustomFieldConfigMeta'][$key]);
				}
			}
			if ($data['PetitCustomFieldConfigMeta']) {
				$conditions = $this->postConditions($data);
			}
		}
		
		if ($contentId) {
			$conditions = array(
				'PetitCustomFieldConfigMeta.petit_custom_field_config_id' => $contentId
			);
		}
		
		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}
	
}
