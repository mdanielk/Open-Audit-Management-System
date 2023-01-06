<?php
class Audit_project extends MD_app{	
	public function __construct(){
		$this->login_required();
	}
	public function index(){
			$this->loadhelper('topnav');
			$this->SetTemplate($this->getview("audit_project","home"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->setVar('TOPNAV',$this->topnav->show());
			$this->publishView();
			$this->konten=$this->output;			
	}
	public function create(){
			$this->loadhelper('topnav');
			$this->SetTemplate($this->getview("audit_project","create"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->setVar('FORM_URL',base_url('audit_project/submit_new_project/ajax/_ajax'));
			$this->setVar('SUCCESS_REDIRECT',base_url('audit_project/'));
			$this->setVar('UNIT_OPTION',$this->unit_option());
			$this->setVar('YEAR_OPTION',$this->year_option());
			$this->setVar('TOPNAV',$this->topnav->show());
			$this->publishView();
			$this->konten=$this->output;		
	}
	public function submit_new_project(){
		header('Content-type:Application/json');
		$this->loadmodel('Auditproject_model','apm');
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method!='POST'){
			die(json_encode('Method not allowed!'));
		}
		else{
			$u		= check_var('post','unit');
			$apt 	= check_var('post','audit_program_title');
			$d	 	= check_var('post','audit_program_description');
			$at 	= check_var('post','audit_type');
			$ca 	= date('Y-m-d H:i:s');
			$ci 	= $_SESSION['login_id'];
			if($u=='' || $apt=='' || $at==''){
				$o = array('status'=>0,'message'=>'Error, check mandatory field!');
			}
			else{
				$data = array('u'=>$u,'apt'=>$apt,'d'=>$d,'at'=>$at,'ca'=>$ca,'ci'=>$ci);
				
				if($this->apm->create_program($data)==true){
					$o=array('status'=>1,'message'=>'Success');
				}
				else{
					$o = array('status'=>0,'message'=>'Error insert data!','data'=>$data);
				}
			}			
		}	
		echo json_encode($o);
	}
	private function year_option(){
		$current_year = date("Y");
		$next_year = date("Y");
		$option = '<option value="'.$current_year.'">'.$current_year.'</option>
					<option value="'.$next_year.'">'.$next_year	.'</option>';
		return $option;
	}
	private function unit_option(){
		$data = '';
		$this->loadmodel('Auditprogram_model','apm');		
		$array = $this->apm->get_unit();
		foreach ($array as $r){
			$data .= '<option value = "'.$r['id'].'">'.$r['unit_name'].'</option>';
		}
		return $data;
	}	
}
?>