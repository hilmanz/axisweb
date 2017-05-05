function redrawTwitterList(data){
	var str = "";
	$.each(data, function(i, item) {	
		str +='<div id="'+data[i].feed_id_str+'" class="row">';
		str +='<a class="thumb showPopup" href="#popupUser"><img src="content/user/thumb/1.jpeg" /></a>';
		str +='<div class="entry">';
		str +='<p><a class="showPopup" href="#popupUser">@'+data[i].twitter_id+'</a>'+data[i].txt+'</p>';
		str +='<span class="time">'+data[i].created_at_str+'</span>';
		str +='<span class="date"></span>';
		str +='</div>';
		str +='<div class="chek"><input class="twitCheck" type="checkbox" no="'+data[i].feed_id_str+'" /></div>';
		str +='</div>';
	});
	
	return str;
}
