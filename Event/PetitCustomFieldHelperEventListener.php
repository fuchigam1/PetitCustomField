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
		'Form.afterEnd'
	);
	
/**
 * formAfterCreate
 * - ブログ記事追加画面にプチ・カスタムフィールド編集欄を追加する
 * - ブログ設定編集画面にプチ・カスタムフィールド設定編集リンクを表示する
 * 
 * @param CakeEvent $event
 * @return array
 */
	public function formAfterEnd(CakeEvent $event) {
		$Form = $event->subject();
		
		if ($Form->request->params['controller'] == 'blog_posts') {
			if (!empty($Form->request->data['PetitCustomFieldConfig']['status'])) {
				// ブログ記事追加画面にプチ・カスタムフィールド編集欄を追加する
				if ($Form->request->action == 'admin_add') {
					if ($event->data['id'] == 'BlogPostForm') {
						$event->data['out'] = $event->data['out'] . $Form->element('PetitCustomField.petit_custom_field_form');
					}
				}
				// ブログ記事編集画面にプチ・カスタムフィールド編集欄を追加する
				if ($Form->request->action == 'admin_edit') {
					if ($event->data['id'] == 'BlogPostForm') {
						$event->data['out'] = $event->data['out'] . $Form->element('PetitCustomField.petit_custom_field_form');
					}
				}
			}
		}
		
		if ($Form->request->params['controller'] == 'blog_contents') {
			// ブログ設定編集画面にプチ・カスタムフィールドメタ設定一覧リンクを表示する
			if ($Form->request->action == 'admin_edit') {
				if ($event->data['id'] == 'BlogContentAdminEditForm') {
					$output = $Form->BcBaser->link('≫プチ・カスタムフィールド設定', array(
						'plugin' => 'petit_custom_field',
						'controller' => 'petit_custom_field_config_metas',
						'action' => 'index', $Form->viewVars['blogContent']['PetitCustomFieldConfig']['id']
					));
					$event->data['out'] = $event->data['out'] . $output;
				}
			}
		}
		
		return $event->data['out'];
	}
	
}
