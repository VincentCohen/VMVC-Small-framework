<?php
class BaseView{
	
	public function __construct(){
		echo "baseview loaded";
	}

	public function render($strView, $arrData){
		//	extract data
		extract($arrData);

		//load view
		if(file_exists(VIEW_PATH . $strView . VIEW_EXTENSION)){

		}else{
			var_dump(VIEW_PATH . $strView . VIEW_EXTENSION);
		}
	}

}