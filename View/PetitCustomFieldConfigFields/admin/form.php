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
$this->BcBaser->js(array('PetitCustomField.admin/petit_custom_field'));
$currentModelName = $this->request->params['models']['PetitCustomFieldConfigField']['className'];
?>
<script type="text/javascript">
	$(window).load(function() {
		$("#PetitCustomFieldConfigFieldName").focus();
	});
</script>

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
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('name'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.name', 'カスタムフィールド名') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input" colspan="3">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.name',
					array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true, 'placeholder' => 'カスタムフィールドの名称')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.name') ?>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('label_name'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.label_name', 'ラベル名') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input" colspan="3">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.label_name',
					array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true, 'placeholder' => 'ラベルの名称')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.label_name') ?>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('field_name'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.field_name', 'フィールド名') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input" colspan="3">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.field_name',
					array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true, 'placeholder' => 'field_name_sample')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.field_name') ?>
			<br /><small>※半角英数で入力してください。</small>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('field_type'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.field_type', 'フィールドタイプ') ?>&nbsp;<span class="required">*</span>
		</th>
		<td class="col-input" colspan="3">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.field_type', array('type' => 'select', 'options' => $customFieldConfig['field_type'])) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.field_type') ?>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('status'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.status', '利用状態') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.status', array('type' => 'checkbox', 'label' => '利用中')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.status') ?>
		</td>
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.required', '必須設定') ?>
		</th>
		<td class="col-input" id="Row<?php echo $currentModelName . Inflector::camelize('required'); ?>" colspan="3">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.required', array('type' => 'checkbox', 'label' => '必須入力とする')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.required') ?>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('default_value'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.default_value', '初期値') ?>
		</th>
		<td class="col-input" colspan="3">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.default_value', array('type' => 'text', 'size' => 60, 'maxlength' => 255, 'counter' => true)) ?>
				<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpPetitCustomFieldConfigFieldDefaultValue', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
				<div id="helptextPetitCustomFieldConfigFieldDefaultValue" class="helptext">
					<ul>
						<li>選択肢の入力内容のラベル名（キー）を指定してください。</li>
						<li>選択肢でラベル名（キー）と値を指定した場合は、値を指定してください。</li>
					</ul>
				</div>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.default_value') ?>
			<br /><small>※入力欄の初期値を指定できます。</small>
			
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('validate'); ?>Group">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.validate', '入力値チェック') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.validate', array('type' => 'select', 'multiple' => 'checkbox', 'options' => $customFieldConfig['validate'])) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.validate') ?>
		</td>
	</tr>
	<tr  id="Row<?php echo $currentModelName . Inflector::camelize('size'); ?>Group">
		<th class="col-head">
			テキスト
		</th>
		<td class="col-input">
			<div class="pcf-input-box">
				<span class="span4" id="Row<?php echo $currentModelName . Inflector::camelize('size'); ?>">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.size', '入力サイズ') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.size', array('type' => 'text', 'size' => 4, 'placeholder' => '60')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.size') ?>
				</span>
				<span class="span4" id="Row<?php echo $currentModelName . Inflector::camelize('max_lenght'); ?>">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.max_length', '最大入力文字数') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.max_length', array('type' => 'text', 'size' => 4, 'placeholder' => '255')) ?>
					<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpPetitCustomFieldConfigFieldMaxLength', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
					<div id="helptextPetitCustomFieldConfigFieldMaxLength" class="helptext">
						<ul>
							<li>入力すると、指定文字数制限による入力チェックが行われます。</li>
						</ul>
					</div>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.max_length') ?>
				</span>
				<span class="span4" id="Row<?php echo $currentModelName . Inflector::camelize('counter'); ?>">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.counter', array('type' => 'checkbox', 'label' => '文字数カウンターを表示する')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.counter') ?>
				</span>
			</div>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('placeholder'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.placeholder', 'プレースホルダー') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.placeholder', array('type' => 'text', 'size' => 60, 'placeholder' => 'プレースホルダー表示例')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.placeholder') ?>
			<br /><small>※入力欄内に未入力時に表示される文字を指定できます。</small>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('rows'); ?>Group">
		<th class="col-head">
			テキストエリア
		</th>
		<td class="col-input">
			<div class="pcf-input-box">
				<span class="span4" id="Row<?php echo $currentModelName . Inflector::camelize('rows'); ?>">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.rows', '行数') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.rows', array('type' => 'text', 'size' => 5, 'placeholder' => '3')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.rows') ?>
					<br /><small>※Wysiwyg Editorの場合は〜px指定となります。</small>
				</span>
				<span class="span4"id="Row<?php echo $currentModelName . Inflector::camelize('cols'); ?>">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.cols', '横幅サイズ') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.cols', array('type' => 'text', 'size' => 5, 'placeholder' => '40')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.cols') ?>
					<br /><small>※Wysiwyg Editorの場合は〜％指定となります。</small>
				</span>
				<span class="span4" id="Row<?php echo $currentModelName . Inflector::camelize('editor_tool_type'); ?>">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.editor_tool_type', 'Ckeditorのタイプ') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.editor_tool_type', array('type' => 'select', 'options' => $customFieldConfig['editor_tool_type'])) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.editor_tool_type') ?>
				</span>
			</div>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('choices'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.choices', '選択肢') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.choices', array('type' => 'textarea', 'rows' => '4')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.choices') ?>
			<br /><small>選択肢を改行毎に入力します。</small>
				<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpPetitCustomFieldConfigFieldChoices', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
				<div id="helptextPetitCustomFieldConfigFieldChoices" class="helptext">
					<ul>
						<li>より細かく制御する場合は、ラベル名（キー）と値の両方を指定することができます。</li>
						<li>指定したいラベル名（キー）と値を半角「:」で区切って入力してください。</li>
						<li>（例：ラベル名:値）</li>
					</ul>
				</div>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('separator'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.separator', '区切り文字') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.separator', array('type' => 'text', 'size' => 60, 'placeholder' => '&nbsp;&nbsp;')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.separator') ?>
			<br /><small>※ラジオボタン表示の際の区切り文字を指定できます。</small>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('auto_convert'); ?>">
		<th class="col-head">
			入力テキスト変換処理
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.auto_convert', '自動変換') ?>
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.auto_convert', array('type' => 'select', 'options' => $customFieldConfig['auto_convert'])) ?>			
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.auto_convert') ?>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('prepend'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.prepend', '入力欄前に表示') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.prepend', array('type' => 'text', 'size' => 60)) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.prepend') ?>
			<br /><small>※入力欄の前に表示される文字を指定できます。</small>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('append'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.append', '入力欄後に表示') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.append', array('type' => 'text', 'size' => 60)) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.append') ?>
			<br /><small>※入力欄の後に表示される文字を指定できます。</small>
		</td>
	</tr>
	<tr id="Row<?php echo $currentModelName . Inflector::camelize('description'); ?>">
		<th class="col-head">
			<?php echo $this->BcForm->label('PetitCustomFieldConfigField.description', 'このフィールドの説明文') ?>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PetitCustomFieldConfigField.description', array('type' => 'textarea', 'rows' => '2')) ?>
			<?php echo $this->BcForm->error('PetitCustomFieldConfigField.description') ?>
			<br /><small>※入力欄に説明文を指定できます。</small>
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
