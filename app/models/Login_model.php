<?php 
	class Login_model extends MD_model{
		public function __construct(){
			$this->connect_sqlite();
		}

		public function check_login($u,$p){
			$u=str_replace("'","",$u);
			$sql = "SELECT login_id,username,password,role_id FROM logins WHERE username='$u' LIMIT 1";
			$ret=$this->dbsqlite->query($sql);
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){	
				if (md5($p)==$r['password']){
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
		}
	}