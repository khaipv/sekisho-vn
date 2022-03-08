@extends('timemaster')
@section('content')
<!-- overtime.overtime  -->
      <div >
                <div style="margin-top: -35px; float: left;margin-left: -25px;margin-bottom: 30px;" >
                   <b style="font-size: 18px">   Application </b>
                   <!-- overtime.overtime.blade -->
                </div>
           
                    
       </div>  
 @if(session()->has('message'))
<div style="   margin-left: 25px;>
      
        <div class="alert alert-danger">
            <div style="text-align: center;" class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        </div>
        @endif     

   
       <div class="col-sm-10"  > 
     
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
 
 
  
    

        <form method="post" action="{{ action('OvertimeController@createOT') }}" accept-charset="UTF-8">
           <table class="table table-hover table-bordered" >
    {{csrf_field()}}
    <tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Type</font></strong></td> 
      <td class="col-md-2" >
      <select class="form-control" id="selectApp"  name="selectApp">
       @foreach($master as $master)
       @if($master->type =='app')
            <option value="{{$master->val}}"
                @if( $master->val == old('selectApp') ) 
          selected="selected" @endif >
              @if($master->val =='403')
              {{ $master->value }}
              @else
              {{ $master->name }}
              @endif
            </option>
       @endif
       @endforeach
      </select>
      </td> 
      <td class="col-md-3" >
      </td>
    </tr>
 </table>
 <div id="OverTimeID">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" width="12%" colspan="2" nowrap><strong>Dates</strong></td> 
            <td bgcolor="#CACFD2" nowrap><strong>Type</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>In </strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Out</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Rest</strong></td>
            <td bgcolor="#CACFD2" width="6%" nowrap><strong>OT Start</strong></td>
            <td  bgcolor="#CACFD2" width="6%" nowrap><strong>OT End</strong></td>  
            <td  bgcolor="#CACFD2" nowrap><strong>Reason</strong></td>      
    </thead>
    <tbody>
      <tr>
            <td style="padding-top: 20px;">
              <input type="date"    id="dateOTID" name="dateOT"  value="<?php echo e(old('fromDates')) ; ?>"  onchange="dateOTHand(event);" 
           style="width: 100%;"    >
            </td>
            <td style="padding-top: 20px;">
              <label id="lblDateNameHOOT" style="font-weight: normal;" >{{$days->name}}</label>
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
               <input type="Time"     id="fromOTID" name="fromOT" value="{{ old('fromOT')}}"
           style="width: 100%"   >
            </td>
            <td style="padding-top: 20px;">
               <input type="Time"    id="toOTID" name="toOT" value="{{ old('toOT')}}"
           style="width: 100%"   >
            </td>
            <td>
              <textarea  name="note" class="form-control" value="{{ old('note')}}" style="resize: none;"></textarea>  
            </td>
      </tr>
    </tbody>
  </table>
  
    </div>
    <div id="DayoffID" style="display: none;" >
<table class="table table-hover table-bordered" >
  <tr>
            <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            From(Date-Shift)</font></strong></td> 
            <td class="col-md-2" style="width: 14%" >
      <input type="date" class="form-control"     id="dateDOFromID" name="dateDOFrom"  value="<?php echo e(old('dateDOFrom')) ; ?>"   
           style="width: 85%;"    >
        
      </td> 
      <td class="col-md-1" >
        <select name='termFrom' class="form-control" style="width: 110%"   >
            <?php 
              foreach($masterTerm as $en){
             if($en->type =='term'){
                echo "<option value='".$en->val."'";
                 if( $en->val  == old('termFrom') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
        </select>
      </td> 
        <td  style="background: #7EC0EE;width: 17%"  class="col-md-1"><strong><font color="556B2F">
           To(Date-Shift)</font></strong></td> 
            <td class="col-md-2" style="width: 16.5%" >
        <input type="date" class="form-control"     id="dateDOToID" name="dateDOTo"   
           style="width: 85%;"    > 
         
        
      </td> 
        <td class="col-md-1" >
      <select class="form-control"  style="width: 110%"  name="termTo"  >
                <?php 
              foreach($masterTerm as $en){
             if($en->type =='term'){
                echo "<option value='".$en->val."'";
                 if( $en->val  == old('termTo') ) echo " selected='selected'  ";
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
 <textarea  name="note" class="form-control" value="{{ old('note')}}" style="resize: none;"></textarea>  
        
      </td> 
      <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Types of day off </font></strong></td> 
       <td class="col-md-5" colspan="2" style="width: 14%" >
        <select class="form-control"  style="width: 80%"  name="doType"  >
                <?php 
              foreach($masterDO as $en){
             if($en->type =='dostatus'){
                echo "<option value='".$en->val."'";
                 if( $en->val  == old('termTo') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
      </select>
         </td>      
    </tr>
     </table>
    
 
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

   </div>
    <div id="HolidayID" style="display: none;" >
    
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" colspan="2" nowrap><strong>Date</strong></td> 
            <td bgcolor="#CACFD2" nowrap><strong>Type</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Term</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Reason</strong></td>  
              
    </thead>
    <tbody>
      <tr>
         <td style="padding-top: 20px;">
              <input type="date"    id="dateHOID" name="dateHO"  value="<?php echo e(old('fromDates')) ; ?>"  onchange="dateOTHand(event);" 
           style="width: 100%;"    >
            </td>
            <td style="padding-top: 20px;">
              <label id="lblDateNameHO" style="font-weight: normal;" >{{$days->name}}</label>
            </td>
            <td style="padding-top: 20px;">
              <label id="lblDateTypeHO" style="font-weight: normal;" >{{$days->nameX}}</label>
            </td >
           <td style="padding-top: 20px;">
               <select name='term'  >
            <?php 
              foreach($masterTerm as $en){
             if($en->type =='term'){
                echo "<option value='".$en->val."'";
                 if( $en->val  == old('term') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select>
            </td>
            <td>
              <textarea  name="note" class="form-control" value="{{ old('note')}}" style="resize: none;"></textarea>  
            </td>   

          
      </tr>
    </tbody>
     
  </table>
 

  </div>
    <input type="submit" class="btn" value="Create">
</form>
 
 
<div id="DayoffID2" style="display: none;" >


    <!-- end of  -->
   
  
 <!-- end of form -->
  </div>

<iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>

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

                  document.getElementById('lblDateNameHOOT').innerHTML = data.name;

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
              document.getElementById("LeaveID").style.display="none";
            

          }
         if (national_id ==402) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("DayoffID").style.display="none";
            document.getElementById("HolidayID").style.display="block";
             document.getElementById("LeaveID").style.display="none";
          }
           if (national_id ==403) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("HolidayID").style.display="none";
            document.getElementById("DayoffID").style.display="block";
             document.getElementById("LeaveID").style.display="none";
          }
          if (national_id ==405) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("HolidayID").style.display="none";
            document.getElementById("DayoffID").style.display="none";
             document.getElementById("LeaveID").style.display="block";
          }
        });
 document.getElementById("myFrame").onload = function() {myFunction()};
  function myFunction() {
     var national_id=  document.getElementById("selectApp").value;
      if (national_id ==401) {
             document.getElementById("OverTimeID").style.display="block";
            document.getElementById("HolidayID").style.display="none";
             document.getElementById("DayoffID").style.display="none";
              document.getElementById("LeaveID").style.display="none";
            

          }
         if (national_id ==402) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("DayoffID").style.display="none";
            document.getElementById("HolidayID").style.display="block";
             document.getElementById("LeaveID").style.display="none";
          }
           if (national_id ==403) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("HolidayID").style.display="none";
            document.getElementById("DayoffID").style.display="block";
             document.getElementById("LeaveID").style.display="none";
          }
          if (national_id ==405) {
            document.getElementById("OverTimeID").style.display="none";
             document.getElementById("HolidayID").style.display="none";
            document.getElementById("DayoffID").style.display="none";
             document.getElementById("LeaveID").style.display="block";
          }
  }
  </script>
  
@endsection