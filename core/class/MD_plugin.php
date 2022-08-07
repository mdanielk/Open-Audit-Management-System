<?php
class MD_Plugin{
	public function load($plugin){
		if ($plugin=='datepicker'){
			return '<link rel="stylesheet" href="plugin/datepicker/jquery.ui.all.css">
					<script src="plugin/datepicker/jquery-1.7.2.js"></script>
					<script src="plugin/datepicker/jquery.ui.core.js"></script>
					<script src="plugin/datepicker/jquery.ui.widget.js"></script>
					<script src="plugin/datepicker/jquery.ui.datepicker.js"></script>
					<script>
						$(function() {
						$( ".datepicker" ).datepicker();
						});
				</script>';
		}
	}
}
?>