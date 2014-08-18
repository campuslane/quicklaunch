@extends(Theme::layout('default'))

@section('title', 'Quick Launch Home')

@section('main')

	<h1>Quick Launch Home</h1>



	{{Form::open(['action'=>'Cornernote\Quicklaunch\Controllers\HomeController@postProcess', 'method'=>'post'])}}
		
		<div class="form-group">
			{{Form::label('resource_name', 'Resource Name (singular)')}}
			{{$errors->first('resource_name', '<div class="alert alert-danger">:message</div>')}}
			{{Form::text('resource_name', Input::old('resource_name', $resource_name), ['class'=>'form-control'])}}
		</div>

		<div class="form-group">
			{{Form::label('namespace', 'Namespace')}}
			{{$errors->first('namespace', '<div class="alert alert-danger">:message</div>')}}
			{{Form::text('namespace', Input::old('namespace', $namespace), ['class'=>'form-control'])}}
		</div>




		<div class="form-group">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::label('field_names[]', 'Field Name')}}
				{{$errors->first('field_names', '<div class="alert alert-danger">:message</div>')}}
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::label('field_type[]', 'Field Type')}}
				{{$errors->first('field_type', '<div class="alert alert-danger">:message</div>')}}
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_names[]', Input::old('field_names', $field), ['class'=>'form-control'])}}
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4">
				{{Form::text('field_types[]', Input::old('field_types', $field), ['class'=>'form-control'])}}
			</div>
		</div>
			
		</div>
	
		{{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
		
	{{Form::close()}}
	
	
	
@stop
