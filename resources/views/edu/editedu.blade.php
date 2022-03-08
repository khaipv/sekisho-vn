@extends('master')
@section('content')

     <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Edit Education   </b>
                   <!-- approve.approveDetail  -->
                </div>
       @if(count($errors))
      <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
<div class="container">
<form method="post" action="{{action('EduController@update', $edu->id)}}">
   <div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" BORDER="0">
   <input name="_method" type="hidden" value="PATCH">
   {{csrf_field()}}
		  <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td class="col-md-2" >
        {{$candi->code}}
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Name (First Middle Last Name )</font></strong></td> 
            <td class="col-md-2" >
        {{$candi->firstName}}&nbsp;{{$candi->midleName}}&nbsp;{{$candi-> lastName}}
      </td> 
    </tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2"  >
			 <input type="date" name="date"  style="width : 35%" required="true" value="{{ $edu -> date }}"  class="form-control" />    
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Education </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			 <input type="text" class="form-control" name="education" required="true" id="education"  maxlength="200"
       value="{{$edu->education}}">  
	       <input type="hidden" class="form-control" name="canID" id="canID" 
         value="{{$candi->id}}"  >
			</td> 
		</tr>
    	    </table>
 </div>
</div>
 
</div> 
<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
   <table  >
  <tr >
            <td    class="col-md-1">  <button style="margin-left: 10px"  onclick="window.close();">Close</button></td> 
            <td    class="col-md-1">
            </td>
            <td class="col-md-2" >
         <button style="margin-left: 10px" type="submit"  >Update</button>
      </td> 
    </tr>
  </table>

  </div>
</div>
</div>
  </form>

 


@endsection