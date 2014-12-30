<?php
/**
 * [Controller] PetitCustomField 基底コントローラ
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldAppController extends BcPluginAppController {
/**
 * ヘルパー
 *
 * @var array
 */
	public $helpers = array('Blog.Blog');
	
/**
 * コンポーネント
 * 
 * @var     array
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');
	
/**
 * サブメニューエレメント
 *
 * @var array
 */
	public $subMenuElements = array('petit_custom_field');
	
/**
 * ぱんくずナビ
 *
 * @var string
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index'))
	);
	
/**
 * 管理画面タイトル
 *
 * @var string
 */
	public $adminTitle = '';
	
/**
 * ブログコンテンツデータ
 * 
 * @var array
 */
	public $blogContentDatas = array();
	
/**
 * beforeFilter
 *
 * @return	void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$judgePetitCustomFieldConfigUse = false;
		$judgePetitCustomFieldUse = false;
		$message = '';
		
		// ブログ設定データを取得
		$BlogContentModel = ClassRegistry::init('Blog.BlogContent');
		$this->blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
		// プチ・ブログカスタムフィールド設定データを取得
		//$datas = $this->PetitCustomFieldConfig->find('all', array('recursive' => -1));
		
		$this->set('judgePetitCustomFieldConfigUse', $judgePetitCustomFieldConfigUse);
		$this->set('judgePetitCustomFieldUse', $judgePetitCustomFieldUse);
		
		if (!$judgePetitCustomFieldConfigUse) {
			//$this->setMessage($message, true);
		}
		$this->set('customFieldConfig', Configure::read('petitCustomField'));
	}
	
/**
 * [ADMIN] 一覧表示
 * 
 * @return void
 */
	public function admin_index() {
		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions($this->modelClass, array('default' => $default));
		
		$conditions = $this->_createAdminIndexConditions($this->request->data);
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num']
		);
		$datas = $this->paginate($this->modelClass);
		if ($datas) {
			$this->set('datas', $datas);
		}
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
			$this->{$this->modelClass}->set($this->request->data);
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setMessage('更新が完了しました。');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->render('form');
	}
	
/**
 * [ADMIN] 削除
 *
 * @param int $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->{$this->modelClass}->delete($id)) {
			$message = 'NO.' . $id . 'のデータを削除しました。';
			$this->setMessage($message);
			$this->redirect(array('action' => 'index'));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * [ADMIN] 削除処理　(ajax)
 *
 * @param int $id
 * @return void
 */
	public function admin_ajax_delete($id = null) {
		if (!$id) {
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
		$data = $this->{$this->modelClass}->read(null, $id);
		// 削除実行
		if ($this->{$this->modelClass}->delete($id)) {
			$this->{$this->modelClass}->saveDbLog($data[$this->modelClass]['id'] .' を削除しました。');
			return true;
		} else {
			return false;
		}
	}
	
/**
 * [ADMIN] 無効状態にする
 * 
 * @param int $id
 * @return void
 */
	public function admin_unpublish($id) {	
		if (!$id) {
			$this->setMessage('この処理は無効です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->_changeStatus($id, false)) {
			$this->setMessage('「無効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->setMessage('処理に失敗しました。', true);
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * [ADMIN] 有効状態にする
 * 
 * @param int $id
 * @return void
 */
	public function admin_publish($id) {
		if (!$id) {
			$this->setMessage('この処理は無効です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->_changeStatus($id, true)) {
			$this->setMessage('「有効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->setMessage('処理に失敗しました。', true);
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * [ADMIN] 無効状態にする（AJAX）
 * 
 * @param int $id
 * @return void
 */
	public function admin_ajax_unpublish($id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, false)) {
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
 * @param int $id
 * @return void
 */
	public function admin_ajax_publish($id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, true)) {
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
 * @param int $id
 * @param boolean $status
 * @return boolean 
 */
	protected function _changeStatus($id, $status) {
		$data = $this->{$this->modelClass}->find('first', array(
			'conditions' => array('id' => $id),
			'recursive' => -1
		));
		$data[$this->modelClass]['status'] = $status;
		if ($status) {
			$data[$this->modelClass]['status'] = true;
		} else {
			$data[$this->modelClass]['status'] = false;
		}
		$this->{$this->modelClass}->set($data);
		if ($this->{$this->modelClass}->save()) {
			return true;
		} else {
			return false;
		}
	}
	
/**
 * [ADMIN] 並び順を上げる
 * 
 * @param int $id
 * @return void
 */
	public function admin_move_up($id) {
		$this->pageTitle = $this->adminTitle .'並び順繰り上げ';
		
		if (!$id) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->{$this->modelClass}->Behaviors->enabled('List')) {
			if ($this->{$this->modelClass}->moveUp($id)) {
				$message = $this->pageTitle .'ました。';
				$this->setMessage($message, false, false);
				clearViewCache();
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('データベース処理中にエラーが発生しました。', true);
			}
		} else {
			$this->setMessage('ListBehaviorが無効のモデルです。', true);
		}
		$this->render(false);
		$this->redirect(array('action' => 'index'));
	}

/**
 * [ADMIN] 並び順を下げる
 * 
 * @param int $id 
 * @return void
 */
	public function admin_move_down($id) {
		$this->pageTitle = $this->adminTitle .'並び順を繰り下げ';
		
		if (!$id) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->{$this->modelClass}->Behaviors->enabled('List')) {
			if ($this->{$this->modelClass}->moveDown($id)) {
				$message = $this->pageTitle .'ました。';
				$this->setMessage($message, false, false);
				clearViewCache();
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('データベース処理中にエラーが発生しました。', true);
			}
		} else {
			$this->setMessage('ListBehaviorが無効のモデルです。', true);
		}
		$this->render(false);
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * [ADMIN] ListBehavior利用中のデータ並び順を割り振る
 * 
 * @return void
 */
	function admin_reposition() {
		if ($this->{$this->modelClass}->Behaviors->enabled('List')) {
			if ($this->{$this->modelClass}->fixListOrder()) {
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
	
}
