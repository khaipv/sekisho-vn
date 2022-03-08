@extends('timemaster')
@section('content')
      <div class="content container">
           
                <div class="title m-b-md">
                  Request's Detail
                </div>
                @if(session()->has('message'))
        <div class="alert alert-danger">
            <div style="text-align: center;" class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        </div>
        @endif     
       </div>  

    <div class="row">
  <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
       <div class="content ">
        
     
       
     
    
   
  <div id="OverTimeID">
        <form  autocomplete="off" accept-charset="UTF-8">

  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" width="12%" colspan="2" nowrap><strong>Dates</strong></td> 
            <td bgcolor="#CACFD2" nowrap><strong>Type</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Start</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>End</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Rest</strong></td>
            <td bgcolor="#CACFD2" width="6%" nowrap><strong>From</strong></td>
            <td  bgcolor="#CACFD2" width="6%" nowrap><strong>To</strong></td>  
            <td  bgcolor="#CACFD2" ><strong>Reason</strong></td>      
    </thead>
    <tbody>
      <tr>
            <td style="padding-top: 20px;">
             {{ $leaveObj->date}}
            </td>
            <td style="padding-top: 20px;">
              <label id="lblDateName" style="font-weight: normal;" >{{$days->name}}</label>
            </td>
            <td style="padding-top: 20px;">
              <label id="lblDateType" style="font-weight: normal;" >{{$days->nameX}}</label>
            </td >
             <td style="padding-top: 20px;">
              <label id="lblDateStart" style="font-weight: normal;" >{{$days->attendance}}</label>
            </td>
             <td style="padding-top: 20px;">
              <label id="lblDateEnd" style="font-weight: normal;" >{{$days->leaving}}</label>
            </td>
             <td style="padding-top: 20px;">
              <label id="lblDateRest" style="font-weight: normal;" >{{$rest}}</label>
            </td>
            <td style="padding-top: 20px;">
               {{ $leaveObj -> start }}
            </td>
            <td style="padding-top: 20px;">
               {{$leaveObj->end}}
            </td>
            <td>
              {{$leaveObj->note}}  
            </td>
      </tr>
    </tbody>
  </table>

<table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" nowrap><strong>No</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Approve</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Reason</strong></td>
    </thead>
    <tbody>
  <tr>
            <td style="padding-top: 20px;" nowrap="">
             1
            </td>
            <td style="padding-top: 20px;">
              {{ $auUser->maName}}
            </td>
            <td style="padding-top: 20px;">
             
              {{$leaveObj->mannote1}}
            </td >
      </tr>
  <tr>
            <td style="padding-top: 20px;" nowrap="">
             2
            </td>
            <td style="padding-top: 20px;">
              {{ $auUser->mbName}}
            </td>
            <td style="padding-top: 20px;">
               {{$leaveObj->mannote2}}
              
            </td >
  </tr>
  </tbody>
  </table>
    <a href="{{action('ApplicationController@editApp', [$leaveObj->id,$leaveObj->statusCP])}}" 
             class="btn ">Edit</a>
</form>
  </div>
  
   

   </div> 
</div>
 </div>
    <br>
    <br>
 

 
  <script type="text/javascript">

    var url = "{{ url('/showOT') }}";
      function dateOTHand(e){
          var token = $("input[name='_token']").val();
         province_id= e.target.value;
       
                $.ajax({
            url: url,
            method: 'POST',
            data: {
                id: province_id,
                _token: token
            },
            success: function(data) {
                 
               
               document.getElementById('lblDateNameHO').innerHTML = data.name;
               document.getElementById('lblDateTypeHO').innerHTML = data.nameX;

              document.getElementById('lblDateName').innerHTML = data.name;
              document.getElementById('lblDateType').innerHTML = data.nameX;
              document.getElementById('lblDateStart').innerHTML = data.attendance;
              document.getElementById('lblDateEnd').innerHTML = data.leaving;
              if ( data.attendance<data.restS && data.leaving> data.restE) {
                document.getElementById('lblDateRest').innerHTML = '01:00';
              } else document.getElementById('lblDateRest').innerHTML = ' ';
              
            }
        });
}
       $("select[name='selectApp']").change(function(){
          var national_id = $(this).val();

          if (national_id ==401) {
             document.getElementById("OverTimeID").style.display="block";
            document.getElementById("HolidayID").style.display="none";
             document.getElementById("DayoffID").style.display="none";
            

          }
         if (national_id ==402) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("DayoffID").style.display="none";
            document.getElementById("HolidayID").style.display="block";
          }
           if (national_id ==403) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("HolidayID").style.display="none";
            document.getElementById("DayoffID").style.display="block";
          }
        });

  </script>
  
@endsection