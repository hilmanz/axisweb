var uID = 0;
var jmlData = 0;
var scrollStats = true;
var since_admin_id = 0;
$('.scrollPopupConversation').live('scroll', function(){
	var settings = {
		showArrows: false
	};
	var pane = $('#popup_user_detail_right');
	pane.jScrollPane(settings);
	var api = pane.data('jsp');
	var mod = parseInt(jmlData)%20;
	if($('.scrollPopupConversation').outerHeight() + api.getContentPositionY() >= api.getContentHeight() && scrollStats == true && jmlData >=20 && mod == 0)
		{
			scrollStats = false;
			moreConversation(jmlData);
		}
});

function profileConversation(user_id){
	uID = user_id;
	var settings = {
		showArrows: false
	};
	var pane = $('#popup_user_detail_right');
	pane.jScrollPane(settings);
	var api = pane.data('jsp');
	$.ajax({
		url: 'index.php?page=profile&act=profileConversation',
		type: 'POST',
		data : {userID: user_id},
		dataType: 'json',
		beforeSend: function(){
			api.getContentPane().html('<img src="img/loaders.gif">');
			$('#popup_user_detail_left').html('<img src="img/loaders.gif">');
		},
		success: function(response) {		
			$('#popup_user_detail_left').html(popupUserLeft(response[0],response[0].isMe));			
			api.getContentPane().html(popupUserConversation(response));
			api.getContentPane().append('<span class="spanLoader">');
			api.reinitialise();
			jmlData = response.length;
		}
	});
}

function moreConversation(nextConvers){
	var settings = {
		showArrows: false
	};
	var pane = $('#popup_user_detail_right');
	pane.jScrollPane(settings);
	var api = pane.data('jsp');
	$.ajax({
		url: 'index.php?page=profile&act=moreConversation',
		type: 'POST',
		data : {startID: nextConvers},
		dataType: 'json',
		beforeSend: function(){
			$('span.spanLoader').html('<img class="conversLoader" src="img/loaders.gif">');
		},
		success: function(response) {
			if (response != null){
				$('span.spanLoader').remove();
				api.getContentPane().append(popupUserConversation(response));
				api.getContentPane().append('<span class="spanLoader">');
				api.reinitialise();
				jmlData = parseInt(jmlData) + parseInt(response.length);
				scrollStats = true;
			}
		}
	});
}

function updateStats(){
	$.ajax({
		url: 'index.php?page=home&act=updateStats',
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(response) {		
			$('#counterUserDaftar').html(response['daftar']);
			$('#counterUserOnline').html(response['online']);
			$('#headNickname').html(response['nickname']);
			clearTimeout(updateStatsTimeout);
			updateAdminStatusTimeout = setTimeout(updateAdminStatus,2*1000);
		}
	});
}

function bigImage(user_id){
	$.ajax({
		url: 'index.php?page=profile&act=bigImage',
		type: 'POST',
		data : {userID: user_id},
		dataType: 'json',
		beforeSend: function(){
			$('.fullImage').html('<img style="width:40px;" src="img/loaders.gif">');
		},
		success: function(response) {		
			$('.authorImage img').attr('src', 'http://graph.facebook.com/'+response['fb_id']+'/picture');
			$('.authorImage .userName').html(response['nickname']);
			$('.fullImage').html('<img src="image_update/normal_'+response['image']+'">');
		}
	});
}

function shareFB(fb_user,fb_img,fb_post){
	FB.ui({
		method: 'feed',
		name: 'AXIS BAROKAH',
		link: 'http://www.tangkapberkahaxis.com',
		picture: 'http://www.tangkapberkahaxis.com/'+fb_img+'',
		caption: '@'+fb_user,
		description: fb_post
	});						  
}

function updateAdminStatus(){
	var settings = {
		showArrows: false
	};
	var pane = $('#boxAdminStatus');
	pane.jScrollPane(settings);
	var api = pane.data('jsp');
	$.ajax({
		url: 'index.php?page=home&act=updateAdminStatus',
		type: 'POST',
		data : {since_id: since_admin_id},
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(response) {
			if( response[0].lastAdminStatus != 0)  {
					if (response != 'false'){
					//console.log(since_admin_id);
					if(since_admin_id!=0 )
					{
					api.getContentPane().prepend(prependLatesAdmin(response));
					api.reinitialise();
					}
					since_admin_id = response[0].lastAdminStatus;
				}
			}
			clearTimeout(updateAdminStatusTimeout);
			cekUnlikeLogOutTimeout = setTimeout(cekUnlikeLogOut,2000);
		}
	});
}

function updateObrolan(){
			var settings = {
				showArrows: false
			};
			var pane = $('.scrollbar2')
			pane.jScrollPane(settings);
			var api = pane.data('jsp');
			var postTS = parseInt($('#post_ts').val());
			$.ajax({
				url: 'index.php?page=home&act=updateOMM',
				type: 'POST',
				data : {ts: postTS},
				dataType: 'json',
				beforeSend: function(){
				},
				success: function(response) {
					if(response){
						api.getContentPane().prepend(prepObrolan(response));
						api.reinitialise();
						$('#post_ts').val(response['posting_date_ts']);
					}
					clearTimeout(updateObrolanTimeout);
					postingToTableTimeout = setTimeout(postingToTable,2*1000);
				}
			});
		}
		
function shareFBPopup(){
		$.post('index.php?page=profile&actAjax=shareFBAjax');
	}
function shareTWPopup(){
	$.post('index.php?page=profile&actAjax=sharetwitterAjax');
}		