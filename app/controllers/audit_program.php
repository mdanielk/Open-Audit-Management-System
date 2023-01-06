<?php
class Audit_program extends MD_app{	
	public function __construct(){
		$this->login_required();
	}
	public function index(){
			$this->loadhelper('topnav');
			$this->SetTemplate($this->getview("audit_program","home"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->setVar('LIST_URL',base_url('audit_program/audit_program_list/ajax/_ajax'));
			$this->setVar('URL_CREATE_NEW',base_url('audit_program/create'));
			$this->setVar('LINK_DETAIL',base_url('audit_program/program/id/'));
			$this->setVar('TOPNAV',$this->topnav->show());
			$this->publishView();
			$this->konten=$this->output;			
	}
	public function audit_program_list(){
			header('Content-type:Application/json');
			$this->loadmodel('Auditprogram_model','apm');
			$method = $_SERVER['REQUEST_METHOD'];
			if ($method!='GET'){
				die(json_encode('Method not allowed!'));
			}
			else{
				$data['t']	= check_var('get','total');
				$data['s']	= check_var('get','start');
				$data['o']	= check_var('get','sort_by');
				$data['r']	= check_var('get','sort_type');
				$data['c']	= $_SESSION['login_id'];
				$ap = array('status'=>1,'content'=>$this->apm->get_audit_program($data));
				echo json_encode($ap);
			}
	}
	public function create(){
			$this->loadhelper('topnav');
			$this->SetTemplate($this->getview("audit_program","create"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->setVar('FORM_URL',base_url('audit_program/submit_new_program/ajax/_ajax'));
			$this->setVar('SUCCESS_REDIRECT',base_url('audit_program/'));
			$this->setVar('UNIT_OPTION',$this->unit_option());
			$this->setVar('TOPNAV',$this->topnav->show());
			$this->publishView();
			$this->konten=$this->output;			
	}
	public function program(){
			$this->loadhelper('topnav');
			$this->SetTemplate($this->getview("audit_program","program"));
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->setVar('LINK_DETAIL',base_url('audit_program/program/id/'));
			$this->setVar('TOPNAV',$this->topnav->show());
			$this->publishView();
			$this->konten=$this->output;		
	}
	public function submit_new_program(){
		header('Content-type:Application/json');
		$this->loadmodel('Auditprogram_model','apm');
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method!='POST'){
			die(json_encode('Method not allowed!'));
		}
		else{
			//$u		= check_var('post','unit');
			$apt 	= check_var('post','audit_program_title');
			$d	 	= check_var('post','audit_program_description');
			$at 	= check_var('post','audit_type');
			$vi 	= check_var('post','visibility');
			$ca 	= date('Y-m-d H:i:s');
			$ci 	= $_SESSION['login_id'];
			if($apt=='' || $at=='' || $vi==''){
				$o = array('status'=>0,'message'=>'Error, check mandatory field!');
			}
			else{
				$data = array('apt'=>$apt,'d'=>$d,'at'=>$at,'ca'=>$ca,'ci'=>$ci,'vi'=>$vi);
				
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
	public function processes(){
			$pid = check_var('get','pid');
			$this->loadmodel('Auditprogram_model','apm');
			$audit_program = $this->apm->get_audit_program_detail($pid);
			$this->loadhelper('topnav');
			$this->SetTemplate($this->getview("audit_program","processes"));
			$this->setVar('TOPNAV',$this->topnav->show());
			$this->setVar('PID',$pid);
			$this->setVar('AUDIT_PROGRAM_TITLE',$audit_program['audit_program_title']);
			$this->setVar('AUDIT_PROGRAM_DESCRIPTION',$audit_program['description']);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->setVar('NEW_PROCESS_MODAL',$this->new_process_modal($pid));
			$this->setVar('URL_EDIT_PROCESS',__HOMEPAGE__.'/audit_program/edit_process_modal');
			$this->setVar('URL_DELETE_PROCESS',__HOMEPAGE__.'/audit_program/delete_process_modal');
			$this->setVar('URL_SUBMIT_EDIT_PROCESS', __HOMEPAGE__.'/audit_program/save_edit_process/ajax/_ajax');
			$this->setVar('URL_SUBMIT_DELETE_PROCESS', __HOMEPAGE__.'/audit_program/do_delete_process/ajax/_ajax');
			$this->setVar('LIST_URL',base_url('audit_program/processes_list/ajax/_ajax'));
			$this->publishView();
			$this->konten=$this->output;		
	}
	public function edit_process_modal(){
		$pid = check_var('get','id');
		$this->loadmodel('Auditprogram_model','apm');
		$data = $this->apm->get_audit_process($pid);
		if($data == null){
			echo 'Record not found';
		}
		else if($data['creator_id']!=$_SESSION['login_id']){
			echo '<div class="row"><div class="col s12 center"><h5><i class="material-icons"> sentiment_very_dissatisfied</i><br>Sorry, You have no sufficient right to do this action</h5></div></div>';
		}
		else{
			$url = __HOMEPAGE__.'/audit_program/save_edit_process/ajax/_ajax';
			$status_1 = $data['status']=='active'?'selected="selected"':'';
			$status_2 = $data['status']=='disabled'?'selected="selected"':'';
			echo '
			<script type="text/javascript">$("select").formSelect();</script>
			<form onSubmit="submitEditProcess(); return false;" id="form-edit-process" method="post" action="#">
				<div class="modal-content">
					<div class="row">
						<div class="input-field s12"><strong>Update Business Process<strong></div>
					</div>
					<div class="row">
						<div class="input-field col s12 m2 l2">
							<input type="text" name="code" id="code" autocomplete="off" value="'.$data['code'].'">
							<label for="code">Process Code</label>
						</div>
						<div class="input-field col s12 m10 l10">
							<input type="text" name="process_name" id="process_name" value="'.$data['process_name'].'">
							<label for="process_name">Process Name</label>
						</div>		
					</div>
					<div class="row">
						<div class="input-field col s12 m12 l12">
							<textarea rows=4 class="materialize-textarea" name="description" id="description">'.$data['description'].'</textarea>
							<label for="description">Process description</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m6 l6">
							<input type="text" name="reference" id="reference" value="'.$data['reference'].'">
							<label for="reference">Reference</label>
						</div>					
						<div class="input-field col s6 m4 l4">
							<select name="status" id="status">
								<option value="active" '.$status_1.'>Active</option>
								<option value="disabled" '.$status_2.'>Disabled</option>
							</select>
							<label for="status">Status</label>
						</div>
					</div>
				</div>
				<input type="hidden" name="process_id" id="process_id" value="'.$pid.'">
				<div class="modal-footer"> 
				<button type="button" onClick="closeModal()" class="left waves-effect teal waves-teal white-text btn">Cancel</button>
				<button type="submit" class="waves-effect green waves-green white-text btn">Submit <i class="material-icons">send</i></button></div>
				</form>';
		}
	}
	public function delete_process_modal(){
		$pid = check_var('get','id');
		$this->loadmodel('Auditprogram_model','apm');
		$data = $this->apm->get_audit_process($pid);
		if($data == null){
			echo 'Record not found';
		}
		else if($data['creator_id']!=$_SESSION['login_id']){
			echo '<div class="row"><div class="col s12 center"><h5><i class="material-icons"> sentiment_very_dissatisfied</i><br>Sorry, You have no sufficient right to do this action</h5></div></div>';
		}
		else{
			echo '<div class="row">
			<div class="col s12 center"><h5><i class="material-icons"> delete_sweep</i><br>Delete business process ?</h5></div>
			<div class="col s12 center">Are you sure that you want to delete process <b>"'.$data['process_name'].'"</b> ?
			<br> You can\'t undo this action.</div>
			</div>
			<div class="row">
				<div class="col s2"></div>
				<div class="col s8">
					 <blockquote  class="red lighten-4">
					 &nbsp;<br>
					 <div class="valign-wrapper red-text accent-4"><i class="material-icons">warning</i><b>Warning</b></div>
					  By deleting this process included  risks, controls, and testing steps will also be deleted.<br>
					  &nbsp;
					</blockquote>
				</div>
				<div class="col s2"></div>
			</div>
			<div class="row">
			<div class="col s6"><a href="#" onClick="closeModal()" class="btn btn-waves-effect teal lighten-2 right">Cancel</a></div>
			<div class="col s6"><a href="#" onClick="submitDeleteProcess('.$pid.')" class="btn btn-waves-effect red darken-1 left">Delete process <i class="material-icons">delete</i></a></div>
			</div>
			';
		}
	}
	public function delete_reference_modal(){
		$rid = check_var('get','id');
		$this->loadmodel('Auditprogram_model','apm');
		$data = $this->apm->get_audit_reference($rid);
		if($data == null){
			echo 'Record not found';
		}
		else if($data['creator_id']!=$_SESSION['login_id']){
			echo '<div class="row"><div class="col s12 center"><h5><i class="material-icons"> sentiment_very_dissatisfied</i><br>Sorry, You have no sufficient right to do this action</h5></div></div>';
		}
		else{
			echo '<div class="row">
			<div class="col s12 center"><h5><i class="material-icons"> delete_sweep</i><br>Delete reference ?</h5></div>
			<div class="col s12 center">Are you sure that you want to delete reference <b>"'.$data['reference_name'].' ( Code: '.$data['reference_code'].')"</b> ?
			<br> You can\'t undo this action.</div>
			</div>
			<div class="row">
				<div class="col s2"></div>
				<div class="col s8">
					 <blockquote  class="red lighten-4">
					 &nbsp;<br>
					 <div class="valign-wrapper red-text accent-4"><i class="material-icons">warning</i><b>Warning</b></div>
					  By deleting this reference included  files will also be deleted.<br>
					  &nbsp;
					</blockquote>
				</div>
				<div class="col s2"></div>
			</div>
			<div class="row">
			<div class="col s6"><a href="#" onClick="closeModal()" class="btn btn-waves-effect teal lighten-2 right">Cancel</a></div>
			<div class="col s6"><a href="#" onClick="submitDeleteReference('.$rid.')" class="btn btn-waves-effect red darken-1 left">Delete references <i class="material-icons">delete</i></a></div>
			</div>
			';
		}
	}
	private function new_process_modal($pid){
		$url = __HOMEPAGE__.'/audit_program/save_new_process/ajax/_ajax';
		return '<form id="form-new-process" method="post" action="'.$url.'">
				<div class="modal-content">
					<div class="row">
						<div class="input-field s12"><strong>Create New Business Process<strong></div>
					</div>
					<div class="row">
						<div class="input-field col s12 m2 l2">
							<input type="text" name="code" id="code" autocomplete="off">
							<label for="code">Process Code</label>
						</div>
						<div class="input-field col s12 m10 l10">
							<input type="text" name="process_name" id="process_name">
							<label for="process_name">Process Name</label>
						</div>		
					</div>
					<div class="row">
						<div class="input-field col s12 m12 l12">
							<textarea rows=4 class="materialize-textarea" name="description" id="description"></textarea>
							<label for="description">Process description</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12 m6 l6">
							<input type="text" name="reference" id="reference">
							<label for="reference">Reference</label>
						</div>					
						<div class="input-field col s6 m4 l4">
							<select name="status" id="status">
								<option value="active">Active</option>
								<option value="disabled">Disabled</option>
							</select>
							<label for="status">Status</label>
						</div>
					</div>
				</div>
				<input type="hidden" name="program_id" id="program_id" value="'.$pid.'">
				<div class="modal-footer"> <button type="submit" class="waves-effect green waves-green btn-flat">Submit</button></div>
				</form>';
	}
	private function new_reference_modal($pid){
		$url = __HOMEPAGE__.'/audit_program/save_new_reference/ajax/_ajax';
		return '<form id="form-new-references" method="post" action="'.$url.'">
				<div class="modal-content">
					<div class="row">
						<div class="input-field s12"><strong>Create New Reference for Audit Program<strong></div>
					</div>
					<div class="row">
						<div class="input-field col s12 m2 l2">
							<input type="text" name="reference_code" id="reference_code" autocomplete="off">
							<label for="reference_code">References Code</label>
						</div>
						<div class="input-field col s12 m10 l10">
							<input type="text" name="reference_name" id="reference_name">
							<label for="reference_name">Reference Name</label>
						</div>		
					</div>
					<div class="row">
						<div class="input-field col s12 m12 l12">
							<textarea rows=4 class="materialize-textarea" name="description" id="description"></textarea>
							<label for="description">Reference description</label>
						</div>
					</div>
					<div id="uploadForm">
						<div class="row">
							<div class="input-field file-field col s12 m6 l6">
								<div class="btn">Select file
									<input type="file" name="reference_file[]" id="reference_file" multiple="multiple">
								</div>							
								<div class="file-path-wrapper">
									<input class="file-path validate" type="text" name="filename[]">
								</div>		
							</div>
							<a href="#" onClick="addUploadForm()" class="btn btn-add"><i class="material-icons">add</i></a>
						</div>
					</div>
				</div>
				<input type="hidden" name="program_id" id="program_id" value="'.$pid.'">
				<div class="modal-footer"> <button type="submit" class="waves-effect green waves-green btn-flat">Submit</button></div>
				</form>';
	}
	public function save_new_process(){
		header('Content-type:Application/json');
		$this->loadmodel('Auditprogram_model','apm');	
		
		$post = array(
					'process_name'	=>check_var('post','process_name'),
					'description'	=>check_var('post','description'),
					'status'		=>check_var('post','status'),
					'program_id'	=>check_var('post','program_id'),
					'code'			=>check_var('post','code'),
					'reference'		=>check_var('post','reference'),
					'deleted'		=>'0',
					'created_at'	=>date("Y-m-d H:i:s"),
					'creator_id'	=>$_SESSION['login_id'],
				);
		$array_val = array_values($post);
		if(in_array("", $array_val)){
				$data = array('status'=>0,'message'=>'Error: All field required');
		}
		else
		{
			if($this->apm->insert_new_process($post)==true)
				$data = array('status'=>1,'message'=>'Data updated succesfully');
			else
				$data = array('status'=>0,'message'=>'Error: Database error!');
		}
		echo json_encode($data);
	}
	public function save_new_reference(){
		header('Content-type:Application/json');
		$this->loadmodel('Auditprogram_model','apm');
		$data= array();
		$post = array(
					'reference_code'	=>check_var('post','reference_code'),
					'reference_name'	=>check_var('post','reference_name'),
					'description'		=>check_var('post','description'),
					'program_id'		=>check_var('post','program_id'),
					'deleted'			=>'0',
					'created_at'		=>date("Y-m-d H:i:s"),
					'creator_id'		=>$_SESSION['login_id'],
				);
				
		$array_val = array_values($post);
		if(in_array("", $array_val)){
				$data = array('status'=>0,'message'=>'Error: All field required');
		}
		else
		{
			//filename value
			if($_POST['filename']!=null){
				$filename = implode(',',$_POST['filename']);
				$post['filename']=$filename;
			}
			else{
				$post['filename']='empty';
			}
			//--end of filename
			if($this->apm->insert_new_reference($post)==true){
				$data = array('status'=>1,'message'=>'Data updated succesfully');
				$n = count($_FILES['reference_file']['name']);
				
				for ($i=0;$i<$n;$i++){
					$file = $_FILES['reference_file']['tmp_name'][$i];
					$name = $_FILES['reference_file']['name'][$i];
					$name = str_replace(' ','_',strtolower($name));
					$name = 'reference_'.$name;
					$upload_status = $this->upload_reference($file,$name);
					$data_reference = array('reference_id'=>1,'filename'=>$name,'description'=>'');
					$this->apm->insert_reference_file($data_reference);
				}
				$data = array('status'=>1,'message'=>'Data saved succesfully!','upload_status'=>$upload_status);
			}
			else
				$data = array('status'=>0,'message'=>'Error: Database error!','upload_status'=>$upload_status);
		}
		echo json_encode($data);
	}
	private function upload_reference($file,$name){
		$target_dir = "files/";
		$target_file = $target_dir . basename($name);
		$uploadFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$uploadOk = 1;
		if (move_uploaded_file($file, $target_file)) {
			$message = "The files has been uploaded.";
			}
		else {
			$message = "Sorry, there was an error uploading your file.";
			}
		return $message;
	}
	public function save_edit_process(){
		header('Content-type:Application/json');
		$this->loadmodel('Auditprogram_model','apm');	
		$process_id	= check_var('post','process_id');
		$post = array(
					'process_name'	=>check_var('post','process_name'),
					'description'	=>check_var('post','description'),
					'status'		=>check_var('post','status'),					
					'code'			=>check_var('post','code'),
					'reference'		=>check_var('post','reference'),
					'deleted'		=>'0',
					'created_at'	=>date("Y-m-d H:i:s"),
					'creator_id'	=>$_SESSION['login_id'],
				);
		$array_val = array_values($post);
		if(in_array("", $array_val)){
				$data = array('status'=>0,'message'=>'Error: All field required');
		}
		else
		{
			if($this->apm->update_process($post,$process_id)==true)
				$data = array('status'=>1,'message'=>'Data updated succesfully');
			else
				$data = array('status'=>0,'message'=>'Error: Database error!');
		}
		echo json_encode($data);
	}
	public function do_delete_process(){
		header('Content-type:Application/json');
		$this->loadmodel('Auditprogram_model','apm');	
		$process_id	= check_var('get','id');
		
		if($process_id==null){
				$data = array('status'=>0,'message'=>'Error: unknown process id');
		}
		else
		{
			if($this->apm->delete_process($process_id)==true)
				$data = array('status'=>1,'message'=>'Data deleted succesfully');
			else
				$data = array('status'=>0,'message'=>'Error: Database error!');
		}
		echo json_encode($data);
	}
	public function do_delete_reference(){
		header('Content-type:Application/json');
		$this->loadmodel('Auditprogram_model','apm');	
		$reference_id	= check_var('get','id');
		
		if($reference_id==null){
				$data = array('status'=>0,'message'=>'Error: unknown reference id');
		}
		else
		{
			if($this->apm->delete_reference($reference_id)==true)
				$data = array('status'=>1,'message'=>'Data deleted succesfully');
			else
				$data = array('status'=>0,'message'=>'Error: Database error!');
		}
		echo json_encode($data);
	}
	public function processes_list(){
			header('Content-type:Application/json');
			$this->loadmodel('Auditprogram_model','apm');
			$method = $_SERVER['REQUEST_METHOD'];
			if ($method!='POST'){
				die(json_encode('Method not allowed!'));
			}
			else{
				$data['pid']	= check_var('post','pid');
				$data['c']		= $_SESSION['login_id'];
				$ap = array('status'=>1,'content'=>$this->apm->get_audit_processes($data));
				echo json_encode($ap);
			}
	}
	public function risks(){
		$pid = check_var('get','pid');
		$this->loadmodel('Auditprogram_model','apm');
		$audit_program = $this->apm->get_audit_program_detail($pid);
		$this->loadhelper('topnav');
		$this->SetTemplate($this->getview("audit_program","processes"));
		$this->setVar('TOPNAV',$this->topnav->show());
		$this->setVar('PID',$pid);
		$this->setVar('AUDIT_PROGRAM_TITLE',$audit_program['audit_program_title']);
		$this->setVar('AUDIT_PROGRAM_DESCRIPTION',$audit_program['description']);
		$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
		$this->setVar('NEW_PROCESS_MODAL',$this->new_process_modal($pid));
		$this->setVar('URL_EDIT_PROCESS',__HOMEPAGE__.'/audit_program/edit_process_modal');
		$this->setVar('URL_DELETE_PROCESS',__HOMEPAGE__.'/audit_program/delete_process_modal');
		$this->setVar('URL_SUBMIT_EDIT_PROCESS', __HOMEPAGE__.'/audit_program/save_edit_process/ajax/_ajax');
		$this->setVar('URL_SUBMIT_DELETE_PROCESS', __HOMEPAGE__.'/audit_program/do_delete_process/ajax/_ajax');
		$this->setVar('LIST_URL',base_url('audit_program/processes_list/ajax/_ajax'));
		$this->publishView();
		$this->konten=$this->output;		
	}
	public function risks_list(){
		
	}
	public function controls_list(){
		
	}
	public function steps_lists(){
		
	}
	public function references(){
		$pid = check_var('get','pid');
		$this->loadmodel('Auditprogram_model','apm');
		$audit_program = $this->apm->get_audit_program_detail($pid);
		$this->loadhelper('topnav');
		$this->SetTemplate($this->getview("audit_program","references"));
		$this->setVar('TOPNAV',$this->topnav->show());
		$this->setVar('PID',$pid);
		$this->setVar('AUDIT_PROGRAM_TITLE',$audit_program['audit_program_title']);
		$this->setVar('AUDIT_PROGRAM_DESCRIPTION',$audit_program['description']);
		$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
		$this->setVar('NEW_REFERENCE_MODAL',$this->new_reference_modal($pid));
		$this->setVar('URL_EDIT_REFERENCE',__HOMEPAGE__.'/audit_program/edit_reference_modal');
		$this->setVar('URL_DELETE_REFERENCE',__HOMEPAGE__.'/audit_program/delete_reference_modal');
		$this->setVar('URL_SUBMIT_EDIT_REFERENCE', __HOMEPAGE__.'/audit_program/save_edit_reference/ajax/_ajax');
		$this->setVar('URL_SUBMIT_DELETE_REFERENCE', __HOMEPAGE__.'/audit_program/do_delete_reference/ajax/_ajax');
		$this->setVar('LIST_URL',base_url('audit_program/references_lists/ajax/_ajax'));
		$this->publishView();
		$this->konten=$this->output;		
	}
	public function references_lists(){
			header('Content-type:Application/json');
			$this->loadmodel('Auditprogram_model','apm');
			$method = $_SERVER['REQUEST_METHOD'];
			if ($method!='POST'){
				die(json_encode('Method not allowed!'));
			}
			else{
				$data['pid']	= check_var('post','pid');
				$data['c']		= $_SESSION['login_id'];
				$ap = array('status'=>1,'content'=>$this->apm->get_audit_references($data));
				echo json_encode($ap);
			}
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