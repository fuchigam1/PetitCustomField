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
	petitCustomFieldConfigFieldFieldTypeChangeHandler($("#PetitCustomFieldConfigFieldFieldType").val());
	// タイプを選択すると入力するフィールドが切り替わる
	$("#PetitCustomFieldConfigFieldFieldType").change(function(){
		petitCustomFieldConfigFieldFieldTypeChangeHandler($("#PetitCustomFieldConfigFieldFieldType").val());
	});
	
	// カスタムフィールド名の入力時、ラベル名が空の場合は名称を自動で入力する
	$("#PetitCustomFieldConfigFieldName").change(function(){
		if(!$("#PetitCustomFieldConfigFieldLabelName").val()){
			$("#PetitCustomFieldConfigFieldLabelName").val($("#PetitCustomFieldConfigFieldName").val());
		}
	});

/**
 * タイプの値によってフィールドの表示設定を行う
 * 
 * @param {string} value フィールドタイプ
 */
	function petitCustomFieldConfigFieldFieldTypeChangeHandler(value){
		switch ($("#PetitCustomFieldConfigFieldFieldType").val()){
			case 'text':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").show('slow');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().show('slow');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().show('slow');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldSizeGroup").show('slow');
					$("#RowPetitCustomFieldConfigFieldSize").show('slow');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").show('slow');
					$("#RowPetitCustomFieldConfigFieldCounter").show('slow');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").show('slow');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").show('slow');
				break;
				
			case 'textarea':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").show('slow');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().show('slow');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().show('slow');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldSizeGroup").show('slow');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").show('slow');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").show('slow');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").show('slow');
					$("#RowPetitCustomFieldConfigFieldRows").show('slow');
					$("#PetitCustomFieldConfigFieldCols").show('slow');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").show('slow');
				break;
				
			case 'date':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
				
			case 'datetime':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
				
			case 'select':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").show('slow');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
				
			case 'radio':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").show('slow');
				$("#RowPetitCustomFieldConfigFieldSeparator").show('slow');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
				
			case 'checkbox':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().show('fast');
					
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
				
			case 'multiple':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").show('slow');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().show('slow');
				
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").show('slow');
				$("#RowPetitCustomFieldConfigFieldSeparator").show('slow');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
			case 'pref':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
				
			case 'wysiwyg':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").show('slow');
					$("#RowPetitCustomFieldConfigFieldRows").show('slow');
						$("#PetitCustomFieldConfigFieldRows").attr('placeholder', '200px');
					$("#PetitCustomFieldConfigFieldCols").show('slow');
						$("#PetitCustomFieldConfigFieldCols").attr('placeholder', '100%');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").show('slow');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
			
			case 'file':
				// バリデーション項目
				$("#RowPetitCustomFieldConfigFieldValidateGroup").hide('fast');
					$("#PetitCustomFieldConfigFieldValidateHANKAKUCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNUMERICCHECK").parent().hide('fast');
					$("#PetitCustomFieldConfigFieldValidateNONCHECKCHECK").parent().hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldSizeGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldSize").hide('fast');
					$("#RowPetitCustomFieldConfigFieldMaxLenght").hide('fast');
					$("#RowPetitCustomFieldConfigFieldCounter").hide('fast');
				$("#RowPetitCustomFieldConfigFieldPlaceholder").hide('fast');
				
				$("#RowPetitCustomFieldConfigFieldRowsGroup").hide('fast');
					$("#RowPetitCustomFieldConfigFieldRows").hide('fast');
					$("#PetitCustomFieldConfigFieldCols").hide('fast');
					$("#RowPetitCustomFieldConfigFieldEditorToolType").hide('fast');
					
				$("#RowPetitCustomFieldConfigFieldChoices").hide('fast');
				$("#RowPetitCustomFieldConfigFieldSeparator").hide('fast');
				$("#RowPetitCustomFieldConfigFieldAutoConvert").hide('fast');
				break;
				
		}
	}
});
