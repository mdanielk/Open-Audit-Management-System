<?php
defined( '__ACCESS__' ) or die( '<h2 align=center>Akses Ditolak!!</h2>' );
class MD_Form {
	public function __construct() {
	}
	// tampilkan input form
	public function f_input($array = ''){
		$this->input = '';
		if (array_key_exists('label',$array)){
		$name=array_key_exists('name',$array)?$array['name']:'';
			$this->input .= '<div class="line"><div class="formLabel"><label for="'.$name.'">'.$array['label'].'</label></div>';
		}
		$this->input .= '<div class="formBox"><input ';
		foreach ($array as $property=>$val){
			if ($property=='label'){
				$this->input .='';
			}
				else {
					$this->input .= $property .'="'.$val.'" ';
				}		
			
			}
		$this->input.='></div></div>';
		return $this->input;
	}
	
	function f_select_db($label,$name,$tabel,$kolomValue,$kolomOpsi,$val){
		$return='<div>
		<div class="formLabel"><label>'.$label.'</label></div>
		<div class="formBox">
		<select id="'.$name.'" name="'.$name.'">';
		$return.='<option value="">--Silahkan Pilih--</option>';
		//----data tabel
		$sql=mysql_query("SELECT * FROM $tabel ORDER BY $kolomValue");
		while ($row=mysql_fetch_array($sql)){
		if ($val==$row[$kolomValue]){$selected="selected";} else {$selected="";}
		$return.= '<option '.$selected.' value="'.$row[$kolomValue].'">'.$row[$kolomOpsi].'</option>';
			}
		$return.= "</select></div></div>";
		return $return;
	}

	public function __destruct(){
	}
}

?>