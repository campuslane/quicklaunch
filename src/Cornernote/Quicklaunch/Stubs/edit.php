@extends(Theme::layout('default'))

@section('title', 'Edit $MODEL$')

@section('main')

	<h1>Edit $MODEL$</h1>
	{{Form::open(['route'=>['$RESOURCE$.update', $$SINGLE$->id], 'method'=>'put'])}}
		
		@include('$COLLECTION$.form_fields')
	
		{{Form::submit('Update', ['class'=>'btn btn-primary'])}}
		
	{{Form::close()}}

@stop