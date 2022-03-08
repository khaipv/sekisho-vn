@extends('timemaster')
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
	<a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>
	<a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>
	<a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a>
	<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
      <label for="lgFormGroupInput"  >Month-Year</label>  <input type="month" id="start" required name="month">
		<input type="file" name="import_file" />
		<button class="btn btn-primary">Import File</button>
	</form>

</div>
<script>
	 document.body.addEventListener('keyup',keyUpHandler,false);
    
    function keyUpHandler(e){  
        var evt = e || window.event;
        var target = evt.target || evt.srcElement;
        var key = e.keyCode || e.which;   

        //check if it is our required input with class test input
        if(target.className.indexOf('test-input') > -1){
            insertTimingColor(target,key)
        }

    }

    function insertTimingColor(element,key){
        var inputValue = element.value;
        if(element.value.trim().length == 2 && key !== 8){
            element.value = element.value + ':';
        }
    }
         function validate(evt) 
     {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
   var regex = /[0-9]|\+/;
  var regexJ = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();

  }
    var evt = e || window.event;
        var target = evt.target || evt.srcElement;
        var key = e.keyCode || e.which;   

        //check if it is our required input with class test input
        if(target.className.indexOf('test-inputsss') > -1){
            insertTimingColor(target,key)
        }
if(regexJ.test(key)) {
     theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
}
else {
    console.log("No Japanese characters");
}

   }

	</script>
  
   
@endsection