@extends('timemaster')
@section('content')
      <div class="content container">
           
                <div class="title m-b-md">
                  Day Off Detail 
                  <!-- overtime.detail -->
                </div>
                    
       </div>  

    <div class="row">
  <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
       <div class="content ">
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
  
  <div id="OverTimeID">
        
  </div>
  
<div id="DayoffID"  >
     

   
   
  <form method="post" action="{{ action('OvertimeController@dayoffSearch') }}" accept-charset="UTF-8">
 
 <br>
 <br>
 
 <td> <a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;</a> 
           </td>  
 <table  class="table table-hover table-bordered" >
    <thead >
      <tr>
            <td bgcolor="#CACFD2"  nowrap><strong>Compensatory Day</strong></td> 
            <td bgcolor="#CACFD2"  nowrap><strong>Status</strong></td>
            <td bgcolor="#CACFD2"  nowrap><strong>Holiday or Weekend (AM)</strong></td>
            <td bgcolor="#CACFD2"  nowrap><strong>Holiday or Weekend (PM)</strong></td>
        
              
     </tr>
    
    </thead>
    <tbody>
       @foreach($data as $value)
        <tr>
           <td >{{ $value->date }} </td>
           <td >{{ $value->status }} </td>
           <td >{{ $value->amDB }} </td> 
           <td >{{ $value->pmDB }} </td>
           
           
        </tr>

    @endforeach
    </tbody>
  </table>
</form>
 <!-- end of form -->
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