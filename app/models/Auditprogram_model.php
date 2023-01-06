<?php 
	class Auditprogram_model extends MD_model{
		public function __construct(){
			$this->connect_sqlite();
		}
		public function get_audit_program($data){
			$t	= $data['t']; //total perpage
			$s	= $data['s']; //start 
			$o	= $data['o']; //sort by
			$r	= $data['o']; //sort type
			$c	= $data['c']; //creator id
			$sql = "SELECT audit_programs.id,audit_program_title, description,audit_type,created_at,visibility  
					FROM audit_programs
					WHERE (creator_id=$c or visibility ='public') and deleted = 0
					LIMIT 5";
			$ret=$this->dbsqlite->query($sql);
			$output = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				$arr = array('id'=>$r['id'],
							 'audit_program_title'=>$r['audit_program_title'],
							 'description'=>$r['description'],
							 'audit_type'=>$r['audit_type'],
							 'visibility'=>$r['visibility'],
							 'created_at'=>$r['created_at'],
							 'process_count'=>$this->get_process_count($r['id']),							 
							 'reference_count'=>$this->get_reference_count($r['id']),							 
							 );
				array_push($output,$arr);
			}
			return $output;
		}
		public function get_audit_program_detail($pid){
			$sql = "SELECT audit_programs.id,audit_program_title, description,audit_type,created_at 
					FROM audit_programs
					WHERE id='$pid'
					LIMIT 1";
			$ret=$this->dbsqlite->query($sql);
			$output = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				$output['audit_program_title']=$r['audit_program_title'];
				$output['description']=$r['description'];
			}
			return $output;
		}
		public function get_audit_processes($data){
			$pid = $data['pid'];
			$sql = "SELECT processes.id,processes.process_name,processes.description,processes.created_at,processes.creator_id,logins.username,processes.code,processes.reference
				FROM processes
				LEFT JOIN logins ON processes.creator_id = logins.login_id
				WHERE program_id='$pid' AND deleted = 0
				ORDER BY id DESC";
			$ret=$this->dbsqlite->query($sql);
			$output = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				$arr = array('id'=>$r['id'],
							 'code'=>$r['code'],
							 'reference'=>$r['reference'],
							 'process_name'=>$r['process_name'],
							 'description'=>$r['description'],
							 'username'=>$r['username'],
							 'creator_id'=>$r['creator_id'],
							 'created_at'=>$r['created_at']							 
							 );
				array_push($output,$arr);
			}
			return $output;
		}
		private function get_process_count($program_id){
			$sql="SELECT COUNT(id) as process_count FROM processes WHERE program_id='$program_id' AND deleted = 0";
			$ret = $this->dbsqlite->query($sql);
			$r=$ret->fetchArray(SQLITE3_ASSOC);
			$process_count = $r['process_count'];
			return $process_count;
		}
		private function get_reference_count($program_id){
			$sql="SELECT COUNT(id) as reference_count FROM 'references' WHERE program_id='$program_id' AND deleted = 0";
			$ret = $this->dbsqlite->query($sql);
			$r=$ret->fetchArray(SQLITE3_ASSOC);
			$reference_count = $r['reference_count'];
			return $reference_count;
		}
		public function get_audit_process($process_id){
			$sql = "SELECT processes.id,processes.process_name,processes.description,processes.status,processes.created_at,processes.creator_id,logins.username,processes.code,processes.reference
				FROM processes
				LEFT JOIN logins ON processes.creator_id = logins.login_id
				WHERE processes.id='$process_id'
				LIMIT 1";
			$ret=$this->dbsqlite->query($sql);
			
			$r = $ret->fetchArray(SQLITE3_ASSOC);
			$arr = array('id'=>$r['id'],
						 'code'=>$r['code'],
						 'reference'=>$r['reference'],
						 'status'=>$r['status'],
						 'process_name'=>$r['process_name'],
						 'description'=>$r['description'],
						 'username'=>$r['username'],
						 'creator_id'=>$r['creator_id'],
						 'created_at'=>$r['created_at']							 
						 );
			
			return $arr;
		}
		public function get_audit_reference($reference_id){
			$sql = "SELECT 	a.id,
							a.reference_name,
							a.reference_code,
							a.description,
							a.filename,
							a.creator_id,
							a.created_at
				FROM 'references' as a
				LEFT JOIN logins as b ON a.creator_id = b.login_id
				WHERE a.id='$reference_id'
				LIMIT 1";
			$ret=$this->dbsqlite->query($sql);
			
			$r = $ret->fetchArray(SQLITE3_ASSOC);
			$arr = array('id'=>$r['id'],
						 'reference_name'=>$r['reference_name'],
						 'reference_code'=>$r['reference_code'],
						 'description'=>$r['description'],
						 'filename'=>$r['filename'],
						 'creator_id'=>$r['creator_id'],
						 'created_at'=>$r['created_at']							 
						 );
			
			return $arr;
		}
		public function get_unit(){
			$sql = "SELECT id,unit_name,unit_code FROM units WHERE is_active=1 ORDER BY unit_name ASC";
			$ret=$this->dbsqlite->query($sql);
			$data = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				$arr = array('id'=>$r['id'],'unit_name'=>$r['unit_name']);
				array_push($data,$arr);
			}
			return $data;
		}
		public function create_program($data){
			$apt 	= $data['apt'];
			$d	 	= $data['d'];
			$at 	= $data['at'];
			$ca 	= $data['ca'];
			$ci 	= $data['ci'];
			$vi 	= $data['vi'];
			
			$sql = "INSERT INTO audit_programs ('audit_program_title','description','audit_type','visibility','created_at','creator_id')
					VALUES ('$apt','$d','$at','$vi','$ca','$ci')";
			if ($ret=$this->dbsqlite->query($sql)){
				return TRUE;
			}
			else{
				return FALSE;
			}	
		}
		public function insert_new_process($data){
			if(!empty($data)){
				$cols 	= implode(', ',array_keys($data));
				$values = json_encode(array_values($data));
				$values=str_replace("[","",$values);
				$values=str_replace("]","",$values);
				
				$sql = "INSERT INTO processes ($cols)
					VALUES (".$values.")";
				
					if ($ret=$this->dbsqlite->query($sql)){
						return TRUE;
					}
					else{
						return FALSE;
					}	
			}
		}
		public function insert_new_reference($data){
			if(!empty($data)){
				$cols 	= implode(', ',array_keys($data));
				$values = json_encode(array_values($data));
				$values=str_replace("[","",$values);
				$values=str_replace("]","",$values);
				
				$sql = "INSERT INTO 'references' ($cols)
					VALUES (".$values.")";
				
					if ($ret=$this->dbsqlite->query($sql)){
						return TRUE;
					}
					else{
						return FALSE;
					}	
			}
		}
		public function insert_reference_file($data){
			if(!empty($data)){
				$cols 	= implode(', ',array_keys($data));
				$values = json_encode(array_values($data));
				$values=str_replace("[","",$values);
				$values=str_replace("]","",$values);
				
				$sql = "INSERT INTO 'reference_files' ($cols)
					VALUES (".$values.")";
				
					if ($ret=$this->dbsqlite->query($sql)){
						return TRUE;
					}
					else{
						return FALSE;
					}	
			}
		}
		public function update_process($data,$id){
			if(!empty($data)){								
				$sql = "UPDATE processes SET ";
						foreach($data as $key=>$value) {
						   $sql .= $key . " = '" . $value . "', "; 
						}
						$sql = trim($sql, ' '); 
						$sql = trim($sql, ',');
						$sql .= " WHERE id = $id";
				
				if ($ret=$this->dbsqlite->query($sql)){
						return TRUE;
					}
				else{
						return FALSE;
					}
			}
			else{
				return FALSE;
			}

		}
		public function delete_process($id){
			$sql = "UPDATE processes SET deleted = 1 WHERE id ='$id' and creator_id='".$_SESSION['login_id']."'";
			if ($ret=$this->dbsqlite->query($sql)){
					return TRUE;
				}
			else{
					return FALSE;
				}
		}
		public function delete_reference($id){
			$sql = "UPDATE 'references' SET deleted = 1 WHERE id ='$id' and creator_id='".$_SESSION['login_id']."'";
			if ($ret=$this->dbsqlite->query($sql)){
					return TRUE;
				}
			else{
					return FALSE;
				}
		}
		public function get_audit_references($data){
			$pid = $data['pid'];
			$sql = "SELECT a.id,a.reference_name,a.reference_code,a.created_at,a.creator_id,a.description,a.website,b.username
					FROM 'references' as a
					LEFT JOIN logins as b ON a.creator_id = b.login_id
					WHERE a.program_id='$pid' AND a.deleted = 0
					ORDER BY a.id DESC";
			$ret=$this->dbsqlite->query($sql);
			$output = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				$arr = array('id'=>$r['id'],
							 'reference_code'=>$r['reference_code'],
							 'reference_name'=>html_entity_decode($r['reference_name']),
							 'description'=>$r['description'],
							 'website'=>$r['website'],
							 'creator_id'=>$r['creator_id'],
							 'created_at'=>$r['created_at'],							 
							 'username'=>$r['username']							 
							 );
				array_push($output,$arr);
			}
			return $output;
		}
	}