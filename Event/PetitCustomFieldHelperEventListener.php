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
		'Form.afterEnd',
		'Form.afterCreate',
	);
	
/**
 * formAfterCreate
 * - ブログ記事追加・編集画面にプチ・カスタムフィールド編集欄を追加する
 * - ブログ設定編集画面にプチ・カスタムフィールド設定編集リンクを表示する
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function formAfterEnd(CakeEvent $event) {
		$View = $event->subject();
		
		if ($View->request->params['controller'] == 'blog_posts') {
			if ($View->request->action == 'admin_add' || $View->request->action == 'admin_edit') {
				if ($event->data['id'] == 'BlogPostForm' || $event->data['id'] == 'BlogPostForm') {
					if (!empty($View->request->data['PetitCustomFieldConfig']['status'])) {
						if ($View->request->data['PetitCustomFieldConfig']['form_place'] == 'normal') {
							// ブログ記事追加画面にプチ・カスタムフィールド編集欄を追加する
							$event->data['out'] = $event->data['out'] . $View->element('PetitCustomField.petit_custom_field_form');
						}
					}
				}
			}
		}
		
		if ($View->request->params['controller'] == 'blog_contents') {
			if ($View->request->action == 'admin_edit') {
				// ブログ設定編集画面にプチ・カスタムフィールドメタ設定一覧リンクを表示する
				if ($event->data['id'] == 'BlogContentAdminEditForm') {
					$output = $View->BcBaser->link('≫プチ・カスタムフィールド設定', array(
						'plugin' => 'petit_custom_field',
						'controller' => 'petit_custom_field_config_metas',
						'action' => 'index', $View->viewVars['blogContent']['PetitCustomFieldConfig']['id']
					));
					$event->data['out'] = $event->data['out'] . $output;
				}
			}
		}
		
		return $event->data['out'];
	}
	
/**
 * formAfterCreate
 * - ブログ記事追加・編集画面にプチ・カスタムフィールド編集欄を追加する
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function formAfterCreate(CakeEvent $event) {
		$View = $event->subject();
		
		if ($View->request->params['controller'] == 'blog_posts') {
			if ($View->request->action == 'admin_add' || $View->request->action == 'admin_edit') {
				if ($event->data['id'] == 'BlogPostForm' || $event->data['id'] == 'BlogPostForm') {
					if (!empty($View->request->data['PetitCustomFieldConfig']['status'])) {
						if ($View->request->data['PetitCustomFieldConfig']['form_place'] == 'top') {
							// ブログ記事追加画面にプチ・カスタムフィールド編集欄を追加する
							$event->data['out'] = $event->data['out'] . $View->element('PetitCustomField.petit_custom_field_form');
						}
					}
				}
			}
		}
		
		return $event->data['out'];
	}
	
}
