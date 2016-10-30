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

class PetitCustomFieldConfigFieldsController extends PetitCustomFieldAppController
{

	/**
	 * ControllerName
	 * 
	 * @var string
	 */
	public $name = 'PetitCustomFieldConfigFields';

	/**
	 * Model
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
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		// カスタムフィールド設定からコンテンツIDを取得してセット
		if (!empty($this->request->params['pass'][0])) {
			$configData = $this->PetitCustomFieldConfigField->PetitCustomFieldConfig->find('first', array(
				'conditions' => array('PetitCustomFieldConfig.id' => $this->request->params['pass'][0]),
				'recursive'	 => -1,
			));
			$this->set('contentId', $configData['PetitCustomFieldConfig']['content_id']);
		}
	}

	/**
	 * [ADMIN] 編集
	 * 
	 * @param int $configId
	 * @param int $foreignId
	 */
	public function admin_edit($configId = null, $foreignId = null)
	{
		$this->pageTitle = $this->adminTitle . '編集';
		$this->help		 = 'petit_custom_field_config_fields';
		$deletable		 = true;

		if (!$configId || !$foreignId) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}

		$this->crumbs[] = array('name' => 'フィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));

		if (empty($this->request->data)) {
			// $data = $this->PetitCustomFieldModel->getSection($Model->id, $this->PetitCustomFieldModel->name);
			$data = $this->{$this->modelClass}->getSection($foreignId, $this->modelClass);
			if ($data) {
				$this->request->data = array($this->modelClass => $data);
			}
		} else {
			// バリデーション重複チェックのため、foreign_id をモデルのプロパティに持たせる
			$this->PetitCustomFieldConfigField->foreignId = $foreignId;
			if ($this->PetitCustomFieldConfigField->validateSection($this->request->data, 'PetitCustomFieldConfigField')) {
				if ($this->PetitCustomFieldConfigField->saveSection($foreignId, $this->request->data, 'PetitCustomFieldConfigField')) {
					$message = $this->name . '「' . $this->request->data['PetitCustomFieldConfigField']['name'] . '」を更新しました。';
					$this->setMessage($message, false, true);
					$this->redirect(array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
				} else {
					$this->setMessage('入力エラーです。内容を修正して下さい。', true);
				}
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}

		$fieldNameList = $this->PetitCustomFieldConfigField->getControlSource('field_name');
		$this->set(compact('fieldNameList', 'configId', 'foreignId', 'deletable'));
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->render('form');
	}

	/**
	 * [ADMIN] 編集
	 * 
	 * @param int $configId
	 */
	public function admin_add($configId = null)
	{
		$this->pageTitle = $this->adminTitle . '追加';
		$this->help		 = 'petit_custom_field_config_fields';
		$deletable		 = false;

		$this->crumbs[]	 = array('name' => 'カスタムフィールド設定管理', 'url' => array('plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
		$foreignId		 = $this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->getMax('field_foreign_id') + 1;

		if (!$configId) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'petit_custom_field_configs', 'action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->PetitCustomFieldConfigField->defaultValues();
		} else {
			if ($this->PetitCustomFieldConfigField->validateSection($this->request->data, 'PetitCustomFieldConfigField')) {
				if ($this->PetitCustomFieldConfigField->saveSection($foreignId, $this->request->data, 'PetitCustomFieldConfigField')) {

					// リンクテーブルにデータを追加する
					$saveData = array(
						'PetitCustomFieldConfigMeta' => array(
							'petit_custom_field_config_id'	 => $configId,
							'field_foreign_id'				 => $foreignId,
						),
					);
					// load しないと順番が振られない。スコープが効かない。
					$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->Behaviors->load(
							'PetitCustomField.List', array('scope' => 'petit_custom_field_config_id')
					);
					$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->create($saveData);
					$this->PetitCustomFieldConfigField->PetitCustomFieldConfigMeta->save($saveData);

					$message = $this->name . '「' . $this->request->data['PetitCustomFieldConfigField']['name'] . '」の追加が完了しました。';
					$this->setMessage($message, false, true);
					$this->redirect(array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $configId));
				} else {
					$this->setMessage('入力エラーです。内容を修正して下さい。', true);
				}
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}

		$fieldNameList = $this->PetitCustomFieldConfigField->getControlSource('field_name');
		$this->set(compact('fieldNameList', 'configId', 'foreignId', 'deletable'));
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->render('form');
	}

	/**
	 * [ADMIN] 削除
	 * 
	 * @param int $configId
	 * @param int $foreignId
	 */
	public function admin_delete($configId = null, $foreignId = null)
	{
		if (!$configId || !$foreignId) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}

		// 削除前にメッセージ用にカスタムフィールドを取得する
		$data = $this->PetitCustomFieldConfigField->getSection($foreignId, 'PetitCustomFieldConfigField');

		if ($this->PetitCustomFieldConfigField->resetSection($foreignId)) {
			$message = $this->name . '「' . $data['PetitCustomFieldConfigField']['name'] . '」を削除しました。';
			$this->setMessage($message, false, true);
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
	protected function _createAdminIndexConditions($data)
	{
		$conditions		 = array();
		$blogContentId	 = '';

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

	/**
	 * [ADMIN][AJAX] 重複値をチェックする
	 *   ・foreign_id が異なるものは重複とみなさない
	 * 
	 */
	public function admin_ajax_check_duplicate()
	{
		Configure::write('debug', 0);
		$this->layout	 = null;
		$result			 = true;

		if (!$this->RequestHandler->isAjax()) {
			$message = '許可されていないアクセスです。';
			$this->setMessage($message, true);
			$this->redirect(array('controller' => 'petit_custom_field_configs', 'action' => 'index'));
		}

		if ($this->request->data) {
			$conditions = array();
			if (array_key_exists('name', $this->request->data[$this->modelClass])) {
				$conditions = array(
					$this->modelClass . '.' . 'key'		 => $this->modelClass . '.' . 'name',
					$this->modelClass . '.' . 'value'	 => $this->request->data[$this->modelClass]['name'],
				);
			}
			if (array_key_exists('label_name', $this->request->data[$this->modelClass])) {
				$conditions = array(
					$this->modelClass . '.' . 'key'		 => $this->modelClass . '.' . 'label_name',
					$this->modelClass . '.' . 'value'	 => $this->request->data[$this->modelClass]['label_name'],
				);
			}
			if (array_key_exists('field_name', $this->request->data[$this->modelClass])) {
				$conditions = array(
					$this->modelClass . '.' . 'key'		 => $this->modelClass . '.' . 'field_name',
					$this->modelClass . '.' . 'value'	 => $this->request->data[$this->modelClass]['field_name'],
				);
			}

			if ($this->request->data[$this->modelClass]['foreign_id']) {
				$conditions = Hash::merge($conditions, array(
							'NOT' => array($this->modelClass . '.foreign_id' => $this->request->data[$this->modelClass]['foreign_id']),
				));
			}

			$ret = $this->{$this->modelClass}->find('first', array(
				'conditions' => $conditions,
				'recursive'	 => -1,
			));
			if ($ret) {
				$result = false;
			} else {
				$result = true;
			}
		}

		$this->set('result', $result);
		$this->render('ajax_result');
	}

}
