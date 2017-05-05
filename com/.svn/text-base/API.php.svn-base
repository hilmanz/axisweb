<?php
include_once $ENGINE_PATH."Utility/Paginate.php";
/**
 * 
 * API base class
 * @author duf
 *
 */
class API extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $access_info = null;
	function __construct($req,$access_info=null){
		parent::__construct($req);
		$this->access_info = $access_info;
	}
	function mainLayout($val=null){
		if($val==null){
			return $this->_mainLayout;
		}else{
			$this->_mainLayout = $val;
		}
	}
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		global $CONFIG;
		if($this->is_authorized()){
			return $this->run();
		}else{
			return $this->toJson(401,'unauthorized access',null);
		}
	}
	function is_authorized(){
		return true;
	}
	function admin(){
		
	}
	function toJson($status,$msg,$data){
		return json_encode(array("status"=>$status,"message"=>$msg,"data"=>$data));
	}
	function param($name){
		return $this->Request->getParam($name);
	}
	function assign($name,$val){
		$this->View->assign($name,$val);
	}
	function post($name){
		return $this->Request->getPost($name);
	}
	function out($tpl){
		return $this->View->toString($tpl);
	}
	function __toString(){
		//return $this->out($this->_mainLayout);
	}
	function getList($sql,$start,$total,$base_url){
		//paging
		$paging = new Paginate();
		$sql1 = $sql." LIMIT ".$start.",".$total;
		$sql2 = eregi_replace("SELECT (.*) FROM","SELECT COUNT(*) as total FROM",$sql);
		$sql2 = eregi_replace("ORDER BY(.*)","",$sql2);
		$this->open();
		$list = $this->fetch($sql,1);
		$rs = $this->fetch($sql2);
		$this->close();
		$this->assign("list",$list);
		$this->assign("pages",$paging->generate($start, $total, $rs['total']));
		
	}
	
	function object2array($object) {
		if (is_object($object)) {
			foreach ($object as $key => $value) {
				$array[$key] = $value;
			}
		}
		else {
			$array = $object;
		}
		return $array;
	}
}
?>