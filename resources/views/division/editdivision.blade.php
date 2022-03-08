@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    Edit Division  </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
<form method="post" action="{{action('DivisionController@update', $id)}}">
 {{ csrf_field() }}
    
   <div class="col-sm-2"  > </div>
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
 <div class="col-sm-11" style="margin-top: -3%" > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
    <p align="right"> <a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;  </a> 
          </p>
  <table class="table table-hover table-bordered" >
		<tr >
			<label for="lgFormGroupInput"  class="col-form-label col-form-label-sm" style="color: #556B2F"">{{$division->clientCode}}&nbsp;{{$division->clientName}}</label>
		</tr> 
		<tr >
		 <input name="_method" type="hidden" value="PATCH">
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td colspan="3" class="col-md-2" >
				 {{$division->code}}
          
		</tr>
    <tr>
        </td> 
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Division's Name </font><font style="color:red">*</font></strong></td> 
            <td colspan="3" class="col-md-2">
      {{csrf_field()}}
           <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="Division's Name" name="divisionname" value="{{$division->divisionname}}">
        <span class="text-danger">{{ $errors->first('divisionname') }}</span>
              </td>    
    </tr>
		<tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Introduce </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			 {{csrf_field()}}
		<select class="form-control" id="introduceList" style="width: 50%" name="introduce">
           <option value="{{$division->introduce}}">{{$division->introduceName}}</option>
         @foreach($introduce as $instroduce)
         @if($instroduce->type =='Introduce')
                    <option value="{{$instroduce->code}}">{{ $instroduce->name }}</option>
         @endif
         @endforeach
            <option  > </option>
		</select>
          </td>
           
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Customer's Importance </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2">
			{{csrf_field()}}
            <select class="form-control"   name="rate" id="rateList">
         <option value="{{$division->rate}}">{{$division->rate2}}</option>
           @foreach($master as $master)
         @if($master->type =='Imp')
                    <option value="{{$master->code}}">{{ $master->name }}</option>
         @endif
         @endforeach
         <option  > </option>
			</select>
              </td>      
		</tr>
        <tr >
             
             
		</tr>  
    <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status</font></strong></td> 
            <td class="col-md-2" >
      <select   class="form-control name="status" >
               <option value="" >--------</option>
               
                <?php 
               foreach($status as $status){
                   if($status->type =='clientStatus') {
                echo "<option value='".$status->val."'";
                 if( $status->val  == $division->status )  echo " selected='selected'  ";
                echo ">".$status->name."</option>";
              }}
            ?>

            </select>
      </td> 
   <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">PIC(SEKISHO) </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
       {{csrf_field()}}
    <select class="form-control"  name="pic_s" id="pic_slst">
           <option value="{{$division->pic_s}}">{{$division->name_s}}</option>
             @foreach($users as $users)
         @if($users->id != $division->pic_s)
                    <option value="{{$users->id}}">{{ $users->name }}</option>
         @endif
         @endforeach
          
    </select>
          </td> 
    </tr>
		<tr >
         
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Condition Of Trade</font></strong></td> 
            <td colspan="3">
			 {{csrf_field()}}
         <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="condition" 
        value="{{$division->condition}}">
              </td>      
		</tr>

    <tr >

      <td   class="col-md-1"><strong><font color="556B2F">[Address・Tel]</font></strong></td> 
             
     </tr>
     <tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Country </font><font style="color:red">*</font></strong></td> 
      <td colspan="3" >
 {{csrf_field()}}
<select class="form-control" id="national_id2"   name="national">
         <option value="{{$division->national_ID}}">{{$division->nationName}}</option>
          @foreach($national as $national)
           @if($national->Id != $division->national_ID)
              <option value="{{$national->Id}}">{{ $national->CommonName }}</option>
           @endif
          @endforeach
      </select>
    </td>  
  </tr> 
  
  <tr >
     <td  style="background: #7EC0EE" class="col-md-1"  ><strong><font color="556B2F">Province</font></strong></td> 
      <td class="col-md-2">
 {{csrf_field()}}
  <select  class="form-control"  name="province">
          <option value="{{$division->provinceId}}">{{$division->provinceName}}</option>
          @foreach($province as $province)
             @if($province->Id != $division->provinceId)
              <option value="{{$province->Id}}">{{ $province->Name }}</option>
               @endif
          @endforeach
  </select> 
</td >
<td  style="background: #7EC0EE" class="col-md-1" ><strong><font color="556B2F">District</font></strong></td>
<td>
   <select class="form-control"   name="district">
          <option value="{{$division->districtId}}">{{$division->districtName}}</option>
           @foreach($district as $district)
             @if($district->Id != $division->districtId)
              <option value="{{$district->Id}}">{{ $district->Name }}</option>
             @endif
          @endforeach
      </select>
    </td>   
</tr>




<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Other </font><font style="color:red">*</font></strong></td> 
  <td colspan="3" class="col-md-5" >
<input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="address"
value="{{$division->address}}">
</td> 
        
</tr>
<tr >
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Tel </font><font style="color:red">*</font></strong></td> 
            <td colspan="3" >
			{{csrf_field()}}
				<input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="mobile" 
         value="{{$division->mobile}}"   onkeypress='validate(event)'>
          </td> 
        </tr >
        <tr >
        <td  class="col-md-1"><strong><font color="556B2F">[Work Condition]</font></strong></td> 
        </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Work Time</font></strong></td> 
            <td class="col-md-2" >
      
        <input type="text" style="width: 30%;margin-top: 8px" id="worktime1"  name="worktime1" 
         value="{{$division->worktime1}}"   >~
          <input type="text" style="width: 30%" id="worktime2"  name="worktime2" 
         value="{{$division->worktime2}}"   >
          </td> 
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Holidays</font></strong></td> 
            <td class="col-md-2">
      <input type="text" class="form-control form-control-lg" id="holidays" 
       name="holidays"        value="{{$division->holidays}}">
              </td>      
    </tr>
     <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
            Review Times</font></strong></td> 
            <td class="col-md-2" >
      
        <input type="text" class="form-control form-control-lg" id="review" 
       name="review"        value="{{$division->review}}">
          </td> 
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
             Year Bonus</font></strong></td> 
            <td class="col-md-2">
      <input type="text" class="form-control form-control-lg" id="yearBonus" 
       name="yearBonus"        value="{{$division->yearBonus}}">
              </td>      
    </tr>
     <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
            Welfare</font></strong></td> 
            <td colspan="3" >
      
        <input type="text" class="form-control form-control-lg" id="welfare" 
       name="welfare"        value="{{$division->welfare}}">
          </td> 
          <tr >
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
             Other Welfare</font></strong></td> 
            <td colspan="3">
      {{-- <input style="line-height: 34%;" type="text" class="form-control form-control-lg" id="otherWelfare" 
       name="otherWelfare"  value="{{$division->otherWelfare}}"> --}}
       <textarea id="otherWelfare"  name="otherWelfare" rows="4" class="form-control" style="resize: none;" value="{{$division->otherWelfare}}">{{$division->otherWelfare}}</textarea>
              </td>      
    </tr>

           
      <tr  >
      </tr>
		

      <tr >
           
               
      </tr>
    </table>
 </div>
</div>
 
</div> 
<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9" style="top: -20px;" >
  <table class="table table-hover table-bordered"  >


	    </table>
 </div>
</div> 
</div>

<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  style="top: -40px;">
  <table class="table table-hover table-bordered" >
         

      </table>
 </div>
</div> 
</div>

    </div>

  
    
  <div class="content">
      
      <button type="submit" class="btn btn-default" style=" background-color: #DCDCDC;">Update</button>

    </div>
  </form>
</div>
</div>
<br>
<div  class="container">

   <div >
    <div class="content">
 <div  class="row" >
   <hr  width="120%" style="margin-left: -10%"  align="center" /> 
   <div  align="left" style="margin-left: 1%;margin-bottom: -3%" >
    <b style="font-size: 18px" >PIC List   </b>
                  

                </div>

      <div  align="right"  style="margin-right:   1%">
      <td>
    <form method="GET" action="{{ url('addnewpic') }}">
           
           
      <td> <input  id="companyinputid" name="division2" type="hidden"  value="{{$division->id}}"></td>
                
           
<div  align="right">
                    <button class="btn btn-default" style=" background-color: #DCDCDC;">Add New PIC</button>
      </div>
           </div>
        </form> 
        </td>
  </div>
  <table class="table table-hover table-bordered" >

    <thead >
            <td nowrap><strong>PIC's Name</strong></td> 
           
            <td nowrap><strong>Department</strong></td>
            <td nowrap><strong>Position</strong></td>
             <td nowrap><strong>Email</strong></td>
            <td nowrap><strong>Mobile</strong></td>
            
    </thead>
    <tbody>
    @foreach($pic as $value)
        <tr>
           
          <td>
            @if (strlen ($value->firstName) >0) 
               <a href="{{action('PicController@show', $value->id)}}">{{$value->firstName}}&nbsp;{{$value->midleName}}&nbsp;{{$value-> lastName}}</a>
            @else
               <a href="{{action('PicController@show', $value->id)}}">{{$value->firstNameJ}}{{$value->midleNameJ}}{{$value-> lastNameJ}}</a>
            @endif

           </td>
           
            <td>{{ $value->department }}</td>    
        <td>{{ $value->position }}</td> 
         <td>{{ $value->email }}</td>
        <td>{{ $value->mobile }}</td>       
        </tr>
    @endforeach
    <tr>
      <td colspan="13">
        <div class="pagination">{!! str_replace('/?', '?', $pic->render()) !!}</div>      
      </td>
    </tr>
    </tbody>

</table>
<iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
  
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
            
          // 
          } else
          { document.getElementById("provinceID").style.display="block";
           
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
var optionValues =[];
$('#introduceList option').each(function(){
   if($.inArray(this.value, optionValues) >-1){
      $(this).remove()
   }else{
      optionValues.push(this.value);
   }
});
var advertise =[];
$('#advertiseList option').each(function(){
   if($.inArray(this.value, advertise) >-1){
      $(this).remove()
   }else{
      advertise.push(this.value);
   }
});

var rate =[];
$('#rateList option').each(function(){
   if($.inArray(this.value, rate) >-1){
      $(this).remove()
   }else{
      rate.push(this.value);
   }
});
</script>

@endsection