
@extends('timemaster')
@section('content')
      <div class="content container">
           
          
                          <div style="margin-top: -25px;margin-left: -8%; float: left;" >
                   <b style="font-size: 18px"> Master List</b>
                </div>
                          
       </div>  
 <form method="get" action="{{ url('masterSearch') }}">
  <div class="row">
  <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
       <div class="content ">
  <table class="table table-hover table-bordered" >
    <tr>
       {{csrf_field()}}
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Name</font></strong></td> 
            <td class="col-md-2"><input type="text" style="width: 100%;padding:0px;"  id="nameScr"  name="nameScr"
                value="{{ old('companysrc') }}"  ></td> 
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Master's Type</font></strong></td> 
            <td class="col-md-2">
               <select  style="width: 100%"  class="form-control" name="masterTypeScr">
        <option  > </option>
                 @foreach($masterTypeS as $masterTypeS)
                   <option value="{{$masterTypeS->code}}"  
                    @if(   $masterTypeS->code == old('canUni')  ) selected="selected" @endif   >{{ $masterTypeS->name }}</option>
                @endforeach
            </select>
              </td>   
               <td    class="col-md-1"> <button type="submit">Search</button>     </td>       
   </tr>
 </table>
   </div> 
</div>
 </div>

    </form>   



             <div class="col-sm-2"  > </div> 
       <div class="col-sm-8"  > 
      <button type="button" data-toggle="modal" data-target="#createModal" style="margin-left: 0%;">
 Create
</button>
       <div class="content ">

  <table id="myTable" class="table table-bordered " content ="charset=UTF-8" >
    <thead >
        <tr>
                <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('name', 'Name')</strong></td> 
       <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('type', 'Type')</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('sort', 'Sort')</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Edit', 'Edit')</strong></td>
            <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Delete', 'Delete')</strong></td>
        </tr>  
    </thead>
    <tbody>
    @foreach($master as $value)
        <tr>
           
        <td>{{ $value->name }}</td>
            <td>{{ $value->type }}</td>
             <td>{{ $value->sort }}</td>
            <td> <a href="#"
    data-target="#favoritesModal{{$value->id}}"
    data-toggle="modal"
    data-id="{{ $value->id }}"
    data-code="{{ $value->code }}"
 > Edit</a></td>
 <td>

     <form action="{{action('MasterController@destroy',$value->id)}}" class="delete" method="post">
            {{ csrf_field() }}
                 <input name="_method" type="hidden" value="DELETE">

     <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit" id="delete"  onclick="clicked();">Delete </button>

          </form>
   
 </td>

            
        </tr>
    @endforeach
     <tr>
      <td colspan="10">
        <div class="pagination">
         {{ $master->appends(Request::except('page'))->links() }}   
        </div>      
      </td>
    </tr>
    </tbody>
</table>
 </div>  
 </div> 
 @foreach ($master as $t)    
 <div class="modal fade"  id="favoritesModal{{$t->id}}" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
     <form method="post" action="{{action('MasterController@update', $t->id)}}">
      <input type="hidden" name="_method" value="PUT">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
 <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Edit Master</h4>
          
      </div>
      {{ csrf_field() }}
      <div class="modal-body">
              <div class="form-body">
             <label for="uname"><b>Code:</b></label>               
                <input type="text" class="form-control" name="code" id="code" 
                value="{{$t->code}}">             
          </div>
          <div class="form-body">
             <label for="uname"><b>Name:</b></label>               
                <input type="text" class="form-control" name="name" id="name" 
               required value="{{$t->name}}">             
          </div>
         <div class="form-body">
             <label for="uname"><b>Type:</b></label>               
                <label for="uname">{{$t->type}}</label>                  
          </div>
            <div class="form-body">
             <label for="uname"><b>Sort:</b></label>               
                <input type="text" class="form-control" name="sort" id="sort" 
                value="{{$t->sort}}">             
          </div>

     
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
     <form method="post" action="{{url('master')}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Create Master</h4>
          
      </div>
      {{ csrf_field() }}
        <div class="modal-body">
                 <div class="form-body">
             <label for="uname"><b>Code:</b></label>               
                <input type="text" class="form-control" name="code" id="code" 
              required value="{{ old('code')}}" >              
          </div>
          <div class="form-body">
             <label for="uname"><b>Name:</b></label>               
                <input type="text" class="form-control" name="name" id="name" 
            required   value="{{ old('name')}}" >              
          </div>
         <div class="form-body">
             <label for="uname"><b>Type:</b></label>               
                   <select class="form-control"   name="type" id="type">
           @foreach($masterType as $masterType)
                    <option value="{{$masterType->code}}">{{ $masterType->name }}
                    </option>
         @endforeach
         <option  > </option>
      </select>              
          </div>
            <div class="form-body">
             <label for="uname"><b>Sort:</b></label>               
                <input type="text" class="form-control" name="sort" id="sort" 
                value="{{ old('sort')}}">             
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
        return confirm("Do you want to delete this Master ?");
    });
</script>    
    </body>
@endsection