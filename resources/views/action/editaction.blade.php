@extends('master')
@section('content')

     <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Edit Action </b>
                   <!-- approve.approveDetail  -->
                </div>
      
<div class="container">
<form method="post" action="{{action('ActionController@update', $act->id)}}">
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
  <table class="table table-hover table-bordered" BORDER="0">
   <input name="_method" type="hidden" value="PATCH">
   {{csrf_field()}}
    {{-- <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Sekisho pic</font></strong></td> 
            <td class="col-md-2" >
        {{$sekisho_pic->name}}
      </td> 
    </tr> --}}

    <tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Sekisho pic </font><font style="color:red">*</font></strong></td> 
      <td class="col-md-2" >
 <select name='sekisho_pic'  style="height: 28px; width: 160px" required="true">
  <option value="" >--------</option>
   <?php 
   foreach($name as $occedit) {
    
    echo "<option value='".$occedit->id."'";
     if( $occedit->id  == old('action')  ) { echo " selected='selected'  ";  } 
      elseif( old('action')==null && $occedit->id  == $act->sekisho_pic ) { echo " selected='selected'  ";  } 
   

    echo ">".$occedit->name."</option>";
    
  }
  ?>   
  </select>  
 
</td> 
</tr>



    <tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date </font><font style="color:red">*</font></strong></td> 
      <td class="col-md-2"  >
    <input type="date" name="date" style="width : 30%" required="true" min="1990-01-01" 
    max="2030-01-01"  
    @if(!empty( old('date') )) value= "{{old('date')}}" else value="{{ $act ->date }}"   @endif 
    value="{{ $act ->date }}"   />  
    </td> 
    </tr>
    
    
    
    <tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Time </font><font style="color:red">*</font></strong></td> 
      <td class="col-md-2"  >
    <input type="time" name="fromedit" style="width : 30%" required="true"
    @if(!empty( old('fromedit') )) value= "{{old('fromedit')}}" else value="{{ $act ->time_start }}"   @endif 
    value="{{ $act ->time_start }}"   /> ~  
    <input type="time" name="toedit"    style="width : 30%" 
    @if(!empty( old('toedit') )) value= "{{old('toedit')}}" else value="{{ $act ->time_end }}"   @endif
    value="{{ $act ->time_end }}"   />    
    </td> 
    </tr>

  


    <tr >
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Action </font><font style="color:red">*</font></strong></td> 
      <td class="col-md-2" >
 <select name='action'  style="height: 28px; width: 160px" required="true">
  <option value="" >--------</option>
   <?php 
   foreach($follow_action as $occedit) {
    
    echo "<option value='".$occedit->id."'";
     if( $occedit->id  == old('action')  ) { echo " selected='selected'  ";  } 
      elseif( old('action')==null && $occedit->id  == $act->action ) { echo " selected='selected'  ";  } 
   

    echo ">".$occedit->name."</option>";
    
  }
  ?>   
  </select>  
 
</td> 
</tr>


<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F" required="true">Job Finding </font><font style="color:red">*</font></strong></td> 
  <td class="col-md-2" >
<select name='demand'  style="height: 28px; width: 160px">
<option value="" >--------</option>
<?php 
foreach($follow_demand as $occedit) {

echo "<option value='".$occedit->id."'";
 if( $occedit->id  == old('demand')  ) { echo " selected='selected'  ";  } 
  elseif( old('demand')==null && $occedit->id  == $act->job_seeking_need ) { echo " selected='selected'  ";  } 


echo ">".$occedit->name."</option>";

}
?>   
</select>  

</td> 
</tr>


<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Note</font></strong></td> 
  <td class="col-md-2" >
<textarea  name="note" rows="5" class="form-control" style="resize: none;">@if(!empty( old('note') )){{old('note')}}@else{{ $act->note}}@endif</textarea>   

</td> 
</tr>



    {{-- <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Name (First Middle Last Name )</font></strong></td> 
            <td class="col-md-2" >
        {{$candi->firstName}}&nbsp;{{$candi->midleName}}&nbsp;{{$candi-> lastName}}
      </td> 
    </tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date *</font></strong></td> 
            <td class="col-md-2"  >
		 <input type="date" name="fromedit" style="width : 30%" required="true" min="1990-01-01" 
      max="2030-01-01"  
     @if(!empty( old('fromedit') )) value= "{{old('fromedit')}}" else value="{{ $job -> from }}"   @endif 
      value="{{ $job -> from }}"   /> ~  
       <input type="date" name="toedit" min="1990-01-01" 
      max="2030-01-01"   style="width : 30%" 
        @if(!empty( old('toedit') )) value= "{{old('toedit')}}" else value="{{ $job -> to }}"   @endif
         value="{{ $job -> to }}"   />    
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company *</font></strong></td> 
            <td class="col-md-2" >
			 <input type="text" class="form-control" name="companyedit" id="companyedit"  maxlength="200"
     @if(!empty( old('companyedit') )) value= "{{old('companyedit')}}" else value="{{ $job -> company }}"   @endif  value="{{ $job -> company }}"  required="true">  
	       <input type="hidden" class="form-control" name="canID" id="canID" 
         value="{{$candi->id}}"  >
			</td> 
		</tr>
      <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Position</font></strong></td> 
            <td class="col-md-2" >
       <input type="text" class="form-control" name="positionedit" id="position"  maxlength="200"
      @if(!empty( old('positionedit') )) value= "{{old('positionedit')}}" else value="{{ $job -> position }}"   @endif  value="{{ $job -> position }}">  
       
      </td> 
    </tr>
 <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Occupation</font></strong></td> 
            <td class="col-md-2" >
       <select name='occedit'  style="height: 28px; width: 160px">
        <option value="" >--------</option>

        </select>  
       
      </td> 
    </tr>
       <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Detail *</font></strong></td> 
            <td class="col-md-2" >
       <textarea  name="detailedit" rows="5" class="form-control" style="resize: none;" required="true">@if(!empty( old('detailedit') )){{old('detailedit')}}@else{{$job->detail}}@endif</textarea>   
       
      </td> 
    </tr>
    --}}

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
          <button style="margin-left: 10px" type="submit"  >Update</button>
      </td> 
    </tr>
  </table>

  </div>
</div>
</div>
  </form>

 


@endsection