/**
 * [ADMIN] PetitCustomField
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @package			PetitCustomField
 * @license			MIT
 */
/**
 * プチカスタムフィールド用のJS処理
 */
$(function(){
	$fieldType = $("#PetitCustomFieldConfigFieldFieldType").val();
	petitCustomFieldConfigFieldFieldTypeChangeHandler($fieldType);
	// タイプを選択すると入力するフィールドが切り替わる
	$("#PetitCustomFieldConfigFieldFieldType").change(function(){
		petitCustomFieldConfigFieldFieldTypeChangeHandler($("#PetitCustomFieldConfigFieldFieldType").val());
	});
	
	// カスタムフィールド名の入力時、ラベル名が空の場合は名称を自動で入力する
	$("#PetitCustomFieldConfigFieldName").change(function(){
		$labelName = $("#PetitCustomFieldConfigFieldLabelName");
		var labelNameValue = $labelName.val();
		if(!labelNameValue){
			$labelName.val($("#PetitCustomFieldConfigFieldName").val());
		}
	});
	
	// 編集画面のときのみ実行する（削除ボタンの有無で判定）
	if ($('#BtnDelete').html()) {
		$('#BeforeFieldName').hide();
		$("#BtnSave").click(function(){
			$beforeFieldName = $('#BeforeFieldName').html();
			$inputFieldName = $('#PetitCustomFieldConfigFieldFieldName').val();
			if ($beforeFieldName !== $inputFieldName) {
				if(!confirm('フィールド名を変更した場合、これまでの記事でこのフィールドに入力していた内容は引き継がれません。\n本当によろしいですか？')) {
					$('#BeforeFieldNameComment').css('visibility', 'visible');
					$('#BeforeFieldName').show();
					return false;
				}
			}
		});
	}
	
/**
 * タイプの値によってフィールドの表示設定を行う
 * 
 * @param {string} value フィールドタイプ
 */
	function petitCustomFieldConfigFieldFieldTypeChangeHandler(value){
		$defaultValue = $("#RowPetitCustomFieldConfigFieldDefaultValue");
		$validateGroup = $("#RowPetitCustomFieldConfigFieldValidateGroup");
			$validateHankaku = $("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK");
			$validateNumeric = $("#PetitCustomFieldConfigFieldValidateNUMERICCHECK");
			$validateNonCheckCheck = $("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK");
		$sizeGroup = $("#RowPetitCustomFieldConfigFieldSizeGroup");
			$size = $("#RowPetitCustomFieldConfigFieldSize");
			$maxLength = $("#RowPetitCustomFieldConfigFieldMaxLenght");
			$counter = $("#RowPetitCustomFieldConfigFieldCounter");
		$placeholder = $("#RowPetitCustomFieldConfigFieldPlaceholder");
		$rowsGroup = $("#RowPetitCustomFieldConfigFieldRowsGroup");
			$rows = $("#PetitCustomFieldConfigFieldRows");
			$cols = $("#PetitCustomFieldConfigFieldCols");
			$editorToolType = $("#RowPetitCustomFieldConfigFieldEditorToolType");
		$choices = $("#RowPetitCustomFieldConfigFieldChoices");
		$separator = $("#RowPetitCustomFieldConfigFieldSeparator");
		$autoConvert = $("#RowPetitCustomFieldConfigFieldAutoConvert");
		
		switch (value){
			case 'text':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.show('slow');
					$validateHankaku.parent().show('slow');
					$validateNumeric.parent().show('slow');
					$validateNonCheckCheck.parent().hide('fast');
				
				$sizeGroup.show('slow');
					$size.show('slow');
					$maxLength.show('slow');
					$counter.show('slow');
				$placeholder.show('slow');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.show('slow');
				break;
				
			case 'textarea':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.show('slow');
					$validateHankaku.parent().show('slow');
					$validateNumeric.parent().show('slow');
					$validateNonCheckCheck.parent().hide('fast');
				
				$sizeGroup.show('slow');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.show('slow');
				$placeholder.show('slow');
				
				$rowsGroup.show('slow');
					$rows.show('slow');
						$rows.attr('placeholder', '3');
					$cols.show('slow');
						$cols.attr('placeholder', '40');
					$editorToolType.hide('fast');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.show('slow');
				break;
				
			case 'date':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().hide('fast');
				
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
				
			case 'datetime':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().hide('fast');
					
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
				
			case 'select':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().hide('fast');
					
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.show('slow');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
				
			case 'radio':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().hide('fast');
					
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.show('slow');
				$separator.show('slow');
				$autoConvert.hide('fast');
				break;
				
			case 'checkbox':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().show('fast');
					
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
				
			case 'multiple':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.show('slow');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().show('slow');
				
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.show('slow');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
				
			case 'pref':
				$defaultValue.show('slow');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().hide('fast');
					
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
				
			case 'wysiwyg':
				$defaultValue.hide('fast');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().hide('fast');
					
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.show('slow');
					$rows.show('slow');
						$rows.attr('placeholder', '200px');
					$cols.show('slow');
						$cols.attr('placeholder', '100%');
					$editorToolType.show('slow');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
			
			case 'file':
				$defaultValue.hide('fast');
				// バリデーション項目
				$validateGroup.hide('fast');
					$validateHankaku.parent().hide('fast');
					$validateNumeric.parent().hide('fast');
					$validateNonCheckCheck.parent().hide('fast');
					
				$sizeGroup.hide('fast');
					$size.hide('fast');
					$maxLength.hide('fast');
					$counter.hide('fast');
				$placeholder.hide('fast');
				
				$rowsGroup.hide('fast');
					$rows.hide('fast');
					$cols.hide('fast');
					$editorToolType.hide('fast');
					
				$choices.hide('fast');
				$separator.hide('fast');
				$autoConvert.hide('fast');
				break;
		}
	}
});
