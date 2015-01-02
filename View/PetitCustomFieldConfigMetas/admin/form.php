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
<script type="text/javascript">
	$(window).load(function() {
		$("#PetitCustomFieldConfigContentId").focus();
	});
/**
 * コンテンツを切替えたときに、更新ボタンを有効化する
 */
$(function(){
	var beforeContentId = $("#BeforePetitCustomFieldConfigContentId").html();
	$('#BtnSave').attr('disabled', true);
	
	$("#PetitCustomFieldConfigContentId").change(function(){
		if (beforeContentId !== $("#PetitCustomFieldConfigContentId").val()) {
			$('#BtnSave').attr('disabled', false);
		} else {
			$('#BtnSave').attr('disabled', true);
		}
	});
});
</script>

<?php echo $this->BcForm->create($currentModelName, array('url' => array('action' => 'edit'))) ?>
<?php echo $this->BcForm->input("{$currentModelName}.id", array('type' => 'hidden')) ?>

<div id="BeforePetitCustomFieldConfigContentId" style="display: none;"><?php echo $this->BcForm->value('PetitCustomFieldConfig.content_id') ?></div>
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
	<?php echo $this->BcForm->submit('登　録', array('div' => false, 'class' => 'button btn-red', 'id' => 'BtnSave')) ?>
<?php else: ?>
	<?php echo $this->BcForm->submit('更　新', array('div' => false, 'class' => 'button btn-red', 'id' => 'BtnSave')) ?>
	<?php $this->BcBaser->link('削　除',
		array('action' => 'delete', $this->BcForm->value('PetitCustomField.id')),
		array('class' => 'btn-gray button'),
		sprintf('ID：%s のデータを削除して良いですか？', $this->BcForm->value('PetitCustomField.id')),
		false); ?>
<?php endif ?>
</div>
<?php echo $this->BcForm->end() ?>
