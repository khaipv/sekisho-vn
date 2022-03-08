@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Edit PIC   </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
<form method="post" action="{{action('PicController@update', $id)}}" >
 <input name="_method" type="hidden" value="PATCH">
  <input name="_method" type="hidden" value="POST">

  <div class="form-group row">
       <div class="col-sm-2"  > </div>
       <label for="smFormGroupInput" class=" col-form-label col-form-label-sm">{{$pic->clientName}}  &#95; {{$pic->divisionName}}</label>
 
  </div>
    <div class="form-group row">
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
     {{csrf_field()}}
   <div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" >
   <p style="margin-top: -2.4%" align="right">
<a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;  </a> 
 </p>
<input type="hidden" name="_method" value="PATCH">
<input type="hidden" name="_method" value="POST">
 {{ csrf_field() }}
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			PIC's Name </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-3" colspan="3" >
                         <input style="width: 33%" id="FirstName" name="firstName" placeholder="First Name" type="text" value="{{$pic->firstName}}" />
                        <input style="width: 32%" id="MiddleName" name="midleName" placeholder="Middle Name" type="text" value="{{$pic->midleName}}" />
                        <input style="width: 32" id="LastName" name="lastName" placeholder="Last Name" type="text" value="{{$pic->lastName}}" />
			</td> 
				</tr>
				<tr >
			 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			 PIC's Name （JP/VN）</font></strong></td> 
             <td class="col-md-3" colspan="3" >
              <input style="width: 33%" id="FirstName" name="firstNameJ" type="text" value="{{$pic->firstNameJ}}" />
              <input style="width: 32%" id="LastName" name="midleNameJ"  type="text" value="{{$pic->midleNameJ}}" />
              <input style="width: 32" id="LastName" name="lastNameJ"  type="text" value="{{$pic->lastNameJ}}" />
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Department</font></strong></td> 
            <td class="col-md-1"  >
			<input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="department" value="{{$pic->department}}" >
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Position</font></strong></td> 
            <td class="col-md-1"  >
			 <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="position"  value="{{$pic->position}}" >
			</td> 
			
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Email </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-1" >
			 <input type="text"  class="form-control form-control-lg" id="lgFormGroupInput" placeholder="email" name="email"
         value="{{$pic->email}}">
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			TEL </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-1"  >
			 <input id="tell" name="tell" type="text" onkeypress='validate(event)'  value="{{$pic->tell}}" />
        
			</td> 
			
		</tr>
				<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Mobile</font></strong></td> 
            <td class="col-md-1"  >
			 <input  id="mobile" name="mobile"  onkeypress='validate(event)'  type="text" value="{{$pic->mobile}}" />
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Status Flag </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-1" >
		  <select class="form-control"   name="status">
            <option value="{{$pic->status}}">{{$pic->status}}</option>
            <option value="〇">〇</option>
            <option value="X">X</option>
             
		</select>
			</td> 
			
		</tr>
    <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Mail Magazine </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-1" >
      <select class="form-control"   name="magazine">
            @foreach($magazine as $magazine)
            @if($magazine->type =='magazine')
                    <option value="{{$magazine->code}}"  @if( $magazine->code == $pic->magazine ) selected="selected" @endif     >{{ $magazine->name }}</option>
                     @endif
                @endforeach
             
    </select>
      </td> 
    </tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Note</font></strong></td> 
            <td class="col-md-7" colspan="7" >
			       <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="add" value="{{$pic->add}}"
         >
			</td> 
		
		</tr>
	    </table>
 </div>
</div>
 
</div> 
  
    
  <div class="content">
      <input type="hidden" name="_method" value="PATCH">
      <button  type="submit" class="btn ">Update</button>
           
    </div>
  </form>
</div>
</div>
<br>

<iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>

 <script type="text/javascript">
    var url = "{{ url('/showDistrict') }}";
    var urlWard = "{{ url('/showWard') }}";
    $("select[name='province']").change(function(){
        var province_id = $(this).val();
        var token = $("input[name='_token']").val();

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                id: province_id,
                _token: token
            },
            success: function(data) {
              //   alert(url);
                $("select[name='district'").html('');
                $.each(data, function(key, value){
                    $("select[name='district']").append(
                        "<option value=" + value.Id + ">" + value.Name + "</option>"
                    );
                });
            }
        });
    });
        $("select[name='district']").change(function(){
        var district_id = $(this).val();
        var token = $("input[name='_token']").val();

        $.ajax({
            url: urlWard,
            method: 'POST',
            data: {
                id: district_id,
                _token: token
            },
            success: function(data) {
               
                $("select[name='slbWard'").html('');
                $.each(data, function(key, value){
                        $("select[name='slbWard']").append(
                        "<option value=" + value.Id + ">" + value.Name + "</option>"
                    );
                });
            }
        });
    });
        $("select[name='national']").change(function(){
          var national_id = $(this).val();

          if (national_id >1) {
            //alert(national_id);
            document.getElementById("provinceID").style.display="none";
            document.getElementById("districtID").style.display="none";
            document.getElementById("wardID").style.display="none";
          // 
          } else
          { document.getElementById("provinceID").style.display="block";
            document.getElementById("districtID").style.display="block";
            document.getElementById("wardID").style.display="block";
        }

        });
   $( function() {
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1960:1999',
        dateFormat : 'yy-mm-dd',
        defaultDate: '01-01-1985'
    });
  } );
   document.getElementById("myFrame").onload = function() {myFunction()};

function myFunction() {
  var nationalflag= $("select[name='national']").val();
  // alert(nationalflag);
  if (nationalflag>1){
          //  alert(nationalflag);
            document.getElementById("provinceID").style.display="none";
            document.getElementById("districtID").style.display="none";
            
          // 
          } else
          { document.getElementById("provinceID").style.display="block";
            document.getElementById("districtID").style.display="block";
            
        }
}
var exist = {};
$('select > option').each(function() {
    if (exist[$(this).val()])
        $(this).remove();
    else
        exist[$(this).val()] = true;
});
   function validate(evt) 
     {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
    var regex = /[0-9]|\+/;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  } }E
</script>

@endsection