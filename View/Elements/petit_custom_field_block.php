<?php
/**
 * [PUBLISH] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 * 
 * このファイルは、カスタムフィールドを利用する際の利用例を記述したサンプルファイルです。
 * 記事詳細用や記事一覧表示用のビュー・ファイルに記述することで、
 * カスタムフィールドに入力した内容を反映できます。
 * 1フィールド毎に表示したい場合は、以下のソースが例となります。
 * 
 * フィールドのラベル名を表示する: $this->PetitCustomField->getPdcfData($post, 'example_field_name');
 * フィールドの入力内容を表示する: $this->PetitCustomField->getPdcfDataField('example_field_name');
 * 
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
