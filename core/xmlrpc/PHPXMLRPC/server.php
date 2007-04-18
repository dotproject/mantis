<?php

require_once("inc.php");
require_once("Error.php");
require_once("ErrorHandler.php");

class PHPXMLRPCServer {
	
	var $_functions;
	
	var $_server;
	
	var $_err;
	
	function PHPXMLRPCServer(){
		
		$this->_functions = array();
		$this->_server = "";
		$this->_err = new ErrorHandler();
	}
	
	function __construct(){
		$this->PHPXMLRPCServer();	
	}

	function addFunction($RPCFunction, $function){
		if(isset($this->_functions[$RPCFunction])){
			return $this->_err->newError(0, "RPC Function already exists.", __FILE__, __LINE__);
		}
		
		$this->_functions[$RPCFunction] = array("function" => $function);
		
		return true;
	}
	
	function startServer(){
		$this->_server = new xmlrpc_server($this->_functions, false);
		$this->_server->setdebug(0);
			
		$this->_server->service();		
	}
}