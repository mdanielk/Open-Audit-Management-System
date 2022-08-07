<?php
class home extends MD_app{	
	public function __construct(){

	}
	public function index(){
			$this->SetTemplate($this->getview("home","home"));
			$this->setVar('__TITLE__','JUDUL');
			$this->setVar('HOMEPAGE',__HOMEPAGE__);
			$this->publishView();
			$this->konten=$this->output;			
	}

}
?>