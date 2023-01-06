<?php 
	class Auditproject_model extends MD_model{
		public function __construct(){
			$this->connect_sqlite();
		}
		public function get_audit_project($data){
			$u	= $data['u']; //unit
			$t	= $data['t']; //total perpage
			$s	= $data['s']; //start 
			$o	= $data['o']; //sort by
			$r	= $data['o']; //sort type
			$c	= $data['c']; //creator id
			$sql = "SELECT audit_programs.id,unit_id,audit_program_title, description,audit_type,created_at,unit_name 
					FROM audit_programs
					LEFT JOIN units on audit_programs.unit_id=units.id
					WHERE creator_id=$c
					LIMIT 5";
			$ret=$this->dbsqlite->query($sql);
			$output = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				$arr = array('id'=>$r['id'],
							 'unit_id'=>$r['unit_id'],
							 'audit_program_title'=>$r['audit_program_title'],
							 'description'=>$r['description'],
							 'unit_name'=>$r['unit_name'],
							 'audit_type'=>$r['audit_type'],
							 'created_at'=>$r['created_at']							 
							 );
				array_push($output,$arr);
			}
			return $output;
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
		public function create_project($data){
			$u 		= $data['u'];
			$apt 	= $data['apt'];
			$d	 	= $data['d'];
			$at 	= $data['at'];
			$ca 	= $data['ca'];
			$ci 	= $data['ci'];
			
			$sql = "INSERT INTO audit_programs ('unit_id','audit_program_title','description','audit_type','created_at','creator_id')
					VALUES ('$u','$apt','$d','$at','$ca','$ci')";
			if ($ret=$this->dbsqlite->query($sql)){
				return TRUE;
			}
			else{
				return FALSE;
			}	
		}
	}