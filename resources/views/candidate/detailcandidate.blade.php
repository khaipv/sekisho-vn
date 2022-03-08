@extends('master')
@section('content')

<div style="margin-top: -4%; float: left;margin-left: 1%;" >
 <b style="font-size: 18px">   Candiates Detail   </b>
 <!-- approve.approveDetail  -->
</div>

<div class="container">

 <div class="row">
  
   <div class="col-sm-15"  > 
     <div class="col-sm-1"  > </div> 
     <div class = "col-sm-10"  >
       @if(session()->has('message'))
       <div style="text-align: center;" class="alert alert-success">
        {{ session()->get('message') }}
      </div>
      @endif
     
    
        <div style="text-align: center;" id="messdivid" class="alert alert-success"
        hidden >
 <label id="lbltipAddedComment"></label>
</div>
      <input name="_method" type="hidden" value="PATCH">
      <tr>
      
       <p style="margin-top: -2.4%" align="right">
         <?php 
         if (Session::has('ssCandidate')) {
           $url = Session::get('ssCandidate');
           echo "<a href=".$url[0]."> Back</a> ";
         }
         else echo "<a href='javascript:history.back()'>Back &nbsp;&nbsp;&nbsp;  </a> ";
         
         ?>


       </p>
     </tr>



     <div class="tab">
      <input type="radio" name="css-tabs" id="tab-1" checked class="tab-switch">
      <label for="tab-1" class="tab-label" onclick="openCity(event, 'basicInfo')">Basic info</label>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-2" class="tab-switch">
      <label for="tab-2" class="tab-label"onclick="openCity(event, 'detailInfo')">Order info</label>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-3" class="tab-switch">
      <label for="tab-3" id="eduInfoID" class="tab-label"onclick="openCity(event, 'eduInfo')">Education</label>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-4" class="tab-switch">
      <label for="tab-4" id="jobInfoID" class="tab-label"onclick="openCity(event, 'jobInfo')">Job History</label>
    </div>
    <div class="tab">
      <input type="radio" name="css-tabs" id="tab-5" class="tab-switch">
      <label for="tab-5" id="actInfoID" class="tab-label"onclick="openCity(event, 'actInfo')">     Action                          </label>
    </div>
    <br> <br> 

    <div id="basicInfo" class="tabcontent">
     <table class="table table-hover table-bordered" >
       <p align="left"><a  href="{{action('CandidateController@edit', $id)}}">Edit</a> </p>
      <tr >
        <td  style="background: #7EC0EE" ><strong><font color="556B2F">
        NO</font></strong></td> 
        <td class="col-md-1" >
          {{$candi->code}}
        </td> 
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
        Update Time</font></strong></td> 
        <td class="col-md-1" >
          {{substr( $candi->updated_at, 0, 10)}}
        </td> 
      </tr>
      <tr >
        <td  style="background: #7EC0EE"  width="27%" ><strong><font color="556B2F">
        Name (First Middle Last Name )</font></strong></td> 
        <td  width="30.5%" >
          {{$candi->firstName}}&nbsp;{{$candi->midleName}}&nbsp;{{$candi-> lastName}}
        </td> 
        <td  style="background: #7EC0EE"   width="20.5%" ><strong><font color="556B2F"> Name （JP/VN）</font></strong></td> 
        <td  width="30.5%" >
          {{$candi->firstNameJ}}&nbsp;{{$candi-> midleNameJ}}&nbsp;{{$candi-> lastNameJ}}
        </td> 
      </tr>

      @if (!is_null($actionlast))
      <tr >
        <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Follow</font></strong></td> 
        <td colspan="3" class="col-md-1" >
         {{$actionlast->job_seeking_need  }} {{ $actionlast->date }} {{ $actionlast->time_start }} ({{ $actionlast->name }})
       </td> 
       
     </tr>
     @endif

      <tr >
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
        Birthday</font></strong></td> 
        <td style="width: 28%">
         {{$birth2->birth2}}
       </td>
       <td  colspan="2" >
        <table class="table"  style="  margin: -7px">       
         <td  style="background: #7EC0EE"  width="20%"  nowrap="true"><strong><font color="556B2F">
         Marital Status</font></strong></td> 
         <td     width="22%"  nowrap="true">
           {{$candi->married}}
         </td> 
         <td  style="background: #7EC0EE" nowrap="true" ><strong><font color="556B2F">
         Sex *</font></strong></td> 
         <td     nowrap="true">
           {{$candi->sexName}}
         </td> 
       </table>
     </td> 
   </tr>
   
   <tr >
    <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
    Email</font></strong></td> 
    <td class="col-md-1" >
      {{$candi->email}}
    </td> 
    <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Email Status</font></strong></td> 
    <td class="col-md-1" >
      {{$candi->emsName}}
    </td> 
  </tr>
  <tr>
    <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
    Mobile</font></strong></td> 
    <td class="col-md-1" >
      {{$candi->mobile}}
    </td> 
    <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Mobile Status</font></strong></td> 
    <td class="col-md-1" >
      {{$candi->mbsName}}
    </td> 
  </tr>
  
  <tr >
   <td  style="background: #7EC0EE"  ><strong><font color="556B2F">University</font></strong></td> 
   <td colspan="3" class="col-md-1" >
    {{$candi->uName}}
  </td> 
  
</tr>
<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Majors </font></strong></td> 
  <td class="col-md-1" colspan="3" >

    {{$candi->majorName}}
  </td> 

  
</tr>
<tr>
 <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Graduate Year</font></strong></td> 
 <td class="col-md-1" >
  {{$candi->graduates}}
</td> 
<td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
Foreigner </font></strong></td>   
<td class="col-md-1"  >
  
  <input type="checkbox" name="foreigner" 
  <?php if( $candi->foreigner == '1' ) {  echo "checked"; } ?>  value="1"> Foreigner
  
</td> 
</tr>
<tr >
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
  Current address</font></strong></td> 
  <td class="col-md-1" >
    {{$candi->currentAdd}}
  </td> 
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Birth Place</font></strong></td> 
  <td class="col-md-1" >
    {{$candi->birth_Place}}
  </td> 
</tr>
<tr >
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
  English level</font></strong></td> 
  <td class="col-md-1" >
    {{$candi->eLevelName  }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  {{$candi->toeic}}
  </td> 
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Japanese level</font></strong></td> 
  <td class="col-md-1" >
    {{$candi->jLevelName}}
  </td> 
</tr>
<tr >
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
  Korean level</font></strong></td> 
  <td class="col-md-1" >
    {{$candi->kname  }}  
  </td> 
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Chinese level</font></strong></td> 
  <td class="col-md-1" >
    {{$candi->chname}}
  </td> 
</tr>
<tr >
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
  Workplace desired</font></strong></td> 
  <td class="col-md-1" >
   {{$candi->wpName}}
 </td> 
 <td  colspan="2" >
   {{$candi->workPlaceTxt}}
 </td> 
</tr>
<div id="divWP">
 <tr <?php 
 
 if ( $wpflage==0 ) {
   echo " style='visibility:collapse' ";
 }
 
 ?>  >
 <td  style="background: #7EC0EE"  class="col-md-1">
  
 </td> 
 <td class="col-md-1"  colspan="3" >      
   
  <?php 
  foreach($workplaceLst as $copor){
    if (in_array($copor->Id,$workplace_selected ) && !is_null($copor->Id)) {
                    # code...
     
     echo "<div class='col-sm-4'>
     
     <input type='checkbox' name='workplace_select[]' value='".$copor->Id."'";
     if(in_array($copor->Id,$workplace_selected ))  echo "checked";
     echo "> ".$copor->Name."</div>";
     
   }
 }
 ?>
 
</td> 
</tr>
</div>
<tr >
  <td  style="background: #7EC0EE" ><strong><font color="556B2F">
  Pre-Interview</font> </strong></td> 
  <td class="col-md-1" >
   <input type="checkbox" name="vehicle" disable="true" value="Bike" 
   <?php if( $candi->mandan == '1' ) {  echo "checked"; } ?> >    {{$candi->mandanDate}}
 </td> 
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
 Staff Pre-interview</font></strong></td> 
 <td class="col-md-1" >
  {{ $candi->name}}
</td> 
</tr>
<tr>
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
 Entering</font></strong></td> 
 <td class="col-md-1" >
   <input type="checkbox" name="vehicle" disable="true" value="Bike" 
   <?php if( $candi->workcheck == '1' ) {  echo "checked"; } ?> >   {{$candi->workDate}}
 </td> 
 
</tr>
<tr>
 <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Source</font></strong></td> 
 <td class="col-md-1" >
  {{$candi->sourceName}}
</td> 
<td colspan="2">
  @if($candi->source==145)
  {{$candi->sourceTxt}}
  @endif
</td>


</tr>

<tr>
 
</tr>
<tr>
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Left=Desire : Right=Experience </font></strong></td> 
  <td class="col-md-1" colspan="3" >
    
    <?php 
    $itcount=0;
    foreach($subitemsIT as $copor){
     if($copor->code =='1' && (in_array($copor->val,$occupation )
      ||in_array($copor->exp,$experience))  ){
      if ($itcount==0) {
        $itcount++;
        echo "<label style= 'text-align: left;width: 100% '>IT </label>  ";
      }
      echo "<div class='col-sm-4'>
      <input type='checkbox' name='itemsIT_select[]' value='".$copor->val."'";
      if(in_array($copor->val,$occupation))  echo "checked";
      echo "> 
      <input type='checkbox' name='itemsIT_select[]' value='".$copor->exp."'";
      if(in_array($copor->exp,$experience))  echo "checked";
      echo "> ".$copor->name."</div>  ";
    }
  }
  if ($itcount==0 && strlen(trim($candi->otherIT)) >1 ) {
   
    echo "<label style= 'text-align: left;width: 100% '>IT </label>  ";
  }
  echo $candi->otherIT;
  ?>
  
  
  <?php 
  $Mancount=0;
  foreach($subitemsIT as $copor){
   if($copor->code =='2' && (in_array($copor->val,$occupation )
    ||in_array($copor->exp,$experience))  ){
    if ($Mancount==0) {
      $Mancount++;
      echo "<label style= 'text-align: left;width: 100% '>Manufacturing </label>  ";
    }

    echo "<div class='col-sm-4'>
    <input type='checkbox' name='itemsTech_select[]' value='".$copor->val."'";
    if(in_array($copor->val,$occupation))  echo "checked";
    echo ">
    <input type='checkbox' name='itemsTech_select[]' value='".$copor->exp."'";
    if(in_array($copor->exp,$experience))  echo "checked";
    echo "> ".$copor->name."</div>  ";
  }
}
if ($Mancount==0 && strlen(trim($candi->otherManu)) >1 ) {
 
  echo "<label style= 'text-align: left;width: 100% '>Manufacturing </label>  ";
}


echo $candi->otherManu;
?>    


<?php 
$Technologycount=0;
foreach($subitemsIT as $copor){
 if($copor->code =='3'  && (in_array($copor->val,$occupation )
  ||in_array($copor->exp,$experience)) ){
  if ($Technologycount==0) {
    $Technologycount++;
    echo "<label style= 'text-align: left;width: 100% '>Technology</label>  ";
  }

  echo "<div class='col-sm-4'>
  <input type='checkbox' name='itemOther_select[]'
  value='".$copor->val."'";
  if(in_array($copor->val,$occupation))  echo "checked";
  echo "> 
  <input type='checkbox' name='itemOther_select[]'
  value='".$copor->exp."'";
  if(in_array($copor->exp,$experience))  echo "checked";
  echo "> ".$copor->name."</div>";
}
}
if ($Technologycount==0 && strlen(trim($candi->otherTech)) >1 ) {
 
  echo "<label style= 'text-align: left;width: 100% '>Technology </label>  ";
}

echo $candi->otherTech;
?> 

<?php 
$Admincount=0;
foreach($subitemsIT as $copor){
 if($copor->code =='4'  && (in_array($copor->val,$occupation )
  ||in_array($copor->exp,$experience)) ){
  if ($Admincount==0) {
    $Admincount++;
    echo "<label style= 'text-align: left;width: 100% '>Admin</label>  ";
  }

  echo "<div class='col-sm-4'>
  <input type='checkbox' name='itemOther_select[]'
  value='".$copor->val."'";
  if(in_array($copor->val,$occupation))  echo "checked";
  echo "> 
  <input type='checkbox' name='itemOther_select[]'
  value='".$copor->exp."'";
  if(in_array($copor->exp,$experience))  echo "checked";
  echo "> ".$copor->name."</div>";
}
}
if ($Admincount==0 && strlen(trim($candi->otherAdmin)) >1 ) {
 
  echo "<label style= 'text-align: left;width: 100% '>Admin </label>  ";
}

echo $candi->otherAdmin;
?>     
<?php 
$Othercount=0;
foreach($subitemsIT as $copor){
 if($copor->code =='5'  && (in_array($copor->val,$occupation )
  ||in_array($copor->exp,$experience)) ){
   if ($Othercount==0) {
    $Othercount++;
    echo "<label style= 'text-align: left;width: 100% '>Other</label>  ";
  }
  echo "<div class='col-sm-4'>
  <input type='checkbox' name='itemOther_select[]'
  value='".$copor->val."'";
  if(in_array($copor->val,$occupation))  echo "checked";
  echo "> 
  <input type='checkbox' name='itemOther_select[]'
  value='".$copor->exp."'";
  if(in_array($copor->exp,$experience))  echo "checked";
  echo "> ".$copor->name."</div>";
}
}
if ($Othercount==0 && strlen(trim($candi->other)) >1 ) {
 
  echo "<label style= 'text-align: left;width: 100% '>Other </label>  ";
}

echo $candi->other;
?>     
</td>        

</tr>
<tr>
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Skill</font></strong></td>   
  <td class="col-md-3" colspan="3" >
   <!--  <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="interview" value="{{ old('interview')}}" > -->
   
   @foreach($skillList as $value)
   {{$value->name}} <br>
   @endforeach
 </tr>
 <tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Note</font></strong></td>   
  <td class="col-md-3" colspan="3" >
   <!--  <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="interview" value="{{ old('interview')}}" > -->
   
   <div style="overflow-y: auto;white-space: pre-wrap;">{{$candi->plan}}</div>
 </td> 
</tr>  
<tr >
  <td    class="col-md-1"><strong><font color="556B2F">
  Evaluation/評価</font></strong></td>   
  
</tr>  
<tr >
  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
  Evaluation Date</font></strong></td> 
  <td class="col-md-1" >
    {{$candi->evalDate  }}  
  </td> 
</tr>
<tr>
 <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Japanese</font></strong></td> 
 <td class="col-md-1" >
  {{$candi->jpevalname}}
</td> 
<td  style="background: #7EC0EE"  ><strong><font color="556B2F">English</font></strong></td> 
<td class="col-md-1" >
  {{$candi->engevalname}}
</td> 
</tr>
<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Total Evaluation</font></strong></td>   
  <td class="col-md-3" colspan="3" >
   <!--  <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="interview" value="{{ old('interview')}}" > -->
   
   <div style="overflow-y: auto;white-space: pre-wrap;">{{$candi->evaluation}}</div>
 </td> 
</tr>  
</table>

@if(Auth::user()->role > 0)
<p > 
  <form class="delete" action="{{action('CandidateController@destroy', $id)}}" method="post">
    {{csrf_field()}}
    <input name="_method" type="hidden" value="DELETE">
    <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit">Delete</button>
  </form>
</p>
@endif   
</div>
<div align="left">
  <div id="detailInfo" class="tabcontent">

   <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
     <tr>
      <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Code</strong></td> 
      <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Company</strong></td>
      <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Title</strong></td>
      <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Introduce Date</strong></td>
      <td class="fixheader" bgcolor="#CACFD2" nowrap><strong>Enter Date</strong></td>
      <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Note</strong></td>
    </tr>
  </thead>
  <tbody>
    @foreach($orderlist as $value)
    <tr>
      <td nowrap="true"  >{{ $value->ordCode }} </td>
      <td wrap  >{{$value-> clientName}}<br>( {{$value-> divname}})</td>
      <td > {{ $value->title }} </td>
      <td nowrap="true"> {{ $value->introduceDate }} </td>
      <td nowrap="true"> {{ $value->enterDate }} </td>
      <td> {{ $value->note }} </td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>     
<div id="eduInfo" class="tabcontent">
 <div style="text-align: left;">
   
   
   <form method="GET" action="{{ url('addNewEdu') }}" target="_blank"  >
           
           

                          <input  id="canID" name="canID" type="hidden"  value="{{$id}}"> 
   
           
              
                    <button  target="_blank"  >Create</button>
           
  

        </form>   
                          
 <br><br>
</div>  
<table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
  <thead >
   <tr>
    <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong></strong></td> 
    <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Date</strong></td>
    <td class="fixheader" bgcolor="#CACFD2"  ><strong>Education</strong></td>
    <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong></strong></td>
    
  </tr>
</thead>
<tbody>
 
  @foreach($educations as $value)
  <tr>
   <td width="5%">
        <form method="GET" action="{{ url('editEdu') }}" target="_blank"  >
           
           

                          <input  id="canID" name="canID" type="hidden"  value="{{$value->id}}"> 
   
           
              
                    <button  target="_blank"  >SEL</button>
  

        </form>  

  </form>
  </td>   
  <td width="10%">{{ $value->date }}</td>
  <td style="max-width:150px;word-break: break-all;">{{ $value->education }}</td>
  
  
 <td width="5%">

   <form action="{{action('EduController@destroy',$value->id)}}" class="delete" method="post">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="DELETE">

    <button   type="submit" id="delete"  onclick="clicked();">DEL </button>

  </form>
  
</td>


</tr>
@endforeach
</tbody>
</table>
</div>   
@foreach ($educations as $edu)    
<div class="modal fade"  id="favoritesModal{{$edu->id}}" 
 tabindex="-1" role="dialog" 
 aria-labelledby="favoritesModalLabel">
 <form method="post" action="{{action('EduController@update', $edu->id)}}">
  <input type="hidden" name="_method" value="PUT">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title" 
      id="code">Edit Education</h4>
      
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
     
      <div class="form-body">
       <label for="uname"><b>Date:</b></label>               
       <input type="date" name="date"  style="width : 30%"  value="{{ $edu -> date }}"  class="form-control" />        
     </div>
     <div class="form-body">
       <label for="uname"><b>Education:</b></label>               
       <input type="text" class="form-control" name="education" id="education"  maxlength="200"
       value="{{$edu->education}}">               
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
<form method="post" action="{{url('education')}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Add Education</h4>
        
      </div>
      {{ csrf_field() }}
      <div class="modal-body">
       
        <div class="form-body">
         <label for="uname"><b>Date:</b></label>               
         
         <input type="date" name="datecr"   style="width : 30%"   class="form-control" /> 
         <input type="hidden" class="form-control" name="canID" id="canID" 
         value="{{$candi->id}}" 
         >         
       </div>
       <div class="form-body">
         <label for="uname"><b>Education:</b></label>         
         <input type="text" class="form-control" name="educationcr" id="education" maxlength="200"
         >        
         
       </div>
       
       
     </div>
     <div class="modal-footer">
      <button type="button" 
      class="btn btn-default" 
      style=" background-color: #DCDCDC;"
      data-dismiss="modal">Close</button>

      <input type="button" value="更新" onclick="window.opener.location.reload(),window.close()">
      <button type="submit" class="btn btn-default" style=" background-color: #DCDCDC;">Update</button>
    </div>
  </div>
</div>
</form> 
</div>  


<div id="jobInfo" class="tabcontent">
 <div style="text-align: left;">
   
   
<form method="GET" action="{{ url('addNewJob') }}" target="_blank"  >
           
           

                          <input  id="canID" name="canID" type="hidden"  value="{{$id}}"> 
   
           
              
                    <button  target="_blank"  >Create</button>
  

        </form>      
</div>  
<br> 
<table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
  <thead >
   <tr>
    <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong></strong></td> 
    <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Term</strong></td>
    <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Company</strong></td>
    <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Position</strong></td>
    <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Occupation</strong></td>
    <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Detail</strong></td>    
    <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong></strong></td>
    
  </tr>
</thead>
<tbody>
 
  @foreach($hisjoblst as $value)
  <tr>
      <td width="5%">
   <form method="GET" action="{{ url('editJob') }}" target="_blank"  >
   <input  id="canID" name="canID" type="hidden"  value="{{$value->id}}">
   <button  target="_blank"  >SEL</button>
   </form>  
  </td>   
  <td width="20%">{{ $value->from }}   @if(!is_null ($value->to)) - {{ $value->to  }}
    @endif </td>
  <td style="max-width:150px;word-break: break-all;">{{ $value->company }}</td>
 <td style="max-width:150px;word-break: break-all;">{{ $value->position }}</td>
  <td width="8%">{{ $value->occupation }}</td>
 <td style="max-width:150px;word-break: break-all;">{{ $value->detail }}</td>

  
<td width="5%">

   <form action="{{action('HisjobController@destroy',$value->id)}}" class="deletejobs" method="post">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="DELETE">

    <button   type="submit" id="deletejobs"  onclick="clicked();">DEL </button>

  </form>
  
</td>


</tr>
@endforeach
</tbody>
</table>
</div>   
@foreach ($hisjoblst as $edu)    
<div class="modal fade"  id="favoritesJobModal{{$edu->id}}" 
 tabindex="-1" role="dialog" 
 aria-labelledby="favoritesModalLabel">
 <form method="post" action="{{action('HisjobController@update', $edu->id)}}">
  <input type="hidden" name="_method" value="PUT">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title" 
      id="code">Edit Job</h4>
        @if(!empty($message) && isset($type) && $type == 4)
      <div style="text-align: center;" class="alert alert-success">
        {{ $message}}
      </div>
      
       @endif
      
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
     
      <div class="form-body">
       <label for="uname" ><b>Term:</b></label>     <br>          
       <input type="date" name="fromedit" style="width : 30%" required="true" min="1990-01-01"  value="{{ $edu -> from }}"   /> ~   <input type="date" name="toedit"  min="1990-01-01"  style="width : 30%"  value="{{ $edu -> to }}"  />             
     </div>
     <div class="form-body">
       <label for="uname"><b>Company:</b></label>               
       <input type="text" class="form-control" name="companyedit" id="companyedit" maxlength="200"
       value="{{$edu->company}}">               
     </div>
     <div class="form-body">
       <label for="uname"><b>Position:</b></label>               
       <input type="text" class="form-control" name="positionedit" id="positionedit" maxlength="200"
       value="{{$edu->position}}">               
     </div>
     <div class="form-body">
       <label for="uname"><b>Occupation:</b></label>  <br>             
       <select name='occedit'  style="height: 28px; width: 160px">
        
         <?php 
         foreach($subitemsIT as $occedit) {
          
          echo "<option value='".$occedit->id."'";
          if( $occedit->id  == $edu->idoc ) { echo " selected='selected'  ";  } 
          echo ">".$occedit->name."</option>";
          
        }
        ?>   
        </select>       
      </div>
<div class="form-body">
       <label for="uname"><b>Detail:</b></label>    <br>     
       <textarea  name="detailedit" rows="5" class="form-control" style="resize: none;">{{$edu->detail}}</textarea>     
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
<div class="modal fade"  id="createJobModal" 
tabindex="-1" role="dialog" 
aria-labelledby="favoritesModalLabel">
<form method="post" action="{{url('hisjob')}}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" 
        id="code">Add Job</h4>
          @if( $type==3 )
       <div style="text-align: center;" class="alert alert-success">
        {{ $message }}
      </div>
      @endif
      </div>
      {{ csrf_field() }}
      <div class="modal-body">
       
        <div class="form-body">
         <label for="uname"><b>Date:</b></label>       <br>        
         
         <input type="date" name="fromcr"  style="width : 30%" required="true"  min="1990-01-01" 
          @if(!empty($fromcr)) value= "{{$fromcr}}"   @endif
         /> ~<input type="date" name="tocr" 
          @if(!empty($tocr)) value= "{{$tocr}}"   @endif  style="width : 30%"   /> 
         <input type="hidden" class="form-control" name="canID" id="canID"   min="1990-01-01" 
         value="{{$id}}" 
         >         
       </div>
       <div class="form-body">
         <label for="uname"><b>Company:</b></label>         
         <input type="text" class="form-control" maxlength="200" name="companycr"
          @if(!empty($companycr)) value= "{{$companycr}}"   @endif id="companycr" >         
       </div>
       <div class="form-body">
         <label for="uname"><b>Position:</b></label>         
         <input type="text" class="form-control" maxlength="200" name="positioncr"
          @if(!empty($positioncr)) value= "{{$positioncr}}"   @endif  id="positioncr" >         
       </div>
       <div class="form-body">
         <label for="uname"><b>Occupation:</b></label> <br>         
         <select name='occupationcr'  style="width: 55%; height: 28px;">
           
          <?php 
          foreach($subitemsIT as $en){
            
            echo "<option value='".$en->id."'";
            if(!empty($occupationcr) && $en->id  == $occupationcr ) echo " selected='selected'  ";
            echo ">".$en->name."</option>";
            
            
          }
          ?>
        </select>     
      </div>
      <div class="form-body">
       <label for="uname"><b>Detail:</b></label>         
       <textarea  name="detailcr" rows="5" class="form-control" style="resize: none;"> @if(!empty($detailcr))  {{$detailcr}}   @endif </textarea>     
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
  




<div id="actInfo" class="tabcontent">

  <div style="text-align: left;">
  
  
        
    <form method="GET" action="{{ url('addAction') }}" target="_blank"  >
               
               
    
      <input  id="canID" name="canID" type="hidden"  value="{{$id}}"> 
  
  
  
  <button  target="_blank"  >Create</button>
  
  
  </form>
  
  </div> 
  
  
  <br> 
  

   <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
     <thead >
      <tr>
       <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong></strong></td> 
       <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Time</strong></td>
       <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong>Action</strong></td>
       <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Job Finding</strong></td>
       <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Note</strong></td>    
       <td class="fixheader" bgcolor="#CACFD2"  nowrap><strong>Sekisho Pic</strong></td>    
       <td class="fixheader" bgcolor="#CACFD2"   nowrap><strong></strong></td>
       
     </tr>
   </thead>
   <tbody>
     
  
     @foreach($actionlist as $value)
     <tr>
         <td width="5%">
      <form method="GET" action="{{ url('editAction') }}" target="_blank"  >
      <input  id="canID" name="canID" type="hidden"  value="{{$value->id}}">
      <button  target="_blank"  >SEL</button>
      </form>  
     </td>   
     <td style="max-width:200px;word-break: break-all;">{{ $value->date}}  {{ $value->time_start }} @if (!is_null($value->time_end)) ~ {{ $value->time_end  }} @endif </td>
     <td width="8%">{{ $value->action  }}</td>
     <td style="max-width:150px;word-break: break-all;text-align: center; ">{{ $value->job_seeking_need }}</td>
     <td style="max-width:150px;word-break: break-all;">{{ $value->note }}</td>
     <td style="max-width:150px;word-break: break-all;">{{ $value->name }}</td>
  
    <td width="5%">
  
      <form action="{{action('ActionController@destroy',$value->id)}}" class="actionjobs" method="post">
       {{ csrf_field() }}
       <input name="_method" type="hidden" value="DELETE">
   
       <button   type="submit" id="deleteacts"  onclick="clicked();">DEL </button>
   
     </form>
     
   </td>
  </tr>
  @endforeach
  </tbody>
  </table>
  </div> 

  
     




</div> 
</div>



</div>

</div> 

</div>


<iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>

<script type="text/javascript">
 // alert( localStorage.getItem('test') ); // 1
 

  document.getElementById("myFrame").onload = function() {myFunction()};
  function myFunction() {
    
   var divWP = document.getElementById("divWP");
   var nationalflag=  document.getElementById("nationalID").value;

 //alert(nationalflag);
  // alert(nationalflag);
  if (nationalflag>1){
    
    document.getElementById("provinceID").style.display="none";
    document.getElementById("districtID").style.display="none";
    
          // 
        } 

        else
          { document.getElementById("provinceID").style.display="block";
        document.getElementById("districtID").style.display="block";
        
      }
    }
    $(".delete").on("submit", function(){
      localStorage.setItem('result', "1");
      return confirm("Do you want to delete this data ?");

    });
    
     $(".deletejobs").on("submit", function(){
      localStorage.setItem('result', "4");
      return confirm("Do you want to delete this data ?");

    });

    $(".deleteacts").on("submit", function(){
      localStorage.setItem('result', "5");
      return confirm("Do you want to delete this data ?");

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
 function myFunction() {

 

      var type = {!! json_encode($type) !!};

      var messx=localStorage.getItem('result');

      if (type ==1) {

        document.getElementById("eduInfoID").style.display="none";
        document.getElementById("basicInfo").style.display="none";
         document.getElementById("detailInfo").style.display="block";
        document.getElementById("jobInfo").style.display="none";
        document.getElementById("actInfo").style.display="none";
        document.getElementById("detailInfo").click();




      } 
    else if (type==2) {
        document.getElementById("detailInfo").style.display="none";
        document.getElementById("basicInfo").style.display="none";
        document.getElementById("eduInfo").style.display="none";
        document.getElementById("jobInfo").style.display="block";
        document.getElementById("actInfo").style.display="none";
        document.getElementById("jobInfoID").click();


      }  
       else if (type==3) {
        document.getElementById("detailInfo").style.display="none";
        document.getElementById("basicInfo").style.display="none";
        document.getElementById("eduInfo").style.display="none";
        document.getElementById("jobInfo").style.display="block";
        document.getElementById("actInfo").style.display="none";
        document.getElementById("jobInfoID").click();
        document.getElementById("btnJobCreate").click();


      }  
       else if (type==4) {
          var idjob;

            idjob = {!! json_encode($idjob) !!};

        document.getElementById("detailInfo").style.display="none";
        document.getElementById("basicInfo").style.display="none";
        document.getElementById("eduInfo").style.display="none";
        document.getElementById("jobInfo").style.display="none";
        document.getElementById("actInfo").style.display="block";
        document.getElementById("jobInfoID").click();
        document.getElementById("btnJobModal"+idjob).click();


      }

      // else if (type==4) {
      //   document.getElementById("detailInfo").style.display="none";
      //   document.getElementById("basicInfo").style.display="none";
      //   document.getElementById("eduInfo").style.display="none";
      //   document.getElementById("jobInfo").style.display="none";
      //   document.getElementById("actInfo").style.display="block";
      //   document.getElementById("actInfoID").click();
      //   document.getElementById("btnJobCreate").click();


      // } 

      // else if (type==5) {
      //     var idjob;

      //       idjob = {!! json_encode($idjob) !!};

      //   document.getElementById("detailInfo").style.display="none";
      //   document.getElementById("basicInfo").style.display="none";
      //   document.getElementById("eduInfo").style.display="none";
      //   document.getElementById("jobInfo").style.display="block";
      //   document.getElementById("jobInfoID").click();
      //   document.getElementById("btnJobModal"+idjob).click();


      // } 
        else if (messx==1) {
  document.getElementById("detailInfo").style.display="none";
        document.getElementById("basicInfo").style.display="none";
         document.getElementById("eduInfo").style.display="block";
        document.getElementById("jobInfo").style.display="none";
        document.getElementById("actInfo").style.display="none";
        document.getElementById("eduInfoID").click();
    document.getElementById('lbltipAddedComment').innerHTML 
                ="susscess!! Candidate was updated"; 
elementss = document.getElementById("messdivid");
  elementss.style.display="block";
       

 } else if (messx==4) {
  document.getElementById("detailInfo").style.display="none";
        document.getElementById("basicInfo").style.display="none";
         document.getElementById("jobInfo").style.display="block";
         document.getElementById("actInfo").style.display="none";
        document.getElementById("eduInfo").style.display="none";
        document.getElementById("jobInfoID").click();
    document.getElementById('lbltipAddedComment').innerHTML 
                ="susscess!! Candidate was updated"; 
elementss = document.getElementById("messdivid");
  elementss.style.display="block";
       

 }


 else if (messx==5) {
  document.getElementById("detailInfo").style.display="none";
        document.getElementById("basicInfo").style.display="none";
         document.getElementById("jobInfo").style.display="none";
        document.getElementById("eduInfo").style.display="none";
        document.getElementById("actInfo").style.display="block";
        document.getElementById("actInfoID").click();
    document.getElementById('lbltipAddedComment').innerHTML 
                ="susscess!! Candidate was updated"; 
elementss = document.getElementById("messdivid");
  elementss.style.display="block";
       

 }

   else {
    if (messx != 1000) {
       document.getElementById('lbltipAddedComment').innerHTML 
                ="susscess!! Candidate was updated"; 
                elementss = document.getElementById("messdivid");
  elementss.style.display="none";
    }
 // var detailInfo=  document.getElementById("detailInfo").value;
 document.getElementById("detailInfo").style.display="none";
 document.getElementById("eduInfo").style.display="none";
 document.getElementById("jobInfo").style.display="none";
 document.getElementById("actInfo").style.display="none";
  document.getElementById("basicInfo").style.display="block";
 //  detailInfo.style.display="block";
}

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
    width: 140px;
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