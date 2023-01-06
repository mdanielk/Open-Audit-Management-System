<?php
/* 
Top Menu class_alias
*/
class Topnav extends MD_app{
	public function show(){
		$dropdown = '<ul id="dropdownProfile" class="dropdown-content">
						<li><a href="#!">Your profile</a></li>
						<li><a href="#!">Settings</a></li>
						<li><a href="#!">Help</a></li>
						<li><a href="knowledge/">Knowledge Base</a></li>
						<li class="divider"></li>
						<li><a href="'.__HOMEPAGE__.'/logout/">Logout</a></li>
					</ul>';
		$topmenu ='<header>				
				<div class="navbar-fixed">
				<nav>
					<div class="nav-wrapper">
					  <div class="logo"><a href="'.__HOMEPAGE__.'" class="brand-logo">Audit System</a></div>
					  <ul class="right">
						<li><a href="'.__HOMEPAGE__.'">Home</a></li>';
				foreach ($this->get_menu() as $k){
					if($k['submenus']!= null){
						$topmenu.='<li><a class="dropdown-trigger" href="#!" data-target="dd_menu'.$k['id'].'">'.$k['text'].'<i class="material-icons right">arrow_drop_down</i></a></li>';
						$dropdown.='<ul id="dd_menu'.$k['id'].'" class="dropdown-content">';
						foreach($this->get_submenu($k['id']) as $l){
							$dropdown.='<li><a href="'.__HOMEPAGE__.'/'.$l['url'].'">'.$l['text'].'</a></li>';
						}
						$dropdown.='</ul>';
					}
					else{
						$topmenu.='<li><a href="'.__HOMEPAGE__.'/'.$k['url'].'">'.$k['text'].'</a></li>';
					}
				}						
				$topmenu.='<li><a class="dropdown-trigger" href="#!" data-target="dropdownProfile">Login As User!<i class="material-icons right">arrow_drop_down</i></a></li>
					  </ul>
					</div>
				</nav>
				</div>
				'.$dropdown.'
				</header>';
		return $topmenu;
	}
	private function get_menu(){
			$this->connect_sqlite();
			$sql = "SELECT id,text,url FROM menus WHERE deleted = 0 ORDER BY sequence ASC";
			$ret=$this->dbsqlite->query($sql);
			$data = array();
			$datasubmenus = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){
				
				$arr = array('id'=>$r['id'],
							 'text'=>$r['text'],
							 'url'=>$r['url'],
							 'submenus'=>$this->get_submenu($r['id'])
							 );
				array_push($data,$arr);
			}
			return $data;
		}
	private function get_submenu($parent_id){
			$this->connect_sqlite();
			$sql = "SELECT id,text,url FROM submenus WHERE deleted = 0 AND parent_id = $parent_id ORDER BY sequence ASC";
			$ret=$this->dbsqlite->query($sql);
			$data = array();
			while($r = $ret->fetchArray(SQLITE3_ASSOC) ){			
				$arr = array('id'=>$r['id'],
							 'text'=>$r['text'],
							 'url'=>$r['url']
							 );
				array_push($data,$arr);
			}
			return $data;
	}
}
