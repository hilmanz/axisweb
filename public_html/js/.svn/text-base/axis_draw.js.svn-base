function popupUserLeft(a,b){
	var str='<a class="bigThumb">';
		str+='<img src="https://graph.facebook.com/'+a['fb_id']+'/picture?type=large" />';
		if (b == a['user_id']){
			str+='<span class="star">&nbsp;</span >';
		}
		str+='</a>';
		str+='<span class="userName">'+a['nickname']+'</span>';
	return str;
}

function popupUserConversation(a){
	var str='';
	var fb_img;
	//var checkIMG = "images/default-icon.gif";
	var checkIMG = [];
	var i = 0;
	$.each(a, function(s, item) {
		if(a[s].img != null || a[s].img != ""){
			checkIMG.push('image_update/small_'+a[s].img+'');
		}
	});
	$.each(a, function(x, item) {
		str+='<div class="row" style="font-family: Verdana,Geneva,sans-serif;font-size: 12px;">';
		if (a[x].img != null){
			str+='<a class="thumb"><img src="image_update/small_'+a[x].img+'" /></a>';
			fb_img = "image_update/small_"+a[x].img;
			i++;
		}else{
			if (checkIMG[i] == null || a[x].img == null || a[x].img == ''){
				str+='<a class="thumb"><img src="images/default-icon.gif" /></a>';
				fb_img = "images/default-icon.gif";
			}else{
				str+='<a class="thumb"><img src="'+checkIMG[i]+'" /></a>';
				fb_img = checkIMG[i];
			}	
		}
		str+='<div class="entry">';
		str+='<p class="wordWarp"><a>@'+a[x].nickname+'</a> '+a[x].posting+'</p>';
		str+='<span class="time">'+a[x].posting_date+'</span>';
		str+='<span class="date"></span>';
		str+='<a class="facebook" href="#" onclick="shareFB(\''+a[x].nickname+'\',\''+fb_img+'\',\''+a[x].posting+'\');shareFBPopup();">&nbsp;</a>';
		str+='<a class="twitter" href="https://twitter.com/intent/tweet?button_hashtag=AXISbarokah&text='+a[x].posting+' https://www.tangkapberkahaxis.com" data-size="large" data-related="AxisBarokah" onclick="shareTWPopup()">&nbsp;</a>';
		str+='</div>';
		str+='</div><!-- end .row -->';
	});
	return str;
}

function prependLatesAdmin(a){
	var str = '';
	$.each(a, function(x, item) {
		str+='<div class="row">';
		str+='<p>'+a[x].posting+'</p>';
		str+='<span class="time">'+a[x].posting_date+'</span>';
		str+='<span class="date"></span>';
		str+='</div>';
	});
	return str;
}

function prepObrolan(data){
	var str = '<div class="row">';
	str += '<a class="thumb showPopup" href="#popupUser" onclick="javascript:profileConversation('+data['user_id']+')"><img src="https://graph.facebook.com/'+data['fb_id']+'/picture" /></a>';
	str += '<div class="entry">';
	str += '<p class="wordWarp"><a class="showPopup" href="#popupUser" onclick="javascript:profileConversation('+data['user_id']+')">@'+data['nickname']+'</a> '+data['posting']+'</p>';
	str += '<span class="time">'+data['posting_date']+'</span>';
	str += '<span class="date"></span>';
	str += '</div>';
	str += '</div>';
	return str;
}