@extends('timemaster')
@section('content')
      <div class="content container">
           
                <div class="title m-b-md">
                   Dayoff List
                </div>
                <a
    href="#"
    data-target="#createModal"
    data-toggle="modal"
 >
     Create
</a>      
       </div>  

 <form method="get" action="{{ url('masterSearch') }}">
  <div class="row">
  <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
     
       <div class="content ">
   

  <table class="table table-hover table-bordered" >
   
    <tr >
         <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">From</font></strong></td> 
          <td colspan="1" class="col-md-1" >
       <input type="date"    id="canJob" name="fromDate" value="{{ old('fromDate')}}"
           style="width: 55%"    >
          
               
         </td> 
               <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">To</font></strong></td> 
          <td colspan="1" class="col-md-1" >
       <input type="date"    id="canJob" name="toDate" value="{{ old('toDate')}}"
           style="width: 55%"   >
         
               
  
      </td> 
    </tr>
    
 </table>

   </div> 
</div>
 </div>
<br>
<div align="center">
        <button type="submit">Search</button>        
</div> 
    </form>   
    <br>
    <br>
         <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
     <label for="lgFormGroupInput" class="col-sm-12 col-form-label col-form-label-sm">{{$userH->name}} . Annual Leave Remaining :  <?php echo ( floor( $userH->usTime/480)) ?> day 
      <?php echo ( floor( ($userH->usTime - floor( $userH->usTime/480)*480 )/60 )  ) ?> hours . 
      Compensation Remaining :  <?php echo ( floor( $userH->compenTime/480)) ?> day 
      <?php echo ( floor( ($userH->compenTime - floor( $userH->compenTime/480)*480 )/60 )  ) ?> hours .</label>
       <div class="content ">
 
  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
        <tr>
          <td  style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('usrName', 'Name')</strong></td> 
          <td colspan="2" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('fromDate', 'From')</strong></td> 
         <td colspan="2" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('toDate', 'To')</strong></td> 
        
          <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('status', 'Status')</strong></td>
             <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('note', 'Note')</strong></td>
                <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Leadnote', 'Leader Note')</strong></td>
            <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Reject', 'Edit')</strong></td>
             <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Cancel', 'Delete')</strong></td>
        
        </tr>  
    </thead>
    <tbody>
    @foreach($dayoff as $value)
        <tr>
           <td >{{ $value->usrName }} </td>
            <td >{{ $value->fromDate }} </td>
             <td > {{ $value->fromTime }}</td>
            <td >{{ $value->toDate }} </td>
             <td > {{ $value->toTime }}</td>
             <td>{{ $value->statusName }}</td>
               <td>{{ $value->note }}</td>
                <td>{{ $value->lNote }}</td>
            
            
            <td> 
              <?php if($value->status == '1'): ?>
              <a href="#"
    data-target="#favoritesModal{{$value->id}}"
    data-toggle="modal"
    data-id="{{ $value->id }}"
    data-code="{{ $value->code }}"
 > Edit</a>
  <?php endif; ?>
</td>
 <td>

     <form action="{{action('DayoffController@destroy',$value->id)}}" class="delete" method="post">
            {{ csrf_field() }}
                 <input name="_method" type="hidden" value="DELETE">

     <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit" id="delete"  onclick="clicked();"
      <?php if ($value->status == '2'){ ?> disabled <?php   } ?> >Delete </button>

          </form>
   
 </td>

            
        </tr>
    @endforeach
     <tr>
      <td colspan="10">
        <div class="pagination">
         {{ $dayoff->appends(Request::except('page'))->links() }}   
        </div>      
      </td>
    </tr>
    </tbody>
</table>
 
 </div>  
 </div> 
 @foreach ($dayoff as $t)    
 <div class="modal fade"  id="favoritesModal{{$t->id}}" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
     <form method="post" action="{{action('DayoffController@update', $t->id)}}">
      <input type="hidden" name="_method" value="PUT">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
 <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Edit Dayoff</h4>
          
      </div>
      {{ csrf_field() }}
      <div class="modal-body">
      <table class="table" >
          <tr> 
              <td class="col-md-1"  >
                <label for="uname"><b>From:</b></label>  
              </td>
              <td class="col-md-2"  >
                <input type="date"  class="form-control"  name="fromDate" id="fromDate" 
              required value="{{$t->fromDate}}" >    
              </td>
              
              <td class="col-md-4" style="margin-top : 30px;" >
                <input class="test-input" style="width: 40%" placeholder="hh:mm"
                 value="{{$t->fromTime}}" onkeypress='validate(event)'  required  name="fromTime"/>   
               </td>   
          </tr>
          <tr> 
              <td class="col-md-1"  >
                <label for="uname"><b>To:</b></label>  
              </td>
              <td class="col-md-2"  >
                <input type="date"  class="form-control"  name="toDate" id="toDate" 
              required value="{{$t->toDate}}" >    
              </td>
              <td class="col-md-4"  >
                <input class="test-input" style="width: 40%" placeholder="hh:mm" 
                value="{{$t->toTime}}" onkeypress='validate(event)' required  name="toTime"/>   
               </td>       
          </tr>
          <tr> 
              <td class="col-sm-1"  >
                <label for="uname"><b>Type:</b></label>  
              </td>
              <td class="col-sm-2"  >
                 <select name='type' >
            <option value="" <?php if(empty($kindDO)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($kindDO as $kind){
             if($kind->type =='dayoff'){
                echo "<option value='".$kind->val."'";
                 if( $kind->val  == $t->type )echo " selected='selected'  ";
                echo ">".$kind->name."</option>";
                  }
              }
            ?>
          </select>
              </td>  
          </tr>
     </table>
     <table>
          <tr> 
              <td class="col-sm-1"  >
                <label for="uname"><b>Note:</b></label>  
              </td>
              <td class="col-sm-9"  >
                 <textarea  name="note" class="form-control" 
                  style="resize: none;">{{$t->note}}</textarea>  
              </td>
                  
          </tr>
     </table>
    
      </div>
      <div class="modal-footer">
        <button type="button" 
           class="btn btn-default" 
           style=" background-color: #DCDCDC;""
           data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-default" style=" background-color: #DCDCDC;">Update</button>
         
      </div>
    </div>
  </div>
    </form> 
</div>  
  @endforeach
   
<div class="modal fade"  id="createModal" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
     <form method="post" action="{{url('dayoff')}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Create Master</h4>
          
      </div>
      {{ csrf_field() }}
        <div class="modal-body">
         <table class="table" >
          <tr> 
              <td class="col-md-1"  >
                <label for="uname"><b>From:</b></label>  
              </td>
              <td class="col-md-2"  >
                <input type="date"  class="form-control"  name="fromDate" id="fromDate" 
              required value="{{ old('fromDate')}}" >    
              </td>
              
              <td class="col-md-4" style="margin-top : 30px;" >
                <input class="test-input" style="width: 40%" placeholder="hh:mm" value="{{ old('fromTime')}}" required  name="fromTime" onkeypress='validate(event)'/>   
               </td>   
               
          </tr>
         
          <tr> 
              <td class="col-md-1"  >
                <label for="uname"><b>To:</b></label>  
              </td>
              <td class="col-md-2"  >
                <input type="date"  class="form-control"  name="toDate" id="toDate" 
              required value="{{ old('toDate')}}" >    
              </td>
              <td class="col-md-4"  >
                <input class="test-input" style="width: 40%" placeholder="hh:mm" value="{{ old('toTime')}}" onkeypress='validate(event)' required  name="toTime"/>   
               </td>       
          </tr>
          <tr> 
              <td class="col-sm-1"  >
                <label for="uname"><b>Type:</b></label>  
              </td>
              <td class="col-sm-2"  >
                 <select name='type' >
            <option value="" <?php if(empty($kindDO)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($kindDO as $kind){
             if($kind->type =='dayoff'){
                echo "<option value='".$kind->val."'";
                 if( $kind->val  == old('type') ) echo " selected='selected'  ";
                echo ">".$kind->name."</option>";
                  
                  }
              }
            ?>
          </select>
              </td>
                  
          </tr>
         
          
     </table>
         <table class="table" >   
           <tr> 
              <td class="col-sm-1"  >
                <label for="uname"><b>Note:</b></label>  
              </td>
              <td class="col-sm-7"  >
                 <textarea  name="note" class="form-control" value="{{ old('note')}}" style="resize: none;">
              </textarea>  
              </td>
                  
          </tr>
      
       
          <table>
      </div>
      <div class="modal-footer">
        <button type="button" 
           class="btn btn-default" 
           style=" background-color: #DCDCDC;"
           data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-default" style=" background-color: #DCDCDC;">Update</button>
      </div>
    </div>
  </div>
    </form> 
</div> 
  <script>
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
        return confirm("Do you want to delete this dayoff order ?");
    });
  </script>
  
@endsection