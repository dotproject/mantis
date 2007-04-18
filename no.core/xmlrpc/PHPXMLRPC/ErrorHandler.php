<?php
class ErrorHandler {
    
    /**
	 * A convenience method used to construct a new error object.  The object
	 * would then be returned by the code that encountered a problem.
	 *
	 * Example usage:
	 * return newError(1024,'database connect failed',__FILE__,__LINE__);
	 *
	 * @param $errorNumber An error number associated with the error
	 * @param $errorMessage A textual error message
	 * @param $fileName The name of the file containing the error
	 * @param $lineNumber The line number of the error
	 * @return An initialized Error object
	 */
	function newError($errorNumber,$errorMessage,$fileName,$lineNumber){
	
		$error = new Error();		
		$error->setAttribute('error_number',$errorNumber);
		$error->setAttribute('error_message',$errorMessage);
		$error->setAttribute('file_name',$fileName);
		$error->setAttribute('line_number',$lineNumber);
				
		return $error;
	}
    
}
?>