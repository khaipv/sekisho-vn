@extends('master2')
@section('content')
<div class="container">
	<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
  </div> <!-- end .flash-message -->
	{!! Session::forget('success') !!}
	<br />
	<a href="{{ URL::to('downloadExcel/xlsx/all') }}"><button class="btn btn-success">Download All</button></a>
	<a href="{{ URL::to('downloadExcel/xlsx/m1') }}"><button class="btn btn-success">Download Magazine1</button></a>
	<a href="{{ URL::to('downloadExcel/xlsx/m2') }}"><button class="btn btn-success">Download Magazine2</button></a>
	<a href="{{ URL::to('downloadCandidate/xlsx/m2') }}"><button class="btn btn-success">Download Candidate</button></a>
		<a href="{{ URL::to('downloadDiv/xlsx/m2') }}"><button class="btn btn-success">Download Division</button></a>
				<a href="{{ URL::to('downloadOrder/xlsx/m2') }}"><button class="btn btn-success">Download Order</button></a>
	
	
	<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="file" name="import_file" />
		<button class="btn btn-primary">Import File</button>
	</form>
</div>
  
   
@endsection