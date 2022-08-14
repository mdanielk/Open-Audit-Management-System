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
		$this->setVar('__FORM_URL__',base_url('login/create_new_account/ajax/_ajax'));		
		$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
		$this->setVar('LINK_LOGIN',base_url('login/'));
		$this->publishView();
		$this->konten=$this->output;
	}
	public function create_new_account(){
		header('Content-type:Application/json');
		$this->loadmodel('Login_model','lm');
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method!='POST'){
			die(json_encode('method not allowed'));
		}		
		$u 	= check_var('post','username');
		$p	= check_var('post','password');
		$c	= check_var('post','confirm_password');
		$e	= check_var('post','email');
		if($u=='' || $p=='' || $e=='' || $p!=$c){
			
		}
		else{
			$data = array('username'=>$u,'password'=>$p,'email'=>$e);
			
			if($this->lm->create_account($data)==true){
				$_SESSION['login']=true;
				$_SESSION['username']=$u;
				$o=array('status'=>1,'message'=>'Success');
			}
			else{
				$o = array('status'=>0,'message'=>'Error insert data!','data'=>$data);
			}
		}
		echo json_encode($o);
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
			$_SESSION['username']=$u;
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