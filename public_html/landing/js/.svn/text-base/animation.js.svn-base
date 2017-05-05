/// Acit Jazz
/// ANIMATION AXIS
/// LOAD IMAGES FIRST
// ANIMATION
$(window).load(function(){
	function moveHp1(){
		$('#hp1').animate({'top':'+=30'},2000).delay(0).animate({'top':'-=30'},2000,function(){
													setTimeout(moveHp1,0);
		});
	}
	function moveHp2(){
		$('#hp2').delay(500).animate({'top':'-=30'},2000).delay(0).animate({'top':'+=30'},2000,function(){
													setTimeout(moveHp2,0);
		});
	}
	function moveHp3(){
		$('#hp3').delay(1000).animate({'top':'-=30'},2000).delay(0).animate({'top':'+=30'},2000,function(){
													setTimeout(moveHp3,0);
		});
	}
	function moveHp4(){
		$('#hp4').delay(300).animate({'top':'+=30'},2000).delay(0).animate({'top':'-=30'},2000,function(){
													setTimeout(moveHp4,0);
		});
	}
	function moveHp5(){
		$('#hp5').delay(100).animate({'top':'-=30'},2000).delay(0).animate({'top':'+=30'},2000,function(){
													setTimeout(moveHp5,0);
		});
	}
	function moveFd(){
		$('#fd').delay(100).animate({'top':'+=10'},2000).delay(0).animate({'top':'-=10'},2000,function(){
													setTimeout(moveFd,0);
		});
	}
	// Run the functions 
	moveHp1();
	moveHp2();
	moveHp3();
	moveHp4();
	moveHp5();
	moveFd();
});

// ROTATE GEAR
setInterval(
	function () {
		$('#gear1,#gear2,#gear3,#gear4').animate({rotate: '+=10deg'}, 0);
	},
	200
);