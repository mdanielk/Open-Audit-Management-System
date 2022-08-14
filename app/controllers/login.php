<?php
class login extends MD_app{	
	public function __construct(){
		
	}
	public function index(){
		$this->SetTemplate($this->getview("login","login"));
		$this->setVar('FORM_URL',base_url('login/authentication/ajax/_ajax'));
		$this->setVar('LINK_NEW_ACCOUNT',base_url('login/new_account'));
		$this->setVar('LINK_FORGOT_PASSWORD',base_url('login/forgot/'));
		$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
		$this->publishView();
		$this->konten=$this->output;			
	}
	public function new_account(){
		$this->SetTemplate($this->getview("login","new_account"));
		$this->setVar('__FORM_URL__',base_url('login/post_new_account/ajax/_ajax'));		
		$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
		$this->setVar('LINK_LOGIN',base_url('login/'));
		$this->publishView();
		$this->konten=$this->output;
	}
	public function authentication(){
		header('Content-type:Application/json');
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method!='POST'){
			die(json_encode('method not allowed'));
		}		
		$u 	= check_var('post','user_id');
		$p	= check_var('post','password');

		$this->loadmodel('Login_model','lm');
		if($this->lm->check_login($u,$p)==true){
			$_SESSION['login']=true;
			$out = array(
				'status'=>1,
				'message'=>'Success!',
				'username'=>$u
			);
		}
		else{
			$out = array(
				'status'=>0,
				'message'=>'Oppps, Wrong username or password!',
				'username'=>$u
			);
		}
		
		echo json_encode($out);
	}
}
?>