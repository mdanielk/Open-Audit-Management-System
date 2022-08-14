<?php 
class topnav{
	public function show(){
			$topnav = '<header>
						<ul id="dropdownProfile" class="dropdown-content">
						  <li><a href="#!">Your profile</a></li>
						  <li><a href="#!">Settings</a></li>
						  <li><a href="#!">Help</a></li>
						  <li class="divider"></li>
						  <li><a href="'.__HOMEPAGE__.'/logout/">Logout</a></li>
						</ul>
						<div class="navbar-fixed">
						<nav>
							<div class="nav-wrapper">
							  <div class="logo"><a href="'.__HOMEPAGE__.'" class="brand-logo">'.__TITLE__.'</a></div>
							  <ul class="right">
								<li><input name="search" class="input-field autocomplete" placeholder="search"></li>
								<li><a href="'.__HOMEPAGE__.'">Home</a></li>
								<li><a href="">Audit Plan</a></li>
								<li><a href="">Fieldwork</a></li>
								<li><a href="">Report</a></li>
								<li><a href="">Follow Up</a></li>
								<li><a class="dropdown-trigger" href="#!" data-target="dropdownProfile">Login As '.$_SESSION['username'].'!<i class="material-icons right">arrow_drop_down</i></a></li>
							  </ul>
							</div>
						 </nav>
						</div>
						</header>';
		return $topnav;
	}
}
?>