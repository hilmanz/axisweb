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
	
	jQuery(document).ready(function($) {
		/*------------POP UP------------*/	
		jQuery("a.showPopup").click(function(){
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
												cursor: 	 'pointer'
											});
		$('#no_mouse_events').ResetScroller({	onmouseover: 'play', onmouseout: 'play'   });
			
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
		$('a.showPopup').click(
			function (e) {
				$('html, body').animate({scrollTop: '0px'}, 800);
			}
		);
	});