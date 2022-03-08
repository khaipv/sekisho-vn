@extends('master')
@section('content')
  
              <div style="margin-top: -25px; float: left;" >
                   <b style="font-size: 18px"> &nbsp;&nbsp;Candidate List </b>
                    <!-- udp.udp.blade -->
                </div>
                <br>

   <form method="GET" action="{{ url('orderCandiSearch') }}">
   <input name="_method" type="hidden" value="PATCH">
  <div class="row">
 <p style="margin-top: -2.4%" align="right">
<a href="{{action('OrderController@show', $order->id)}}">Back &nbsp;&nbsp;&nbsp;  </a> 
 </p>
       <div class="col-sm-8"  > 
       <div class="content ">
  <table class="table table-hover table-bordered" >
    {{csrf_field()}}
<input name="_method" type="hidden" value="PATCH">
  <input name="orderID" type="hidden" value="{{$order->id}}">  
   <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Candidate's Name</font></strong></td> 
            <td colspan="1" class="col-md-1" >
       <input type="text"   id="companyFistsrc" name="canFirstsrc" placeholder="First Name" 
              value="{{ old('canFirstsrc') }}">
      </td> 
            <td colspan="1" class="col-md-1" >
        <input type="text"   id="companyMidlesrc" name="canMidlesrc"  placeholder="Midle Name" 
                    value="{{ old('canMidlesrc') }}">
      </td> 
            <td colspan="1" class="col-md-1" >
      <input type="text"   id="companyLastsrc" name="canLastsrc" placeholder="Last Name" 
                    value="{{ old('canLastsrc') }}">
      </td> 
	  <td rowspan="2" class="col-md-1" >
	  <input type="submit" value="Search"> 
	  </td> 
    </tr>
	<tr>
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">No, Email, Phone</font></strong></td> 
            <td colspan="1" class="col-md-1" >
       <input type="text"   id="canNoFrom" name="canNoFrom" 
              value="{{ old('canNoFrom') }}" style="width: 40%">
              ~
              <input type="text"   id="canNoTo" name="canNoTo"  
              value="{{ old('canNoTo') }}" style="width: 40%">

      </td> 
            <td colspan="1" class="col-md-1" >
        <input type="text"   id="canEmail" name="canEmail"  placeholder="Email" 
                    value="{{ old('canEmail') }}">
      </td> 
            <td colspan="1" class="col-md-1" >
      <input type="text"   id="canPhone" name="canPhone" placeholder="Phone" 
                    value="{{ old('canPhone') }}">
      </td> 
 </tr>
 </table>

   </div> 
     <div class="col-sm-2"  ></div> 
</div>
 </div>

 
    </form>   
   
    <form method="get" action="{{url('updateOrderCandi')}}" autocomplete="off" 
    onsubmit="return validateForm()" >
 {{csrf_field()}}
<input name="_method" type="hidden" value="PATCH">

        
       <div class="col-sm-12"  > 
       <div class="content ">
        <div align="center">
          @if(!is_null($candilist) && count($candilist) >0)
        <button type="submit">Add</button>     
         @endif   
</div>
@if(!is_null($candilist))
<div class="ex1">
  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
         <tr>
          <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Select</strong></td>
            <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Code</strong></td> 
            <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Name</strong></td>
           
            <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Birthday</strong></td>
            <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Status</strong></td>
            <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Introduce Date</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" nowrap><strong>Enter Date</strong></td>
            <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Note</strong></td>
			<td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Remove</strong></td>
     </tr>
   
    </thead>
    <tbody>
        <input name="orderID2" type="hidden"  value="{{$order->id}}">  

    @foreach($candilist as $value)
        <tr>
           <td > 
               <input type="checkbox" name="accept[]" id="{{ $value->caid }}"  value="{{ $value->caid }}" 
               
                  <?php 
             
             if(!is_null($value->ocID ) && $value->ocOrderID==$order->id){
                echo "checked";
               
                  
                  }
              
            ?>
          value="{{ $value->id }}"> 
              </td> 
            <td wrap  >{{ $value->code }} </td>
			  <td nowrap="true"  >
           <a href="{{action('CandidateController@show', $value->idCandidates)}}">{{$value->firstName}}&nbsp;{{$value->midleName}}&nbsp;{{$value-> lastName}}</a>
          
        </td>
			  <td nowrap="true"  >{{ $value->birth }} </td>
        <td nowrap="true">
          <select class="form-control"   name="canStatus[]" >
            @if(!is_null($statuslist))
                @foreach($statuslist as $status)
                {{-- @if( $status->type=='canoType') --}}
                    <option value="{{$status->id}}"  <?php 
                    if( $status->id  == $value->status ) { echo " selected='selected'  ";  } 
                        ?>
                     >{{ $status->name }}</option>
                {{-- @endif     --}}
                @endforeach
                @endif  
            </select>

        </td>
			  <td nowrap="true"> 
           <input name="canid[]" type="hidden" value="{{$value->caid}}">  
                   @if($value->introduceDate <>null)
                  <input type="date" style="width: 100%"  value= "{{$value->introduceDate}}"      name="introduceDate[]"/>   
                    @else
                     <input type="date"  style="width: 100%"       name="introduceDate[]"/>  
                    @endif
             </td>
			 <td nowrap="true"> 
                   @if($value->enterDate <>null)
                  <input type="date" style="width: 100%"  value= "{{$value->enterDate}}"      name="enterDate[]"/>   
                    @else
                     <input type="date"  style="width: 100%"       name="enterDate[]"/>  
                    @endif
             </td>
			 <td > <input class="input"  style="width: 95%" placeholder="note" 
                value="{{$value->note}}"   name="note[]"/>  
			 </td>
        <td>
             
            @if (!is_null($value->ocID ) && $value->ocOrderID==$order->id)
            <a  href="{{action('OrderCandiController@destroyCandi',$value->id)}}" onclick="return confirm('Do you want to remove this Candidate ?')">&nbsp;&nbsp;&nbsp;Remove</a> 
            @endif
          
       </td>
              
              
          
          
    

            
        </tr>
    @endforeach
    
    </tbody>
</table>
</div>
 @endif
 </div>  
 
 
   </form>  

 <style> 
  .fixheader {
    position: sticky; top: 0;
  }
    .fixheader2 {
    position: sticky; top: 10%;
  }
div.ex1 {
 
  height: 390px;

  overflow-y: scroll;
}

div.ex2 {
  background-color: lightblue;
  height: 40px;
  width: 200px;  
  overflow-y: hidden;
}

div.ex3 {
  background-color: lightblue;
  height: 40px;
  width: 200px;  
  overflow-y: auto;
}

div.ex4 {
  background-color: lightblue;
  height: 40px;
  width: 200px;  
  overflow-y: visible;
}
</style>
  <script>
    function validateHhMm(inputField) {
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputField.value);

        if (isValid) {
            inputField.style.backgroundColor = '#bfa';
        } else {
            inputField.style.backgroundColor = '#fba';
        }

        return isValid;
    }
    
  
   $(".delete").on("submit", function(){
        return confirm("Do you want to Approve this dayoff order ?");
    });


function validateForm() {

   
 //var x = document.forms["udpOT"]["fromTime"][1].value;
  var fromTArr = document.getElementsByName("fromTime[]");
  var dateArr= document.getElementsByName("date[]");
  var toTArr = document.getElementsByName("toTime[]");
   for (var i = 0; i < fromTArr.length; i++) {
    if (( ( fromTArr[i].value.length==0)^(toTArr[i].value.length==0) ) ||(fromTArr[i].value>toTArr[i].value ) ) {
     
     alert("Wrong input time at row: "+dateArr[i].value);
     return false;
    }
  }
   return confirm("Do you want to Update List ?");

 
   
}

  </script>
  
@endsection