function openPage(pageName,elmnt,color) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("adm-page-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("mdl-navigation__link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].style.backgroundColor = "";
		tablinks[i].style.color = "#aebac3";
    }
    document.getElementById(pageName).style.display = "block";
    elmnt.style.backgroundColor = color;
	elmnt.style.color = "#ff5d6b";
}
document.getElementById("defaultOpen").click();
var acc = document.getElementsByClassName("accordion");
var i;
for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("ac-active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
var notification1 = document.getElementById('bottom-toast');
function showToast(msg) {
	notification1.MaterialSnackbar.showSnackbar({message:msg});
}
function validateUserDelete(id,num) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_user_delete').value.replace("%a",id))) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {user_to_delete:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				document.getElementById('usersTableId').deleteRow(num);
			}
		}
	});
	}
}
function validateUserBan(id,num) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_user_ban').value.replace("%a",id))) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {user_to_ban:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				$("#user_ban_btn_"+num).html("check_box");
			} else if(resp.response == "false") {
				$("#user_ban_btn_"+num).html("check_box_outline_blank");
			}
		}
	});
	}
}
function validateUserAdmin(id,num) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_user_admin').value.replace("%a",id))) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {user_to_admin:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				$("#user_admin_btn_"+num).html("check_box");
			} else if(resp.response == "false") {
				$("#user_admin_btn_"+num).html("check_box_outline_blank");
			}
		}
	});
	}
}
function updateRulesPage(contt) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {update_rules_page:contt},
		success: function(resp){
			showToast(resp.message);
		}
	});
}
function updatePolicyPage(contt) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {update_policy_page:contt},
		success: function(resp){
			showToast(resp.message);
		}
	});
}
function updateAboutPage(contt) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {update_about_page:contt},
		success: function(resp){
			showToast(resp.message);
		}
	});
}
function validateSettingsUpdateForm() {
	$.ajax({
		type: 'post',
		url: "system/functions/update_settings.php",
		data: $("#settingsUpdateFormId").serialize(),
		success: function(resp){
			showToast(resp);
		}
	});
	return false;
}
function validateSuggestionDelete(id,num) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_message_delete').value)) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {suggestion_to_delete:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				$("#suggestion_id_"+num).remove();
			}
		}
	});
	}
}
function validateAllSuggestionsDelete(num) {
	if(num > 0) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_all_messages_delete').value)) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {suggestion_all_to_delete:1},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				for(var i=0;i<=num;i++){
					$("#suggestion_id_"+i).remove();
					$("#removed_all_suggestions").html(document.getElementById('lang_text_after_delete_messages').value);
				}
			}
		}
	});
	}
	} else {
		showToast(document.getElementById('lang_toast_no_messages_to_delete').value);
	}
}
function validateContactDelete(id,num) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_message_delete').value)) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {contact_to_delete:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				$("#contacts_id_"+num).remove();
			}
		}
	});
	}
}
function validateAllContactsDelete(num) {
	if(num > 0) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_all_messages_delete').value)) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {contact_all_to_delete:1},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				for(var i=0;i<=num;i++){
					$("#contacts_id_"+i).remove();
					$("#removed_all_contacts").html(document.getElementById('lang_text_after_delete_messages').value);
				}
			}
		}
	});
	}
	} else {
		showToast(document.getElementById('lang_toast_no_messages_to_delete').value);
	}
}
function validateReportDelete(id,num) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_message_delete').value)) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {report_to_delete:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				$("#report_id_"+num).remove();
			}
		}
	});
	}
}
function validateAllReportsDelete(num) {
	if(num > 0) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_all_messages_delete').value)) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {report_all_to_delete:1},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				for(var i=0;i<=num;i++){
					$("#report_id_"+i).remove();
					$("#removed_all_reports").html(document.getElementById('lang_text_after_delete_messages').value);
				}
			}
		}
	});
	}
	} else {
		showToast(document.getElementById('lang_toast_no_messages_to_delete').value);
	}
}
function validateImage(url,callBack) {
	if(url) {
		var img = new Image();
		img.onload = function() {
			callBack(true);
		}
		img.onerror = function() {
			callBack(false);
		}
		img.src = url;
	} else {
		callBack(true);
	}
}
function validateUploadMovieForm() {
	document.getElementById('movieDescriptionInputId').value = getEditorData(4);
	var cover = document.forms["UploadMovieForm"]["media_cover"].value.replace(/http:\/\//ig,"https://");
	validateImage(cover,function(existsImage){
		if(existsImage) {
	$.ajax({
		type: 'post',
		url: "system/functions/upload_movie.php",
		data: $("#postUploadMovieFormId").serialize(),
		success: function(resp){
			showToast(resp);
		},
		error: function(resp){
			showToast(resp);
		}
	});
		} else {
			showToast(document.getElementById('lang_popup_admin_error_wrong_cover_img').value);
		}
	});
	return false;
}
function hex2bin(bin) {
	var hex = bin, bytes = [], str;
	for(var i=0; i<hex.length-1; i+=2) {
		bytes.push(parseInt(hex.substr(i, 2), 16));
	}
	str = String.fromCharCode.apply(String, bytes);
	return decodeURIComponent(escape(str));
}
function validateMovieDelete(id,num,name) {
	if(confirm(document.getElementById('lang_popup_admin_confirm_movie_delete').value.replace("%a",hex2bin(name)))) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/admin_actions.php",
		data: {movie_to_delete:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				document.getElementById('moviesTableId').deleteRow(num);
			}
		}
	});
	}
}
function notEmpty(str,obj) {
	if(str != "_no_data") {
		if(obj != null) {
			obj.parent().addClass("is-dirty");
		}
		return str;
	} else {
		if(obj != null) {
			obj.parent().removeClass("is-dirty");
		}
		return "";
	}
}
function validateMovieEditMode(str) {
	openPage('cn_newmedia', document.getElementById('upload-media-btn'), '#222b31');
	var data = hex2bin(str).split("=exdiv=");
	$("#adm_a").val(notEmpty(data[1],$("#adm_a")));
	editor4.setData(notEmpty(data[2]));
	$("#media-type-id").val(data[3]);
	var genres = data[4].split("=div=");
	$("#genre1_id").val(genres[0]);
	$("#genre2_id").val(genres[1]);
	$("#adk_a").val(notEmpty(data[5],$("#adk_a")));
	$("#adk_b").val(notEmpty(data[6],$("#adk_b")));
	$("#adk_c").val(notEmpty(data[7],$("#adk_c")));
	$("#adg_a").html(notEmpty(data[8],$("#adg_a")));
	$("#adg_b").html(notEmpty(data[9],$("#adg_b")));
	$("#adg_c").html(notEmpty(data[10],$("#adg_c")));
	$("#adg_d").html(notEmpty(data[11],$("#adg_d")));
	var props = data[12].split("=div=");
	for(var i=0;i<5;i++){
		$("#cn_newmedia").find("input[type=checkbox]").prop("checked",false).parent().removeClass("is-checked");
	}
	if(data[12] != "_no_data") {
	for(var i=0;i<props.length;i++){
		$("#cn_newmedia").find("input[type=checkbox][value="+props[i]+"]").click();
	}
	}
	$("#arm_a").val(notEmpty(data[13],$("#arm_a")));
	$("#arm_b").val(notEmpty(data[14],$("#arm_b")));
	$("#arm_c").html(notEmpty(data[15],$("#arm_c")));
	$("#movieUniqId").val(data[20]);
}
function updateSitemapFile(manual) {
	$.ajax({
		type: 'post',
		url: "system/generate_sitemap.php",
		data: {generated_sitemap:1},
		success: function(resp){
			if(manual){
				showToast(resp);
			}
		}
	});
	return false;
}