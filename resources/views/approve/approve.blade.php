@extends('timemaster')
@section('content')
     
                 <div style="margin-top: -25px; float: left;" >
                   <b style="font-size: 18px">Staff's Request</b>
                   <!-- approve.aprove.blade -->
                </div>
                    
 
         @if(session()->has('message'))
            <div style="text-align: center;" class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

    <form method="get" action="{{ action('ApproveController@approveSearch') }}" accept-charset="UTF-8">
      <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
       <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe> 
  <div class="row">

       <div class="col-sm-10"  > 
       <div class="content ">
  <table class="table table-hover table-bordered" >
    <tr >
         <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">From</font></strong></td> 
          <td colspan="1" class="col-md-1" >
       <input type="date"    id="appfrom" name="fromDate" 
       @if( isset($firstDay) )
       value="{{$firstDay }}"
       @else 
       value="{{ old('fromDate')}}"
       @endif
       
           style="width: 55%"> 
         </td> 
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">To</font></strong></td> 
      <td colspan="1" class="col-md-1" >
       <input type="date"    id="appto" name="toDate" 
          @if( isset($lastDay) )
       value="{{$lastDay }}"
       @else 
       value="{{ old('toDate')}}"
       @endif
           style="width: 55%">
      </td> 
    </tr>
    <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Request's Type</font></strong></td> 
          <td colspan="1" class="col-md-1" >
      <select class="form-control"   name="typeSelect" >
               <option value="">--------</option>
                @foreach($mastersType as $master)
                    <option value="{{$master->val}}"  
                     @if( $master->val == old('typeSelect') ) 
          selected="selected" @endif     >{{ $master->name }}</option>
                @endforeach
            </select>
         </td>
          <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status</font></strong></td> 
          <td colspan="1" class="col-md-1" >
       <select class="form-control"   name="statusSelect">
          
           <option value=""   @if(   old('statusSelect')=="") selected="selected" @endif  >--------</option>
            <option value="99" @if(  old('statusSelect') !=""  ) selected="selected" @endif >Approving</option>
       @foreach($masters as $master)
       @if($master->type =='status')
            <option value="{{$master->val}}"   
                      @if($master->val == old('statusSelect')) selected="selected" @endif 
                            >{{ $master->name }}</option>
       @endif
       @endforeach
      </select>
         </td>

      
    </tr>
    <tr>
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Staffs</font></strong></td> 
          <td colspan="1" class="col-md-1" >
      <select class="form-control"   name="staffSelect" >
               <option value="">--------</option>
                @foreach($staffLst as $master)
                    <option value="{{$master->code}}"  
                     @if( $master->code == old('staffSelect') ) 
          selected="selected" @endif     >{{ $master->name }}</option>
                @endforeach
            </select>
         </td>
          <td colspan="2" class="col-md-1" >
             <button type="submit">Search</button>       
          </td>
      
    </tr>
 </table>
   </div> 
</div>
 </div>


    </form>   
  <form method="post" action="{{url('approve')}}" autocomplete="off" id="demoForm" class="demoForm">
             <div class="content" style="padding-left: 6.5%">
            
          </div>
          <input type="hidden" name="_method" value="POST">
          {{ csrf_field() }}
     
       <div class="col-sm-14"  > 
     <div class="ex1">
  <table  class="table table-hover table-bordered" >
    <thead >
      <tr>
         <td    class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Staff's Code</strong></td>
               <td  class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Name</strong></td>
               
            <td  class="fixheader" bgcolor="#CACFD2" colspan="4" nowrap><strong>Date-Time</strong></td> 
            <td  class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Type</strong></td>
            
            <td  class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Status</strong></td>
            <td  class="fixheader" bgcolor="#CACFD2" style="width: 250px" rowspan="2" colspan="4" ><strong>Apply Reason</strong></td>
               
                <td  class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Manager Comment</strong></td>
                 <td  class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Accept</strong><br>
                  <input type="checkbox" id="acceptCheck"  onclick="acceptFunction()"></td> 
                 </td>
                  <td  class="fixheader" bgcolor="#CACFD2" rowspan="2"><strong>Manager <br> Deny</strong>
                  <input type="checkbox" id="denyCheck"  onclick="denyFunction()">
                </td>
 <td  class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Created_at</strong></td>

            
     </tr>
     <tr>
            <td   class="fixheader2" bgcolor="#CACFD2" nowrap><strong>From Date</strong></td>  
            <td   class="fixheader2" bgcolor="#CACFD2" nowrap><strong>Term</strong></td>
            <td   class="fixheader2" bgcolor="#CACFD2" nowrap><strong>To Date</strong></td>
            <td   class="fixheader2" bgcolor="#CACFD2" nowrap><strong>Term</strong></td>
     </tr>
    </thead>
    <tbody>
       @foreach($dayAlls as $value)
        <tr>
           <td >{{ $value->usCode }} </td>
           <td  nowrap>{{ $value->usName }} </td>
         
           <td nowrap >
                
           <a href="{{action('ApproveController@appAprove', [$value->id,$value->typedb,$value->idType,$idlist,$typedblist,$value->id])}}">{{ $value->date }}</a>
          </td>
           <td >{{ $value->mtermFrom }} </td>
           <td nowrap>{{ $value->toDate }} </td>
           <td >
             @if(!is_null($value->toDate))
                  {{$value->mtermTo}}
             @endif
            </td>
           <td >{{ $value->mtype }} </td> 
           <td colspan="4" nowrap="true"  <?php if($value->statusCode == 108): ?> style="background-color:#00ff00;" <?php endif; ?>>
            {{ $value->mstatus}} 
             </td>
           <td  >{{ $value->note }} </td>
            
              <td >
                @if($userH->role==1 )
                <input class="input"   placeholder="note" value="{{$value->mannote1}}"  
                style="width: 90%" name="note[]"/>  
                @endif
                 @if($userH->role >=2 )
                <input class="input"  placeholder="note"   value="{{ $value->mannote2 }}"  
                style="width: 90%" name="note[]"/>  
                @endif
              </td>   
              <td>
          <input type="checkbox" name="accept[]" id="{{ $value->idType }}" disable="true"  
          value="{{ $value->idType }}"> 
              </td> 
              <td>
          <input type="checkbox" name="deny[]" id="{{ $value->idType }}" disable="true"  
         value="{{ $value->idType }}">  
              </td>  
               

                <input type="hidden" id="timeSID" name="timeSID[]" value="{{ $value->idType}}">  
                <input type="hidden" id="typedb" name="typedb[]" value="{{ $value->typedb}}"> 
                 
                 <td  nowrap>{{substr($value->created,0,10)}} </td>
        </tr>
    @endforeach
      <input type="hidden" id="idlist" name="idlist" value="{{ $idlist}}"> 
       <input type="hidden" id="typedblist" name="typedblist" value="{{ $typedblist}}">
               
    </tbody>
  </table>

 </div> 
 <div align="center">

   <tr>
              
              <button type="submit">Update</button>
              
            </tr>
            </div>
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
 
  height: 370px;

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
  <script>
    document.getElementById("myFrame").onload = function() {myFunction()};
  function myFunction() {
    

  var appfromFrame=  document.getElementById("appfrom").value;
    
 //alert(nationalflag);
  // alert(nationalflag);
  if (appfromFrame == ''){

          
          var date = new Date();
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
           
var lastDayWithSlashes; =  (lastDay.getMonth() + 1) + '/' +(lastDay.getDate()) + '/' + lastDay.getFullYear();

    document.getElementById("appfrom").value=lastDayWithSlashes;
           
          // 
          } 
}
   document.body.addEventListener('keyup',keyUpHandler,false);
    function keyUpHandler(e){  
        var evt = e || window.event;
        var target = evt.target || evt.srcElement;
        var key = e.keyCode || e.which;   
        //check if it is our required input with class test input
        if(target.className.indexOf('test-input') > -1){
            insertTimingColor(target,key)
        }
    }
    function insertTimingColor(element,key){
        var inputValue = element.value;
        if(element.value.trim().length == 2 && key !== 8){
            element.value = element.value + ':';
        }
    }
         function validate(evt) 
     {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
   var regex = /[0-9]|\+/;
  var regexJ = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();

  }
    var evt = e || window.event;
        var target = evt.target || evt.srcElement;
        var key = e.keyCode || e.which;   

        //check if it is our required input with class test input
        if(target.className.indexOf('test-inputsss') > -1){
            insertTimingColor(target,key)
        }
if(regexJ.test(key)) {
     theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
}
else {
    console.log("No Japanese characters");
}
   }
   $(".delete").on("submit", function(){
        return confirm("Do you want to Approve this dayoff order ?");
    });

  </script>
@endsection