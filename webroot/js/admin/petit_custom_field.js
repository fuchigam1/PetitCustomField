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
	
	// カスタムフィールド名、ラベル名、フィールド名の入力時、リアルタイムで重複チェックを行う
	$("#PetitCustomFieldConfigFieldName").keyup(checkDuplicateValueChengeHandler);
	$("#PetitCustomFieldConfigFieldLabelName").keyup(checkDuplicateValueChengeHandler);
	$("#PetitCustomFieldConfigFieldFieldName").keyup(checkDuplicateValueChengeHandler);
	// 重複があればメッセージを表示する
	function checkDuplicateValueChengeHandler() {
		var fieldId = this.id;
		var options = {};
		// 本来であれば編集時のみ必要な値だが、actionによる条件分岐でビュー側に値を設定しなかった場合、
		// Controllerでの取得値が文字列での null となってしまうため、常に設定し取得している
		var foreignId = $("#ForeignId").html();
		
		switch (fieldId) {
			case 'PetitCustomFieldConfigFieldName':
				options = {
					"data[PetitCustomFieldConfigField][foreign_id]": foreignId,
					"data[PetitCustomFieldConfigField][name]": $("#PetitCustomFieldConfigFieldName").val()
				};
				break;
			case 'PetitCustomFieldConfigFieldLabelName':
				options = {
					"data[PetitCustomFieldConfigField][foreign_id]": foreignId,
					"data[PetitCustomFieldConfigField][label_name]": $("#PetitCustomFieldConfigFieldLabelName").val()
				};
				break;
			case 'PetitCustomFieldConfigFieldFieldName':
				options = {
					"data[PetitCustomFieldConfigField][foreign_id]": foreignId,
					"data[PetitCustomFieldConfigField][field_name]": $("#PetitCustomFieldConfigFieldFieldName").val()
				};
				break;
		}
		$.ajax({
			type: "POST",
			data: options,
			url: $("#AjaxCheckDuplicateUrl").html(),
			dataType: "html",
			cache: false,
			success: function(result, status, xhr) {
				if(status === 'success') {
					if(!result) {
						if (fieldId === 'PetitCustomFieldConfigFieldName') {
							$('#CheckValueResultName').show('fast');
						}
						if (fieldId === 'PetitCustomFieldConfigFieldLabelName') {
							$('#CheckValueResultLabelName').show('fast');
						}
						if (fieldId === 'PetitCustomFieldConfigFieldFieldName') {
							$('#CheckValueResultFieldName').show('fast');
						}
					} else {
						if (fieldId === 'PetitCustomFieldConfigFieldName') {
							$('#CheckValueResultName').hide('fast');
						}
						if (fieldId === 'PetitCustomFieldConfigFieldLabelName') {
							$('#CheckValueResultLabelName').hide('fast');
						}
						if (fieldId === 'PetitCustomFieldConfigFieldFieldName') {
							$('#CheckValueResultFieldName').hide('fast');
						}
					}
				}
			}
		});
	}
	
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
	
	// 正規表現チェックのチェック時に、専用の入力欄を表示する
	$('#PetitCustomFieldConfigFieldValidateREGEXCHECK').change(function() {
		$value = $(this).prop('checked');
		if ($value) {
			$('#PetitCustomFieldConfigFieldValidateRegexBox').show();
		} else {
			$('#PetitCustomFieldConfigFieldValidateRegexBox').hide();
		}
	});
	
	// 正規表現入力欄が空欄になった際はメッセージを表示して入力促す
	$('#PetitCustomFieldConfigFieldValidateRegex').change(function() {
		if (!$(this).val()) {
			$('#CheckValueResultValidateRegex').show();
		} else {
			$('#CheckValueResultValidateRegex').hide();
		}
	});
	
	// 正規表現チェックが有効の場合に、正規表現入力欄が空の場合は submit させない
	$("#BtnSave").click(function(){
		$validateRegexCheck = $('#PetitCustomFieldConfigFieldValidateREGEXCHECK');
		if ($validateRegexCheck.prop('checked')) {
			$validateRegex = $('#PetitCustomFieldConfigFieldValidateRegex').val();
			if (!$validateRegex) {
				alert('正規表現入力欄が未入力です。');
				return false;
			}
		}
	});
	
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
			$validateRegex = $('#PetitCustomFieldConfigFieldValidateREGEXCHECK');
				$validateRegexBox = $('#PetitCustomFieldConfigFieldValidateRegexBox');
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
					$validateRegex.parent().show('slow');
						// 正規表現チェックが有効に指定されている場合は、専用の入力欄を表示する
						if ($validateRegex.prop('checked')) {
							$validateRegexBox.show('fast');
						}
				
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
					$validateRegex.parent().show('slow');
						// 正規表現チェックが有効に指定されている場合は、専用の入力欄を表示する
						if ($validateRegex.prop('checked')) {
							$validateRegexBox.show('fast');
						}
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
					$validateRegex.parent().hide('fast');
						$validateRegexBox.hide('fast');
				
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
