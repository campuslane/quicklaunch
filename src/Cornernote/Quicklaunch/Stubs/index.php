@extends(Theme::layout('default'))

@section('title', '$COLLECTIONUPPER$')

@section('main')


	<h1>$COLLECTIONUPPER$</h1>
	<a href="{{route('$RESOURCE$.create')}}" class="pull-right btn btn-primary">New $MODEL$</a>

	@if(count($$COLLECTION$))

		<table class="table table-bordered">
		@foreach($$COLLECTION$ as $$SINGLE$)
			<tr>
				<td>{{$$SINGLE$->$MAINFIELD$}}</td>
				<td>
					<a href="{{route('$COLLECTION$.edit', $$SINGLE$->id)}}">Edit</a> | 
					<a href="{{route('$COLLECTION$.show', $$SINGLE$->id)}}">Show</a> |
					<a class="delete" data-id="{{$$SINGLE$->id}}" href="#">Delete</a>
				</td>
			</tr>
		@endforeach
		</table>

	@else
		No $COLLECTION$ added yet.
	@endif

	<script>
		$(document).ready(function(){

			$(document).on('click', '.delete', function(e){
				e.preventDefault();
				if (confirm('Are you sure you want to delete?')) {
					var id = $(this).attr('data-id');
				    $.ajax({
				      type: "POST",
				      data: {_method:'delete'}, 
				      url: '/$COLLECTION$/' + id,
				      success: function(result) {
				        location.reload(true);
				      }
				    });
				}
		  	});

		});
	</script>

@stop
