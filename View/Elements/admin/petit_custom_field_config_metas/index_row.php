<?php
/**
 * [ADMIN] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
$classies = array();
if (!$this->PetitCustomField->allowPublish($data)) {
	$classies = array('unpublish', 'disablerow');
} else {
	$classies = array('publish');
}
$class=' class="'.implode(' ', $classies).'"';
?>
<tr<?php echo $class ?>>
	<td class="row-tools">
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
			array('action' => 'edit', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('title' => '編集')) ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')),
			array('action' => 'ajax_delete', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
	</td>
	<td style="width: 45px;">
		<?php echo $data['PetitCustomFieldConfigMeta']['id']; ?>
	</td>
	<td><?php //echo $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'] ?>
		<?php //echo $data['PetitCustomFieldConfigMeta']['field_foreign_id'] ?>
		<?php echo $this->BcBaser->link($data['PetitCustomFieldConfigField']['name'],
				array('controller' => 'petit_custom_field_config_fields', 'action' => 'edit',
						$data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['field_foreign_id']), array('title' => '編集')) ?>
	</td>
	<td>
		<?php echo $data['PetitCustomFieldConfigField']['field_name'] ?>
	</td>
	<td>
		<?php echo $this->PetitCustomField->arrayValue($data['PetitCustomFieldConfigField']['field_type'], $customFieldConfig['field_type'], '<small>未登録</small>'); ?>
	</td>
	<td style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['PetitCustomFieldConfigMeta']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['PetitCustomFieldConfigMeta']['modified']) ?>
	</td>
</tr>
