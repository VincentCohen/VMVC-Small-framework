<?php

class ProductsController extends BaseController{
	
	function __construct(){
	
	}

	function action_viewProduct(){
		$data["bar"] = "asdasdas";

		$this->render("foo", $data);
	}
}