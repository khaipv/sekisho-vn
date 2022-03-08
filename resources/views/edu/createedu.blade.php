@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Add Education   </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
  <form method="post" action="{{url('education')}}" name="formid"  onsubmit="return do_something()">
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


<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" >
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
            <td class="col-md-2" >
			 {{csrf_field()}}
			 <input type="date" name="datecr"   style="width : 35%" min="1990-01-01" required="true"
      max="2030-01-01"   class="form-control" /> 
         <input type="hidden" class="form-control" name="canID" id="canID" 
         value="{{$id}}"  >
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Education </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			 <input type="text" class="form-control" name="educationcr" required="true" id="education" maxlength="200"
         >        
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
            <td    class="col-md-1">  <button style="margin-left: 10px" type="button" onclick="window.close();">Close</button>
        
            </td> 
            <td    class="col-md-1">
            </td>
            <td class="col-md-2" >
              <button style="margin-left: 10px" type="submit"  >Create</button>
      
      </td> 
    </tr>
  </table>

  </div>
</div>
</div>

 
     <div align="right">    
            
</div>
  </form>
</div>
<script type="text/javascript">
//   $('form').on('submit', function(event) {
//     event.preventDefault();
   
    
//     this.submit(); //now submit the form
//     window.opener.location.reload();
//       document.getElementById("refeshbtn").click();
// });


function do_something(){
  // window.opener.location.reload();

  //window.close();
   // Do your stuff here
   return true; // submit the form
   window.close();

  
}
  </script>
@endsection