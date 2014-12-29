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
	<?php echo $this->BcForm->create('PetitCustomFieldConfigField', array('url' => array('action' => 'add', $configId))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('PetitCustomFieldConfigField', array('url' => array('action' => 'edit', $configId, $foreignId))) ?>
<?php endif ?>

<div id="PetitCustomFieldConfigFieldTable">
<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.status', 'このカスタムフィールドの利用') ?>
			<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpPetitCustomFieldConfigFieldStatus', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div id="helptextPetitCustomFieldConfigFieldStatus" class="helptext">
				<ul>
					<li>このカスタムフィールドの利用の有無を指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.status', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('利用'))) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.status') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.name', 'カスタムフィールド名') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.name',
					array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true, 'placeholder' => 'カスタムフィールドの名称')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.name') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.label_name', 'ラベル名') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.label_name',
					array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true, 'placeholder' => 'ラベルの名称')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.label_name') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.field_name', 'フィールド名') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.field_name',
					array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true, 'placeholder' => 'field_name_sample')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.field_name') ?>
			<br /><small>※半角英数</small>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.field_type', 'フィールドタイプ') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.field_type', array('type' => 'select', 'options' => $customFieldConfig['field_type'])) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.field_type') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.choices', '選択肢') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.choices',
					array('type' => 'textarea', 'rows' => '4')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.choices') ?>
			<br /><small>選択肢を改行毎に入力します。より細かく制御するには、値とラベル名の両方を指定することができます。</small>
			<br /><small>赤：赤<br />青：ブルー</small>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.required', '必須設定') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.required', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('必須と'))) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.required') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.description', '説明文') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.description', array('type' => 'textarea', 'rows' => '2')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.description') ?>
			<br /><small>※フィールドの説明文</small>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.default_value', '初期値') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.default_value', array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true)) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.default_value') ?>
		</td>
	</tr>
</table>
</div>

<div class="submit">
	<?php echo $this->BcForm->submit('保　存', array('div' => false, 'class' => 'btn-red button')) ?>
	<?php if ($deletable): ?>
		<?php $this->BcBaser->link('削除',
			array('action' => 'delete', $configId, $foreignId),
			array('class' => 'btn-gray button'),
			sprintf('ID：%s のデータを削除して良いですか？', $this->BcForm->value('PetitCustomFieldConfigField.name')),
			false); ?>
	<?php endif ?>
</div>
<?php echo $this->BcForm->end() ?>
