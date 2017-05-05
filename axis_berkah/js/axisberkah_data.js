var deleteTwitList = new Array();
var feedID;

$('input.twitCheck').live('click', function(){
	feedID = $(this).attr('no');
	if ($(this).is(':checked')) {
		deleteTwitList.push(feedID);
	} else {
		var arrPosition = deleteTwitList.indexOf(feedID);
		if(arrPosition >= -1){
			deleteTwitList.splice(arrPosition,1);
		}
	} 
});

$('#hapusTweet').live('click', function(){
	if(deleteTwitList.length != 0){
		$.ajax({
			url: 'index.php?page=home&act=deleteTweet',
			type: 'POST',
			data : {twitID: deleteTwitList},
			dataType: 'json',
			beforeSend: function(){
				$('#twitList').html('<div class="loaderBox" align="center"><img src="img/loaders.gif"></div>');
			},
			success: function(response) {		
				//$('#twitList').html(redrawTwitterList(response));
				
				//if (response != null){
				if(response.status==1){
					window.location = "index.php?page=home&act=tweetNotification&r="+response.rs;
				}
				//}
			}
		});
	}
});

//Auto load Scroll
var uID = 0;
var jmlData = 0;
var scrollStats = true;
var since_admin_id = 0;
var interval = null;
$('#twitList').live('scroll', function(){
	var settings = {
		showArrows: false
	};
	var pane = $('#twitList');
	pane.jScrollPane(settings);
	var api = pane.data('jsp');
	var mod = parseInt(jmlData)%20;
	if($('#twitList').outerHeight() + api.getContentPositionY() >= api.getContentHeight() && scrollStats == true && jmlData >=20 && mod == 0)
		{
			scrollStats = false;
			moreConversation(jmlData);
		}
});
function refresh_tweet(){
	
	$.ajax({
		url: 'index.php?page=home&act=moreConversation',
		type: 'POST',
		data : {startID: 0},
		dataType: 'json',
		beforeSend: function(){
			
		},
		success: function(response) {
			var str = '<div id="twitList" class="scrollbar scroll-pane wide6 tall6">';
			if (response != null){
					if(response.tweet!=null){
						if(response.tweet.length>0){
							$.each(response.tweet,function(i,v){
								 str+='<div id="'+v.feed_id_str+'" class="row">';
		                         str+='<a class="thumb showPopup" href="#popupUser"><img src="'+v.pic+'"/></a>';
		                         str+='<div class="entry">';
		                         str+='<p><a class="showPopup" href="#popupUser">@'+v.twitter_id+'</a>'+v.txt+'.</p>';
		                         str+='<span class="time">'+v.created_at_str+'</span>';
		                         str+='<span class="date"></span>';
		                         str+='</div>';
		                         str+='<div class="chek"><input class="twitCheck" type="checkbox" no="'+v.feed_id_str+'" /></div>';
		                         str+='</div><!-- end .row -->';
							});
							str+="</div>";
							$(".contentTweet").html(str);
							$("#boxMessage h1").html('Buseeet...!<br /> Kamu punya kata-kata khilaf <br />sebanyak '+response.bad_words+' tweet.<br />Buruan deh dihapus!');
							$("#hapusTweet").fadeIn();
							clearInterval(interval);
						}else{
							if(response.status==2){
								document.location.reload();
							}
						}
					}else{
						if(response.status==2){
							document.location.reload();
						}
					}
				}
			}
		
	});
}
function moreConversation(nextConvers){
	var settings = {
		showArrows: false
	};
	var pane = $('#twitList');
	pane.jScrollPane(settings);
	var api = pane.data('jsp');
	$.ajax({
		url: 'index.php?page=home&act=moreConversation',
		type: 'POST',
		data : {startID: nextConvers},
		dataType: 'json',
		beforeSend: function(){
			api.getContentPane().append('<span class="spanLoader">');
			$('span.spanLoader').html('<img class="conversLoader" src="img/loaders.gif">');
		},
		success: function(response) {
			if (response != null){
				$('span.spanLoader').remove();
				api.getContentPane().append(redrawTwitterList(response));
				api.getContentPane().append('<span class="spanLoader">');
				api.reinitialise();
				jmlData = parseInt(jmlData) + parseInt(response.length);
				scrollStats = true;
			}
		}
	});
}