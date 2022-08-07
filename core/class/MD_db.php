<?php
class MD_db extends SQLite3{
	function __construct($sqlite_files){	
		$this->open('dbs/'.$sqlite_files);		
		}
   }


class MD_mysqli extends mysqli {
    public function __construct($host, $user, $pass, $db) {
        parent::__construct($host, $user, $pass, $db);

        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ')'.mysqli_connect_error());
        }
    }
}


?>
