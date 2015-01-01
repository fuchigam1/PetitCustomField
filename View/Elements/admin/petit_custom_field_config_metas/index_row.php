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
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_unpublish.png', array('width' => 24, 'height' => 24, 'alt' => '無効', 'class' => 'btn')),
			array('action' => 'ajax_unpublish', $data['PetitCustomFieldConfigMeta']['id']), array('title' => '無効', 'class' => 'btn-unpublish')) ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_publish.png', array('width' => 24, 'height' => 24, 'alt' => '有効', 'class' => 'btn')),
			array('action' => 'ajax_publish', $data['PetitCustomFieldConfigMeta']['id']), array('title' => '有効', 'class' => 'btn-publish')) ?>

	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
			array('action' => 'edit', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('title' => '編集')) ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')),
			array('action' => 'ajax_delete', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>

	<?php // 並び替えはconfigIdで絞り込んだ画面で有効化する ?>
	<?php if($this->request->params['pass']): ?>
		<?php if ($count != 1 || !isset($datas)): ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_up.png', array('width' => 24, 'height' => 24, 'alt' => '上へ移動', 'class' => 'btn')),
					array('controller' => 'petit_custom_field_config_metas', 'action' => 'move_up', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('class' => 'btn-up', 'title' => '上へ移動')) ?>
		<?php else: ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_up.png', array('width' => 24, 'height' => 24, 'alt' => '上へ移動', 'class' => 'btn')),
					array('controller' => 'petit_custom_field_config_metas', 'action' => 'move_up', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('class' => 'btn-up', 'title' => '上へ移動', 'style' => 'display:none')) ?>
		<?php endif ?>
		<?php if (!isset($datas) || count($datas) != $count): ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_down.png', array('width' => 24, 'height' => 24, 'alt' => '下へ移動', 'class' => 'btn')),
					array('controller' => 'petit_custom_field_config_metas', 'action' => 'move_down', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('class' => 'btn-down', 'title' => '下へ移動')) ?>
		<?php else: ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_down.png', array('width' => 24, 'height' => 24, 'alt' => '下へ移動', 'class' => 'btn')),
					array('controller' => 'petit_custom_field_config_metas', 'action' => 'move_down', $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['id']), array('class' => 'btn-down', 'title' => '下へ移動', 'style' => 'display:none')) ?>
		<?php endif ?>
	<?php endif ?>
	</td>
	<td style="width: 45px;">
		<?php //echo $data['PetitCustomFieldConfigMeta']['id']; ?>
		<?php echo $data['PetitCustomFieldConfigMeta']['position']; ?>
	</td>
	<td><?php //echo $data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'] ?>
		<?php //echo $data['PetitCustomFieldConfigMeta']['field_foreign_id'] ?>
		<?php echo $this->BcBaser->link($data['PetitCustomFieldConfigField']['name'],
				array('controller' => 'petit_custom_field_config_fields', 'action' => 'edit',
						$data['PetitCustomFieldConfigMeta']['petit_custom_field_config_id'], $data['PetitCustomFieldConfigMeta']['field_foreign_id']), array('title' => '編集')) ?>
		<br />
		<?php echo $data['PetitCustomFieldConfigField']['label_name'] ?>
	</td>
	<td>
		<?php echo $data['PetitCustomFieldConfigField']['field_name'] ?>
	</td>
	<td>
		<?php echo $this->PetitCustomField->arrayValue($data['PetitCustomFieldConfigField']['field_type'], $customFieldConfig['field_type'], '<small>未登録</small>'); ?>
	</td>
	<td>
		<?php if ($data['PetitCustomFieldConfigField']['required']): ?><p class="annotation-text"><small>必須入力</small></p><?php endif ?>
		<?php echo $this->PetitCustomField->arrayValue($data['PetitCustomFieldConfigField']['auto_convert'], $customFieldConfig['auto_convert'], '<small>未登録</small>'); ?>
	</td>
</tr>
