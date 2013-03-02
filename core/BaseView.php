<?php
class BaseView{
	
	public function __construct(){
		echo "baseview loaded";
	}

	public function render($strView, $arrData){
		$strFilePath = VIEW_PATH . $strView . VIEW_EXTENSION; //	Path to file

		//	extract data
		extract($arrData);

		//load view
		if(file_exists($strFilePath)){
			require_once($strFilePath);
		}else{
			die( " VIEW NOT FOUND ");
		}
	}

}