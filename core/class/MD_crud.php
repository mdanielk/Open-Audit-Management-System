<?php
class MD_Crud{
	public function show_table($arr){
	if (is_array($arr)){
		$query = isset($arr['query'])?$arr['query']:'';
		$header = isset($arr['header'])?$arr['header']:'';
		$kolom = isset($arr['kolom'])?$arr['kolom']:'';
		$controller = isset($arr['controller'])?$arr['controller']:'';
		$identifier = isset($arr['identifier'])?$arr['identifier']:'';
		$action_link = isset($arr['action_link'])?$arr['action_link']:'';
		$class = isset($arr['class'])?' class="'.$arr['class'].'"':'';
		//-----
		$this->table='<table '.$class.'><tr><th>No.</th>';
		foreach ($header as $head){
			$this->table.='<th>'.$head.'</th>';	
		}
		if (is_array($action_link)){
		$this->table.='<th>Aksi</th>';
		}
			$this->table.='</tr>';
		$exec = mysql_query($query);
		 	if (($exec != FALSE)&&(mysql_num_rows($exec)!='')){
				$num = 1;
		 		while ($r = mysql_fetch_array($exec)){
					$this->table.='<tr><td>'.$num++.'</td>';
					foreach ($kolom as $namakolom){
						$this->table.='<td>'.$r[$namakolom].'</td>';
					}
					
					if (is_array($action_link)){
					$this->table.='<td>';
						if ($action_link['edit']==TRUE){
							$this->table.='<li><a href="?md='.$controller.'&aksi=edit&id='.$r[$identifier].'">Edit</li>';
						}
						if ($action_link['delete']==TRUE){
							$this->table.='<li><a href="?md='.$controller.'&aksi=delete&id='.$r[$identifier].'">Delete</li>';
						}
						if ($action_link['detail']==TRUE){
							$this->table.='<li><a href="?md='.$controller.'&aksi=detail&id='.$r[$identifier].'">Detail</li>';
						}
						$this->table.='</td>';
					}
										
					$this->table.='</tr>';
				}
		 	}			
		
		$this->table.='</tr></table>';
		return $this->table;
		}
	}
	public function insert($table,$kolom,$data){
		$query = mysql_query("INSERT INTO $table ($kolom) VALUES ($data)");
		if ($query == TRUE){
			return TRUE;
		}
	}
	public function delete($table,$id_kolom,$id_value){
		$query = mysql_query("DELETE FROM $table WHERE $id_kolom='$id_value'");
		if ($query == TRUE){
			return TRUE;
		}
	}
	public function get_data($sql,$kolom){
			$execSQL=mysql_query($sql) or die (mysql_error());
			$row=mysql_fetch_array($execSQL);
			$exploded=explode(",", "$kolom");
			foreach ($exploded as $val) { 
				$this->$val=$row[$val];		
			}
		}
	public function edit($table,$set,$id_kolom,$id_value){
		$query = mysql_query("UPDATE $table SET $set WHERE $id_kolom = $id_value");	
		if ($query){
			return TRUE;
		}	
		else{
			return FALSE;
		}
	}
	
	
}
?>