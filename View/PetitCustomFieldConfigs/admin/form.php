<?php
/**
 * [ADMIN] PetitBlogCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitBlogCustomField
 * @license			MIT
 */
$hasAddableBlog = false;
if (count($blogContentDatas) > 0) {
	$hasAddableBlog = true;
}
?>
<script type="text/javascript">
	$(window).load(function() {
		$("#PetitCustomFieldConfigFormPlace").focus();
	});
</script>

<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('PetitCustomFieldConfig', array('url' => array('action' => 'add'))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('PetitCustomFieldConfig', array('url' => array('action' => 'edit'))) ?>
	<?php echo $this->BcForm->input('PetitCustomFieldConfig.id', array('type' => 'hidden')) ?>
<?php endif ?>

<?php if($this->request->params['action'] != 'admin_add'): ?>
<h2>
<?php $this->BcBaser->link($blogContentDatas[$this->request->data['PetitCustomFieldConfig']['content_id']] .' ブログ設定編集はこちら', array(
	'admin' => true, 'plugin' => 'blog', 'controller' => 'blog_contents',
	'action' => 'edit', $this->request->data['PetitCustomFieldConfig']['content_id']
)) ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php $this->BcBaser->link('≫記事一覧こちら', array(
	'admin' => true, 'plugin' => 'blog', 'controller' => 'blog_posts',
	'action' => 'index', $this->request->data['PetitCustomFieldConfig']['content_id']
)) ?>
</h2>
<?php endif ?>

<div id="PetitCustomFieldConfigTable">

<table cellpadding="0" cellspacing="0" class="form-table section">
	<?php if($this->request->params['action'] != 'admin_add'): ?>
	<tr>
		<th class="col-head"><?php echo $this->BcForm->label('PetitCustomFieldConfig.id', 'NO') ?></th>
		<td class="col-input">
			<?php echo $this->BcForm->value('PetitCustomFieldConfig.id') ?>
		</td>
	</tr>
	<?php endif ?>

	<?php if ($hasAddableBlog): ?>
		<?php if($this->request->params['action'] == 'admin_add'): ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('PetitCustomFieldConfig.content_id', 'ブログ') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('PetitCustomFieldConfig.content_id', array('type' => 'select', 'options' => $blogContentDatas)) ?>
				<?php echo $this->BcForm->error('PetitCustomFieldConfig.content_id') ?>
			</td>
		</tr>
		<?php endif ?>

		<tr>
			<th class="col-head">
				<?php echo $this->BcForm->label('PetitCustomFieldConfig.status', 'カスタムフィールドの利用') ?>
				<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpPetitCustomFieldConfigStatus', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
				<div id="helptextPetitCustomFieldConfigStatus" class="helptext">
					<ul>
						<li>ブログ記事でのプチ・カスタムフィールドの利用の有無を指定します。</li>
					</ul>
				</div>
			</th>
			<td class="col-input">
				<?php echo $this->BcForm->input('PetitCustomFieldConfig.status', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('利用'))) ?>
				<?php echo $this->BcForm->error('PetitCustomFieldConfig.status') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head">
				<?php echo $this->BcForm->label('PetitCustomFieldConfig.form_place', 'カスタムフィールドの表示位置指定') ?>
			</th>
			<td class="col-input">
				<?php echo $this->BcForm->input('PetitCustomFieldConfig.form_place', array('type' => 'select', 'options' => $customFieldConfig['form_place'])) ?>
				<?php echo $this->BcForm->error('PetitCustomFieldConfig.form_place') ?>
			</td>
		</tr>
	<?php else: ?>
	<tr>
		<th class="col-head"><?php echo $this->BcForm->label('PetitCustomFieldConfig.content_id', 'ブログ') ?></th>
		<td class="col-input">
			追加設定可能なブログがありません。
		</td>
	</tr>
	<?php endif ?>
</table>
</div>

<?php if ($hasAddableBlog): ?>
<div class="submit">
	<?php echo $this->BcForm->submit('保　存', array('div' => false, 'class' => 'button btn-red', 'id' => 'BtnSave')) ?>
</div>
<?php endif ?>
<?php echo $this->BcForm->end() ?>
