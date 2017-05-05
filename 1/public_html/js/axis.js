var seatings = {};
var table_screen = {page:0,pos:0};
var r_width = 0; //right total position
var r_pos = 600; //right start position
var l_width = 0; //left total width
var l_pos = 0; //left-most position
var cwidth = 50; //container width
var cmargin = 10; //containr margin
var user_state = {};
var initBintang = true;
function init_seats(s,user_id,nickname){
	seatings = {seats:[{fb_id:user_id,name:nickname,friend:1,special:1}],
				size:s,
				middle:Math.floor(s/2),
				guests:[],
				friends:[],
				specials:[],
				offliner:[],
				initialized:false,
				since_id:0};
	$.ajax({
		  url: service_url+"?service=table&m=seats&access_token="+at,
		  dataType: 'json',
		  success: function( response ) {
				if(response.status=="1"){
					if(response.data!=null){
						seatings = response.data;
						var seats = [];
						for(var x in seatings.seats){
							
						}
						//$.
					}
				}
				activity();
				update_table();
				scrollPanes();
				
		  }
	});
}
function activity(){
	
		$.ajax({
			  url: service_url+"?service=table&m=activity&since_id="+seatings.since_id+"&access_token="+at,
			  dataType: 'json',
			  success: function( response ) {
					if(response.status=="1"){
						if(response.data!=null){
							seatings.since_id = response.data.since_id;
							var people = response.data.people;
							if(people!=null){
								$.each(people,function(x,val){
									if(val.flag==0){
										go_offline({fb_id:val.fb_id,name:val.name,special:val.special,friend:val.is_friend});
									}else{
										go_online({fb_id:val.fb_id,name:val.name,special:val.special,friend:val.is_friend});
									}
								});
								update_seatings();
								scrollPanes();
							}
						}
						
					}
			  }
		});
	
}
function update_seatings(){
	for(var nn in seatings.seats){
		if(seatings.seats[nn].fb_id!=null){
			var s_fb_id = seatings.seats[nn].fb_id;
			try{
				if(user_state[s_fb_id]==null){
					user_state[s_fb_id] = 1;
				}
			}catch(e){
				user_state[s_fb_id] = 1;
			}
		}
	}
	//console.log("seats : "+seatings.seats);
	//check offline people first
	while(seatings.offliner.length>0){
		var off = seatings.offliner.shift();
		var to_be_deleted = [];
		$.each(seatings.seats,function(x,v){
			if(seatings.seats[x].fb_id==off.fb_id){
				console.log(x+' --> ada nih');
				if(seatings.seats[x].special==1){
					to_be_deleted.push(x);
					//seatings.seats[x].fb_id=null;
					//seatings.seats[x].name="";
				
				}else{
					seatings.seats[x].fb_id=null;
					seatings.seats[x].name="";
					seatings.seats[x].friend=0;
					seatings.seats[x].special=0;
				}
			}
			user_state[off.fb_id] = 0;
		});
		$.each(to_be_deleted,function(x,n){
			seatings.seats.splice(n,1);
		});
	}
	
	//give new online friend a seat
	while(seatings.friends.length>0){
		var g = seatings.friends.pop();
		var is_empty_seat = false;
		
		try{
			if(user_state[g.fb_id]==null){
				user_state[g.fb_id]=0;
			}
		}catch(e){user_state[g.fb_id]=0;}
		
		console.log("friend : "+g.fb_id+" -> "+user_state[g.fb_id]);
		
		//kasih jatah kursi untuk friend
		//friend itu akan mencari kursi kosong dari area teman dan area guest.
		//jika ditemukan.. maka friend akan duduk disitu.
		// jika tidak ditemukan.. maka friend akan geser kursi si guest.. dan duduk di area teman paling akhir.
		if(user_state[g.fb_id]==0){
			var friend_last_pos = 0;
			$.each(seatings.seats,function(x,v){
				friend_last_pos = x;
				if(x>0){
					if(seatings.seats[x].fb_id==null&&seatings.seats[x].special!=1&&(seatings.seats[x].friend==0)){
						is_empty_seat = true;
						seatings.seats[x] = g;
						console.log("kosong nih");
						return false;
					}
					//sudah jauh keluar dari area friend, maka kita assume bangku disini.
					if(seatings.seats[x].friend==0){
						console.log("kick guest");
						return false;
					}
				}
			});
			if(!is_empty_seat){
				//gak ada yang kosong
				console.log("geser");
				//ini harusnya push sebelum kursi guest
				if(friend_last_pos>0){
					seatings.seats.splice(friend_last_pos,0,g);
				}else{
					seatings.seats.push(g);
				}
			}
			user_state[g.fb_id] = 1;
		}
	}
	//give new online guests a seat
	while(seatings.guests.length>0){
		var g = seatings.guests.shift();
		
		var is_empty_seat = false;
		
		try{
			if(user_state[g.fb_id]==null){
				user_state[g.fb_id]=0;
			}
		}catch(e){user_state[g.fb_id]=0;}
		console.log("guests : "+g.fb_id+" -> "+user_state[g.fb_id]);
		if(user_state[g.fb_id]==0){
			$.each(seatings.seats,function(x,v){
				if(seatings.seats[x].fb_id==null&&seatings.seats[x].special!=1&&seatings.seats[x].friend!=1){
					is_empty_seat = true;
					seatings.seats[x] = g;
					return false;
				}
			});
			if(!is_empty_seat){
				//gak ada yang kosong
				//console.log("geser");
				seatings.seats.push(g);
			}
			user_state[g.fb_id] = 1;
		}
	}
	//give new online special guests a seat
	while(seatings.specials.length>0){
		var g = seatings.specials.shift();
		try{
			if(user_state[g.fb_id]==null){
				user_state[g.fb_id]=0;
			}
		}catch(e){user_state[g.fb_id]=0;}
		console.log("special : "+g.fb_id+" -> "+user_state[g.fb_id]);
		if(user_state[g.fb_id]==0){
			var is_empty_seat = false;
			$.each(seatings.seats,function(x,v){
				if(seatings.seats[x].fb_id==null&&seatings.seats[x].special==1){
					console.log("special kosong");
					is_empty_seat = true;
					seatings.seats[x] = g;
					return false;
				}
			});
			if(!is_empty_seat){
				//gak ada yang kosong
				//special guests selalu disamping user.
				console.log("special geser");
				seatings.seats.splice(1,0,g);
			}
			user_state[g.fb_id] = 1;
		}
	}
	
	//console.log("seats now : "+seatings.seats);
	//if the amount of user online is still below 7 people,
	//then we open empty seats
	var n_seats = seatings.seats.length;
	console.log("seats : "+n_seats);
	if(n_seats<7){
		var x  = 7 - n_seats;
		for(var n=0;n<x;n++){
			console.log("add empty seats");
			seatings.seats.push({fb_id:null,name:null,is_friend:0,special:0});
		}
	}
	
	
	sync_seats();
	update_table();
}
function sync_seats(){
	var postData = {s:JSON.stringify(seatings)};
	$.ajax({
		  url: service_url+"?service=table&m=update_seats&access_token="+at,
		  type:'POST',
		  data: postData,
		  dataType: 'json',
		  success: function( response ) {
				if(response.status=="1"){
					console.log("saved");
				}
		  }
	});
}

function update_table(){
	var left = [];
	var right = [];
	var size = seatings.size;
	
	$.each(seatings.seats,function(x,v){
		console.log("seats  :"+v.name);	
		if(x%2==0){
			right.push(v);
		}else{
			left.push(v);
		}
	});
	for(var nn in left){
		console.log("left : "+left[nn].name);
	}
	for(var nn in right){
		console.log("right : "+right[nn].name);
	}
	left.reverse();	
	var kursi = 1;
	
	$.each(left,function(x,v){
		//if(v.fb_id==null) return true;
		//if(fid==v.fb_id && x!=0)  return true;
		if(kursi>=7) kursi = 1;
		if(kursi == 4 ) kursi++;
		//$("#l"+x).html('');
		//x = left.length - x;
		var itemHtml='';
		if($("#l"+x).html()!=null){
			$("#l"+x).html('');
		}else{
			var div = $("<div>");
			div.attr('id',"l"+x);
			div.attr('class',"item lazy leftSeat");
			div.attr('facebook_id',v.fb_id);
			$("#left").append(div);

		}

		if(v.fb_id!=null){

			var imagesPhoto = "http://graph.facebook.com/"+v.fb_id+"/picture";
		}
		if(v.fb_id!=null){
			itemHtml += '				<span id="special_'+v.fb_id+'"></span>';
			itemHtml += '				<div class="status">';
			itemHtml += '					<div class="user">';
			itemHtml += '						<a class="thumb" href="http://www.facebook.com/'+v.fb_id+'"  target="_blank"><img src="'+imagesPhoto+'" /></a>';
			itemHtml += '						<span class="nickname" id="nickname_'+v.fb_id+'">'+v.name+'</span>';
			itemHtml += '					</div>';
			itemHtml += '					<span class="text" id="post_'+v.fb_id+'"><blink>waiting...</blink></span>';
			itemHtml += '					<span id="details_'+v.fb_id+'"></span>';
			itemHtml += '				</div>';
		}
		itemHtml += '				<div class="kursi kursi'+kursi+'">';
		if(v.fb_id!=null){
			itemHtml += '					<div class="piring" id="piring_'+v.fb_id+'">';
			//itemHtml += '						<div class="thumbFood">';
			//itemHtml += '							<a href="#" id="image_'+v.fb_id+'"></a>';
		//	itemHtml += '						</div>';
			itemHtml += '					</div>';
		}
		//itemHtml += '					<div class="hadiah showPopup"></div>';
		itemHtml += '				</div>';
	
		
		$("#l"+x).append(itemHtml);
		kursi++;
	});
	
	
	$.each(right,function(x,v){
		//if(v.fb_id==null) return true;
		if(fid==v.fb_id && x!=0) return true;
		if(kursi>=7) kursi = 1;
		if(kursi == 4 ) kursi++;
		var itemHtml='';
		if($("#r"+x).html()!=null){
			$("#r"+x).html('');
		}else{
			var div = $("<div>");
			div.attr('id',"r"+x);
			div.attr('class',"item lazy rightSeat");
			div.attr('facebook_id',v.fb_id);
			$("#right").append(div);
			
		}

		if(v.fb_id!=null){
			var imagesPhoto = "http://graph.facebook.com/"+v.fb_id+"/picture";
		}
		
		if(v.fb_id!=null){
			if(x==0) itemHtml += '				<span id="special_'+v.fb_id+'"><div class="spotlight2"></div></span>';
			else itemHtml += '				<span id="special_'+v.fb_id+'"></span>';
			itemHtml += '				<div class="status">';
			itemHtml += '					<div class="user">';
			itemHtml += '						<a class="thumb" href="http://www.facebook.com/'+v.fb_id+'" target="_blank"><img src="'+imagesPhoto+'" /></a>';
			itemHtml += '						<span class="nickname" id="nickname_'+v.fb_id+'">'+v.name+'</span>';
			itemHtml += '					</div>';
			itemHtml += '					<span class="text" id="post_'+v.fb_id+'"><blink>waiting...</blink></span>';
			itemHtml += '					<span id="details_'+v.fb_id+'"></span>';
			if(x==0) itemHtml += '					<a href="#" class="star">&nbsp;</a>';
			itemHtml += '				</div>';
		}
			if(x==0)	itemHtml += '				<div class="kursi kursi4">';
			else itemHtml += '				<div class="kursi kursi'+kursi+'">';
		if(v.fb_id!=null){
			itemHtml += '					<div class="piring" id="piring_'+v.fb_id+'">';
		//	itemHtml += '						<div class="thumbFood">';
	//		itemHtml += '							<a href="#" id="image_'+v.fb_id+'"></a>';
		//	itemHtml += '						</div>';
			itemHtml += '					</div>';
		}
	//	itemHtml += '					<div class="hadiah showPopup"></div>';
		itemHtml += '				</div>';
	
		
		$("#r"+x).append(itemHtml);
		kursi++;
	});
}

function update_table_old(){
	var left = [];
	var right = [];
	var size = seatings.size;
	$.each(seatings.seats,function(x,v){
		//if(x<size){
			if(x%2==0){
				right.push(v);
			}else{
				left.push(v);
			}
		//}
		//displayed_list.push(seatings.seats[x]);
	});
	//left.reverse();
	$.each(left,function(x,v){
		//$("#l"+x).html('');
		if($("#l"+x).html()!=null){
			$("#l"+x).html('');
		}else{
			var div = $("<div>");
			div.attr('id',"l"+x);
			div.css({'float':"right",'width':cwidth+'px','margin-right':cmargin+'px'});
			$("#left").append(div);
			l_width+=(cwidth+cmargin);
			$("#left").css("width",l_width);
			$("#left").css("left",r_pos-l_width);
		}
		var html = $("<div>");
		var img = $("<img>");
		if(v.fb_id!=null){
			img.attr("src","http://graph.facebook.com/"+v.fb_id+"/picture");
		}
		var label = $("<p>");
		label.html(v.name); //nanti ganti jadi nama user
		html.append(img);
		html.append(label);
		$("#l"+x).append(html);
	});
	$.each(right,function(x,v){
		if($("#r"+x).html()!=null){
			$("#r"+x).html('');
		}else{
			var div = $("<div>");
			div.attr('id',"r"+x);
			div.css({'float':"left",'width':'50px','margin-right':'10px'});
			$("#right").append(div);
			r_width+=(cwidth+cmargin);
			$("#right").css("width",r_width);
			
		}
		var html = $("<div>");
		var img = $("<img>");
		if(v.fb_id!=null){
			img.attr("src","http://graph.facebook.com/"+v.fb_id+"/picture");
		}
		var label = $("<p>");
		label.html(v.name); //nanti ganti jadi nama user
		html.append(img);
		html.append(label);
		$("#r"+x).append(html);
	});
}
function moveLeft(target){
	var currentPos = parseInt($(target).css('left'),10);
	$(target).animate({ left:currentPos+100},800,'linear');
}
function moveRight(target){
	var currentPos = parseInt($(target).css('left'),10);
	$(target).animate({left:currentPos-100},800,'linear');
}
function add_guest(s){
	$.each(s,function(x,item){
		seatings.guests.push(s[x]);
	});
}
function send_ping(old_pt){
	$.ajax({
		  url: service_url+"?service=table&m=ping&r="+pt+"&access_token="+at2,
		  dataType: 'json',
		  success: function( response ) {
				if(response.status=="1"){
					console.log(response.data.r);
					pt = response.data.r;
					at = response.data.t;
				}
				activity();
		  }
	});
}
function go_offline(user){
	console.log(user.name+" - "+user.special+" offline");
	seatings.offliner.push(user);
}
function go_online(user){
	if(user.special==1){
		console.log('special guest -> '+user.name);
		seatings.specials.push(user);
	}else if(user.friend==1){
		console.log('friend -> '+user.name);
		seatings.friends.push(user);
	}else{
		console.log('guest -> '+user.name);
		seatings.guests.push(user);
	}
}

//ACIT
	// DETECT BROWSER
	var BrowserDetect = {
    init: function () {
        this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
        this.version = this.searchVersion(navigator.userAgent)
            || this.searchVersion(navigator.appVersion)
            || "an unknown version";
        this.OS = this.searchString(this.dataOS) || "an unknown OS";
    },
    searchString: function (data) {
        for (var i=0;i<data.length;i++)    {
            var dataString = data[i].string;
            var dataProp = data[i].prop;
            this.versionSearchString = data[i].versionSearch ||
			data[i].identity;
						if (dataString) {
							if (dataString.indexOf(data[i].subString) != -1)
								return data[i].identity;
						}
						else if (dataProp)
							return data[i].identity;
					}
				},
				searchVersion: function (dataString) {
					var index = dataString.indexOf(this.versionSearchString);
					if (index == -1) return;
					return
			parseFloat(dataString.substring(index+this.versionSearchString.length+1));
				},
				dataBrowser: [
					{
						string: navigator.userAgent,
						subString: "Chrome",
						identity: "Chrome"
					},
					{
						string: navigator.userAgent,
						subString: "OmniWeb",
						versionSearch: "OmniWeb/",
						identity: "OmniWeb"
					},
					{
						string: navigator.vendor,
						subString: "Apple",
						identity: "Safari",
						versionSearch: "Version"
					},
					{
						prop: window.opera,
						identity: "Opera"
					},
					{
						string: navigator.vendor,
						subString: "iCab",
						identity: "iCab"
					},
					{
						string: navigator.vendor,
						subString: "KDE",
						identity: "Konqueror"
					},
					{
						string: navigator.userAgent,
						subString: "Firefox",
						identity: "Firefox"
					},
					{
						string: navigator.vendor,
						subString: "Camino",
						identity: "Camino"
					},
					{        // for newer Netscapes (6+)
						string: navigator.userAgent,
						subString: "Netscape",
						identity: "Netscape"
					},
					{
						string: navigator.userAgent,
						subString: "MSIE",
						identity: "Explorer",
						versionSearch: "MSIE"
					},
					{
						string: navigator.userAgent,
						subString: "Gecko",
						identity: "Mozilla",
						versionSearch: "rv"
					},
					{         // for older Netscapes (4-)
						string: navigator.userAgent,
						subString: "Mozilla",
						identity: "Netscape",
						versionSearch: "Mozilla"
					}
				],
				dataOS : [
					{
						string: navigator.platform,
						subString: "Win",
						identity: "Windows"
					},
					{
						string: navigator.platform,
						subString: "Mac",
						identity: "Mac"
					},
					{
						string: navigator.platform,
						subString: "Linux",
						identity: "Linux"
					}
				]
			 
			};
			BrowserDetect.init();
	  
	  
    $(document).ready(function(){
		/*------------ADD CLASS DETECT BROWSER------------*/ 
		$("body").addClass(BrowserDetect.browser); 
		
    });
	
	// COUTDOWN CHARACTER 
	function updateCountdown() {
   	// 140 is the max message length
		var remaining = 140 - jQuery('.message_status').val().length;
  	  jQuery('.maxChar').text(remaining + ' karakter tersisa');	
	}	
	jQuery(document).ready(function($) {
		
		$('.message_status').change(updateCountdown);
		$('.message_status').keyup(updateCountdown);
		/*------------POP UP------------*/	
		$("a.showPopup").live('click', function(){
			$('.popupContainer').each(function(){
				$(this).fadeOut();
			});
			var targetID = jQuery(this).attr('href');
			jQuery(targetID).fadeIn();
			jQuery(targetID).addClass('visible');
			jQuery("#bgPopup").fadeIn();
		});
		jQuery("a.closePopup").click(function(){
			jQuery(".popupContainer").fadeOut();
			jQuery("#bgPopup").fadeOut();
		});
	});
	
	
    $(document).ready(function(){
		/*------------ADD CLASS DETECT BROWSER------------*/ 
		$("body").addClass(BrowserDetect.browser); 
		/*------------RUNNING TEXT------------*/ 
		$('.horizontal_scroller').SetScroller({	velocity: 	 60,
												direction: 	 'horizontal',
												startfrom: 	 'right',
												loop:		 'infinite',
												movetype: 	 'linear',
												onmouseover: 'pause',
												onmouseout:  'play',
												onstartup: 	 'play',
												cursor: 	 'text'
											});
		$('#no_mouse_events').ResetScroller({	onmouseover: 'play', onmouseout: 'play'   });
		//Validate Input NUmber
		$(".numeric").numeric();
			
	});
	// SCROLLBAR
	$(function()
	{
		$('.scrollbar')
			.jScrollPane(
				{
					showArrows:false
				}
			);
	});
	/*------------SCROLL UP------------*/	
	$(function() {
		$('a.showPopup,a.showPopupBeli,#weeklyWinner').click(
			function (e) {
				$('html, body').animate({scrollTop: '0px'}, 800);
			}
		);
	});

	
//--Kia--//
function scrollPanes(){
//vars
		var ui;
		var browserWidth = parseInt($(window).width());
		if (browserWidth <= 1024){browserWidth = 1024;}
		$('#viewContainer').css({'width': browserWidth+'px'});
		var conveyor = $(".content-conveyor", $("#sliderContent")),
		item = $(".item", $("#sliderContent"));
		
		//set length of conveyor
		conveyor.css("width", item.length * parseInt(item.css("width")));
		
		var startSLD,stopSLD,currentSLD;
		var widthView = parseInt($('#viewContainer').width()),
		widthPage = parseInt($('#pageContainer').width()),
		movePage = (widthPage-widthView)/2,
		totalChair = Math.round(widthPage/240),
		perView = Math.round(widthView/240);
		
		var centerStar = (widthPage-widthView)/2;
		var leftTotalChair = Math.floor(totalChair/2)-1;
		var rightTotalChair = Math.round(totalChair/2)-1;
		var chair = new Array();
		var rightChair = parseInt(Math.round(perView/2));
		var indexChairRight = 0;
		while(rightChair > 0){
			chair.push(indexChairRight);
			rightChair--;
			indexChairRight++;
		}
		
		var leftChair = parseInt(Math.floor(perView/2))-1;
		var indexChairLeft = leftTotalChair;
		while(leftChair > 0){
			chair.unshift(indexChairLeft);
			leftChair--;
			indexChairLeft--;
		}
		var kursi = 'l';
  
		$.each(chair, function(index, value) {
			var v = parseInt(value);
			if(v == 0){kursi = 'r';}
			$('#'+kursi+''+value+'').css({'visibility':'visible'});
			
		});
		// console.log(chair);
		//config
		var initSLD = false;
		var sliderOpts = {
		  max: (item.length * parseInt(item.css("width"))) - parseInt($(".viewer", $("#sliderContent")).css("width")),
		  slide: function(e, ui) { 
			if (chair.length%2 == 0){
				conveyor.css("left", "-" + (ui.value+120) + "px");
			}else{
				conveyor.css("left", "-" + ui.value + "px");
			}
		  },
		  start: function(event, ui) {
			if (initSLD == false ){
				startSLD = centerStar;
			}else{
				startSLD = parseInt(ui.value);
			}
		  },
		  change: function(event, ui) {
			initSLD = true;
			currentSLD = parseInt(ui.value);
			
			if(currentSLD > startSLD){
				//slide ke kanan			
				var kananSLD = Math.abs(Math.round((currentSLD-movePage)/240));
				// alert('KANAN:'+startSLD+'='+kananSLD+'='+currentSLD+'='+movePage+'='+chair+'=='+leftTotalChair);
				if(kananSLD > 0){
					var lastArr = chair.pop(); 
					if (lastArr == leftTotalChair){
						if (movePage > centerStar){
							var addVal = lastArr+1
						}else{
							var addVal = 0;
						}
					}else{
						var addVal = parseInt(lastArr) + 1;
					}
					
					chair.push(lastArr);
					while(kananSLD > 0){
						//show
						chair.push(addVal);
						
						//hide
						chair.shift();
						
						var checkKananSLD = kananSLD - 1;
						if (checkKananSLD != 0){
							addVal++;
						}
						
						if (addVal > leftTotalChair && movePage < centerStar){
							addVal = 0;
						}
						movePage=movePage+240;
						kananSLD--;
					}
					
					var checkZero = parseInt(chair.indexOf(0,0));
					if(checkZero > 0){
						kursi = 'l';
					}else if(movePage < centerStar){
						kursi= 'l';
					}else{
						kursi = 'r';
					}
					// alert(checkZero);
					//hide
					$('.rightSeat').each(function(index) {
						$(this).css({'visibility':'hidden'});	
					});
					$('.leftSeat').each(function(index) {
						$(this).css({'visibility':'hidden'});	
					});
					
					//show
					$.each(chair, function(index, value) {
						var v = parseInt(value);
						if(v == 0){kursi = 'r';}
						$('#'+kursi+''+value+'').css({'visibility':'visible'});		
					});
				}
				 // alert('KANAN:'+startSLD+'='+kananSLD+'='+currentSLD+'='+movePage+'='+chair+'='+chair.length);
			}
			if(currentSLD < startSLD){
				var kiriSLD = Math.abs(Math.round((movePage-currentSLD)/240));
				 // alert('KIRI:'+startSLD+'='+kiriSLD+'='+currentSLD+'='+movePage+'='+chair+'===='+leftTotalChair);
				if(kiriSLD > 0){
					var firstArr = chair[0];
					var lengthChair = chair.length-1;
					if (firstArr == 0){
						firstArr = leftTotalChair+1;
					}
					// else if(chair[lengthChair] == rightTotalChair){
						// firstArr = 1;
					// }
					// alert(chair[lengthChair]+'==='+rightTotalChair);
					var minVal = parseInt(firstArr) - 1;
					while(kiriSLD > 0){
						chair.unshift(minVal);
						
						chair.pop();
										
						var checkKiriSLD = kiriSLD - 1;
						if(checkKiriSLD != 0){
							minVal--;
						}
						if(movePage > centerStar && minVal == -1){
							minVal = leftTotalChair;
						}
						// alert(leftTotalChair+'=='+minVal);
						movePage=movePage-240;
						kiriSLD--;	
					}
					
					
					var checkZero = parseInt(chair.indexOf(0,0));
					if(checkZero > 0){
						kursi = 'l';
					}else if(checkZero == 0){
						if (movePage > centerStar){
							kursi = 'r';
						}else{
							kursi = 'l';
						}
					}else{
						if (movePage < centerStar){
							kursi = 'l';
						}else{
							kursi = 'r';
						}
					}
					// alert(checkZero);
					//hide
					$('.rightSeat').each(function(index) {
						$(this).css({'visibility':'hidden'});	
					});
					$('.leftSeat').each(function(index) {
						$(this).css({'visibility':'hidden'});	
					});
					
					//show
					$.each(chair, function(index, value) {
						var v = parseInt(value);
						if(v == 0 && checkZero !=0){kursi = 'r';}
						$('#'+kursi+''+value+'').css({'visibility':'visible'});
						$('#'+kursi+''+value+'').fadeIn();
					});
				}
				// alert('KIRI:'+startSLD+'='+kiriSLD+'='+currentSLD+'='+movePage+'='+chair);
			}
		  }
		};
		
		//create slider
		$("#slider").slider(sliderOpts);
		if(initBintang == true){
			if (chair.length%2 == 0){
				var evenWidth = movePage+240;
				$('#pageContainer').css({'left': -evenWidth});
			}else{
				$('#pageContainer').css({'left': -movePage});
			}
			
			$('.ui-slider-handle').css({'left':'49.5%'});
			initBintang = false;
		}
		
		
		
		
}
//Detect browser resize
$(document).ready(function(){
	$(window).bind('resize', scrollPanes);
});
