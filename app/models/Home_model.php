<?php 
	class Home_model extends MD_model{
		public function __construct(){
			$this->connect_sqlite();
		}
		public function get_profile(){
			$login_id = $_SESSION['login_id'];
			$sql = "SELECT fullname,title,avatar FROM persons WHERE person_id=$login_id";
			$ret=$this->dbsqlite->query($sql);
			$arr = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				$arr = array('fullname'=>$r['fullname'],'title'=>$r['title']);
			}
			return $arr;
		}
	}