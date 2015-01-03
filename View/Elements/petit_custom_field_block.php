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
		<dl>
		<?php foreach ($post['PetitCustomField'] as $fieldName => $value): ?>
			<dt><?php echo $fieldName ?></dt>
			<dd><?php echo $this->PetitCustomField->getPdcfData($post, $fieldName) ?></dd>
		<?php endforeach ?>
		</dl>
	</div>
</div>
	<?php endif ?>
<?php endif ?>
