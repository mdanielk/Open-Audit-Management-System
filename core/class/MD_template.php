<?php
class MD_Template{

private $template, $vars;
public function SetTemplate($tempname) {
    $templatePathAndName  = $tempname;
    if(file_exists($templatePathAndName))
        $this->template = file_get_contents($templatePathAndName);
    else
        die("Template not found... aborting...");
}
public function SetTemplateModul($tempname) {
    $templatePathAndName  = $tempname;
    if(file_exists($templatePathAndName))
        $this->template = file_get_contents($templatePathAndName);
    else
        die("Template not found... aborting...");
}
public function setVar($var, $content) {
    $this->vars[$var] = $content;
	$language = new Language;
	$data = $language->strings();
	foreach($data as $r=>$v){ 
			$this->vars[$r] = $v;
	};
	
}

public function replaceAll() {
	if (is_array($this->vars)){
		  foreach($this->vars as $var => $content) {
    		$this->template = str_replace("{" . strtoupper($var). "}", $content, $this->template);
  		}
	}
}

public function publish() {
    $this->replaceAll();
    echo $this->template;
}
public function publishView() {
    $this->replaceAll();
   $this->output=$this->template;
}

public function includeFile(){
  foreach($this->vars as $var => $content) {
    $this->template = str_replace("<-" . strtoupper($var). "->", 
                                  file_get_contents($content), 
                                  $this->template);
  }
}
}

?>

