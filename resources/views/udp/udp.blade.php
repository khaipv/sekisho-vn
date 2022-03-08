@extends('timemaster')
@section('content')
  
              <div style="margin-top: -25px; float: left;" >
                   <b style="font-size: 18px"> &nbsp;&nbsp; Edit Fingerprint </b>
                    <!-- udp.udp.blade -->
                </div>
                <br>
 <form style="margin-top: -25px;  name="udpOT" method="get" action="{{ url('otDaySearch') }}">
  <div class="row">

       <div class="col-sm-8"  > 
       <div class="content ">
  <table class="table table-hover table-bordered" >
    {{csrf_field()}}
<input name="_method" type="hidden" value="PATCH">
    <tr >
         <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">From</font></strong></td> 
          <td colspan="1" class="col-md-1" >
       <input type="date"    id="canJob" name="fromDates" 
       value="<?php echo e(old('fromDates')) ; ?>"
           style="width: 100%"    >              
         </td> 
               <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">To</font></strong></td> 
          <td colspan="1" class="col-md-1" >
       <input type="date"    id="toDates" name="toDates" value="{{old('toDates')}}"
           style="width: 100%"   >
         
               
  
      </td> 
        <td colspan="1" class="col-md-1" ><input type="submit" value="Search"> </td>
    </tr>
 </table>

   </div> 
     <div class="col-sm-2"  ></div> 
</div>
 </div>

 
    </form>   
   
    <form method="get" action="{{url('updatePrint')}}" autocomplete="off" 
    onsubmit="return validateForm()" >
 {{csrf_field()}}
<input name="_method" type="hidden" value="PATCH">
        
       <div class="col-sm-12"  > 
       <div class="content ">
        <div align="center">
          @if(!is_null($days) && count($days) >0)
        <button type="submit">Update</button>     
         @endif   
</div>
@if(!is_null($days))
<div class="ex1">
  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
         <tr>
            <td class="fixheader" bgcolor="#CACFD2" rowspan="2" colspan="2" nowrap><strong>Date-Time</strong></td> 
            <td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Type</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Status</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" colspan="2" nowrap><strong>Printer</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" colspan="2" nowrap><strong>Staff Edit</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Apply Reason</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>{{$userH->maName}}</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>{{$userH->mbName}}</strong></td>
              
     </tr>
     <tr>
            
             <td class="fixheader2" bgcolor="#CACFD2" nowrap><strong>Check in</strong></td>  
            <td class="fixheader2" bgcolor="#CACFD2" nowrap><strong>Check Out</strong></td>
                         <td  bgcolor="#CACFD2" class="fixheader2" nowrap><strong>Check in</strong></td>  
            <td class="fixheader2" bgcolor="#CACFD2" nowrap><strong>Check Out</strong></td>
           
            
       
     </tr>
    </thead>
    <tbody>
    @foreach($days as $value)
        <tr>
           <td style="display:none;"> 
            <input type="date"    id="canJob" name="date[]" 
            value="{{$value->date}}" style="width: 55%"   />
            </td>

            <td wrap  >{{ $value->date }} </td>
             <td >{{ $value->name }} </td>
             <td >{{ $value->nameX }} </td>
              <td >{{ $value->status }} </td>
               <td <?php if($value->late < 0): ?> style="background-color:#00ff00;" <?php endif; ?>>
              {{ $value->attendance }}
            </td>   
                <td >{{ $value->leaving }} </td>
              <td >
                @if($value->inEdit <>null)
                <input type="time" style="width: 100%" placeholder="hh:mm" value="{{ $value->inEdit}}"  min="00:00" max="24:00"   onchange="validateHhMm(this);"  name="fromTime[]"/>  
                @else
                <input type="time" style="width: 100%" placeholder="hh:mm" value="{{ $value->attendance}}"  min="00:00" max="24:00"   onchange="validateHhMm(this);"  name="fromTime[]"/>  
                @endif
                
                </td>
                <td > 
                   @if($value->outEdit <>null)
                  <input type="time" style="width: 100%" placeholder="hh:mm" value= "{{$value->outEdit}}" onchange="validateHhMm(this);"  min="00:00" max="24:00"    name="toTime[]"/>   
                    @else
                     <input type="time" style="width: 100%" placeholder="hh:mm" value= "{{$value->leaving}}" onchange="validateHhMm(this);"  min="00:00" max="24:00"    name="toTime[]"/>  
                    @endif
                </td>
                 <td > <input class="input"  style="width: 95%" placeholder="note" 
                value="{{$value->note}}"   name="note[]"/>   </td>
                <td >{{ $value->mannote1 }} </td>
                <td >{{ $value->mannote2 }} </td>
              
              
              
          
          
    

            
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