<?php
/**
 * [ADMIN] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
$this->BcBaser->css('PetitCustomField.admin/petit_custom_field', array('inline' => false));
?>

<h3>
<?php $this->BcBaser->link($this->BcText->arrayValue($contentId, $blogContentDatas) .' ブログ設定編集はこちら', array(
	'admin' => true, 'plugin' => 'blog', 'controller' => 'blog_contents',
	'action' => 'edit', $contentId
)) ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php $this->BcBaser->link('≫記事一覧こちら', array(
	'admin' => true, 'plugin' => 'blog', 'controller' => 'blog_posts',
	'action' => 'index', $contentId
)) ?>
</h3>

<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('PetitCustomFieldConfigField', array('url' => array('action' => 'add', $configId))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('PetitCustomFieldConfigField', array('url' => array('action' => 'edit', $configId, $foreignId))) ?>
<?php endif ?>

<div id="PetitCustomFieldConfigFieldTable">
<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.status', 'カスタムフィールドの利用') ?>
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
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.required', '必須設定') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.required', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('必須と'))) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.required') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.validate', '入力チェック') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.validate') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.validate', array('type' => 'select', 'multiple' => 'checkbox', 'options' => $customFieldConfig['validate'])) ?>
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
	<tr>
		<th class="col-head">
			テキスト
		</th>
		<td class="col-input">
			<div class="pcf-input-box">
				<span class="span3">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.size', '入力サイズ') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.size', array('type' => 'text', 'size' => 4, 'placeholder' => '60')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.size') ?>
				</span>
				<span class="span3">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.max_length', '最大入力文字数') ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.max_length') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.max_length', array('type' => 'text', 'size' => 4, 'placeholder' => '255')) ?>
				</span>
				<span class="span3">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.counter', '文字数カウンター表示') ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.counter') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.counter', array('type' => 'checkbox')) ?>
				</span>
				<span class="span3">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.auto_convert', '自動変換') ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.auto_convert') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.auto_convert', array('type' => 'select', 'options' => $customFieldConfig['auto_convert'])) ?>
				</span>
			</div>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			テキストエリア
		</th>
		<td class="col-input">
			<div class="pcf-input-box">
				<span class="span4">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.rows', '行数') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.rows', array('type' => 'text', 'size' => 4, 'placeholder' => '3')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.rows') ?>
					<br /><small>※Wysiwyg Editorの場合は〜px指定となります。</small>
				</span>
				<span class="span4">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.cols', '横幅サイズ') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.cols', array('type' => 'text', 'size' => 4, 'placeholder' => '40')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.cols') ?>
					<br /><small>※Wysiwyg Editorの場合は〜％指定となります。</small>
				</span>
				<span class="span4">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.editor_tool_type', 'Ckeditorのタイプ') ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.editor_tool_type') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.editor_tool_type', array('type' => 'select', 'options' => $customFieldConfig['editor_tool_type'])) ?>
				</span>
			</div>
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
			<br /><small>選択肢を改行毎に入力します。
				より細かく制御する場合は、ラベル名と値の両方を指定することができます。</small>
			<br /><small>指定したいラベル名と値を半角「:」で区切って入力してください。（例：ラベル名:値）</small>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.separator', '区切り文字') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.separator', array('type' => 'text', 'placeholder' => '&nbsp;&nbsp;')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.separator') ?>
			<br /><small>※ラジオボタン表示の際の区切り文字を指定できます。</small>
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
