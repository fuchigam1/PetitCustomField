<?php
/**
 * [ADMIN] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
if ($this->request->params['controller'] == 'blog_posts') {
	$blogContentId = $blogContent['BlogContent']['id'];
} else {
	$blogContentId = $this->request->data['PetitCustomField']['content_id'];
}
$style = '';
?>
<?php if($this->request->params['controller'] == 'blog_posts'): ?>
<?php $style = ' style="display: none;"' ?>
<script type="text/javascript">
$(function () {
	$('#PetitCustomFieldTable').insertBefore('.submit');
	$('#textPetitCustomFieldTable').insertBefore('#PetitCustomFieldTable');
	var PetitCustomFieldStatusValue = $('input[name="data[PetitCustomField][status]"]:checked').val();
	$("#textPetitCustomFieldTable").toggle(
		function() {
			$('#PetitCustomFieldTable').slideDown('normal');
		},
		function() {
			$('#PetitCustomFieldTable').slideUp('normal');
		}
	);
});
</script>
<style type="text/css">
	#textPetitCustomFieldTable {
		cursor: pointer;
	}
</style>
<?php else: ?>
<script type="text/javascript">
$(window).load(function() {
	$("#PetitCustomFieldName").focus();
});
</script>
<?php endif ?>

<?php if($this->request->params['controller'] == 'blog_posts'): ?>
<h3 id="textPetitCustomFieldTable">カスタム項目</h3>
<?php endif ?>

<div id="PetitCustomFieldTable">

<?php if ($fieldConfigField): ?>
<table cellpadding="0" cellspacing="0" class="form-table section">
	<?php foreach ($fieldConfigField as $keyFieldConfig => $valueFieldConfig): ?>
	
		<?php if ($valueFieldConfig['PetitCustomFieldConfigField']['status']): ?>
			<?php if ($valueFieldConfig['PetitCustomFieldConfigField']['field_type'] == 'wysiwyg'): ?>
				<?php // Wysiwyg の場合 ?>
				<tr>
					<th colspan="2"><?php echo $this->BcForm->label("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}", $valueFieldConfig['PetitCustomFieldConfigField']['name']) ?></th>
				</tr>
				<tr>
					<td class="col-input" colspan="2">
						<?php echo $this->PetitCustomField->input("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}",
							$this->PetitCustomField->getFormOption($valueFieldConfig, 'PetitCustomFieldConfigField')
						) ?>
						<?php echo $this->BcForm->error("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}") ?>
					</td>
				</tr>
			<?php elseif ($valueFieldConfig['PetitCustomFieldConfigField']['field_type'] == 'upload'): ?>
				<?php // アップロードの場合 ?>
				<tr>
					<th class="col-head">
						<?php echo $this->BcForm->label("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}", $valueFieldConfig['PetitCustomFieldConfigField']['name']) ?>
					</th>
					<td class="col-input">
						<?php echo $this->PetitCustomField->input("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}",
							$this->PetitCustomField->getFormOption($valueFieldConfig, 'PetitCustomFieldConfigField')
						) ?>
						<?php echo $this->BcForm->error("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}") ?>
					</td>
				</tr>
			<?php else: ?>
				<tr>
					<th class="col-head">
						<?php echo $this->BcForm->label("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}", $valueFieldConfig['PetitCustomFieldConfigField']['name']) ?>
					</th>
					<td class="col-input">
						<?php echo $this->PetitCustomField->input("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}",
							$this->PetitCustomField->getFormOption($valueFieldConfig, 'PetitCustomFieldConfigField')
						) ?>
						<?php echo $this->BcForm->error("PetitCustomField.{$valueFieldConfig['PetitCustomFieldConfigField']['field_name']}") ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endif ?>
	
	<?php endforeach ?>
</table>
<?php endif ?>

</div>
