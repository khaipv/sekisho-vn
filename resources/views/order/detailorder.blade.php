@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
    <br></br>
    <b style="font-size: 18px; margin-left: 220px  ">    Order's Detail  </b>
                   <!-- approve.approveDetail  -->
                </div>
                  <div style="margin-top: -4%; float: center ;margin-right:  8%;" >
                    
 </div>
<div class="container"  >
  <div class="content">
              
    <div align="left" style="margin-right:5em">
            
           
    </div> 
 </div> 
<div class="container">
<div class="row" style="margin-left:6em">


 <div class = "col-sm-11"  style="width: 88%" >
   <tr>
      <td><a  href="{{action('OrderController@edit',$order->id)}}">Edit</a> &emsp;
               <td><a  href="{{action('OrderController@clone',$order->id)}}" onclick="return confirm('Do you want to clone this order ?')">&nbsp;&nbsp;&nbsp;Clone</a> 
                <p style="margin-top: -2.4%" align="right">
<a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;  </a> 
 </p>
           </td> 
   </tr>
 
  
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-1" checked class="tab-switch">
      <label for="tab-1" class="tab-label" onclick="openCity(event, 'basicInfo')">Basic info</label>
      </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-2" class="tab-switch">
      <label for="tab-2" class="tab-label"onclick="openCity(event, 'detailInfo')">Detail info</label>
        </div>
   <br> <br> 

 


<div id="basicInfo" class="tabcontent">
  <label   class="col-sm-12 col-form " style="text-align: left;margin-left: -14px ">
    [Basic info]  
  </label>

  
  <table class="table-condensed table table-bordered" id="selecter">
   <input name="_method" type="hidden" value="PATCH">
   
   @if ($order->priority===1)
   <label   class="col-sm-12 col-form " style="color: rgb(255, 7, 7); text-align: center;margin-left: -14px; font-size: 25px; ">
     High Priority
   </label>
   @endif


   <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td colspan="3" class="col-md-5" >
     {{$order->code}}  
      </td> 
    </tr>

    
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company</font></strong></td> 
            <td colspan="3" class="col-md-5" >
      {{$order->clientName}}
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Division</font></strong></td> 
            <td colspan="3" class="col-md-5" >
      {{$order->divisionname}}
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Pic</font></strong></td> 
            <td class="col-md-2" >
      {{$order->picName}}
      </td>         
    </tr>
    <tr>
    <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Type</font></strong></td> 
            <td class="col-md-2" >
      {{$order->typeName}}
      </td> 
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Invoice</font></strong>     </td> 
         <td class="col-md-2" colspan="2" >
        <input type="checkbox" name="invoiceCK"
         <?php if( $order->invoiceCK == '1' ) {  echo "checked"; } ?> value="1" > 
          {{$order->invoiceDate}}
           
        </td> 
    </tr>
    <tr>
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Order Date</font></strong></td> 
            <td class="col-md-2" >
      {{$order->orderDate}}
      </td>
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Progress</font></strong></td> 
            <td class="col-md-1" >
      {{$order->progressName}}
      </td> 
    </tr>
    <tr>
     <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Introduce Date</font></strong></td> 
            <td class="col-md-2" >
      {{$order->introduceDate}}
      </td>
    </tr>

   

  
   
     
      </table>
      </div>
      
      
          
      <div id="detailInfo" class="tabcontent">
        
         <label   class="col-sm-12 col-form " style="text-align: left;margin-left: -14px ">
         [Detail info]   
        </label>
  <table class="table-condensed table-striped table-bordered" id="selecter">
     <div   class="tabcontent">
    <tr> 
       
        </tr>
    <tr>
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Project Title</font></strong></td> 
            <td class="col-md-2" >
      {{$order->title}}
      </td>       
    </tr>
    <tr >
         
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Working Date</font></strong></td> 
            <td class="col-md-2" >
      {{$order->workingDate}}
      </td>
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Recruitment Number</font></strong></td> 
            <td class="col-md-2" >
      {{$order->recruit_num}}
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Working place</font></strong></td> 
            <td colspan="3" class="col-md-5" >
      {{$order->workingPlace}}
      </td> 
    </tr>   

       
    <tr >
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Age</font></strong></td> 
            <td class="col-md-2" >
      {{$order->age}}
      </td> 
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Sex</font></strong></td> 
            <td class="col-md-2" >
      {{$order->sexName}}
      </td>  
    </tr> 
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Japanese Level</font></strong></td> 
            <td class="col-md-2" >
      {{$order->jpName}}
      </td> 
                  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">English Level</font></strong></td> 
            <td class="col-md-2" >
      {{$order->engName}}
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Indispensable Skill</font></strong></td> 
             <td colspan="3" class="col-md-5" >
      {{$order->indispensable}}
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Other Skill</font></strong></td> 
             <td colspan="3" class="col-md-5" >
      {{$order->skill}}
      </td> 
    </tr>
     <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
            Salary From</font></strong></td> 
            <td class="col-md-2" >
              @if($order->salaryFrom >0)
      <input type="text" id="salaryFromID"   style='background:white; border-style: none'
        value="{{ number_format($order->salaryFrom).$order->unitSaFrom }}" name="salaryFrom" disabled >
               @endif
      </td> 
         <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
            Salary To</font></strong></td> 
            <td class="col-md-2" >
              @if($order->salaryTo >0)
      <input type="text" id="salaryToID"   style='background:white; border-style: none'
        value="{{ number_format($order->salaryTo).$order->unitSaTo }}" name="salaryTo" disabled >
               @endif
      </td>                      
    </tr>
    <tr>
     <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Introduce Fee (From) </font></strong></td> 
            <td class="col-md-2" >
               @if($order->introduceFee >0)
      <input type="text" id="introduceFeeID"   style='background:white; border-style: none'
        value="{{number_format($order->introduceFee).$order->unitFrom}}" name="introduceFee" disabled >
               @endif
      </td> 
    </tr>
  <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Partner</font></strong></td> 
            <td class="col-md-2" >
      {{$order->partner}}
      </td> 
                  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Partner Fee</font></strong></td> 
            <td class="col-md-2" >
               @if($order->partnerfee >0)
      <input type="text" id="partnerFeeID"   style='background:white; border-style: none'
        value="{{number_format($order->partnerfee)}}" name="partnerFee" disabled >
         @endif
      </td> 
    </tr>

      <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Warranty Period</font></strong></td> 
               <td colspan="3" class="col-md-5" >
      {{$order->warrantyPeriod}}
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Warranty</font></strong></td> 
                <td colspan="3" class="col-md-5" >
      {{$order->warrantyName}}
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Note</font></strong></td> 
             <td colspan="3" class="col-md-5" >
      {{$order->note}}
      </td> 
    </tr>
    
    </div>
 </table>
 </div>  
       @if(Auth::user()->role > 0)

        <form class="delete" action="{{action('OrderController@destroy', $order->id)}}" method="post">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit">Delete Order</button>
        </form> 
        @endif
 </div>
 </div>
<br><br>
 
<div class="container">
     <a  href="{{action('OrderCandiController@edit',$order->id)}}" onclick="return confirm('Do you want to change candidates this order ?')">&nbsp;&nbsp;&nbsp;Add Candidate List</a> 
   @if(!is_null($candilist))
<div class="ex1">
  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
         <tr>
            <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Code</strong></td> 
            <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Name</strong></td>
           
            <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Birthday</strong></td>
            <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Status</strong></td>
            <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Introduce Date</strong></td>
            <td class="fixheader" bgcolor="#CACFD2" nowrap><strong>Enter Date</strong></td>
            <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Note</strong></td>
      
     </tr>
   
    </thead>
    <tbody>
        <input name="orderID2" type="hidden"  value="{{$order->id}}">  

    @foreach($candilist as $value)
        <tr>
            <td nowrap="true"  >{{ $value->code }} </td>
        <td nowrap="true"  >{{$value->firstName}}&nbsp;{{$value->midleName}}&nbsp;{{$value-> lastName}}</td>
        <td nowrap="true"  >{{ $value->birth }} </td>
         <td nowrap="true"  >{{ $value->statusName }} </td>
        
        <td nowrap="true"> 
         {{$value->introduceDate}}
             </td>
       <td nowrap="true"> 
                  {{$value->enterDate}}
             </td>
       <td > {{$value->note}}
       </td>
 
              
              
          
          
    

            
        </tr>
    @endforeach
    
    </tbody>
</table>
</div>
 @endif
 
 
 
 

 </div>

<div  align="left" style="margin-left: 0px">


</div>


 <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>

         
</div>

<script type="text/javascript">
   document.getElementById("myFrame").onload = function() {myFunction()};
  function myFunction() {

 // var detailInfo=  document.getElementById("detailInfo").value;
   document.getElementById("detailInfo").style.display="none";
 //  detailInfo.style.display="block";
}
  $(".delete").on("submit", function(){
        return confirm("Do you want to delete this Order ?");
    });

 
function openCity(evt, tabName) {

  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
<style>
body {font-family: Arial;}

/* Style the tab */


/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
 
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}

/* new menu tab*/
* {
  box-sizing: border-box;
}
body {
  font-family: "Open Sans";
  
  line-height: 1.618em;
}
.wrapper {
  max-width: 50rem;
  width: 100%;
  margin: 0 auto;
}
.tabs {
  position: relative;
  margin: 3rem 0;
  background: #D3D3D3;
  height: 14.75rem;
}
.tabs::before,
.tabs::after {
  content: "";
  display: table;
}
.tabs::after {
  clear: both;
}
.tab {
  float: left;
}
.tab-switch {
  display: none;
}
.tab-label {
  position: relative;
  display: block;
  line-height: 2.75em;
  height: 3em;
  padding: 0 1.618em;
  background: #D3D3D3;
  border-right: 0.125rem solid #16a085;
  color: #fff;
  cursor: pointer;
  top: 0;
  transition: all 0.25s;
}
.tab-label:hover {
  top: -0.25rem;
  transition: top 0.25s;
}
.tab-content {
  height: 12rem;
  position: absolute;
  z-index: 1;
  top: 2.75em;
  left: 0;
  padding: 1.618rem;
  background: #b3b3ff;
  color: #2c3e50;
  border-bottom: 0.25rem solid #bdc3c7;
  opacity: 0;
  transition: all 0.35s;
}
.tab-switch:checked + .tab-label {
  background: #1abc9c;
  color: #2c3e50;
  border-bottom: 0;
  border-right: 0.125rem solid #fff;
  transition: all 0.35s;
  z-index: 1;
  top: -0.0625rem;
}
.tab-switch:checked + label + .tab-content {
  z-index: 2;
  opacity: 1;
  transition: all 0.35s;
}



</style>
@endsection