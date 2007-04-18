<?php
require_once("inc.php");
require_once("Error.php");
require_once("ErrorHandler.php");

/**
 * The PHPXMLRPCClient is a generic client that can be used to talk to the 
 * generic PHPXMLRPCServer.
 */
class PHPXMLRPCClient {
    
    var $_client_obj;
    var $_function;
    var $_args;
    var $_err;
 
    function __construct(){
        $this->PHPXMLRPCClient();
    }
    
    function PHPXMLRPCClient(){
        $this->_args = Array();
        $this->_err = new ErrorHandler();
    }
    
    
    /**
     * creatClient creates the xmlrpc client object
     *
     * @param $path   - The path after the domain to the server you want to talk to
     * @param $host   - The domain you are talking to
     * @param $port   - The port to connect on, default is 80
     * @param $method - Method to be used, default http. https and http11 can be used if CURL is installed
     */ 
    function createClient($path, $host, $port=80,$method='http'){
        $this->_client_obj = new xmlrpc_client($path, $host, $port, $method);
        $this->_client_obj->setdebug(0);
    }
    
    function setXMLRPCDebug($debug){
	    $this->_client_obj->setdebug($debug);
    }
    
    function resetRequest(){
	 	$this->_function = "";
	 	$this->_args = "";   
    }
    /**
     * setFunction sets the server function to be called
     *
     * @param $func - The name of the function to be called
     */ 
    function setFunction($func){
        
        if(!is_string($func)){
            return $this->_err->newError(0, "Function name is not a string.", __FILE__, __LINE__);
        }
        else {
            $this->_function = $func;
        }
    }
    
   /**
	* setOption sets specific RPC options
	*
	* @param $name 	- The name of the option
	* @param $param - Params to be passed
        */ 
	function setOption( $name,$param ) {
		$this->_client_obj->$name($param);
	}
	
    /**
     * addArg adds an argument to the args array to pass to the function
     *
     * @param variable args - Either a single argument or an array of arguments
     */ 
    function addArg(){
        
        $func_args = func_get_args();
        $func_args = $func_args[0];
        
        if(is_array($func_args)){
            foreach($func_args as $arg){
                $this->_args[] = php_xmlrpc_encode($arg);
            }
        }
        else{
            $this->_args[] = php_xmlrpc_encode($func_args);
        }
    }
    
    
    /**
     * call makes the acutal xmlrpc call to the server specified with the specified
     * function name and args.
     */ 
    function call($convert = true){

		if(!isset($this->_function)){
			//No function was specified
			return $this->_err->newError(0, "No function was specified.", __FILE__, __LINE__);
		}
		else{
			if(!is_a($this->_client_obj, 'xmlrpc_client')){
				return $this->_err->newError(0, "XML Client object is of wrong type.", __FILE__, __LINE__);
		    }

		    $result = $this->_client_obj->send(new xmlrpcmsg($this->_function, $this->_args));
		    
		    if($convert){
			    return $this->_convertResult($result);
		    }

			return $result;
		}

    }
    
    function _convertResult($result){
	    
	   
	    if($result->faultCode()){
		    return $this->_err->newError($result->faultCode(), $result->faultString(), __FILE__, __LINE__);
	    }
	   
	    $result = $result->value(); 
	    
	    if($result->kindOf() == 'array'){
		    $val = $result->arraymem(0);
		    
	    	if($val != "" && $val->kindOf() == 'struct'){
			    return $this->_convertRPCToAssociativeArray($result);
	    	}
	    	else {
			    return $this->_convertRPCToArray($result);
	    	}
	    }
	    else if($result->kindOf() == 'scalar'){
		    return $this->_convertRPCToScalar($result);
	    }
	    
	    
	    return $this->_err->newError(0, "Unknown return data type.", __FILE__, __LINE__);

    }
    
    function _convertRPCToArray($result){

	    $ret = array();
	    
		for($i = 0; $i < $result->arraysize(); $i++) {
			
			$val = $result->arraymem($i);
			
			if($val->kindOf() == 'scalar'){
				$ret[] = $this->_convertRPCToScalar($val);
			}
			else if($val->kindOf() == 'array'){
				
				$tmp = $val->arraymem(0);
				
				if($tmp->kindOf() == 'struct'){
					$ret[] = $this->_convertRPCToAssociativeArray($val);
				}
				else {
					$ret[] = $this->_convertRPCToArray($val);
				}
			}
		}  
		
		return $ret;
    }
    
    function _convertRPCToAssociativeArray($result){
	    
	    $ret = array();
	    
		for($i = 0; $i < $result->arraysize(); $i++) {
			
			$tmp = $result->arraymem($i);
			
			$tmp->structreset();
			
			while(list($key, $val) = $tmp->structeach()){

				if($val->kindOf() == 'scalar'){
					$ret[$key] = $this->_convertRPCToScalar($val);
				}
				else if($val->kindOf() == 'array'){
					$ret[$key] = $this->_convertRPCToArray($val);
				}
				else if($val->kindOf() == 'struct'){
					$ret[$key] = $this->_convertRPCToAssociativeArray($val);
				}
			}
		}  
		
		return $ret;
    }
    
    function _convertRPCToScalar($result){
	    return $result->scalarval();
    }
}
?>
