<?php namespace $NAMESPACE$\Controllers;

use View;
use Redirect;
use Input;
use Validator;
use $NAMESPACE$\Models\$MODEL$;

class $CONTROLLER$ extends \BaseController {

	/**
	 * Display a listing of $COLLECTION$
	 *
	 * @return Response
	 */
	public function index()
	{
		$$COLLECTION$ = $MODEL$::all();

		return View::make('$COLLECTION$.index', compact('$COLLECTION$'));
	}

	/**
	 * Show the form for creating a new $RESOURCE$
	 *
	 * @return Response
	 */
	public function create()
	{
		$$SINGLE$ = new $MODEL$;
		return View::make('$COLLECTION$.create', compact('$SINGLE$'));
	}

	/**
	 * Store a newly created $RESOURCE$ in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), $MODEL$::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$MODEL$::create($data);

		return Redirect::route('$COLLECTION$.index');
	}

	/**
	 * Display the specified $RESOURCE$.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$$SINGLE$ = $MODEL$::findOrFail($id);

		return View::make('$COLLECTION$.show', compact('$SINGLE$'));
	}

	/**
	 * Show the form for editing the specified $RESOURCE$.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$$SINGLE$ = $MODEL$::find($id);

		return View::make('$COLLECTION$.edit', compact('$SINGLE$'));
	}

	/**
	 * Update the specified $RESOURCE$ in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$$SINGLE$ = $MODEL$::findOrFail($id);

		$validator = Validator::make($data = Input::all(), $MODEL$::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$$SINGLE$->update($data);

		return Redirect::route('$COLLECTION$.index');
	}

	/**
	 * Remove the specified $RESOURCE$ from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$MODEL$::destroy($id);

		return Redirect::route('$COLLECTION$.index');
	}

}


