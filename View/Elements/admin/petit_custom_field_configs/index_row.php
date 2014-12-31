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
if (!$this->PetitCustomField->allowPublish($data, 'PetitCustomFieldConfig')) {
	$classies = array('unpublish', 'disablerow');
} else {
	$classies = array('publish');
}
$class=' class="'.implode(' ', $classies).'"';
?>
<tr<?php echo $class ?>>
	<td class="row-tools">
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_unpublish.png', array('width' => 24, 'height' => 24, 'alt' => '無効', 'class' => 'btn')),
			array('action' => 'ajax_unpublish', $data['PetitCustomFieldConfig']['id']), array('title' => '無効', 'class' => 'btn-unpublish')) ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_publish.png', array('width' => 24, 'height' => 24, 'alt' => '有効', 'class' => 'btn')),
			array('action' => 'ajax_publish', $data['PetitCustomFieldConfig']['id']), array('title' => '有効', 'class' => 'btn-publish')) ?>

	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_manage.png', array('width' => 24, 'height' => 24, 'alt' => '管理', 'class' => 'btn')),
			array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $data['PetitCustomFieldConfig']['id']), array('title' => '管理')) ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
			array('action' => 'edit', $data['PetitCustomFieldConfig']['id']), array('title' => '編集')) ?>
	</td>
	<td style="width: 45px;"><?php echo $data['PetitCustomFieldConfig']['id']; ?></td>
	<td>
		<?php echo $this->BcBaser->link($this->BcText->arrayValue($data['PetitCustomFieldConfig']['content_id'], $blogContentDatas, ''),
				array('controller' => 'petit_custom_field_config_metas', 'action' => 'index', $data['PetitCustomFieldConfig']['id']), array('title' => '管理')) ?>
		<?php //echo $data['PetitCustomFieldConfig']['key'] ?>
		<?php //echo $data['PetitCustomFieldConfig']['value'] ?>
	</td>
	<td>
		<?php echo count($data['PetitCustomFieldConfigMeta']) ?>
	</td>
	<td style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['PetitCustomFieldConfig']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['PetitCustomFieldConfig']['modified']) ?>
	</td>
</tr>
