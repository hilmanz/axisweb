<?php
class paketRamadhan extends App{
	var $Request;
	var $View;

	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->run();
	}
	
	function run(){
		$this->main();
	}
	
	function tojson($data){
	
					header('Content-type: application/json');
					print(json_encode($data));exit;
	
	}
	
	function main(){
		
	}
	
	function getContentsByID(){
						
					$sql = " select * from axis_items where home_status=3 limit 10;";
				
					$this->open(0);
						$rsItems = $this->fetch($sql,1);
					$this->close();
					if($rsItems) $data = $rsItems;
					else $data = null;
			
		
		$this->tojson($data);
	
	}

}
