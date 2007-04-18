<?php

/**
 * This class is used to create objects that encapsulate error information.
 * It is one of the few classes that does not inherit from Object.
 */
class Error {

	/** Stores attributes associated with an Error instance */
	var $_attributes;

	/**
	 * Constructs a new instance
	 *
	 * @return An instance of the Error object
	 */	
	function Error(){
		$this->_attributes = array();
	}
	
	/**
	 * A convenience method that builds an error string from the attributes
	 * that have been set.
	 *
	 * @param $showLocation [optional] If true, show the file and line location 
	 *               of the error (if it was set).  If false, do nothing.  For
	 *               initial development, the default is true... but we will
	 *               change it to false in the future.
	 * @return A string representation of the error.
	 */
	function getErrstr($showLocation=true){		
		
		if(count($this->_attributes) > 0){		
			$string = "Error:";
			$errno = $this->getAttribute('error_number');
					
			if($errno){
				// Error number was specified, display it
				$string .= " [$errno]";	
			} 
			
			$string .= " " . $this->getAttribute('error_message');			
			
			if($showLocation){
				$file = basename($this->getAttribute('file_name'));
				$line = $this->getAttribute('line_number');
				
				if($file && $line){
					// File and line data was specified, display it
					$string .= " at $file:$line";					
				}
			}
						
			return $string;
		} else {
			return '';	
		}
	}
	
	/**
	 * Determines whether or not the specified argument is an error.  
	 *
	 * @static This method may be called as Error::isError($mixed);
	 * @param $mixed The argument to examine
	 * @return true if $mixed is an error, else false
	 */
	function isError($mixed){
		if(is_object($mixed) && is_a($mixed,"Error")){
			return true;	
		}
		
		return false;
	}
	
	
	
	/**
	 * Sets the specified attribute to the specified value.
	 *
	 * @param $attribute Legal attributes are:
     *        'error_number'
	 *        'error_message'
	 *        'file_name'
	 *        'line_number'
	 * @return void 
	 */
	function setAttribute($attribute,$value){	
		$this->_attributes[$attribute] = $value;			
	}
		
	/**
	 * Gets the value of the specified attribute
	 *
	 * @param $attribute Legal attributes are:
     *        'error_number'
	 *        'error_message'
	 *        'file_name'
	 *        'line_number'
	 * @return The value of the specified attribute
	 */
	function getAttribute($attribute){
		return $this->_attributes[$attribute];	
	}
	
}

?>
