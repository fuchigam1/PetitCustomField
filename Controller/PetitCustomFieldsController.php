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
	public $adminTitle = 'プチ・ブログカスタムフィールド';
	
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
 * ブログ記事のプチ・カスタムフィールドを、ブログ別に一括で登録する
 *   ・プチ・カスタムフィールドの登録がないブログ記事に登録する
 * 
 * @return void
 */
	public function admin_batch() {
		if($this->request->data) {
			// 既にプチ・カスタムフィールド登録のあるブログ記事は除外する
			// 登録済のプチ・カスタムフィールドを取得する
			$petitCustomFields = $this->PetitCustomField->find('list', array(
				'conditions' => array('PetitCustomField.content_id' => $this->request->data['PetitCustomField']['content_id']),
				'fields' => 'blog_post_id',
				'recursive' => -1));
			// プチ・カスタムフィールドの登録がないブログ記事を取得する
			$BlogPostModel = ClassRegistry::init('Blog.BlogPost');
			if($petitCustomFields) {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'NOT' => array('BlogPost.id' => $petitCustomFields),
						'BlogPost.blog_content_id' => $this->request->data['PetitCustomField']['content_id']),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			} else {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'BlogPost.blog_content_id' => $this->request->data['PetitCustomField']['content_id']),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			}
			
			// プチ・カスタムフィールドを保存した数を初期化
			$count = 0;
			if($datas) {
				foreach ($datas as $data) {
					$this->request->data['PetitCustomField']['blog_post_id'] = $data['BlogPost']['id'];
					$this->request->data['PetitCustomField']['type_radio'] = 0;
					$this->request->data['PetitCustomField']['type_select'] = 0;
					
					$this->PetitCustomField->create($this->request->data);
					if($this->PetitCustomField->save($this->request->data, false)) {
						$count++;
					} else {
						$this->log('ID:' . $data['BlogPost']['id'] . 'のブログ記事のプチ・カスタムフィールド登録に失敗');
					}
				}
			}
			$this->setMessage($count . '件のプチ・カスタムフィールドを登録しました。', false, true);
		}
		unset($petitCustomFields);
		unset($datas);
		unset($data);
		
		$registerd = array();
		foreach ($this->blogContentDatas as $key => $blog) {
			// $key : content_id
			// 登録済のプチ・カスタムフィールドを取得する
			$petitCustomFields = $this->PetitCustomField->find('list', array(
				'conditions' => array('PetitCustomField.content_id' => $key),
				'fields' => 'blog_post_id',
				'recursive' => -1));
			// プチ・カスタムフィールドの登録がないブログ記事を取得する
			$BlogPostModel = ClassRegistry::init('Blog.BlogPost');
			if($petitCustomFields) {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'NOT' => array('BlogPost.id' => $petitCustomFields),
						'BlogPost.blog_content_id' => $key),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			} else {
				$datas = $BlogPostModel->find('all', array(
					'conditions' => array(
						'BlogPost.blog_content_id' => $key),
					'fields' => array('id', 'no', 'name'),
					'recursive' => -1));
			}
			
			$registerd[] = array(
				'name' => $blog,
				'count' => count($datas)
			);
		}
		
		$this->set('registerd', $registerd);
		$this->set('blogContentDatas', $this->blogContentDatas);
		$this->pageTitle = $this->adminTitle . '一括設定';
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
			$conditions['or'][] = array(
				'PetitCustomField.name_2 LIKE' => '%'.$name.'%'
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
