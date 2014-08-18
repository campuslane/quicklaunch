@extends(Theme::layout('default'))

@section('title', 'Add a $MODEL$')

@section('main')

	<h1>Add a $MODEL$</h1>
	{{Form::open(['route'=>'$RESOURCE$.store', 'method'=>'post'])}}
		
		@include('$COLLECTION$.form_fields')
	
		{{Form::submit('Add', ['class'=>'btn btn-primary'])}}
		
	{{Form::close()}}

@stop
