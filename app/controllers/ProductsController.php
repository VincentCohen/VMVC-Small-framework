<?php

class ProductsController extends ControllerBase{
	
	function __construct(){
		echo "products controller loaded";
	}

	function action_viewProduct(){

		echo "viewProduct";
		$data["bar"] = "asdasdas";

		$this->render("foo", $data);
	}
}