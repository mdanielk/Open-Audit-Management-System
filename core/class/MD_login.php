<?php
class MD_Login{
	public function proses($submitName,$username,$password,$tablename,$kolomuser,$kolompass){
		// isi array = submit name,username,password
		if (isset($_POST[$submitName])){
			$username=check_var('post',$username);
			$username=netralize("$username");
			$password=md5(check_var('post',$password));
		// ---- check ke db
			$query = mysql_query("SELECT $kolomuser FROM $tablename WHERE $kolomuser='$username' AND $kolompass='$password'");
			if (mysql_num_rows($query)==0){
				return 'username atau password salah';
			}
			else{				
				$_SESSION['admin_session']=$username;
			}
		}
	}
}
?>