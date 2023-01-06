<?php
require_once 'app/libraries/topmenu/topmenu.php';
class home extends MD_app{	
	public function __construct(){
		$this->login_required();
	}
	public function index(){
			$topmenu = new topmenu;			
			$this->loadmodel('Home_model','hm');
			$profil = $this->hm->get_profile();
			$this->SetTemplate($this->getview("home","home"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->setVar('TOPMENU',$topmenu->show_topmenu());
			$this->setVar('LIST_URL',base_url('audit_program/audit_program_list/ajax/_ajax'));
			$this->setVar('LINK_AUDIT_PROGRAM',base_url('audit_program/'));
			$this->setVar('USERNAME',$_SESSION['username']);
			$this->setVar('FULLNAME',$profil['fullname']);
			$this->setVar('JOBTITLE',$profil['title']);
			$this->setVar('PHOTO',"");
			$this->publishView();
			$this->konten=$this->output;			
	}

}
?>