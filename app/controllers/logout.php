<?php
class logout extends MD_app{	
	public function __construct(){
		
	}
	public function index(){
		$_SESSION['login']=false;
		$this->SetTemplate($this->getview("logout","logout"));
		$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
		$this->publishView();
		$this->konten=$this->output;			
	}
	
}
?>