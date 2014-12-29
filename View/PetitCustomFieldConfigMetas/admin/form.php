<?php
/**
 * [ADMIN] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
?>
<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('PetitCustomField', array('url' => array('action' => 'add'))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('PetitCustomField', array('url' => array('action' => 'edit'))) ?>
	<?php echo $this->BcForm->input('PetitCustomField.id', array('type' => 'hidden')) ?>
	<?php echo $this->BcForm->input('PetitCustomField.blog_post_id', array('type' => 'hidden')) ?>
	<?php echo $this->BcForm->input('PetitCustomField.content_id', array('type' => 'hidden')) ?>
<?php endif ?>

<?php $this->BcBaser->element('petit_custom_field_form') ?>

<div class="submit">
<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->submit('登録', array('div' => false, 'class' => 'btn-red button')) ?>
<?php else: ?>
	<?php echo $this->BcForm->submit('更新', array('div' => false, 'class' => 'btn-red button')) ?>
	<?php $this->BcBaser->link('削除',
		array('action' => 'delete', $this->BcForm->value('PetitCustomField.id')),
		array('class' => 'btn-gray button'),
		sprintf('ID：%s のデータを削除して良いですか？', $this->BcForm->value('PetitCustomField.id')),
		false); ?>
<?php endif ?>
</div>
<?php echo $this->BcForm->end() ?>
