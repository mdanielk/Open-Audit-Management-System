<?php 
	class Login_model extends MD_model{
		public $login_id;
		public function __construct(){
			$this->connect_sqlite();
		}

		public function check_login($u,$p){
			$u=str_replace("'","",$u);
			$sql = "SELECT login_id,username,password,role_id FROM logins WHERE username='$u' LIMIT 1";
			$ret=$this->dbsqlite->query($sql);
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){	
				if (md5($p)==$r['password']){
					$this->login_id=$r['login_id'];
					return TRUE;
				}
				else{
					$this->login_id = 0;
					return FALSE;
				}
			}
		}
		public function create_account($data){
			$u = $data['username'];
			$p = md5($data['password']);
			$e = $data['email'];
			$c = date('Y-m-d H:i:s');
			$i = 1000;
			$r = 1;
			$sql = "INSERT INTO logins ('username','password','email','created_at','creator_id','role_id')
					VALUES ('$u','$p','$e','$c','$i','$r')";
			if ($ret=$this->dbsqlite->query($sql)){
				return TRUE;
			}
			else{
				return FALSE;
			}	
		}
	}