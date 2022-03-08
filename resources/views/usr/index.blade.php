
@extends('timemaster')
@section('content')
      <div class="content container">
                 <div style="margin-top: -25px; float: left;" >
                   <b style="font-size: 18px"> &nbsp;&nbsp;    User List</b>
                   <!-- approve.aprove.blade -->
                </div>
           
                 @foreach($errors->all() as $error)
  <div class="alert alert-success">
          {{ $error }}
          </div>
          @endforeach
 @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
                <a
    href="#"
    data-target="#createModal"
    data-toggle="modal"
 >


     Create
</a>      
       </div>  
 <form method="get" action="{{ url('usrSearch') }}">
  <div class="row">
  <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
     
       <div class="content ">
   

  <table class="table table-hover table-bordered" >
    <tr>
       {{csrf_field()}}
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td class="col-md-2"><input type="text" style="width: 100%;padding:0px;"  id="nameScr"  name="nameScr"
                value="{{ old('nameScr') }}"  ></td> 
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Name</font></strong></td> 
                 <td class="col-md-2"><input type="text" style="width: 100%;padding:0px;"  id="codeScr"  name="codeScr"
                value="{{ old('codeScr') }}"  ></td>  
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
     
       <div class="content ">

  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
        <tr>
		      <td style=" background-color: #CACFD2;" nowrap><strong>Code</strong></td> 
                <td style=" background-color: #CACFD2;" nowrap><strong>Name</strong></td> 
          
         <td style=" background-color: #CACFD2;" nowrap><strong>Edit</strong></td>
            <td style=" background-color: #CACFD2;" nowrap><strong>Delete</strong></td>
             <td style=" background-color: #CACFD2;" nowrap><strong>Join Date</strong></td>
              <td style=" background-color: #CACFD2;" nowrap><strong>Leave Date</strong></td>
        </tr>  
    </thead>
    <tbody>
    @foreach($usrLst as $value)
        <tr>
           
        <td>{{ $value->code }}</td>
            <td>{{ $value->name }}</td>
             
            <td> <a href="#"
    data-target="#favoritesModal{{$value->id}}"
    data-toggle="modal"
    data-id="{{ $value->id }}"
    data-code="{{ $value->code }}"
 > Edit</a></td>
 <td>

     <form action="{{action('USRController@destroy',$value->id)}}" class="delete" method="post">
            {{ csrf_field() }}
                 <input name="_method" type="hidden" value="DELETE">

     <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit" id="delete"  onclick="clicked();">Delete </button>

          </form>
   
 </td>
<td>{{ $value->joinDate }}</td>
<td>{{ $value->leaveDate }}</td>
            
        </tr>
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
     <form method="post" action="{{action('USRController@update', $t->id)}}">
      <input type="hidden" name="_method" value="PUT">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
 <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Edit User</h4>
          
      </div>
      {{ csrf_field() }}
      <div class="modal-body">
              <div class="form-body">
             <label for="uname"><b>Code:</b></label>               
                "{{$t->code}}"             
          </div>
          <div class="form-body">
             <label for="uname"><b>Enail:</b></label>               
                <input type="text" class="form-control" name="email" id="email" 
               required value="{{$t->email}}">    
               <input type="hidden" class="form-control" name="code" id="code" 
               required value="{{$t->code}}">           
          </div>
          <div class="form-body">
             <label for="uname"><b>Login Name :</b></label>               
                <input type="text" class="form-control" name="userName" id="userName" 
               required value="{{$t->userName}}">             
          </div>
          <div class="form-body">
             <label for="uname"><b>Name:</b></label>               
                <input type="text" class="form-control" name="name" id="nameID" 
               required value="{{$t->name}}">             
          </div>
           <div class="form-body">
             <label for="uname"><b>Join Date :</b></label>               
                <input type="date" class="form-control" name="joinDate" id="joinDate" 
            required   value="{{ $t->joinDate}}" >              
          </div>
          <div class="form-body">
             <label for="uname"><b>Leave Date :</b></label>               
                <input type="date" class="form-control" name="leaveDate" id="leaveDate" 
               value="{{ $t->leaveDate}}" >              
          </div>
        
           

     
      </div>
      <div class="modal-footer">
        
         <button type="submit" class="btn btn-link" name="approve" value ="save">Approve</button>
     <button type="submit" class="btn btn-link" name="deny" value ="deny">Close</button>
         
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
             <label for="uname"><b>Email:</b></label>               
                 <input required type="text" class="form-control" name="email" id="email" 
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