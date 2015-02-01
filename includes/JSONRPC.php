<?php

/**
 * JSON-RPC Helper functions
 *
 * These functions make it easier to follow JSON-RPC practices when passing AJAX data to and from the server.
 * It isn't really the 'proper way' to do it, but it's simple and reasonably effective.
 */
class JSONRPC {
	/**
	 * The JSON-RPC ID.  Passed in via input(), passed out via output().
	 * @var integer
	 */
	private $id;

	/**
	 * The method being called.  Passed in via input().
	 * @var [type]
	 */
	private $method;

	/**
	 * Get the method 
	 * @return string
	 */
	public function get_method() { return $this->method; }

	/**
	 * The parameters of the call, passed in via input().
	 * @var array
	 */
	public $params;

	/**
	 * Controls whether the class should exit the script as soon as an error is encountered or not.
	 * @var boolean
	 */
	public $exit_on_error = true;

	/**
	 * Used internally. Tells the output whether an error has happened or not.
	 * @var string
	 */
	private $error_string = null;
	
	/**
	 * Decode on construction if relevant json is passed
	 * @param string
	 */
	public function __construct($request = null) {
		header('Content-type: application/json');
		$this->input($request);
	}

	/**
	 * Decodes a JSON-RPC request string and returns an array of relevant data
	 * @param $request the array of items to be decoded
	 */
	public function input($request = null) {
		// Expected JSON-RPC format:
		//{"method": "foo", "params": {"bar": 123, "baz": 456}, "id": 999}

		if($request == null) $request = $_REQUEST;

		if(!isset($request['id']) || !is_numeric($request['id'])) $this->error('invalid ID');
		if(!isset($request['method']) || gettype($request['method']) != 'string') $this->error('invalid method');
		if(!isset($request['params']) || gettype($request['params']) != 'array') $this->error('invalid params');

		$this->id = (int)$request['id'];
		$this->method = $request['method'];
		$this->params = $request['params'];
	}

	/**
	 * Outputs an error, and exits if so desired
	 * @param  string
	 */
	public function error($error) {
		$this->error_string = $error;
		$this->output(null);
		if($this->exit_on_error == true) exit();
	}	

	/**
	 * Outputs a JSON-formatted response
	 * @param  array 
	 */
	public function output($result = null) {
		echo(
			json_encode(
				array(
					'id' => $this->id,
					'error' => $this->error_string,
					'result' => $result
				)
			)
		);
	}
}

?>