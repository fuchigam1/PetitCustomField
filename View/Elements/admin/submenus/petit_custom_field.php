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
	<th>プチ・カスタムフィールド設定管理メニュー</th>
	<td>
		<ul>
			<li><?php $this->BcBaser->link('カスタムフィールド設定一覧', array('admin' => true, 'plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_configs', 'action'=>'index')) ?></li>
		<?php if ($this->request->params['controller'] == 'petit_custom_field_config_metas'): ?>
			<li><?php $this->BcBaser->link('フィールド設定一覧', array('admin' => true, 'plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action'=>'index', $configId)) ?></li>
		<?php endif ?>
		<?php if ($this->request->params['controller'] == 'petit_custom_field_config_fields'): ?>
			<li><?php $this->BcBaser->link('フィールド設定一覧', array('admin' => true, 'plugin' => 'petit_custom_field', 'controller' => 'petit_custom_field_config_metas', 'action'=>'index', $configId)) ?></li>
		<?php endif ?>
		</ul>
	</td>
</tr>
