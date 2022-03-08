@extends('master')
@section('content')
 
        <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Division Detail   </b>

                </div>

  <div style="margin-top: -4%; float: right ; " >
                   
 </div>
<div   >
 
  <div class="row">
                     <!-- approve.approveDetail  -->
    @if(session()->has('message'))
            <div style="text-align: center;" class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  style="bottom: -40px;margin-top: -5%;" >
   <p   align="right">
   <?php 
    if (Session::has('ssClient')) {
                   $url = Session::get('ssClient');
              echo "<a href=".$url[1]."> Back</a> ";
                    }
        if (Session::has('ssDivision')) {
                   $url = Session::get('ssDivision');
              echo "<a href=".$url[0]."> Back</a> ";
                    }
     ?>
 </p>
      <p style="margin-top: -2.4%" align="left">
       <a  href="{{action('DivisionController@edit', $division->id)}}">Edit</a> 
          </p>
  <table class="table table-hover table-bordered"  >
    <tr >
			<label for="lgFormGroupInput"  class="col-form-label col-form-label-sm" style="color: #556B2F"">{{$division->clientCode}}&nbsp;{{$division->clientName}}</label>
    </tr > 
	<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td colspan="3"  >
				{{$division->code}}
      
   </tr>
   <tr> 
   </td> 
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Division</font></strong></td> 
            <td colspan="3" >
            {{$division->divisionname}}
              </td>      
   </tr>
   	<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Tel</font></strong></td> 
            <td class="col-md-2" >
				{{$division->mobile}}
          </td> 
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Customer Importance</font></strong></td> 
            <td class="col-md-2">
            {{$division->rate2}}
              </td>     
             
   </tr>
    <tr >
          <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Introduce</font></strong></td> 
            <td class="col-md-2">
            {{$division->introduceName}}
              </td>     
            
   </tr>
   <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status</font></strong></td> 
            <td class="col-md-2" >
        {{$division->statusName}}
          </td> 
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">PIC(Sekisho)</font></strong></td> 
            <td class="col-md-2" >
        {{$division->name_s}}
          </td> 
   </tr>
    <tr >
          
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Condition of Trade</font></strong></td> 
            <td class="col-md-2">
            {{$division->condition}}
              </td>      
   </tr>
   <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Work Time</font></strong></td> 
            <td class="col-md-2" >
        {{$division->worktime1}}~{{$division->worktime2}}
          </td> 
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Holiday</font></strong></td> 
            <td class="col-md-2">
            {{$division->holidays}}
              </td>      
   </tr>
   <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
            Review Times</font></strong></td> 
            <td class="col-md-2" >
        {{$division->review}}
          </td> 
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
             Year Bonus</font></strong></td> 
            <td class="col-md-2">
            {{$division->yearBonus}}
              </td>      
   </tr>
     <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
           Welfare</font></strong></td> 
            <td class="col-md-2" >
        {{$division->welfare}}
          </td> 
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
             Other Welfare</font></strong></td> 
            <td class="col-md-2">
            {{$division->otherWelfare}}
              </td>      
   </tr>
       <tr nowrap >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Country</font></strong></td> 
            <td class="col-md-2" >
				{{$division->nationName}}
          </td> 
             <?php 
     if ($division->national_ID ==1) {
       echo "           <td  style='background: #7EC0EE'  class='col-md-1'><strong><font color='556B2F'>Province&emsp;/&ensp;District</font></strong></td> 
            <td class='col-md-2' >
        $division->provinceName / $division->districtName
          </td>   " ;                  
 
       
     }
      ?>
		 </tr>
  

         <tr  nowrap>
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
        Other&emsp;&emsp;&emsp;
      </font></strong></td> 
            <td colspan="3" class="col-md-5">
            {{$division->address}}
              </td>
       
           
   </tr>

    
    </div>
    </table>
 </div>
</div>
 
</div> 
 
  <div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-bordered"  >

      </table>
 </div>
</div>
</div> 



 <div align="center">    
          <hr  width="120%" style="margin-left: -10%"  align="center" /> 
</div>
    <td nowrap="true" align="left">
                <b style="font-size: 18px;margin-left: 0% "  >   Order List   </b>  

 
     
  </td>

<div class="content ">
        
 

 
  
  
<table class="table table-hover table-bordered " content ="charset=UTF-8" >
        
    <thead >
        <tr>
           <td nowrap bgcolor="#CACFD2"><strong>@sortablelink('code', 'Code')</strong></td> 
           <td nowrap bgcolor="#CACFD2"><strong>Title</strong></td> 
           <td nowrap bgcolor="#CACFD2"><strong>Progress</strong></td> 
           <td nowrap bgcolor="#CACFD2"><strong>Order Date(Plan)</strong></td> 
           <td nowrap bgcolor="#CACFD2"><strong>@sortablelink('introduceDate', 'Introduce Date')</strong></td> 
            <td nowrap bgcolor="#CACFD2"><strong>@sortablelink('type', 'Type')</strong></td> 
           <td nowrap bgcolor="#CACFD2"><strong>@sortablelink('pics', 'Pic Sekisho')</strong></td>
        </tr>
    </thead>
    <tbody>
    @foreach($order as $order)
        <tr>
           
            <td><a href="{{action('OrderController@show', $order->id)}}">{{ $order->code }}</a></td>
            <td align="left">{{ $order->title }}</td>
            <td align="left">{{ $order->progressName }}</td>
            <td>{{ $order->orderDate }}</td>
             <td nowrap>{{ $order->introduceDate }}</td>
              <td align="left" nowrap>{{ $order->typeName }}</td>
            <td align="left" nowrap> {{ $order->pics }}</td>

        </tr>

    @endforeach
   
    </tbody>
</table>
 </div>  
<div class="content">
 <div >
  <table>
 
</table>
  </div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-8"  >
       <hr  width="120%" style="margin-left: -10%"  align="center" />
      <table>
       <tr>
      <td nowrap="true" align="left">
                <b style="font-size: 18px ;margin-left: 0% "  >   Pic List   </b>  

 
     
  </td>
                   
          <td width="84%"></td>                
      <td nowrap="true" style="margin-right: 0px ">

    <form method="GET" action="{{ url('addnewpic') }}">
           
           
        <input  id="companyinputid" name="division2" type="hidden"  value="{{$division->id}}"> 
                
           
       
       <button class="btn btn-default" style=" background-color: #DCDCDC;">Add New PIC</button>
      
        </form> 
        </td>
   
</tr>
</table>
  <table class="table table-hover table-bordered" >

    <thead >
            <td nowrap bgcolor="#CACFD2"><strong>PIC's Name</strong></td> 
             
            <td nowrap bgcolor="#CACFD2"><strong>Department</strong></td>
            <td nowrap bgcolor="#CACFD2"><strong>Position</strong></td>
            <td nowrap bgcolor="#CACFD2"><strong>Email</strong></td>
            <td nowrap bgcolor="#CACFD2"><strong>Mobile</strong></td>
    </thead>
    <tbody>
    @foreach($staff as $value)
        <tr>
           <td align="left">
            @if (strlen ($value->firstName) >0) 
               <a href="{{action('PicController@show', $value->id)}}">{{$value->firstName}}&nbsp;{{$value->midleName}}&nbsp;{{$value-> lastName}}</a>
            @else
               <a href="{{action('PicController@show', $value->id)}}">{{$value->firstNameJ}}{{$value->midleNameJ}}{{$value-> lastNameJ}}</a>
            @endif

           </td>
          
            <td align="left">{{ $value->department }}</td>    
      <td align="left" >{{ $value->position }}</td>       
            <td align="left">{{ $value->email }}</td>
            <td align="left">{{ $value->mobile }}</td>
           
   
        </tr>
    @endforeach
    <tr>
      <td colspan="13">
        <div class="pagination">{!! str_replace('/?', '?', $staff->render()) !!}</div>      
      </td>
    </tr>
    </tbody>
    
</table>


 

</div>
<div class="col-sm-2"  > </div>

</div>
 @if(Auth::user()->role > 0)
          <form class="delete" action="{{action('DivisionController@destroy', $division->id)}}" method="post">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-default" style=" background-color: #DCDCDC;" id="delBtn" type="submit">Delete Division</button>
          </form>
          @endif
</div>
<iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
<input type="text" style="width: 20%" class="hidden" id="nationalID" placeholder="tell" name="national" 
        value="{{$division->national_ID}}">
<input type="text" style="width: 20%" class="hidden" id="countDelID" placeholder="tell" name="national" 
        value="{{$delCount}}">
</div>
 <script type="text/javascript">
  document.getElementById("myFrame").onload = function() {myFunction()};
  function myFunction() {
  var nationalflag=  document.getElementById("nationalID").value;
  var delFlag     = document.getElementById("countDelID").value;
   var provinceID = document.getElementById("provinceID");
  
 if (delFlag > 0){
           
             document.getElementById("delBtn").style.display="none";
        }
  if (nationalflag>1){
    
          
           provinceID.style.display="none";
          
          // 
          } else
          { 
           
            provinceID.style.display="block";
            
        }

}
$(".delete").on("submit", function(){
        return confirm("Do you want to delete this Division ?");
    });
</script>
@endsection