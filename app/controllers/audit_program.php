<?php
class Audit_program extends MD_app{	
	public function __construct(){
		$this->login_required();
	}
	public function index(){
			$this->SetTemplate($this->getview("audit_program","home"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->publishView();
			$this->konten=$this->output;			
	}
	public function create(){
			$this->SetTemplate($this->getview("audit_program","create"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->publishView();
			$this->konten=$this->output;			
	}

}
?>