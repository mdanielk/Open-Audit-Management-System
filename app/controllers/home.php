<?php
class home extends MD_app{	
	public function __construct(){

	}
	public function index(){
			$this->SetTemplate($this->getview("home","home"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->publishView();
			$this->konten=$this->output;			
	}

}
?>