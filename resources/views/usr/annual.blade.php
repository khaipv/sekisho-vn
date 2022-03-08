
@extends('timemaster')
@section('content')
      <div class="content container">
                 <div style="margin-top: -25px; float: left;" >
                   <b style="font-size: 18px"> &nbsp;&nbsp;  Edit Annual</b>
                   <!-- usr.annual.blade -->
                </div>
           
     
 @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif      
       </div>  
        <div  style="margin-top: -15px;">

 <form method="get" action="{{ url('usrASearch') }}">
  <div class="row">
  <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
     
       <div style="width: 90%">
   

<table class="table table-hover table-condensed table-striped table-bordered" id="selecter">
   
   {{csrf_field()}}
    <tr>
       
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td class="col-md-2"><input type="text" style="width: 100%;padding:0px;"  id="codeScr"  name="codeScr"
                value="{{ old('codeScr') }}"  ></td> 
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Name</font></strong></td> 
                 <td class="col-md-2"><input type="text" style="width: 100%;padding:0px;"  id="nameScr"  name="nameScr"
                value="{{ old('nameScr') }}"  ></td>  
   </tr>
   <tr>
      <td  style="background: #7EC0EE";  ><strong><font color="556B2F">Year</font></strong></td> 
           <td colspan="1"  align="left" >
       <input type="number"   id="yearScr" name="yearScr"
           style="width: 40%;margin-right: 20%"   
             @if( isset($year) )
       value="{{$year }}"
       @else 
       value="{{ old('yearScr')}}"
       @endif
           >
         
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

       </div>

             <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
     
       <div class="content ">

  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
        <tr>
		      <td style=" background-color: #CACFD2;" nowrap><strong>Code</strong></td> 
                <td style=" background-color: #CACFD2;" nowrap><strong>Name</strong></td> 
                 <td style=" background-color: #CACFD2;" nowrap><strong>Year</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>Annual Leave</strong></td> 
       
             <td style=" background-color: #CACFD2;" nowrap><strong>Used</strong></td>
              <td style=" background-color: #CACFD2;" nowrap><strong>Remain</strong></td>
                <td style=" background-color: #CACFD2;" nowrap><strong>Edit</strong></td>
          
        </tr>  
    </thead>
    <tbody>
    @foreach($usrLst as $value)
    @if( is_null($value->leaveDate) || old('yearScr') <= $value->leaveDate)
        <tr>
           
        <td>{{ $value->code }}</td>
            <td>{{ $value->name }}</td>
             <td>{{ $value->year }}</td>
             <td> <?php 
             $annualLeaveval=0;
             if ( $value->annualLeaveDate > 0) {
                $annualLeaveval=$value->annualLeaveDate;
             } 
              echo (  $annualLeaveLST['a'.$value->code.$value->year] +$annualLeaveval ) ;

               ?>
                 
               </td>
           
<td>{{ $annualLeaveLST['a'.$value->code.$value->year] }}</td>
<td><?php echo  $annualLeaveval;  ?></td>
 <td> <a href="#"
    data-target="#favoritesModal{{$value->id}}"
    data-toggle="modal"
    data-id="{{ $value->id }}"
    data-code="{{ $value->code }}"
 > Edit</a></td>
 
            
        </tr>
        @endif
    @endforeach
     <tr>
     
    </tr>
    </tbody>
</table>
 </div>  
 </div> 
 @foreach ($usrLst as $t)    
 <div class="modal fade"  id="favoritesModal{{$t->id}}" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
     <form method="post" action="{{action('USRAController@update', $t->id)}}">
      <input type="hidden" name="_method" value="PUT">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
 <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Edit Annual</h4>
          
      </div>
      {{ csrf_field() }}
      <div class="modal-body">
              <div class="form-body">
             <label for="uname"><b>Code:</b></label>               
                <input type="hidden" class="form-control" name="code" id="codeID" 
                value="{{$t->code}}">   
                 <input type="hidden" class="form-control" name="id" id="codeID" 
                value="{{$t->id}}"> 
                <input type="hidden" class="form-control" name="year" id="year" 
                value="{{$t->year}}"> 
               <input type="hidden" class="form-control" name="offdays" id="year" 
                value="{{ $annualLeaveLST['a'.$t->code.$t->year]}}"> 

                {{$t->code}}          
          </div>
          <div class="form-body">
             <label for="uname"><b>Name:</b></label>               
                <input type="hidden" class="form-control" name="name" id="nameID" 
               required value="{{$t->name}}"> 
               {{$t->name}}            
          </div>
           <div class="form-body">
             <label for="uname"><b>Annual Day :</b></label>  
             <?php 
             $annualLeaveval=0;
             if ( $t->annualLeaveDate > 0) {
                $annualLeaveval=$t->annualLeaveDate;
             } 
             

               ?>             
                <input type="text" class="form-control" name="annualLeaveDateI" id="annualLeaveDateI" 
            required   value="{{ $annualLeaveLST['a'.$t->code.$t->year] +$annualLeaveval}}" >              
          </div>
        
           

     
      </div>
      <div class="modal-footer">
    <button type="submit" class="btn btn-link" name="deny" value ="deny">Close</button>
         <button type="submit" class="btn btn-link" name="approve" value ="save">Update</button>
    
         
      </div>
    </div>
  </div>
    </form> 
</div>  
  @endforeach
   <div class="modal fade"  id="createModal" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
     <form method="post" action="{{url('usrMaster')}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Create User</h4>
          
      </div>
      {{ csrf_field() }}
        <div class="modal-body">
                 <div class="form-body">
             <label for="uname"><b>Code:</b></label>               
                <input type="text" class="form-control" name="code" id="code" 
              required value="{{ old('code')}}" >              
          </div>
          <div class="form-body">
             <label for="uname"><b>Full Name:</b></label>               
                <input type="text" class="form-control" name="name" id="name" 
            required   value="{{ old('name')}}" >              
          </div>
          <div class="form-body">
             <label for="uname"><b>User Login :</b></label>               
                <input type="text" class="form-control" name="userName" id="userName" 
            required   value="{{ old('userName')}}" >              
          </div>
          <div class="form-body">
             <label for="uname"><b>Join Date :</b></label>               
                <input type="date" class="form-control" name="joinDate" id="joinDate" 
            required   value="{{ old('joinDate')}}" >              
          </div>
           <div class="form-body">
             <label for="uname"><b>Leave Date :</b></label>               
                <input type="date" class="form-control" name="leaveDate" id="leaveDate" 
               value=""{{ old('leaveDate')}}"  >              
          </div>
         <div class="form-body">
             <label for="uname"><b>Annual Leave:</b></label>               
                 <input type="number" class="form-control" name="annualLeave" id="annualLeaveID" 
            required    >           
          </div>
          <div class="form-body">
             <label for="uname"><b>Pass Word:</b></label>               
                <input id="password" type="password" class="form-control" name="password" required   >       
          </div>
           

     
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
   $(".delete").on("submit", function(){
        return confirm("Do you want to delete this User ?");
    });
</script>    
    </body>
@endsection