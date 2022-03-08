@extends('timemaster')
@section('content')
      <div class="content container">
           
            
                     <div style="margin-top: -35px; float: left;margin-left: -25px;" >
                   <b style="font-size: 18px">  Request Detail</b>
                   <!-- approve.aprove.blade -->
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
   @if($type=='401'||$type=='405')
  <div id="OverTimeID">
        <form method="post" action="{{ action('ApplicationController@editOT') }}" accept-charset="UTF-8">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
 <input type="hidden" id="objID" name="objID" value="{{ $deleteObjOVT->id}}">
 <input type="hidden" id="typeID" name="typeID" value="{{ $type}}">
  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" width="12%" colspan="2" nowrap><strong>Dates</strong></td> 
            <td bgcolor="#CACFD2" nowrap><strong>Type</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Start</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>End</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Rest</strong></td>
            <td bgcolor="#CACFD2" width="6%" nowrap><strong>OT Start</strong></td>
            <td  bgcolor="#CACFD2" width="6%" nowrap><strong>OT End</strong></td>  
            <td  bgcolor="#CACFD2" nowrap><strong>Reason</strong></td>      
    </thead>
    <tbody>
      <tr>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->date}}
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
               {{ $deleteObjOVT -> start }}
            </td>
            <td style="padding-top: 20px;">
         {{$deleteObjOVT->end}}
            </td>
            <td>
              {{$deleteObjOVT->note}}
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
              {{ $userH->maName}}
            </td>
            <td style="padding-top: 20px;">
             <input type="hidden" id="mannote1" name="mannote1" 
             value= "{{$deleteObjOVT->mannote1}}">
              {{$deleteObjOVT->mannote1}}
            </td >
      </tr>
  <tr>
            <td style="padding-top: 20px;" nowrap="">
             2
            </td>
            <td style="padding-top: 20px;">
              {{ $userH->mbName}}
            </td>
            <td style="padding-top: 20px;">
               {{$deleteObjOVT->mannote2}}
              
            </td >
  </tr>
  </tbody>
  </table>
  
</form>
  </div>
   @endif
   @if($type=='402')
  <div id="HolidayID"  >
     <form method="post" action="{{ action('ApplicationController@editOT') }}" accept-charset="UTF-8">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
<input type="hidden" id="objID" name="objID" value="{{ $deleteObjOVT->id}}">
<input type="hidden" id="typeID" name="typeID" value="{{ $type}}">
  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" colspan="2" nowrap><strong>Date</strong></td> 
            <td bgcolor="#CACFD2" nowrap><strong>Type</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Term</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Days</strong></td>  
             
    </thead>
    <tbody>
      <tr>
         <td style="padding-top: 20px;">
              {{ $deleteObjOVT->date}}
            </td>
            <td style="padding-top: 20px;">
              <label id="lblDateNameHO" style="font-weight: normal;" >{{$days->name}}</label>
            </td>
            <td style="padding-top: 20px;">
              <label id="lblDateTypeHO" style="font-weight: normal;" >{{$days->nameX}}</label>
            </td >
           <td style="padding-top: 20px;">
               <select name='term' disabled >
            <?php 
              foreach($masterTerm as $en){
             if($en->type =='term'){
                echo "<option value='".$en->val."'";
                 if( $en->val  == $deleteObjOVT->term ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select>
            </td>
            <td>
             {{$deleteObjOVT->note}}
            </td>   

          
      </tr>
    </tbody>
     
  </table>
  
</form>
  </div>
   @endif
   @if($type=='202'||$type=='201'||$type=='203'||$type=='204')
<div id="DayoffID"  >
     <form method="post" action="{{ action('ApplicationController@editOT') }}" accept-charset="UTF-8">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
<input type="hidden" id="objID" name="objID" value="{{ $deleteDayoff->id}}">
<input type="hidden" id="typeID" name="typeID" value="{{ $type}}">
<table class="table table-hover table-bordered" >
  <tr>
            <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            From(Date-Shift)</font></strong></td> 
            <td class="col-md-2" style="width: 14%" >
      {{$deleteDayoff->fromDate}}
        
      </td> 
      <td class="col-md-1" >
        <select name='termFrom' disabled class="form-control" style="width: 110%"   >
            <?php 
              foreach($masterTerm as $en){
             if($en->type =='term'){
                echo "<option value='".$en->val."'";
                 if( $en->val  == $deleteDayoff->fromTerm ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
        </select>
      </td> 
        <td  style="background: #7EC0EE;width: 17%"  class="col-md-1"><strong><font color="556B2F">
           To(Date-Shift)</font></strong></td> 
            <td class="col-md-2" style="width: 16.5%" >
   {{$deleteDayoff->toDate}}        
      </td> 
        <td class="col-md-1" >
      
      <select disabled class="form-control"  style="width: 110%"  name="termTo"  >
                <?php 
              foreach($masterTerm as $en){
             if($en->type =='term'){
                echo "<option value='".$en->val."'";
                 if( $en->val  == $deleteDayoff->toTerm ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
      </select>
     </td> 
       </tr> 
        <tr>
            <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Reason </font></strong></td> 
            <td class="col-md-5" colspan="2" style="width: 14%" >
{{ $deleteDayoff->note}} 
        
      </td> 
      <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Special Leave </font></strong></td> 
       <td class="col-md-5" colspan="2" style="width: 14%" >
         <input type="checkbox" name="annual" value="1"> Example Weding, Relatives or Special Reason
       </td>      
    </tr>
     </table>
</form>

    <!-- end of  -->
   
  <form method="post" action="{{ action('OvertimeController@dayoffSearch') }}" accept-charset="UTF-8">
 
 <br>
 <br>
  <div class="content container">
           
                <div class="title m-b-md" style="padding-right: 25%">
                  Working On Holiday List 
                </div>
                    
 </div>  
 <table  class="table table-hover table-bordered" >
    <thead >
      <tr>
            <td bgcolor="#CACFD2" colspan="2" nowrap><strong>Date-Time</strong></td> 
            <td bgcolor="#CACFD2" rowspan="2" nowrap><strong>Type</strong></td>
            <td bgcolor="#CACFD2" rowspan="2" nowrap><strong>Compensatory Day</strong></td>
             <td bgcolor="#CACFD2" rowspan="2" nowrap><strong>Remain date</strong></td>
            <td bgcolor="#CACFD2" rowspan="2" nowrap><strong>Status</strong></td>
            <td bgcolor="#CACFD2" rowspan="2" nowrap><strong>Apply Reason</strong></td>
              
     </tr>
     <tr>
            <td  bgcolor="#CACFD2" nowrap><strong>Date</strong></td>  
            <td  bgcolor="#CACFD2" nowrap><strong>Term</strong></td>
           
            
       
     </tr>
    </thead>
    <tbody>
       @foreach($dayAlls as $value)
        <tr>
           <td >{{ $value->date }} </td>
           <td >{{ $value->term }} </td>
           <td >{{ $value->mtype }} </td> 
           <td > {{ $value->oriNum }}</td> 
           <td > {{ $value->datenum }}</td> 
           <td >{{ $value->mstatus }} </td>
           <td >{{ $value->note }} </td>
           
        </tr>

    @endforeach
    </tbody>
  </table>
</form>
 <!-- end of form -->
  </div>
  @endif

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