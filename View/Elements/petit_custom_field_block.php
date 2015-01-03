<?php
/**
 * [PUBLISH] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
$this->BcBaser->css('PetitCustomField.petit_custom_field');
?>
<?php if ($this->PetitCustomField->allowPublish($this->PetitCustomField->publicConfigData, 'PetitCustomFieldConfig')): ?>

<?php if (!empty($post)): ?>
	<?php if (!empty($post['PetitCustomField'])): ?>
<div id="PetitCustomFieldBlock">
	<div class="petit-custom-body">
		<table class="table">
			<thead>
				<tr>
					<th>フィールド名</th><th>ラベル名</th><td>内容</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($post['PetitCustomField'] as $fieldName => $value): ?>
				<tr>
					<td><?php echo $fieldName ?></td>
					<td><?php echo $this->PetitCustomField->getPdcfDataField($fieldName) ?></td>
					<td><?php echo $this->PetitCustomField->getPdcfData($post, $fieldName) ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
	<?php endif ?>
<?php endif ?>

<?php endif ?>
