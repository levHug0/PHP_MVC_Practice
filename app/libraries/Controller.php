<?php
	/*
	 * Base Controller
	 * Loads the models and views
	 */
	class Controller {
		// Load Model
		public function model($model) {
			// Require model file
			require_once '../app/models/' . $model . '.php';
			// Instantiate and return the model
			return new $model();
		}

		// Load view
		public function view($view, $data = []) {
			// Check for view file
			$file = '../app/view/' . $view . '.php';
			if (file_exists($file)) {
				require_once $file;
			} else {
				// View doesn't exist
				die("View Doesn't exist");
			}
		}
	}