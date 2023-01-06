<?php 
use Dompdf\Dompdf;
class Document extends MD_app{
	public function index(){	
		
	}
	public function generate(){
		$dompdf = new Dompdf();
		$dompdf->loadHtml($this->_content(),'UTF-8');
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->set_option('defaultMediaType', 'all');
		$dompdf->set_option('isFontSubsettingEnabled', true);
		$dompdf->render();
		$dompdf->stream("document_generated.pdf", array("Attachment" => false));
		exit(0);
	}
	private function _content(){
			$this->SetTemplate($this->getview("document","test"));
			$this->setVar('__TITLE__',__TITLE__);
			$this->setVar('__HOMEPAGE__',__HOMEPAGE__);
			$this->publishView();
			return $this->output;
	}
}