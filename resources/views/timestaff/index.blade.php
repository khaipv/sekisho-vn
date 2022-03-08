
@extends('timemaster')
@section('content')

<div class="container">
      
           
                <div class="content title m-b-md">
                   Edit Fingerprint List
                </div>
                
      
    
       
        <div class="row">
        <div class="col-sm-2"  > </div> 
        <div class="col-sm-8"  >
        <form method="GET" action="{{ url('apptimeSearch') }}" >   
          <table class="table table-hover table-bordered" >
              <tr >
                <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Staff</font></strong></td> 
                <td colspan="1" class="col-md-1" >
                <select class="form-control"   name="tck_user" >
                         <option value="">--------</option>
                          @foreach($tck_user as $tck_user)
                              <option value="{{$tck_user->code}}"  
                               @if( $tck_user->code == old('tck_user') ) 
                    selected="selected" @endif     >{{ $tck_user->name }}</option>
                          @endforeach
                </select>
                </td>
              </tr>
              <tr>
                <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">From</font></strong></td> 
                  <td colspan="1" class="col-md-1" >
                   <input type="date"    id="toDates" name="fromDates" value="{{old('fromDates')}}"
                       style="width: 65%"   >
                  </td> 
                  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">To</font></strong></td> 
                  <td colspan="1" class="col-md-1" >
                   <input type="date"    id="toDates" name="toDates" value="{{old('toDates')}}"
                       style="width: 65%"   >
                  </td> 
            </tr>
            <tr>

              <br>
              <div style="padding-left: 50%">
              <button type="submit" >Search</button>
              </div>
              <br>
              
            </tr>
          </table>   

        </form> 
     </div>    
 
         </div>  
       <div class="row">
      <br>
       
       <div >
      
  <form method="post" action="{{url('timeMan')}}" autocomplete="off" id="demoForm" class="demoForm">
             <div class="content" style="padding-left: 6.5%">
             <tr>
              <br>
              <button type="submit">Update</button>
              <br>
            </tr>
          </div>
          <input type="hidden" name="_method" value="POST">
          {{ csrf_field() }}
  <table class="table table-hover table-bordered" >
    <thead >
        <tr>
       <td colspan="2" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('date', 'Date')</strong></td> 
       <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Code', 'Code')</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('name', 'Name')</strong></td> 
      <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('namex', 'Type')</strong></td>   
          <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('attendance', 'attendance')</strong></td> 
            <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('leaving', 'leaving')</strong></td>    
             <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('code', 'Staffs Reason')
              </strong></td>  
               <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('code', 'Manager Comment')
              </strong></td>
              <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('leaving', 'Accept')</strong><input type="checkbox" id="acceptCheck"  onclick="acceptFunction()"></td> 
               <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('leaving', 'Deny')</strong><input type="checkbox" id="denyCheck"  onclick="denyFunction()"></td>     
        </tr>  
    </thead>
    <tbody>
    @foreach($timesheet as $value)
        <tr>
          <td nowrap="true"<?php if(is_null($value->attendance) AND( $value->leave_from >=$value->hour1) 
          AND( $value->leave_to <=$value->hour2)  ): ?> style="background-color:#F6CFCA;" <?php endif; ?>>
            {{ $value->date }}</td>
          <td><a href="">{{ $value->typedate }}</a></td>
            <td><a href="">{{ $value->code }}</a></td>
            <td nowrap="true">{{ $value->name }}</td>
            <td nowrap="true">{{ $value->nameX }}</td>
            <td <?php if($value->late < 0): ?> style="background-color:#00ff00;" <?php endif; ?>>
              {{ $value->attendance }}
            </td>    
               
             <td <?php if($value->early < 0): ?> style="background-color:#00ff00;" <?php endif; ?>>
              {{ $value->leaving }}  
              </td>
              <td >
                 {{ $value->note }}   
              </td>
              <td style="width: 180%">
                @if($userM->role==1 )
                <input class="input"   placeholder="note" value="{{$value->note}}"  
                style="width: 100%" name="note[]"/>  
                @endif
                 @if($userM->role>=2 )
                <input class="input"   placeholder="note" value="{{$value->mannote2}}"  
                style="width: 100%" name="note[]"/>  
                @endif
              </td>   
              <td>
          <input type="checkbox" name="accept[]" id="{{ $value->id }}" disable="true"  
          value="{{ $value->id }}"> 
              </td> 
              <td>
          <input type="checkbox" name="deny[]" id="{{ $value->id }}" disable="true"  
         value="{{ $value->id }}">  
              </td>  
               <input type="hidden" id="timeSID" name="timeSID[]" value="{{ $value->id}}">      
        </tr>
    @endforeach
     <tr>
      <td colspan="11">
        <div class="pagination">
         {{ $timesheet->appends(Request::except('page'))->links() }}   
        </div>      
      </td>
    </tr>
    
</table>
  </form>
   
       
       </div>
            
     </div>  
    
         <br>  
        
      
   
 </div>
 <script>
  function acceptFunction() {
  var deny = document.forms['demoForm'].elements[ 'deny[]' ];
  var accept = document.forms['demoForm'].elements[ 'accept[]' ];
  if (document.getElementById("acceptCheck").checked==true) {
  document.getElementById("denyCheck").checked=false;
  }
  for (var i=0, len=accept.length; i<len; i++) {
    // alert(deny[i].id);
    accept[i].checked = document.getElementById("acceptCheck").checked;
    if (deny[i].checked==true && document.getElementById("acceptCheck").checked==true) {
    deny[i].checked = !document.getElementById("acceptCheck").checked;
    }
}
  } // end acceptFunction
  function denyFunction() {
  var deny = document.forms['demoForm'].elements[ 'deny[]' ];
  var accept = document.forms['demoForm'].elements[ 'accept[]' ];
   
  document.getElementById("acceptCheck").checked=false;
  
  for (var i=0, len=deny.length; i<len; i++) {
    // alert(deny[i].id);
    deny[i].checked = document.getElementById("denyCheck").checked;
    if (accept[i].checked==true && document.getElementById("denyCheck").checked==true) {
    accept[i].checked = !document.getElementById("denyCheck").checked;
    }
}
  } // end denyFunction


  $('input[name="deny[]"]').click(function(){
    var accept = document.forms['demoForm'].elements[ 'accept[]' ];
    var deny = document.forms['demoForm'].elements[ 'deny[]' ];
   
     //alert($(this).attr('id'));
     for (var i=0, len=accept.length; i<len; i++) {
    if (accept[i].id==$(this).attr('id') && deny[i].checked==true) {
       accept[i].checked = !deny[i].checked;
    }
}
    
    
   
});
   $('input[name="accept[]"]').click(function(){
    var accept = document.forms['demoForm'].elements[ 'accept[]' ];
    var deny = document.forms['demoForm'].elements[ 'deny[]' ];

     //alert($(this).attr('id'));
     for (var i=0, len=deny.length; i<len; i++) {
    if (deny[i].id==$(this).attr('id') && accept[i].checked==true) {
       deny[i].checked = !accept[i].checked;
    }
}
    
    
   
});
</script>        
    </body>
@endsection