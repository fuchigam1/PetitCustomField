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
<?php if (!empty($post)): ?>
	<?php if (!empty($post['PetitCustomField'])): ?>
<div id="PetitCustomFieldBlock">
	<div class="petit-custom-body">
		<table class="table">
			<thead>
				<tr>
					<th>名称（フィールド名）</th><td>内容</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($post['PetitCustomField'] as $fieldName => $value): ?>
				<tr>
					<th>ラベル名（<?php echo $fieldName ?>）</th><td><?php echo $this->PetitCustomField->getPdcfDataField($fieldName) ?></td>
				</tr>
				<tr>
					<th>入力値（<?php echo $fieldName ?>）</th><td><?php echo $this->PetitCustomField->getPdcfData($post, $fieldName) ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
	<?php endif ?>
<?php endif ?>
