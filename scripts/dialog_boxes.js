var dialog = document.getElementById('dialog-1');
var showDialogButton = document.querySelector('#show-suggest-dialog');
var sum;
if(dialog) {
if(!dialog.showModal) {
	dialogPolyfill.registerDialog(dialog);
}
if(showDialogButton) {
showDialogButton.addEventListener('click', function() {
	dialog.showModal();
});
}
dialog.querySelector('.close-dialog-1').addEventListener('click', function() {
	dialog.close();
});
function validateSuggestForm() {
	var a = document.forms["SuggestForm"]["answer_a"].value;
	var b = document.forms["SuggestForm"]["answer_b"].value;
	if(!(a && b)) {
		$('#suggestBoxMessage').html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_empty_field_error').value+"</span>");
	} else {
	$.ajax({
		type: 'post',
		url: "system/functions/post_suggestion.php",
		data: $("#postSuggestionFormId").serialize(),
		success: function(resp){
			dialog.close();
			showToast(resp);
			$("#suggestBoxMessage").html("");
		},
		error: function(resp){
			$("#suggestBoxMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+resp+"</span>");
		}
	});
	}
	return false;
}
}
var dialog2 = document.getElementById('dialog-2');
var showDialogButton2 = document.querySelector('#show-login-dialog');
if(dialog2) {
if(!dialog2.showModal) {
	dialogPolyfill.registerDialog(dialog2);
}
if(showDialogButton2) {
showDialogButton2.addEventListener('click', function() {
	dialog2.showModal();
});
}
dialog2.querySelector('.close-dialog-2').addEventListener('click', function() {
	dialog2.close();
});
function validateLoginForm() {
	var username = document.forms["LoginForm"]["myusername"].value;
	var password = document.forms["LoginForm"]["mypassword"].value;
	if(!(username && password)) {
		$("#loginMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_empty_field_error').value+"</span>");
	} else {
$.ajax({
    type: "POST",
    url: "system/login/checklogin.php",
    data: "myusername=" + username + "&mypassword=" + password,
    dataType: "JSON",
    success: function(e) {
        if ("true" === e.response) return location.reload(), e.username;
        $("#loginMessage").html(e.response), $("#loginSubmitBtn").prop("disabled",false)
    },
    error: function(e, s) {
        console.log(e), console.log(s)
    },
    beforeSend: function() {
		$("#loginSubmitBtn").prop("disabled",true);
        $("#loginMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">donut_large</i> "+document.getElementById('lang_global_loading_text').value+"</span>")
    }
});
	}
	return false;
}
}
var dialog3 = document.getElementById('dialog-3');
var showDialogButton3 = document.querySelector('#show-register-dialog');
if(dialog3) {
if(!dialog3.showModal) {
	dialogPolyfill.registerDialog(dialog3);
}
if(showDialogButton3) {
showDialogButton3.addEventListener('click', function() {
	showRegisterModal();
});
}
dialog3.querySelector('.close-dialog-3').addEventListener('click', function() {
	dialog3.close();
});
function validateRegisterForm() {
	var username = document.forms["RegisterForm"]["newuser"].value;
	var email = document.forms["RegisterForm"]["email"].value;
	var password = document.forms["RegisterForm"]["password1"].value;
	var password2 = document.forms["RegisterForm"]["password2"].value;
	var e = document.forms["RegisterForm"]["answer_e"].value;
	if(!(username && email && password && password2 && e)) {
		$("#registerMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_empty_field_error').value+"</span>");
	} else if(sum != e) {
		$("#registerMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_register_captcha_error').value+"</span>");
	} else if(password && password.length < 4) {
		$("#registerMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_pass_too_short_error').value+"</span>");
	} else if(password != password2) {
		$("#registerMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_my_account_password_mismatch_error').value+"</span>");
	} else {
$.ajax({
    type: "POST",
    url: "system/login/createuser.php",
    data: "newuser=" + username + "&password1=" + password + "&password2=" + password2 + "&email=" + email,
    success: function(e) {
		generateRegCaptcha();
        var s = $(e).text();
        "true" == s.substr(s.length - 4) ? ($("#registerMessage").html(e), $("#regSubmitBtn").prop("disabled",true)) : ($("#registerMessage").html(e), $("#regSubmitBtn").prop("disabled",false))
    },
    beforeSend: function() {
		$("#regSubmitBtn").prop("disabled",true);
        $("#registerMessage").html('<span class="dialog-message" style="color:#f26a6a;"><i class="material-icons dialog-message-ico">donut_large</i> ' + document.getElementById("lang_global_loading_text").value + "</span>")
    }
});
	}
	return false;
}
function showRegisterModal() {
	dialog3.showModal();
	generateRegCaptcha();
}
function generateRegCaptcha() {
	var x = Math.floor((Math.random() * 20) + 1);
	var y = Math.floor((Math.random() * 20) + 1);
	document.getElementById("registerValidatorText").innerHTML = x + " + " + y + " =";
	sum = x + y;
}
}
var dialog4 = document.getElementById('dialog-4');
var showDialogButton4 = document.querySelector('#show-forgotpass-dialog');
if(dialog4) {
if(!dialog4.showModal) {
	dialogPolyfill.registerDialog(dialog4);
}
if(showDialogButton4) {
showDialogButton4.addEventListener('click', function() {
	dialog4.showModal();
});
}
dialog4.querySelector('.close-dialog-4').addEventListener('click', function() {
	dialog4.close();
});
function validateForgotPassForm() {
	var a = document.forms["ForgotPassForm"]["answer_a"].value;
	if(!a) {
		$("#resetPasswordMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_empty_field_error').value+"</span>");
	} else {
        $.ajax({
            type: "POST",
            url: "system/functions/reset_password.php",
            data: $("#resetPasswordFormId").serialize(),
            success: function (data) {
				if(data != "true"){
					$("#resetPasswordMessage").html(data);
				} else {
					dialog4.close();
					showToast(document.getElementById("lang_dialog_forgot_pass_done_msg").value);
					$("#resetPasswordMessage").html("");
				}
				$("#resetPasswordSubmitBtn").prop("disabled",false);
            },
            error: function (data) {
				$("#resetPasswordMessage").html(data);
				$("#resetPasswordSubmitBtn").prop("disabled",false);
            },
			beforeSend: function() {
				$("#resetPasswordSubmitBtn").prop("disabled",true);
				$("#resetPasswordMessage").html('<span class="dialog-message" style="color:#f26a6a;"><i class="material-icons dialog-message-ico">donut_large</i> ' + document.getElementById("lang_global_loading_text").value + "</span>");
			}
        });
	}
	return false;
}
}
var dialog5 = document.getElementById('dialog-5');
var showDialogButton5 = document.querySelector('#show-rules-dialog');
if(!dialog5.showModal) {
	dialogPolyfill.registerDialog(dialog5);
}
showDialogButton5.addEventListener('click', function() {
	dialog5.showModal();
});
dialog5.querySelector('.close-dialog-5').addEventListener('click', function() {
	dialog5.close();
});
var dialog6 = document.getElementById('dialog-6');
var showDialogButton6 = document.querySelector('#show-policy-dialog');
if(!dialog6.showModal) {
	dialogPolyfill.registerDialog(dialog6);
}
showDialogButton6.addEventListener('click', function() {
	dialog6.showModal();
});
dialog6.querySelector('.close-dialog-6').addEventListener('click', function() {
	dialog6.close();
});
var dialog7 = document.getElementById('dialog-7');
var showDialogButton7 = document.querySelector('#show-about-dialog');
if(!dialog7.showModal) {
	dialogPolyfill.registerDialog(dialog7);
}
showDialogButton7.addEventListener('click', function() {
	dialog7.showModal();
});
dialog7.querySelector('.close-dialog-7').addEventListener('click', function() {
	dialog7.close();
});
var dialog8 = document.getElementById('dialog-8');
var showDialogButton8 = document.querySelector('#show-contact-dialog');
if(!dialog8.showModal) {
	dialogPolyfill.registerDialog(dialog8);
}
showDialogButton8.addEventListener('click', function() {
	dialog8.showModal();
});
dialog8.querySelector('.close-dialog-8').addEventListener('click', function() {
	dialog8.close();
});
function validateContactForm() {
	var a = document.forms["ContactForm"]["answer_a"].value;
	var b = document.forms["ContactForm"]["answer_b"].value;
	var c = document.forms["ContactForm"]["answer_c"].value;
	if(!(a && b && c)) {
		$('#contactBoxMessage').html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_empty_field_error').value+"</span>");
	} else {
	$.ajax({
		type: 'post',
		url: "system/functions/post_contact.php",
		data: $("#postContactFormId").serialize(),
		success: function(resp){
			dialog8.close();
			showToast(resp);
			$("#contactBoxMessage").html("");
		},
		error: function(resp){
			$("#contactBoxMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+resp+"</span>");
		}
	});
	}
	return false;
}
var dialog9 = document.getElementById('dialog-9');
var showDialogButton9 = document.querySelector('#show-myaccount-dialog');
if(dialog9) {
if(!dialog9.showModal) {
	dialogPolyfill.registerDialog(dialog9);
}
if(showDialogButton9) {
showDialogButton9.addEventListener('click', function() {
	dialog9.showModal();
});
}
dialog9.querySelector('.close-dialog-9').addEventListener('click', function() {
	dialog9.close();
});
function validateAccountUpdateForm() {
	var a = document.forms["AccountUpdateForm"]["answer_a"].value;
	var b = document.forms["AccountUpdateForm"]["answer_b"].value;
	var c = document.forms["AccountUpdateForm"]["answer_c"].value;
	var d = document.forms["AccountUpdateForm"]["answer_d"].value;
	var ea = document.forms["AccountUpdateForm"]["answer_e"].value;
	if(!a) {
		$("#accountUpdateMessage").html('<span class="dialog-message" style="color:#f26a6a;"><i class="material-icons dialog-message-ico">donut_large</i> ' + document.getElementById("lang_dialog_empty_field_error").value + "</span>");
	} else if(c && c.length < 4) {
		$("#accountUpdateMessage").html('<span class="dialog-message" style="color:#f26a6a;"><i class="material-icons dialog-message-ico">donut_large</i> ' + document.getElementById("lang_dialog_pass_too_short_error").value + "</span>");
	} else {
        $.ajax({
            type: "POST",
            url: "system/functions/update_account.php",
            data: $("#updateAccountFormId").serialize(),
            success: function (data) {
				if(data != "true"){
					$("#accountUpdateMessage").html(data);
				} else {
					dialog9.close();
					showToast(document.getElementById("lang_dialog_my_account_done_msg").value);
					$("#accountUpdateMessage").html("");
					$("#menuAvatarImage").css("background",$("#accountAvatarImage").css("background"));
				}
				$("#accountUpdateSubmitBtn").prop("disabled",false);
            },
            error: function (data) {
				$("#accountUpdateMessage").html(data);
				$("#accountUpdateSubmitBtn").prop("disabled",false);
            },
			beforeSend: function() {
				$("#accountUpdateSubmitBtn").prop("disabled",true);
				$("#accountUpdateMessage").html('<span class="dialog-message" style="color:#f26a6a;"><i class="material-icons dialog-message-ico">donut_large</i> ' + document.getElementById("lang_global_loading_text").value + "</span>");
			}
        });
	}
	return false;
}
}
var dialog10 = document.getElementById('dialog-10');
if(dialog10) {
var showDialogButton10 = document.querySelector('#show-watch-movie-dialog');
var is_view_activated = false;
if(!dialog10.showModal) {
	dialogPolyfill.registerDialog(dialog10);
}
showDialogButton10.addEventListener('click', function() {
	dialog10.showModal();
	if(!is_view_activated) {
		is_view_activated = true;
		document.getElementById("defaultPlay").click();
	}
});
dialog10.querySelector('.close-dialog-10').addEventListener('click', function() {
	dialog10.close();
	var working_player = getCurrentVideoPlayer();
	var working_player_id = getCurrentVideoId();
	if(working_player != "type_html") {
		if(!working_player.paused) {
			working_player.pause();
		}
	} else {
		$('#' + working_player_id).load(window.location.href + ' #html-player-' + working_player_id);
	}
});
}
var dialog11 = document.getElementById('dialog-11');
if(dialog11) {
var showDialogButton11 = document.querySelector('#show-report-dialog');
if(!dialog11.showModal) {
	dialogPolyfill.registerDialog(dialog11);
}
if(showDialogButton11) {
showDialogButton11.addEventListener('click', function() {
	dialog11.showModal();
});
}
dialog11.querySelector('.close-dialog-11').addEventListener('click', function() {
	dialog11.close();
});
function validateReportForm() {
	var a = document.forms["ReportForm"]["answer_a"].value;
	var b = document.forms["ReportForm"]["answer_b"].value;
	if(!(a && b)) {
		$('#reportBoxMessage').html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+document.getElementById('lang_dialog_empty_field_error').value+"</span>");
	} else {
	$.ajax({
		type: 'post',
		url: "system/functions/post_report.php",
		data: $("#postReportFormId").serialize(),
		success: function(resp){
			dialog11.close();
			showToast(resp);
			$("#reportBoxMessage").html("");
		},
		error: function(resp){
			$("#reportBoxMessage").html("<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> "+resp+"</span>");
		}
	});
	}
	return false;
}
}
var notification1 = document.getElementById('bottom-toast');
var showToastButton1 = document.querySelector('#show-suggest-toast');
if(showToastButton1) {
showToastButton1.addEventListener('click', function() {
	showToast(document.getElementById("lang_toast_suggest_only_logged").value);
});
}
var showToastButton2 = document.querySelector('#show-report-toast');
if(showToastButton2) {
showToastButton2.addEventListener('click', function() {
	showToast(document.getElementById("lang_toast_report_only_logged").value);
});
}
function showToast(msg) {
	notification1.MaterialSnackbar.showSnackbar({message:msg});
}