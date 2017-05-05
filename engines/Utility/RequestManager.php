<?php
class RequestManager{
	var $requests = array(array());
	function RequestManager($autoClean=false){
		//check dynamic subdomain
		$this->init_subdomain();
		
		$this->requests['POST'] = $_POST;
		$this->requests['GET'] = $_GET;
		$this->requests['SESSION'] = $_SESSION;
		$this->requests['COOKIE'] = $_COOKIE;
		$this->requests['ENV'] = $_ENV;
		$this->requests['SERVER'] = $_SERVER;
		$this->requests['FILE'] =	$_FILES;
		$this->requests['REQUEST'] = $_REQUEST;
		if(strlen(@$_GET['xn'])>0){
			$this->secureURL($_GET['xn']);
		}
		if(strlen(@$_REQUEST['req'])>0){
			$this->decrypt_params($_REQUEST['req']);
		}
		//if($autoClean){clean();}
		//var_dump($this->requests);
	}
	function init_subdomain(){
		global $CONFIG;
		$http_host = $_SERVER['HTTP_HOST'];
		
		if($http_host==null){
			$http_host = $_ENV['HTTP_HOST'];
		}
		if(eregi($CONFIG['BASE_DOMAIN'],$http_host)){
			$domain_path = explode('.'.$CONFIG['BASE_DOMAIN'],$http_host);
			$subdomain = $domain_path[0];
			$_REQUEST['subdomain'] = $subdomain;
			$_GET['subdomain'] = $subdomain;
		}
	}
	function secureURL($hash){
		$params = json_decode(urldecode64($hash));
		for($i=0;$i<sizeof($params);$i++){
			$this->requests['GET'][$params[$i]->name] = $params[$i]->value;
			$this->requests['REQUEST'][$params[$i]->name] = $params[$i]->value;
		}
		
	}
	function encrypt_params($params){
		if($params['subdomain']==null){
			if($_REQUEST['subdomain']!=NULL){
				$params['subdomain'] = $_REQUEST['subdomain'];
			}else{
				$params['subdomain'] = $_SESSION['subdomain'];
			}
		}else{
			$_SESSION['subdomain'] = $params['subdomain'];
		}
		$str = http_build_query($params);
		return "req=".urlencode64($str);
	}
	function decrypt_params($params){
		if(strlen($params)>0){
			$str = urldecode64($params);
		
			parse_str($str, $arr);
			
			foreach($arr as $name=>$val){
				if($name=="subdomain"){
					$_SESSION['subdomain'] = $val;
				}
				$this->requests['GET'][$name] = $val;
				$this->requests['REQUEST'][$name] = $val;
			}
		}
	}
	function clean($str){
		
		//cleaning up process here
		$str = mysql_escape_string($str);
		
		$str = str_replace("UNION","",$str);
		$str = str_replace("UPDATE%20","",$str);
		$str = str_replace("SELECT%20","",$str);
		$str = str_replace("CREATE%20TABLE","",$str);
		$str = str_replace("DELETE%20","",$str);
		$str = str_replace("DROP","",$str);
		$str = str_replace("union","",$str);
		$str = str_replace("update%20","",$str);
		$str = str_replace("select%20","",$str);
		$str = str_replace("create%20table","",$str);
		$str = str_replace("delete%20","",$str);
		$str = str_replace("drop","",$str);
		
		//print($str."<br/>");
		
		return $str;
	}
	function getParam($name){
		return $this->clean($this->requests['GET'][$name]);
	}
	function getRequest($name){
		return $this->clean($this->requests['REQUEST'][$name]);
	}
	function setParam($name,$value){
		$this->requests['GET'][$name] = $value;
	}
	function getPost($name){
		return $this->clean($this->requests['POST'][$name]);
	}
	function getCookie($name){
		return $this->requests['COOKIE'][$name];
	}
	function getSession($name){
		return $this->requests['SESSION'][$name];
	}
	function getFileName($name){
		return $this->requests['FILE'][$name]['name'];
	}
	function getFileTemp($name){
		return $this->requests['FILE'][$name]['tmp_name'];
	}
	function getFileSize($name){
		return $this->requests['FILE'][$name]['file_size'];
	}
	function isPostAvailable(){
		if(sizeof($_POST)>0){
			return true;
		}	
	}
	function isFileExist($name){
		if($this->requests['FILE'][$name]['name']!=NULL&&
		$this->requests['FILE'][$name]['tmp_name']!=NULL)
		{return true;}
	}
	function peek(){
		print "GET :<br/>";
		print_r($_GET);
		print "<br/>POST:<br/>";
		print_r($_POST);
		print "<br/>SESSION:<br/>";
		print_r($_SESSION);
	}
}
?>
