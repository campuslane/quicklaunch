<?php namespace Cornernote\Quicklaunch;

use Input;
use File;
use Schema;
use Artisan;
use Redirect;

class Parser {

	public $collection;
	public $namespace;
	public $field;
	public $resource;
	public $single;
	public $name;
	public $controller;
	public $model;
	public $stubPath;
	public $terms = ['resource', 'namespace', 'field', 'collection', 'collectionUpper', 'single', 'model', 'controller'];
	public $views = ['index', 'create', 'edit', 'delete', 'show', 'form_fields'];
	public $namespacePath;
	public $modelPath;
	public $migrationFilesPath;
	public $fields = [];


	/**
	 * Constructor
	 * @param [type] $input
	 */
	public function __construct($input)
	{
		// set the names
		$this->setNames($input);
		
		// parse the fields
		$this->parseFields($input['field_names'], $input['field_types']);

		// if we have a namespace
		$this->createDirectories();

		// do the rest
		$this->createController();
		$this->createModel();
		$this->createViews();
		$this->createSchema();
		
	}


	/**
	 * Set Names
	 * @param [Larvel Input] $input
	 */
	public function setNames($input)
	{
		$this->resource = strtolower(str_plural($input['resource_name']));
		$this->namespace = $input['namespace'];
		$this->collection = $this->resource;
		$this->single = str_singular($this->collection);
		$this->collectionUpper = ucwords($this->collection);
		$this->controller = ucwords(str_singular($this->resource)) . 'Controller';
		$this->model = ucwords(str_singular($this->resource));
		$this->stubPath = __DIR__ . '/Stubs/';
	}


	/**
	 * Create Directories
	 * @return [type]
	 */
	public function createDirectories()
	{
		if( $this->namespace ) {

			// set the namespace path names
			$this->namespacePath = app_path() . '/' . $this->namespace . '/';
			$this->controllerPath = $this->namespacePath . 'Controllers/';
			$this->modelPath = $this->namespacePath . 'Models/';
			$this->viewsPath = app_path() . '/views/' . $this->collection;
			$this->migrationFilesPath = app_path() . '/database/migrations/';

			// create the directories
			$this->createDirectory( $this->namespacePath );
			$this->createDirectory($this->namespacePath . 'Controllers');
			$this->createDirectory($this->namespacePath . 'Models');
			$this->createDirectory($this->viewsPath);

		}
	}


	/**
	 * Parse Fields
	 * @param  array $fieldNames
	 * @param  array $fieldTypes
	 * @return none
	 */
	public function parseFields($fieldNames, $fieldTypes)
	{
		foreach($fieldNames as $key=>$fieldName)
		{
			if($fieldName)
			{
				$fieldType = isset($fieldTypes[$key]) ? $fieldTypes[$key] : 'string';
				$this->fields[] = ['name'=>$fieldName, 'type'=>$fieldType];
			}
		}

		return;
	}


	/**
	 * Create Controller
	 * @return boolean
	 */
	public function createController()
	{
	
		// get the controller file stub
		$stub = File::get($this->stubPath . 'controller.php');

		// parse the stub and return the contents
		$controllerContent = $this->parseStub($stub, $this->terms);

		// write the new controller
		File::put($this->controllerPath . $this->controller . '.php', $controllerContent);
	
		return true;
	}



	/**
	 * Create Model
	 * @return [type]
	 */
	public function createModel()
	{
	
		// get the controller file stub
		$stub = File::get($this->stubPath . 'model.php');

		// parse the stub and return the contents
		$modelContent = $this->parseStub($stub, $this->terms);

		// write the new controller
		File::put($this->modelPath . $this->model . '.php', $modelContent);
	
		return true;
	}



	/**
	 * Create Views
	 * @return boolean
	 */
	public function createViews()
	{
		// go through the views array
		foreach($this->views as $view)
		{
			// get the view stub and make the replacements
			$viewStub = File::get($this->stubPath . '/' . $view . '.php');
			$viewPath = $this->viewsPath . '/' . $view . '.blade.php';
			$viewContent = $this->parseStub($viewStub, $this->terms);

			// if this is the form_fields view, add in the form fields content
			if($view == 'form_fields')
			{
				$viewContent = str_replace('$FORMFIELDS$', $this->getFormFieldsContent(), $viewContent);
			}

			// if this is the index view, add in the main field
			else if($view == 'index')
			{
				$viewContent = str_replace('$MAINFIELD$', $this->getMainField(), $viewContent);
			}

			// create the file
			File::put($viewPath, $viewContent);
		}

		return true;
	}



	/**
	 * Create Schema (the migration)
	 * @return boolean
	 */
	public function createSchema()
	{
		// get the fields for the migration class
		$fieldsContent = $this->getSchemaFieldsContent();

		// create the migration file name
		$migrationFileName = $this->createMigrationFileName();

		// create the migration  class name
		$migrationClass = studly_case('create_' . $this->collection . '_table');
		
		// get the schema file stub
		$stub = File::get($this->stubPath . 'schema.php');

		// make the replacements
		$content = str_replace('$FIELDS$', $fieldsContent, $stub);
		$content = str_replace('$MIGRATIONCLASS$', $migrationClass, $content);
		$content = str_replace('$COLLECTION$', $this->collection, $content);

		// create the migration file
		File::put($this->migrationFilesPath . $migrationFileName, $content);

		// do the migration (we force so there is no STDIN output)
		Artisan::call('migrate', array('--force' => true));

		// set the countroller route template
		$controllerRoute = '

		// quicklaunch automatically inserted this
		Route::resource("$COLLECTION$", "$NAMESPACE$\Controllers\$CONTROLLER$");

		';

		// make the replacements
		$controllerRoute = str_replace('$COLLECTION$', $this->collection, $controllerRoute);
		$controllerRoute = str_replace('$NAMESPACE$', $this->namespace, $controllerRoute);
		$controllerRoute = str_replace('$CONTROLLER$', $this->controller, $controllerRoute);

		// now add the controller route to the routes file
		File::append(app_path(). '/routes.php', $controllerRoute);

		return true;
	}


	/**
	 * Get Collection Url
	 * @return string
	 */
	public function getCollectionUrl()
	{
		return '/' . $this->collection;
	}



	/**
	 * Parse Stub
	 * @param  string $stub
	 * @param  array $terms
	 * @return string
	 */
	public function parseStub($stub, $terms)
	{
		foreach($terms as $term) 
		{
			$termUpper = strtoupper($term);
			$stub = str_replace('$' . $termUpper . '$', $this->$term, $stub);
		}

		return $stub;
	}


	/**
	 * Parse Schema Fields
	 * @param  string $stub
	 * @param  array $field
	 * @return string
	 */
	public function parseSchemaFields($stub, $field)
	{
		
		$stub = str_replace('$TYPE$', $field['type'], $stub);
		$stub = str_replace('$NAME$', $field['name'], $stub);

		return $stub;
	}



	/**
	 * Get Main Field for Index List
	 * @return [type]
	 */
	public function getMainField()
	{
		foreach($this->fields as $field)
		{
			if($field['type'] !== 'integer' and $field['type'] !== 'text')
			{
				return $field['name'];
			}
		}
	}


	/**
	 * Get Form Fields Content
	 * @return string
	 */
	public function getFormFieldsContent()
	{
		// initialize content
		$content = '';

		// go through fields
		foreach($this->fields as $field)
		{
			// use type value or default to string
			$fieldType = isset($field['type']) ? $field['type'] : 'string';
			$fieldName = $field['name'];

			// if the type is text, then we use a text area, otherwise text
			$stubFile = ($fieldType == 'text') ? '/textarea_field.txt' : '/text_field.txt';
			
			// get the stub
			$stub = File::get($this->stubPath . $stubFile);

			// make the replacements and append to the content
			$stub = str_replace('$FIELDNAME$', $fieldName, $stub);
			$stub = str_replace('$FIELDLABEL$', ucwords($fieldName), $stub);
			$stub = str_replace('$SINGLE$', $this->single, $stub);

			// append to the content
			$content .= $stub;
			
		}

		// return the content
		return $content;
	}



	/**
	 * Get Schema Fields Content
	 * @return string
	 */
	public function getSchemaFieldsContent()
	{
		// setup the fields within migration file
		$content = '';

		foreach($this->fields as $field)
		{
			$stub = File::get($this->stubPath . 'schema_fields.php');
			$content .= $this->parseSchemaFields($stub, $field) . "\n\t\t\t";
		}

		$content = trim($content, "\n\t\t\t");

		return $content;
	}


	/**
	 * Create Migration File Name
	 * @return string
	 */
	public function createMigrationFileName()
	{
		$migrationFileName = 'create_' . $this->collection . '_table';
		$migrationFileName = date('Y_m_d_His') . '_' . $migrationFileName . '.php';

		return $migrationFileName;
	}


	/**
	 * Create Directory
	 * @param  string $directory
	 * @return string
	 */
	public function createDirectory($directory)
	{
		// make sure the directory exists
		if ( ! File::isDirectory($directory) ) {
			File::makeDirectory($directory);
		}

		return $directory;
	}

}