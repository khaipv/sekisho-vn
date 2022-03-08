@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Create Division   </b>
                   <!-- division.createdivision  -->
                </div>
<div class="container">
  <form method="post" action="{{url('division')}}">
      

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

    </div>
  <div class="row">

 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
   
  <table class="table table-hover table-bordered" >
              <div class="form-group row">
       
       <label for="lgFormGroupInput" class="col-form-label col-form-label-sm">{{$client->Code}}&nbsp;{{$client->companyname}}</label>
   
     </div>
		<tr >
					<td nowrap="true" style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Division's Name *</font></strong></td> 
					<td colspan="3" class="col-md-2" >
						<input type="text" class="form-control form-control-lg" value="{{ old('divisionname')}}" id="lgFormGroupInput" placeholder="Division's Name" name="divisionname" >
				  </td> 
					  
		</tr>
    <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Tel*</font></strong></td> 
          <td class="col-md-2">
            <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" value="{{ old('mobile')}}" name="mobile" 
         onkeypress='validate(event)'>
            </td> 
              <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Customer's Importance*</font></strong></td> 
        <td class="col-md-2" >
          <select class="form-control"   name="rate">
       <option  > </option>
        @foreach($master as $master)
       @if($master->type =='Imp')
            <option value="{{$master->code}}"  @if( session('forms.ssCode')  ==$master->code ) selected="selected" @endif >{{ $master->name }}</option>
       @endif
       @endforeach
      </select>
        </td>    
    </tr>
		<tr >
			
				 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Introduce *</font></strong></td> 
				<td class="col-md-2">
			 <select class="form-control"  style="width: 45%"  name="introduce">
		  <option  > </option>
			@foreach($introduce as $instroduce)
			 @if($instroduce->type =='Introduce')
						<option value="{{$instroduce->code}}"  @if( session('forms.ssIntroduceCode')  ==$instroduce->code ) selected="selected" @endif  >{{ $instroduce->name }}</option>
			 @endif
			 @endforeach
     </select>
              </td>      
		</tr>
		<tr >
       <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status</font></strong></td> 
            <td class="col-md-2" >
      {{csrf_field()}}
           <select name='status'  style="width: 55%; height: 28px;">
            <option value="">  <?php if(empty($status)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($status as $en){
             if($en->type =='clientStatus'){
                echo "<option value='".$en->id."'";
                 if( $en->val == old('status') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select>
      </td> 
   
           
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">PIC(SEKISHO)*</font></strong></td> 
            <td class="col-md-2">
         <select class="form-control"   name="pic_s" id="pic_slst">
       
             @foreach($users as $users)
        
                    <option value="{{$users->id}}"  @if( session('forms.ssUsID')  ==$users->id ) selected="selected" @endif     >{{ $users->name }}</option>
        
         @endforeach
          
		</select>
              </td>      
		</tr>
    <tr >
          <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Work Time</font></strong></td> 
           <td class="col-md-1"  >
            <input type="text" style="width: 30%"  value="{{ old('worktime1')}}" 
            id="lgFormGroupInput" name="worktime1" >
            ~

             <input type="text" style="width: 30%"  value="{{ old('worktime2')}}"
              id="lgFormGroupInput" name="worktime2" >
          </td> 
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Holiday</font></strong></td> 
          <td class="col-md-2">
            <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" value="{{ old('holidays')}}" name="holidays" 
         >
            </td>      
    </tr>
      <tr >
          <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Review Times</font></strong></td> 
           <td class="col-md-1"  >
            <input type="text" class="form-control form-control-lg"   value="{{ old('review')}}" 
            id="review" name="review" >
           

            
          </td> 
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Year Bonus</font></strong></td> 
          <td class="col-md-2">
            <input type="text" class="form-control form-control-lg" id="yearBonus" value="{{ old('yearBonus')}}" name="yearBonus" 
         >
            </td>      
    </tr>
     <tr >
          <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Welfare</font></strong></td> 
           <td class="col-md-1"  >
            <input type="text" class="form-control form-control-lg"   value="{{ old('welfare')}}" 
            id="welfare" name="welfare" >
           

            
          </td> 
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Other Welfare</font></strong></td> 
          <td class="col-md-2">
            <input type="text" class="form-control form-control-lg" id="otherWelfare" value="{{ old('otherWelfare')}}" name="otherWelfare" 
         >
            </td>      
    </tr>
		
				<tr >

           
		 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Condition Of Trade</font></strong></td> 
            <td class="col-md-2" >
				<input type="text" class="form-control form-control-lg" id="lgFormGroupInput" value="{{ old('condition')}}"  name="condition" >
          </td> 
		  
    
		</tr>
     <tr>
      
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Country *</font></strong></td> 
            <td class="col-md-2" >
                  <select class="form-control"   name="national">
                @foreach($national as $national)
                    <option value="{{$national->Id}}" @if( session('forms.ssNaId')  ==$national->Id ) selected="selected" @endif      >{{ $national->CommonName }}</option>
                @endforeach
            </select>
          </td> 
          <td  style="background: #7EC0EE" 
              class="col-md-1">
              <div style="width: 100px" >
              <strong><font color="556B2F">
            Province */District</font></strong>
          </div>
          </td> 
           <td   id="provinceID" >
       {{csrf_field()}}
        <select    name="province">
                @foreach($province as $province)
                    <option value="{{$province->Id}}"  @if( session('forms.ssProId')  ==$province->Id ) selected="selected" @endif     >{{ $province->Name }}</option>
                @endforeach
            </select>
          </br>
           </br>
             <select     name="district">
                @foreach($district as $district)
                    <option value="{{$district->Id}}"  @if( session('forms.ssDisId')  ==$district->Id ) selected="selected" @endif     >{{ $district->Name }}</option>
                @endforeach 
            </select>
          </td> 

    </tr>
      <tr >
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
             Other *&emsp;&emsp;&emsp;&emsp;</font></strong></td> 
            <td colspan="3" class="col-md-4">
         <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" value="{{ old('address')}}"  name="address">
              </td>      
    </tr>
	    </table>
 </div>
</div>
 
</div> 
	<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  style="top: -20px;">
 
 </div>
</div>
 
</div> 
 <div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9" style="top: -40px;">
   
 </div>
</div> 
</div>
  

<input name="ivbId" type="hidden"  value="{{$id}}">

    <div class="form-group row">
      <div class="col-md-5"></div>
      <input type="submit" class="btn" value="Create New Division">
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
            
          // 
          } else
          { document.getElementById("provinceID").style.display="block";
            document.getElementById("districtID").style.display="block";
            
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

function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\+|\/|\-/;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
</script>
@endsection