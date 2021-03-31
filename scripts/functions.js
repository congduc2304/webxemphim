var cachedScrollbarWidth;
function scrollbarWidth() {
    if (void 0 !== cachedScrollbarWidth) return cachedScrollbarWidth;
    var i, d, t = $("<div style='display:block;position:absolute;width:200px;height:200px;overflow:hidden;'><div style='height:300px;width:auto;'></div></div>"),
        e = t.children()[0];
    return $("body").append(t), i = e.offsetWidth, t.css("overflow", "scroll"), i === (d = e.offsetWidth) && (d = t[0].clientWidth), t.remove(), cachedScrollbarWidth = i - d
}
$(document).ready(function(){document.getElementById("dyn-style").innerHTML=".mdl-layout__container{width:calc(100% - "+(287-scrollbarWidth())+"px);}"});
if(document.getElementById('trailer-video')) {
	var trailer_player = new Plyr('#trailer-video');
}
function openPage(pageName, elmnt, color) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablink");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].style.borderBottom = "4px solid transparent";
		tablinks[i].style.fontWeight = "normal";
		tablinks[i].style.color = "#c7c7c7";
	}
	if(document.getElementById('trailer-loaded')) {
	if(pageName != "cn_trailer" && !trailer_player.paused) {
		trailer_player.pause();
	}
	}
	document.getElementById(pageName).style.display = "block";
	elmnt.style.borderBottom = "4px solid " + color;
	elmnt.style.fontWeight = "bold";
	elmnt.style.color = "#fff";
}
var old_video_player;
var old_video_id;
function getCurrentVideoPlayer() {
	return old_video_player;
}
function getCurrentVideoId() {
	return old_video_id;
}
var video_arr = new Object();
function openPlayer(pageName, elmnt, color) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("watch-player");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("series-link");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].style.borderLeft = "4px solid transparent";
		tablinks[i].style.fontWeight = "normal";
		tablinks[i].style.color = "#c7c7c7";
	}
	if(video_arr[pageName] === undefined) {
		if(document.getElementById('video-player-' + pageName)) {
			video_arr[pageName] = new Plyr('#video-player-' + pageName);
		} else if(document.getElementById('html-player-' + pageName)) {
			video_arr[pageName] = "type_html";
		} else if(document.getElementById('hls-player-' + pageName)) {
			var hls_player = document.getElementById('hls-player-' + pageName);
			var source = document.getElementById('hls-source-' + pageName).value;
			video_arr[pageName] = new Plyr(hls_player, {captions: {active: true, update: true, language: 'en'}});
			if(!Hls.isSupported()) {
				hls_player.src = source;
			} else {
				const hls = new Hls();
				hls.loadSource(source);
				hls.attachMedia(hls_player);
				window.hls = hls;
				video_arr[pageName].on('languagechange',function(){setTimeout(function(){hls.subtitleTrack = video_arr[pageName].currentTrack;},50);});
			}
		}
	}
	var video_player = video_arr[pageName];
	document.getElementById(pageName).style.display = "block";
	elmnt.style.borderLeft = "4px solid " + color;
	elmnt.style.fontWeight = "bold";
	elmnt.style.color = "#fff";
	if(old_video_player) {
		if(old_video_player != "type_html") {
			if(!old_video_player.paused) {
				old_video_player.pause();
			}
		} else {
			$('#' + old_video_id).load(window.location.href + ' #html-player-' + old_video_id);
		}
	} else if(video_player != "type_html") {
		video_player.play();
	}
	old_video_player = video_player;
	old_video_id = pageName;
}
function closeWarnMessage() {
	document.querySelector('.login-req-message-warn').style.display = 'none';
}
function chooseAvatarFile() {
	var image_url = prompt(document.getElementById("lang_popup_avatar_image_prompt").value,"images/img_avatar.png").replace(/http:\/\//ig,"https://");
	if(image_url) {
		var img = new Image();
		img.onload = function() {
			$("#accountAvatarImage").css("background","url("+image_url+") center / cover");
			$("#avatarUrlInput").val(image_url);
		};
		img.onerror = function() {
			alert(document.getElementById("lang_popup_avatar_image_error").value);
		}
		img.src = image_url;
	}
}
var alr_watch = false;
function actionViewMedia(id) {
	if(!alr_watch){
		alr_watch = true;
	$.ajax({
		type: 'post',
		url: "system/functions/user_actions.php",
		data: {update_movie_views:id}
	});
	}
}
var tmot = false;
function addToFavorites(id) {
	if(!tmot) {
		tmot = true;
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/user_actions.php",
		data: {add_to_favorites:id},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				$("#addToFavsButtonId").html('<i class="material-icons" style="margin-right:5px;">clear</i> '+document.getElementById("lang_media_coverside_favorite_remove").value);
			} else if(resp.response == "false") {
				$("#addToFavsButtonId").html('<i class="material-icons" style="margin-right:5px;">favorite</i> '+document.getElementById("lang_media_coverside_favorite_button").value);
			}
		}
	});
	setTimeout(function(){tmot = false;},3000);
	}
}
var tlmot = false;
function likeMedia(id,name) {
	if(!tlmot) {
		tlmot = true;
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: "system/functions/user_actions.php",
		data: {like_media:id,media_name_liked:name},
		success: function(resp){
			showToast(resp.message);
			if(resp.response == "true") {
				$("#likeMediaButtonId").html('<i class="material-icons" style="margin-right:5px;">done</i> '+document.getElementById("lang_like_button_done_text").value);
			}
		}
	});
	setTimeout(function(){tlmot = false;},3000);
	}
}
var rwtmot = false;
function validateReviewForm() {
	var arw = document.forms["ReviewForm"]["post_review"].value;
	if(arw != "") {
	if(!rwtmot) {
		rwtmot = true;
	$.ajax({
		type: 'post',
		url: "system/functions/user_actions.php",
		data: $("#postReviewFormId").serialize(),
		success: function(resp){
			showToast(resp);
		}
	});
	setTimeout(function(){rwtmot = false;},30000);
	} else {
		showToast(document.getElementById("lang_review_timeout_msg").value);
	}
	} else {
		showToast(document.getElementById("lang_media_reviews_field_empty_error").value);
	}
	return false;
}