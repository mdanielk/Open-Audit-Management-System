<?php
class MD_Controller extends MD_Template{
	public  $title=__TITLE__;
	public  $konten;
	public  $meta;
	public  $message;
	public  $showview;
	public function __construct(){

	}
	public function check($controller){
		if($controller==''){
			return TRUE;
		}
		elseif(file_exists('app/controllers/'.$controller.'.php')){
			return TRUE;	
		}
		else {
			return FALSE;
		}
	}
	public function load($controller){
		if(file_exists('app/controllers/'.$controller.'.php')){
			include_once 'app/controllers/'.$controller.'.php';			
		}
		else {
			$this->error= 'Error!!. silahkan buat file '.$controller.'.php di folder controllers terlebih dahulu';
		}
	}
	public function loadmodel($model,$alias=''){
		if(file_exists('app/models/'.$model.'.php')){
			include 'app/models/'.$model.'.php';
			if($alias != '')
				$this->$alias=new $model;
			else
				$this->$model=new $model;
			}
		else {
				echo 'model not found';
			}
		}	
	public function loadhelper($helper){
		if(file_exists('app/helpers/'.$helper.'.php')){
			include 'app/helpers/'.$helper.'.php';
			$this->helper=new $helper;			
			}
			else {
				echo 'helper not found';
			}
		}
	public function autoloadstylesheet(){
		$directory = "assets/css/";
		$files = glob($directory . "*.css");
		$data = '';
		foreach($files as $file)
				{
				    $data.='<link rel="stylesheet" href="'.__HOMEPAGE__.'/'.$file.'">'."\n";
				}
		return $data;
		}	
	public function autoloadjavascript(){
		$directory = "assets/js/";
		$files = glob($directory . "*.js");
		$data = '';
		foreach($files as $file)
				{
				    $data.='<script type="text/javascript" src="'.__HOMEPAGE__.'/'.$file.'"></script>'."\n";
				}
		return $data;
		}
	public function autoloadfavicon(){
		$directory = "assets/favicon/";
		$files = glob($directory . "*.png");
		$data = '';
		foreach($files as $file)
				{
				    #$data.='<script type="text/javascript" src="'.__HOMEPAGE__.$file.'"></script>'."\n";
				    $data.='<link href="'.__HOMEPAGE__.'/'.$file.'" rel="shortcut icon" />'."\n";
				}
		return $data;
		  
	}
	public function getview($controller,$view){
		if(file_exists('app/views/'.$controller.'/'.$view.'.html')){
			return 'app/views/'.$controller.'/'.$view.'.html';
		}
		else {
			return FALSE;
		}
	}
	public function crud_table($array=NULL){
    $kolom = isset($array['kolom'])?$array['kolom']:NULL;
    $data = isset($array['data'])?$array['data']:NULL;
    $sql = isset($array['sql'])?$array['sql']:NULL;
    $class = isset($array['class'])?'class="'.$array['class'].'"':NULL;
    $unik = isset($array['unik'])?$array['unik']:NULL;
    $linkedit = isset($array['linkedit'])?$array['linkedit']:NULL;
    $linkdelete = isset($array['linkdelete'])?$array['linkdelete']:NULL;
    $linkdetail = isset($array['linkdetail'])?$array['linkdetail']:NULL;
    $this->setData= "<table $class><thead><th width='20px'>No.</th>";
    foreach ($kolom as &$value) {
   			$this->setData.= '<th>'.$value.'</th>';
		}
    $this->setData.= "<th>&nbsp;</th></tr></thead>";
    $ret=$this->dbsqlite->query($sql);
	$no=1;
     while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
			$this->setData.= '<tr><td >'.$no++.'</td>';
			foreach ($data as &$value) {
   				$this->setData.= '<td>'.$row[$value].'</td>';
			}
			$this->setData.= '<td>';
			if ($linkedit==true){
				$this->setData.= '<a class="green edit-button" href="'.$linkedit.'/'.$row[$array['unik']].'"><i class="icon-pencil bigger-180"></i></a>&nbsp;';
			}
			if ($linkdetail==true){
				$this->setData.= '<a href="'.$linkdetail.'/'.$row[$array['unik']].'" class="btn btn-small detail-button"><span>detail</span></a>&nbsp;';
			}
			if ($linkdelete==true){
				$this->setData.= '	<a class="red delete-button" href="'.$linkdelete.'/'.$row[$array['unik']].'"><i class="icon-trash bigger-180"></i></a>&nbsp;';
			}
			$this->setData.= '</td></tr>';
		}
		$this->setData.="</tr></table>";
		$this->setData.='<script type="text/javascript">
							$(".delete-button").click(function(e) {
							e.preventDefault();
							var url = $(this).attr("href");
							var tr = $(this).closest("tr");
							if(confirm("Apakah anda yakin menghapus data ini?"))	
							$.get(url, function(result){			
								tr.fadeOut(400, function(){
									tr.remove();
									});
								});	
							});
							$(".edit-button").click(function(e) {
								e.preventDefault();
								url = $(this).attr("href");
								$.get(url+"/ajax/_ajax", function(result){			
										$("#form-data").html(result);
								   });
							});
							</script>';
    return $this->setData;
  }
	public function redirect($url){
		header('location:'.__HOMEPAGE__.'/'.$url);
	}
	public function connect_sqlite(){    
		$this->dbsqlite = new MD_db(__SQLITEDB__);		
		$this->dbsqlite->busyTimeout(5000);
	}	
	public function close_sqlite(){
		$this->dbsqlite->close();
		unset($this->dbsqlite);
	}
	public function login_required(){
		if($_SESSION['login']==false){
			$this->redirect('login/');
		}
	}

}
?>