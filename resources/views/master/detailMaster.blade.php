@extends('master')
@section('content')
  <div class="content">
                <div class="title m-b-md">
                    Client Detail
                </div>
      </div> 
<div class="container">
<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" >
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td class="col-md-2" >
			 {{$client->code}}
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company</font></strong></td> 
            <td class="col-md-2" >
			 {{$client->companyname}}
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">URL</font></strong></td> 
            <td class="col-md-2" >
			 {{$client->url}}
			</td> 
		</tr>

	    </table>
 </div>
</div>
 
</div> 


<div class="row">
 <div align="center">
         <td> <a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;</a> 
           </td>     
            <td><a  href="{{action('ClientControler@edit', $client->id)}}">Edit</a> 
           </td> 
</div> 
</div>
</div>
<div class="content">               
</div> 
<div class="content">
 <div class="title m-b-md">
                   Division List 
  </div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-8" >
  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" nowrap><strong>Code</strong></td> 
            <td bgcolor="#CACFD2" nowrap><strong>Branch／Division</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Introduce</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Web Advertise</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>Customer Importance</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>TEL</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>PIC(Sekisho)</strong></td>        
    </thead>
    <tbody>
    @foreach($division as $value)
        <tr>
            <td><a href="{{action('DivisionController@show', $value->id)}}">{{ $value->code }}</a></td>
            <td>{{ $value->divisionname }}</td>
            <td>{{ $value->introduceName }}</td>
            <td>{{ $value->advertiseName }}</td>  
            <td>{{ $value->rate2 }}</td> 
             <td>{{ $value->mobile }}</td>
             <td>{{ $value->pics }}</td>


   
        </tr>
    @endforeach
    <tr>
      <td colspan="13">
        <div class="pagination">{!! str_replace('/?', '?', $division->render()) !!}</div>      
      </td>
    </tr>
    </tbody>
  
     <form method="GET" action="{{ url('addnew') }}">
           
           
      <td> <input  id="companyinputid" name="companyid2" type="hidden"  value="{{$client->id}}"></td>
       <td> <input  id="countDiv" name="countDiv" type="hidden"  value="{{$count}}"></td>
                
           
      
                    <button>Add new Division</button>
     

        </form> 
      
</table>

</div>

<div class="col-sm-2"  > </div>

</div>
          <form class="delete" action="{{action('ClientControler@destroy', $client->id)}}" method="post">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit" id="deleteBtn">Delete Client</button>
          </form> 
          <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
</div>
<script type="text/javascript">
     document.getElementById("myFrame").onload = function() {myFunction()};

function myFunction() {
   var nationalflag= $('#countDiv').val();
   
   if(nationalflag > 0){
      document.getElementById("deleteBtn").style.display="none";
   }
 }
  $(".delete").on("submit", function(){
        return confirm("Do you want to delete this Client ?");
    });
</script>

@endsection