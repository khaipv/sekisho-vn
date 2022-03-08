@extends('timemaster')
@section('content')
      <div class="content container">
                   <div style="margin-top: -35px; float: left;margin-left: -25px;" >
                   <b style="font-size: 18px">   Request Details  </b>
                   <!-- approve.approveDetail  -->
                </div>
                    
       </div>  

    <div class="row" style="margin-top: -40px">
  <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
       <div class="content ">
         @if(substr( $message  ,0, 6)=='Succes')
      <div class="alert alert-danger">
        
        <br/>
        <ul>
         
          <li>{{ $message }}</li>
         
        </ul>
      </div>
    @endif
    
    
   @if($type=='401'||$type=='405')

  <div id="OverTimeID"  >
        <form method="post" action="{{ action('ApproveController@appAproveMan') }}" accept-charset="UTF-8">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
 <input type="hidden" id="objID" name="objID" value="{{ $deleteObjOVT->id}}">
 <input type="hidden" id="typeID" name="typeID" value="{{ $type}}">
 <input type="hidden" id="idType" name="idType" value="{{ $idType}}">
      <input type="hidden" id="next" name="next" value="{{ $next}}"> 
            <input type="hidden" id="previousTypeID" name="previousTypeID" value="{{$previousTypeID}}"> 
       <input type="hidden" id="previous" name="previous" value="{{ $previous}}">
             <input type="hidden" id="nextType" name="nextType" value="{{ $nextType}}"> 
       <input type="hidden" id="previousType" name="previousType" value="{{ $previousType}}">
          <input type="hidden" id="idlist" name="idlist" value="{{ $idlist}}">
             <input type="hidden" id="typedblist" name="typedblist" value="{{ $typedblist}}">
                 <input type="hidden" id="nextTypeID" name="nextTypeID" value="{{$nextTypeID}}"> 
 <!-- table detail of Overtime start  -->

  
  <table class="table table-hover table-bordered" >
    <tr>
       <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Staff </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{ $usnames}}
       </td>  
        
    </tr>
    <tr>
        <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Type </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{$deleteObjOVT->type}}
       </td>  
        <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Status </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
         {{$deleteObjOVT->mstatus}}
       </td>  
     
    </tr>
        
  
        <tr>
    
       <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Date </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
           {{ $deleteObjOVT->date}}<br>( {{$days->name}}-{{$days->nameX}} )
       </td>  
      <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            OverTime (In - Out) </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
      {{ substr( $deleteObjOVT->start,0,5)}} - {{ substr( $deleteObjOVT->end,0,5)}}    <b>(  <?php 
     $startOT= \Carbon\Carbon::parse($deleteObjOVT-> start);
         $endOT= \Carbon\Carbon::parse($deleteObjOVT-> end);
      $timeOTSum= abs($endOT->diffInMinutes($startOT));
      if ($timeOTSum - floor($timeOTSum / 60) * 60 <10) {
            $timeOTSum_txt = floor($timeOTSum/60).':0'.($timeOTSum - floor($timeOTSum / 60) * 60);
                 } else 
              $timeOTSum_txt = floor($timeOTSum/60).':'.($timeOTSum - floor($timeOTSum / 60) * 60);  if ($timeOTSum>0) {
                 echo $timeOTSum_txt;
               }
     ?> </b>
  )
       </td> 
    </tr>
    <tr>
         <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
           Printer(In - Out)  </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
        {{ substr( $days->attendance,0,5)}} - {{ substr( $days->leaving,0,5)}}
       </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Reason </font></strong></td> 
            <td class="col-md-5" colspan="5" style="width: 14%;text-align: left;" >
{{$deleteObjOVT->note}}
      </td> 
    </tr>
     </table>
       <!-- table detail of Overtime end  -->
   <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" nowrap><strong>No</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Approve</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Reason</strong></td>
    </thead>
    <tbody>
  @if($userH->role==2 )
    <tr>
            <td style="padding-top: 20px;" nowrap="">
             2
            </td>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->maName}}
            </td>
            <td style="padding-top: 20px;">
               <textarea  name="note" class="form-control"  style="resize: none;">{{$deleteObjOVT->mannote1}}</textarea>  
            </td >
  </tr>
       <tr>
            <td style="padding-top: 20px;" nowrap="">
             1
            </td>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->mbName}}
            </td>
            <td style="padding-top: 20px;">
              {{$deleteObjOVT->mannote2}}
            </td >
      </tr>

    @endif
  @if($userH->role>=3 )
   <tr>
            <td style="padding-top: 20px;" nowrap="">
             1
            </td>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->maName}}
            </td>
            <td style="padding-top: 20px;">
              {{$deleteObjOVT->mannote1}}
            </td >
      </tr>
  <tr>
            <td style="padding-top: 20px;" nowrap="">
             2
            </td>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->mbName}}
            </td>
            <td style="padding-top: 20px;">
               <textarea  name="note" class="form-control"  style="resize: none;">{{$deleteObjOVT->mannote2}}</textarea>  
            </td >
  </tr>
  @endif
    </tbody>
  </table>
   <td>
    <input type="hidden" id="custId" name="requestID" value="{{$deleteObjOVT->id}}">
    <input type="hidden" id="custId" name="requestType" value="{{$type}}">
     @if( ($deleteObjOVT->status == 101 && $userH->id == 119  ) ||
      ($userH->id==1024 && ( $deleteObjOVT->status==101 || $deleteObjOVT->status==103 ||
       $deleteObjOVT->status==105  ||  $deleteObjOVT->status==108   ) ) )
     <button type="submit" class="btn btn-success" style="margin-right: 5% " name="approve" value ="save">Approve</button>

     <button type="submit" class="btn btn-danger" name="deny" value ="deny">Deny</button>
     @endif
     <br>

   </td>
  
</form>
  </div>
   @endif
   @if($type=='402')
  <div id="HolidayID"  >
      <form method="post" action="{{ action('ApproveController@appAproveMan') }}" accept-charset="UTF-8">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
<input type="hidden" id="objID" name="objID" value="{{ $deleteObjOVT->id}}">

<input type="hidden" id="idType" name="idType" value="{{ $idType}}">
   <input type="hidden" id="next" name="next" value="{{ $next}}"> 
            <input type="hidden" id="previousTypeID" name="previousTypeID" value="{{$previousTypeID}}"> 
       <input type="hidden" id="previous" name="previous" value="{{ $previous}}">
             <input type="hidden" id="nextType" name="nextType" value="{{ $nextType}}"> 
       <input type="hidden" id="previousType" name="previousType" value="{{ $previousType}}">
          <input type="hidden" id="idlist" name="idlist" value="{{ $idlist}}">
             <input type="hidden" id="typedblist" name="typedblist" value="{{ $typedblist}}">
                 <input type="hidden" id="nextTypeID" name="nextTypeID" value="{{$nextTypeID}}"> 
 <!-- work on holiday start -->
 <table class="table table-hover table-bordered" >
  <tr>
       <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Staff </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{ $usnames}}
       </td>  
      
    </tr>
    <tr>
        <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Type </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{$deleteObjOVT->type}}
       </td>  
        <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Status </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
         {{$deleteObjOVT->mstatus}}
       </td>  
     
    </tr>
        
    <tr>
       <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Date </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
           {{ $deleteObjOVT->date}} <br> ( {{$days->name}}-{{$days->nameX}}) 
       </td> 
       <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            In - Out </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
        {{ substr( $days->attendance,0,5)}} - {{ substr( $days->leaving,0,5)}}
       </td>        
    </tr>
    <tr>
            <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Reason </font></strong></td> 
            <td class="col-md-5" colspan="5" style="width: 14%;text-align: left;" >
              {{$deleteObjOVT->note}}
      </td> 
    </tr>
     </table>
 <!-- work on holiday end -->
  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" nowrap><strong>No</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Approve</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Reason</strong></td>
    </thead>
    <tbody>
  @if($userH->role==1 )
      <tr>
            <td style="padding-top: 20px;" nowrap="">
             1
            </td>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->maName}}
            </td>
            <td style="padding-top: 20px;">
               <textarea  name="note" class="form-control"  style="resize: none;">{{$deleteObjOVT->mannote1}}</textarea>  
            </td >
      </tr>
    @endif
  @if($userH->role>=2 )
   <tr>
            <td style="padding-top: 20px;" nowrap="">
             1
            </td>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->maName}}
            </td>
            <td style="padding-top: 20px;">
              {{$deleteObjOVT->mannote1}}
            </td >
      </tr>
  <tr>
            <td style="padding-top: 20px;" nowrap="">
             2
            </td>
            <td style="padding-top: 20px;">
              {{ $deleteObjOVT->mbName}}
            </td>
            <td style="padding-top: 20px;">
               <textarea  name="note" class="form-control"  style="resize: none;">{{$deleteObjOVT->mannote2}}</textarea>  
            </td >
  </tr>
  @endif
    </tbody>
  </table>
   <td>
    <input type="hidden" id="custId" name="requestID" value="{{$deleteObjOVT->id}}">
    <input type="hidden" id="custId" name="requestType" value="{{$type}}">
     
      @if( ($deleteObjOVT->status == 101 && $userH->id == 119  ) ||
      ($userH->id==1024 && ( $deleteObjOVT->status==101 || $deleteObjOVT->status==103 ||
       $deleteObjOVT->status==105  ||  $deleteObjOVT->status==108   ) ) )
    <button type="submit" class="btn btn-success" style="margin-right: 5% " name="approve" value ="save">Approve</button>
     <button type="submit" class="btn btn-danger" name="deny" value ="deny">Deny</button>
     @endif
   
</form>
  </div>
   @endif
   @if($type=='202'||$type=='201'||$type=='203'||$type=='204')
<div id="DayoffID"  >
    <form method="post" action="{{ action('ApproveController@appAproveMan') }}" accept-charset="UTF-8">
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
<input type="hidden" id="objID" name="objID" value="{{ $deleteDayoff->id}}">
<input type="hidden" id="typeID" name="typeID" value="{{ $type}}">
   <input type="hidden" id="next" name="next" value="{{ $next}}"> 
   <input type="hidden" id="idType" name="idType" value="{{ $idType}}">
            <input type="hidden" id="previousTypeID" name="previousTypeID" value="{{$previousTypeID}}"> 
       <input type="hidden" id="previous" name="previous" value="{{ $previous}}">
             <input type="hidden" id="nextType" name="nextType" value="{{ $nextType}}"> 
       <input type="hidden" id="previousType" name="previousType" value="{{ $previousType}}">
          <input type="hidden" id="idlist" name="idlist" value="{{ $idlist}}">
             <input type="hidden" id="typedblist" name="typedblist" value="{{ $typedblist}}">
                 <input type="hidden" id="nextTypeID" name="nextTypeID" value="{{$nextTypeID}}"> 
 <table class="table table-hover table-bordered" >
  <tr>
       <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Staff </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{ $usnames}}
       </td>  
    </tr>
    <tr>
        <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Type </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{ $deleteDayoff->typeName}}  
       </td>  
        <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Status </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{ $deleteDayoff->statusName}}
       </td>  
     
    </tr>
        
<tr>
<td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
             From(Date-Shift) </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
          {{$deleteDayoff->fromDate}} - {{$deleteDayoff-> fromName}} <br>( {{$days->name}}-{{$days->nameX}} )
       </td>  
        <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
             To(Date-Shift) </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
        @if(!is_null($deleteDayoff->toDate)) 
        {{$deleteDayoff->toDate}} 
    - {{$deleteDayoff->toName}}
     <br>( {{$daysTo->name}}-{{$daysTo->nameX}} )
          @endif
       </td>  
</tr>
<tr>
<td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
             Days </font></strong></td> 
       <td class="col-md-5"  style="width: 14%" >
           {{$dayss}} days 
       </td> 
</tr>

    <tr>
            <td  style="background: #7EC0EE;width: 15%"  class="col-md-1"><strong><font color="556B2F">
            Reason </font></strong></td> 
            <td class="col-md-5" colspan="5" style="width: 14%;text-align: left;" >
              {{$deleteDayoff->note}}
      </td> 
    </tr>
     </table>

     
     
 <table  class="table table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" nowrap><strong>No</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Approve</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Reason</strong></td>
    </thead>
    <tbody>
  @if($userH->role==1 )
      <tr>
            <td style="padding-top: 10px;" nowrap="">
             1
            </td>
            <td style="padding-top: 10px;">
              {{ $deleteDayoff->maName}}
            </td>
            <td style="padding-top: 10px;">
               <textarea  name="note" class="form-control"  style="resize: none;">{{$deleteDayoff->mannote1}}</textarea>  
            </td >
      </tr>
    @endif
  @if($userH->role>=2 )
   <tr>
            <td style="padding-top: 10px;" nowrap="">
             1
            </td>
            <td style="padding-top: 10px;">
              {{ $deleteDayoff->maName}}
            </td>
            <td style="padding-top: 10px;">
              {{$deleteDayoff->mannote1}}
            </td >
      </tr>
  <tr>
            <td style="padding-top: 10px;" nowrap="">
             2
            </td>
            <td style="padding-top: 10px;">
              {{ $deleteDayoff->mbName}}
            </td>
            <td style="padding-top: 10px;">
               <textarea  name="note" class="form-control"  style="resize: none;">{{$deleteDayoff->mannote2}}</textarea>  
            </td >
  </tr>
  @endif
    </tbody>
  </table>
   <td>
    <input type="hidden" id="custId" name="requestID" value="{{$deleteDayoff->id}}">
    <input type="hidden" id="custId" name="requestType" value="{{$type}}">
      @if( ($deleteDayoff->status == 101 && $userH->id == 119  ) ||
      ($userH->id==1024 && ( $deleteDayoff->status==101 || $deleteDayoff->status==103 ||
       $deleteDayoff->status==105  ||  $deleteDayoff->status==108   ) ) )
    <button type="submit" class="btn btn-success" style="margin-right: 5% " name="approve" value ="save">Approve</button>
     <button type="submit" class="btn btn-danger"  name="deny" value ="deny">Deny</button>
     @endif
   </td>

</form>

  </div>
  @endif

   </div> 

<div class="col-sm-12"  > 
     <div class="col-sm-5"  > 
        @if($previous<>0)
        <a  href="{{action('ApproveController@appAprove', [$previous,$previousType,$previousTypeID,$idlist,$typedblist,$next] )}}">
   Previous</a>
        @endif
           </div>
           <div class="col-sm-6"  > 
     
    </div>
    <div class="col-sm-1"  > 
        @if($next<>0)
        <a  href="{{action('ApproveController@appAprove', [$next,$nextType,$nextTypeID,$idlist,$typedblist,$next] )}}"> Next</a>
          @endif
         
  </div>
   
    </div> <br><br>
    <div class="col-sm-12"  > 
     <div class="col-sm-5"  > 
       
           </div>
           <div class="col-sm-6"  > 
      <a  href="{{action('ApproveController@appBack' )}}"> Back</a>
    </div>
    <div class="col-sm-1"  > 
       
         
  </div>
   
    </div>
      @if(isset($data))
          <table  class="table table-hover table-bordered" style="width: 80%" >
    <thead >
      <tr>
            <td style="text-align: center;" bgcolor="#CACFD2"  nowrap><strong>Date</strong></td> 
         
            <td style="text-align: center;" bgcolor="#CACFD2"  nowrap><strong> (AM)</strong></td>
            <td style="text-align: center;" bgcolor="#CACFD2"  nowrap><strong> (PM)</strong></td>
        
              
     </tr>
    
    </thead>
    <tbody>
    
       @foreach($data as $value)
        <tr>
           <td style="text-align: center;">{{ $value->date }} </td>
          
           <td style="text-align: center;">
            @if ($value->amID==-1)
            <?php echo "(Annual Leave)" ?> 
            @elseif($value->amID==-5)
             <?php echo "(Unpaid Leave)" ?> 
            @else 
            {{ $value->amDB }}
            @endif
           </td> 
           <td style="text-align: center;">
           @if ($value->pmID==-1)
            <?php echo "(Annual Leave)" ?> 
             @elseif($value->pmID==-5)
             <?php echo "(Unpaid Leave)" ?> 
            @else 
            {{ $value->pmDB }}
            @endif
          </td>
           
           
        </tr>

    @endforeach
    
    </tbody>
  </table>
  @endif
</div>

     
            <input type="hidden" id="next" name="next" value="{{ $next}}"> 
            <input type="hidden" id="previousTypeID" name="previousTypeID" value="{{$previousTypeID}}"> 
       <input type="hidden" id="previous" name="previous" value="{{ $previous}}">
             <input type="hidden" id="nextType" name="nextType" value="{{ $nextType}}"> 
       <input type="hidden" id="previousType" name="previousType" value="{{ $previousType}}">
          <input type="hidden" id="idlist" name="idlist" value="{{ $idlist}}">
             <input type="hidden" id="typedblist" name="typedblist" value="{{ $typedblist}}">
                 <input type="hidden" id="nextTypeID" name="nextTypeID" value="{{$nextTypeID}}"> 
                 <input type="hidden" name="privateNull" value=NULL>
                 <input type="hidden" name="message"  value="{{$message}}">
 </div>
    <br>
    <br>
 <div class="row">
  
</div>
 
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