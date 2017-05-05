<?php
/**
* ADMINISTRATION PAGE
* @author Hapsoro Renaldy N <hapsoro.renaldy@winixmedia.com>
*/

include_once "common.php";
//header('Pragma: public');        
//header('Cache-control: private');
//header('Expires: -1');
$view = new BasicView();

$admin = new Admin();
//$admin->DEBUG=true;
//assign sections
if($admin->auth->isLogin()){
	switch($req->getRequest("s")){
		case "page":
			include_once $APP_PATH."StaticPage/StaticPage.php";
			$admin->execute(new StaticPage($req),"static");
		break;
		case "splash":
			include_once $APP_PATH."BSI/SplashScreen.php";
			
			$admin->execute(new SplashScreen($req),"splash");
		break;
		
		case "admin":
			include_once $APP_PATH."Admin/Admin.php";
			$admin->execute(new AdminConfig($req),"admin");
		break;
		case "article":
			include_once $APP_PATH."Article/Article.php";
			$admin->execute(new Article($req),"article");
		break;
		case "builder":
			include_once $APP_PATH."Builder/Builder.php";
			$admin->execute(new Builder($req),"builder");
		break;
		case "message":
			include_once $APP_PATH."axis/admin/message.php";
			$admin->execute(new message($req),"message");
		break;
		case "user_management":
			include_once $APP_PATH."axis/admin/user_management.php";
			$admin->execute(new user_management($req),"user_management");
		break;
		case "news":
			include_once $APP_PATH."axis/admin/news.php";
			$admin->execute(new news($req),"news");
		break;
		case "activity":
			include_once $APP_PATH."axis/admin/activity.php";
			$admin->execute(new activity($req),"activity");
		break;
        default:
			//$view->assign("mainContent","dashboard");
			//load Plugin
			if($req->getRequest("s")!=NULL){
				$plugin = $admin->loadPlugin(&$req,$req->getRequest("s"));
				//print_r($plugin);
				if($plugin){
					$admin->execute($plugin,$req->getRequest("s"));
				}
			}else{
				//or load dashboard if there's no request specified.
				$admin->showDashboard();
			}
		break;
	}
}
//assign content to main template
$admin->show();
$view->assign("mainContent",$admin->toString());
//output the populated main template
print $view->toString($MAIN_TEMPLATE);
?>