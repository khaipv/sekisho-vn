@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Add Action   </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
  <form method="post" action="{{url('action')}}">
   


<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
   @if(count($errors))
      <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  <table class="table table-hover table-bordered" >
		{{-- <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td class="col-md-2" >
			  {{$candi->code}}
			</td> 
		</tr> --}}
    

<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Sekisho pic </font><font style="color:red">*</font></strong></td> 
  <td class="col-md-2" >
<select name='sekisho_pic'  style="width: 55%; height: 28px;" required="true">
 <option value="" >--------</option>
<?php 
foreach($name as $en){
  
  echo "<option value='".$en->id."'";
  if(!empty( old('name')) && $en->id  ==  old('name')) echo " selected='selected'  ";
  echo ">".$en->name."</option>";
  
  
}
?>
</select>     
</tr>

		<tr >
		{{ csrf_field() }}
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			<input type="date" name="date"  style="width : 45%" required="true"  min="1990-01-01" 
      max="2030-01-01"  required="true" 
          value="{{ old('date')}}" 
         />
          {{-- ~<input type="date" name="tocr" min="1990-01-01" 
      max="2030-01-01" 
             value="{{ old('tocr')}}"   style="width : 35%"   />  --}}
         <input type="hidden" class="form-control" name="canID" id="canID"  
         value="{{$candi->id}}" 
         >         
			</td> 


     
		</tr>


    <tr >
      {{ csrf_field() }}
              <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Time </font><font style="color:red">*</font></strong></td> 
              <td class="col-md-2" >
        <input type="time" name="fromtime"  style="width : 21%" required="true"  required="true" value="{{ old('fromcr')}}"/>
            ~ <input type="time" name="totime" value="{{ old('tocr')}}"   style="width : 21%"   /> 
           <input type="hidden" class="form-control" name="canID" id="canID"  
           value="{{$candi->id}}" 
           >         
        </td> 
  
  
       
      </tr>


      
    <tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Action </font><font style="color:red">*</font></strong></td> 
      <td class="col-md-2" >
 <select name='follow_action'  style="width: 55%; height: 28px;" required="true">
     <option value="" >--------</option>
    <?php 
    foreach($follow_action as $en){
      
      echo "<option value='".$en->id."'";
      if(!empty( old('follow_action')) && $en->id  ==  old('follow_action')) echo " selected='selected'  ";
      echo ">".$en->name."</option>";
      
      
    }
    ?>
  </select>     
</tr>
		    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1" required="true"><strong><font color="556B2F">Job Finding </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			 <select name='follow_job'  style="width: 55%; height: 28px;">
           <option value="" >--------</option>
          <?php 
          foreach($follow_demand as $en){
            
            echo "<option value='".$en->id."'";
            if(!empty( old('follow_job')) && $en->id  ==  old('follow_job')) echo " selected='selected'  ";
            echo ">".$en->name."</option>";
            
            
          }
          ?>
        </select>     
		</tr>
		 <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Note</font></strong></td> 
            <td class="col-md-2" >
			   <textarea  name="note" rows="5" class="form-control" style="resize: none;" >{{ old('note')}}</textarea>     
		</tr>
   

	    </table>
 </div>
</div>
 
</div> 

<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
   <table  >
  <tr >
            <td    class="col-md-1">  <button style="margin-left: 10px" type="button" onclick="window.close();">Close</button></td> 
            <td    class="col-md-1">
            </td>
            <td class="col-md-2" >
            <button style="margin-left: 10px" type="submit"  >Create</button>
      </td> 
    </tr>
  </table>

  </div>
</div>
</div>
  </form>
</div>
@endsection