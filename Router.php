<?php
/**
*	Router Class
*/
class Router{

    /**
    * Array that holds all routes
    * @var array
    */ 
    private static $arrRoutes = array();

    /**
    * String, base url
    * @var array
    */ 
    private static $strBaseUrl = "";

    /**
    * The base REQUEST_URI.
    * @var string
    */
    private static $strRequestUri = "";

    /**
    * The current request.
    * @var string
    */
    private static $strCurrentRequest = "";


    /**
    * Router dispatch method
    *
    * Maps the given URL to its target.
    */
    public static function dispatch(){
        $boolRouted = false; // did we find a route and successfully execute it?
        self::$strCurrentRequest = str_replace(self::$strBaseUrl, "", self::$strRequestUri);

        $arrMap = self::map();

        if($arrMap){
            $boolRouted = self::execute($arrMap);
            var_dump($boolRouted);
        }else{
            //  router fallback
        }

        if($boolRouted == false){
            echo "404 BARF";
        }

    	//	Load controllers 
    }

    /**
    * Router mapping method
    * Finds maps based on the routes
    *
    * strRoute represents the string with its params added by addRoute
    * strCurrentRegex represents the regex string generated by the strRoute
    * strCurrentRequest represents the current url
    */
    public static function map(){
    	$strCurrentRequest = self::$strCurrentRequest;
    	$arrReturnMap = array();

    	//	Loop trough routes
    	foreach(self::$arrRoutes as $strRoute => $arrMap){

    		//	match current route on url segments and generate a regex out of it.
    		$strCurrentRegex = preg_replace("/:([\w-]+)/", "([\w-]+)", $strRoute);

    		//	match generated regex on current request
    		$boolHasMatch = preg_match("@^".$strCurrentRegex."*$@i", $strCurrentRequest, $arrMatches);

    		if(!$boolHasMatch){
    			continue;
    		}else{
    			// extracting params to array
    			if (preg_match_all("/:([\w-]+)/", $strRoute, $arrParams)){
    				
    				// get the matches
    				$arrParams = $arrParams[1];
    				$arrMapParams = array();

    				foreach ($arrParams as $strKey => $strName) {
    					//	push param keys and values to array						
                        if (isset($arrMatches[$strKey + 1])){
                            $arrMapParams[$strName] = $arrMatches[$strKey + 1];
                        }
                    }

                    if(isset($arrMapParams["action"])){
                        $arrMap["action"] = $arrMapParams["action"];
                        unset($arrMapParams["action"]);
                    }

                    $arrMap["params"] = $arrMapParams;
                    $arrReturnMap = $arrMap;
    			}else{
                    $arrMap["params"] = array();
                    $arrReturnMap = $arrMap;
                }
    		}
    	}

    	if(count($arrReturnMap) == 0){
    		return false;
    	}

        var_dump($arrReturnMap);
    	return $arrReturnMap;
    }

    /**
    * Executes the route
    * @param string $URI 
    */
    public static function execute($arrMap){
        $strControllerPath = CONTROLLER_DIR . $arrMap["controller"] . CONTROLLER_SUFFIX . ".php";
        $strControllerPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $strControllerPath;
        $strController = $arrMap["controller"] . CONTROLLER_SUFFIX;
        $strAction  = METHOD_PREFIX . $arrMap["action"];
        $arrParams = $arrMap["params"];

        if(file_exists($strControllerPath)){
            include_once($strControllerPath);

            $objController = new $strController;

            if(method_exists($objController, $strAction)){
                call_user_func_array(array($objController, $strAction), $arrParams);

                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
    * Router setBaseUrl method
    *
    * Adds route to router array.
    * @param string url
    */
    public static function setBaseUrl($strUrl){
        self::$strBaseUrl = (string) $strUrl;

        return true;
    }

    /**
    * Sets the request uri
    * @param string $URI 
    */
    public static function setRequestUri($strUri){
        self::$strRequestUri = (string) $strUri;

        return true;
    }

    /**
    * Sets the request uri
    * @param string $URI 
    */
    public static function execute404($strUri){
        self::$strRequestUri = (string) $strUri;

        return true;
    }


    /**
    * Router addRoute method
    *
    * Adds route to router array.
    * @param string $arrRoute
    */
    public static function addRoute($strRoute, $arrMap){
    	self::$arrRoutes[$strRoute] = $arrMap;
    }
	
}