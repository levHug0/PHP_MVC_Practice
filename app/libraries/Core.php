<?php  
	/* 
	*  App Core Class
	*  Creates URL & loads core controller
	*  URL FORMAT -/controller/method/params
	*/
	class Core {
		protected $currentController = 'Pages';
		protected $currentMethod = 'index';
		protected $params = [];

		public function __construct() {
			//print_r($this->getUrl());
			$url = $this->getUrl();

			// Look in controllers for first value (index 0)
			// Might look something like ./controllers/Post.php
			if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
				// if exists, set as currentController
				$this->currentController = ucwords($url[0]);
				// Unset 0 Index
				unset($url[0]);
			}

			// Require the controller ../app technically we are
			// in the index.php file NOT in the libraries/core.php
			require_once '../app/controllers/' . $this->currentController . '.php';

			// Instantiate controller class
			// Might look something like $Pages = new Pages();
			$this->currentController = new $this->currentController();

			// Check for second part of url ie. pages/about
			if (isset($url[1])) {
				// Check to see if method exists in controller
				// method_exists(class, methodName) returns true if method exist
				if (method_exists($this->currentController, $url[1])) {
					$this->currentMethod = $url[1];
					unset($url[1]);	// call_user_func won't work properly if you don't unset
				}
			}

			// Get params, if there are more url params left
			// then it gets added, else it stays as an empty array
			$this->params = $url ? array_values($url) : [];

			// Call a callback with array of params
			// If you don't get what below means have a look at the code at the very bottom of this page
			// In short, second parameter '$this->params' is parameter argument
			// first parameter is an array[] index 0 is the class and index 1 is the method called
			call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
		}

		public function getUrl() {
			// php_mvc/post/dog/cool
			// $_GET['url'] outputs 'post/dog/cool'
			if (isset($_GET['url'])) {
				// removes whitespaces on the right of the string
				$url = rtrim($_GET['url'], '/');
				// Checks to see if it's a valid url
				$url = filter_var($url, FILTER_SANITIZE_URL);
				// Explodes url turning it into an array separated by '/'
				$url = explode('/', $url);
				return $url;
			}
		}
	}

/*
	function foobar($arg, $arg2) {
    echo __FUNCTION__, " got $arg and $arg2\n";
	}
	class foo {
	    function bar($arg, $arg2) {
	        echo __METHOD__, " got $arg and $arg2\n";
	    }
	}

	// Call the foobar() function with 2 arguments
	call_user_func_array("foobar", array("one", "two"));

	// Call the $foo->bar() method with 2 arguments
	$foo = new foo;
	call_user_func_array(array($foo, "bar"), array("three", "four"));

	OUTPUTS:
	foobar got one and two
	foo::bar got three and four
*/