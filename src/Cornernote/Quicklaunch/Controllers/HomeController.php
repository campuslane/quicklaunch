<?php namespace Cornernote\Quicklaunch\Controllers;

use View;
use Input;
use File;
use Redirect;

class HomeController extends \Controller {


	public function getIndex()
	{
		$data['resource_name'] = '';
		$data['namespace'] = '';
		$data['field'] = '';
		return View::make('quicklaunch::form', $data);
	}


	public function postProcess()
	{

		// instantiate a new parser
		$parser = new \Cornernote\Quicklaunch\Parser(Input::all());
		
		return Redirect::to($parser->getCollectionUrl());

	}

}