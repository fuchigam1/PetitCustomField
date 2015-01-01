<?php
/**
 * [ADMIN] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
$currentModelName = $this->request->params['models']['PetitCustomFieldConfigMeta']['className'];
?>
<?php echo $this->BcForm->create($currentModelName, array('url' => array('action' => 'edit'))) ?>
<?php echo $this->BcForm->input("{$currentModelName}.id", array('type' => 'hidden')) ?>

<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr>
		<th class="col-head"><?php echo $this->BcForm->label($currentModelName .'.id', 'NO') ?></th>
		<td class="col-input">
			<?php echo $this->BcForm->value($currentModelName .'.id') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfig.content_id', 'このカスタムフィールドを設定中のコンテンツ') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfig.content_id', array('type' => 'select', 'options' => $blogContentDatas)) ?>
		</td>
	</tr>
</table>

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
