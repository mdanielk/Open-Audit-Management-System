<?php
class login extends MD_app{	
	public function __construct(){
		
	}
	public function index(){
			$this->SetTemplate($this->getview("login","login"));
			$this->setVar('HOMEPAGE',__HOMEPAGE__);
			$this->publishView();
			$this->konten=$this->output;			
	}

}
?>