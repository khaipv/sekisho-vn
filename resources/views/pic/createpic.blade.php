@extends('master')
@section('content')
    <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    Create PIC   </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
     
  <form method="post" action="{{url('pic')}}">
 {{csrf_field()}}

     <div class="form-group row">
       <div class="col-sm-2"  > </div>
  <div class="col-sm-5" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-form-label col-form-label-sm">{{$branch->companyname}} &#47; &nbsp;{{$branch->divisionname}}</label>
    </div>
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


    </div>
   <div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" >
   
<input type="hidden" name="_method" value="POST">

		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			PIC's Name *</font></strong></td> 
            <td class="col-md-3" colspan="3" >
			 <input style="width: 33%" id="FirstName" name="firstName" value="{{ old('firstName') }}" type="text"  placeholder="First Name"  />
             <input style="width: 32%" id="MiddleName" name="midleName" value="{{ old('midleName')}}" type="text" placeholder="Middle Name " />
             <input style="width: 32%" id="LastName" name="lastName" value="{{ old('lastName')}}" type="text" placeholder="Last Name" />
			</td> 
				</tr>
				<tr >
			 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			 PIC's Name （JP/VN）</font></strong></td> 
             <td class="col-md-3" colspan="3" >
			            <input style="width: 33%" id="FirstName" name="firstNameJ" value="{{ old('firstNameJ')}}"  type="text"   />
                        <input style="width: 32%" id="FirstName" name="midleNameJ" value="{{ old('midleNameJ') }}" type="text"   />
                        <input style="width: 32%" id="LastName" name="lastNameJ" value="{{ old('lastNameJ')}}" type="text"   />
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Department</font></strong></td> 
            <td class="col-md-1"  >
			<input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="department" value="{{ old('department')}}">
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Position</font></strong></td> 
            <td class="col-md-1"  >
			<input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="position" value="{{ old('position')}}" >
			</td> 
			
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Email *</font></strong></td> 
            <td class="col-md-1" >
			 <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="email" value="{{ old('email')}}" >
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			TEL *</font></strong></td> 
            <td class="col-md-1"  >
			<input  class="form-control form-control-lg" id="tell" name="tell" type="text" onkeypress='validate(event)' value="{{ old('tell') }}"   />
			</td> 
			
		</tr>
				<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Mobile</font></strong></td> 
            <td class="col-md-1"  >
			 <input style="width: 90%" id="mobile" name="mobile"  type="text" onkeypress='validate(event)' value="{{ old('mobile')}}"  />
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Status Flag *</font></strong></td> 
            <td class="col-md-1" >
			<select class="form-control" value="{{ old('status')}}"  style="width: 50%" name="status" 
      id="status">
            <option value="〇">〇</option>
            <option value="X">X</option>
             
        </select>
			</td> 
			
		</tr>
     <tr > 
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Mail Magazine *</font></strong></td> 
            <td class="col-md-1" >
      <select class="form-control" value="{{ old('magazine')}}"  style="width: 100%" 
      name="magazine" id="magazine">
            <?php 
            foreach($magazine as $magazine)
            {
             if($magazine->type =='magazine'){
                echo "<option value='".$magazine->code."'";
                 if( $magazine->code  == old('magazine') ) echo " selected='selected'  ";
                echo ">".$magazine->name."</option>";
                  
                  }
               
            }
              ?>
        </select>
      </td> 
    </tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Note</font></strong></td> 
            <td class="col-md-7" colspan="7" >
			        <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="add"
        value="{{ old('add')}}"  >
			</td> 
		
		</tr>
	    </table>
 </div>
</div>
 
</div> 


     

<input name="division_id" type="hidden"  value="{{$id}}">  
        <div class="content">
  
      <input type="submit" class="btn btn-default" style=" background-color: #DCDCDC;" value="Create New PIC">
	  <br>
	  <br>
	  <br>
    </div>
  </form>
</div>
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
if(regexJ.test(key)) {
     theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
}
else {
    console.log("No Japanese characters");
}

   }

 $("select[name='status']").change(function()
      {
        var selectedValue = document.getElementById("status").value;
       if (selectedValue=='X') {

       document.getElementById("magazine").value = "3";
       }
      });

</script>
@endsection