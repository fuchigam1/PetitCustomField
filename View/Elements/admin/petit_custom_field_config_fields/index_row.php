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
<tr>
	<td class="row-tools">
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')),
			array('action' => 'edit', $data['PetitCustomFieldConfigField']['id']), array('title' => '編集')) ?>
	</td>
	<td style="width: 45px;"><?php echo $data['PetitCustomFieldConfigField']['id']; ?></td>
	<td>
		<?php echo $this->BcBaser->link($data['PetitCustomFieldConfigField']['key'], array('action' => 'edit', $data['PetitCustomFieldConfigField']['foreign_id']), array('title' => '編集')) ?>
		<?php //echo $data['PetitCustomFieldConfigField']['key'] ?>
		<br />
		<?php echo $data['PetitCustomFieldConfigField']['value'] ?>
	</td>
	<td style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['PetitCustomFieldConfigField']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['PetitCustomFieldConfigField']['modified']) ?>
	</td>
</tr>
