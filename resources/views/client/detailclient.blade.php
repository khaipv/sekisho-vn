@extends('master')
@section('content')

        <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Company Detail   </b>
                   <!-- approve.approveDetail  -->
                </div>
                <div style="margin-top: -15px; float: center ;margin-right:  8%;" >
                   
 </div>
<div class="container">
<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
        <p style="margin-top: -2.4%;margin-bottom: 0%" align="left">
       <a  href="{{action('ClientControler@edit', $client->id)}}">Edit</a> 
          </p>
          <p style="margin-top: -2.4%" align="right">
 <?php 
    if (Session::has('ssClient')) {
                   $url = Session::get('ssClient');
              echo "<a href=".$url[0]."> Back</a> ";
                    } else {
                      echo   "<a href='javascript:history.back()'>Back &nbsp;&nbsp;&nbsp;  </a> ";

                    }
        
     ?>
  
  
 </p>
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
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status</font></strong></td> 
            <td class="col-md-2" >
       {{$client->status}}
      </td> 
    </tr>

	    </table>
        <div align="left" style="margin-top: -3%">
          <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
  @if(Auth::user()->role > 0)
          <form class="delete" action="{{action('ClientControler@destroy', $client->id)}}" method="post">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit" id="deleteBtn">Delete</button>
            
          </form> 

 @endif 
  
 </div>
 <hr  width="120%" style="margin-left: -10%"  align="center" />
       
 
 </div>
</div>
 
</div> 


<div class="row">

</div>
</div>
<div class="content">               
</div> 
<div class="content">

<div class="row">
  
    <div class="col-sm-2"  > </div>
    <div class="col-sm-8" >
   
 <div style="width: 100%">
  <table>
  <tr>
    <td nowrap="true">
                <b style="font-size: 18px;margin-left: 0% "  >    Division List   </b>  

 
     
  </td>
  <td width="74%"></td>
    <td nowrap="true">
      <form method="GET" action="{{ url('addnew') }}"   >
           
           

                          <input  id="companyinputid" name="companyid2" type="hidden"  value="{{$client->id}}"> 
        <input  id="countDiv" name="countDiv" type="hidden"  value="{{$count}}"> 
           
              
                    <button   >Add new Division</button>
  

        </form> 
        </td>
 </tr>
 </table>
 </div>
        
        <div id="divisionID">
          
      
  <table  class="table table-hover table-bordered" >
    <thead >
            <td bgcolor="#CACFD2" nowrap><strong>Code</strong></td> 
            <td bgcolor="#CACFD2" nowrap><strong>Branch／Division</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>Introduce</strong></td>
      <td bgcolor="#CACFD2" nowrap><strong>Status</strong></td>  
            <td  bgcolor="#CACFD2" nowrap><strong>Customer Importance</strong></td>
            <td bgcolor="#CACFD2" nowrap><strong>TEL</strong></td>
            <td  bgcolor="#CACFD2" nowrap><strong>PIC(Sekisho)</strong></td>        
    </thead>
    <tbody>
    @foreach($division as $value)
        <tr <?php if( !is_null( $value->status )): ?> style="background-color:#DCDCDC;text-align: right;" 
              <?php else : ?> style="text-align: right;" <?php endif; ?> >
            <td><a href="{{action('DivisionController@show', $value->id)}}">{{ $value->code }}</a></td>
            <td align="left">{{ $value->divisionname }}</td>
            <td align="left">{{ $value->introduceName }}</td>
              <td nowrap>{{ $value->statusName }}</td> 
            <td>{{ $value->rate2 }}</td> 
             <td>{{ $value->mobile }}</td>
             <td align="left">{{ $value->pics }}</td>


   
        </tr>
    @endforeach
 
    </tbody>
  
   
      
</table>
</div>

 

 
         
</div>

<div class="col-sm-2"  > </div>

</div>

</div>
<script type="text/javascript">
     document.getElementById("myFrame").onload = function() {myFunction()};

function myFunction() {
   var nationalflag= $('#countDiv').val();
   
   if(nationalflag > 0){
      document.getElementById("deleteBtn").style.display="none";
       
   } else {
    document.getElementById("divisionID").style.display="none";
   }
 }
  $(".delete").on("submit", function(){
        return confirm("Do you want to delete this Client ?");
    });
</script>

@endsection