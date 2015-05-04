<?php
/**
 * [HelperEventListener] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
class PetitCustomFieldHelperEventListener extends BcHelperEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'Form.afterCreate',
		'Form.afterForm',
		'Form.afterEnd',
	);
	
/**
 * formAfterCreate
 * - ブログ記事追加・編集画面にプチ・カスタムフィールド編集欄を追加する
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function formAfterCreate(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return $event->data['out'];
		}
		
		$View = $event->subject();
		if ($View->request->params['controller'] != 'blog_posts') {
			return $event->data['out'];
		}
		
		$targetAction = array('admin_add', 'admin_edit');
		if (in_array($View->request->params['action'], $targetAction)) {
			$targetId = array('BlogPostForm', 'BlogPostForm');
			if (in_array($event->data['id'], $targetId)) {
				if (!empty($View->request->data['PetitCustomFieldConfig']['status'])) {
					if ($View->request->data['PetitCustomFieldConfig']['form_place'] == 'top') {
						// ブログ記事追加画面にプチ・カスタムフィールド編集欄を追加する
						echo $View->element('PetitCustomField.petit_custom_field_form');
					}
				}
			}
		}
		
		return $event->data['out'];
	}
	
/**
 * formAfterForm
 * - ブログ記事追加・編集画面にプチ・カスタムフィールド編集欄を追加する
 * 
 * @param CakeEvent $event
 */
	public function formAfterForm(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}
		
		$View = $event->subject();
		if ($View->request->params['controller'] != 'blog_posts') {
			return;
		}
		
		$targetAction = array('admin_add', 'admin_edit');
		if (in_array($View->request->params['action'], $targetAction)) {
			if (!empty($View->request->data['PetitCustomFieldConfig']['status'])) {
				if ($View->request->data['PetitCustomFieldConfig']['form_place'] == 'normal') {
					// ブログ記事追加画面にプチ・カスタムフィールド編集欄を追加する
					echo $View->element('PetitCustomField.petit_custom_field_form');
				}
			}
		}
	}
	
/**
 * formAfterCreate
 * - ブログ設定編集画面にプチ・カスタムフィールド設定編集リンクを表示する
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function formAfterEnd(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return $event->data['out'];
		}
		
		$View = $event->subject();
		if ($View->request->params['controller'] != 'blog_contents') {
			return $event->data['out'];
		}
		
		if ($View->request->params['action'] == 'admin_edit') {
			// ブログ設定編集画面にプチ・カスタムフィールドメタ設定一覧リンクを表示する
			if ($event->data['id'] == 'BlogContentAdminEditForm') {
				$output = '<div id="PetitCustomFieldConfigBox">';
				$output .= $View->BcBaser->getLink('≫プチ・カスタムフィールド設定', array(
					'plugin' => 'petit_custom_field',
					'controller' => 'petit_custom_field_config_metas',
					'action' => 'index', $View->viewVars['blogContent']['PetitCustomFieldConfig']['id']
				));
				$output .= '</div>';
				$event->data['out'] = $event->data['out'] . $output;
			}
		}
		
		return $event->data['out'];
	}
	
}
