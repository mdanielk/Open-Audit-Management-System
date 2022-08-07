<?php
class MD_app extends MD_Controller{
function __construct(){
$this->meta='<meta name="author" content="mdanielk">'."\n";
$this->meta.='<meta http-equiv="cache-control" content="no-cache">';
}
function loadapp($md,$fx){
if($md!=''){
	$this->load($md);
	if (class_exists($md)){		
	$className = new $md;
		if ($fx!=''){
			if(method_exists($className,$fx)){
			$className->$fx();
			}
		}

		$this->title=$className->title;
		$this->konten=$className->konten;
			}	
			
		}
	}															
}
?>