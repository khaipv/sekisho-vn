@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Add Job   </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
  <form method="post" action="{{url('hisjob')}}">
   


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
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td class="col-md-2" >
			  {{$candi->code}}
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Name (First Middle Last Name )</font></strong></td> 
            <td class="col-md-2" >
			  {{$candi->firstName}}&nbsp;{{$candi->midleName}}&nbsp;{{$candi-> lastName}}
			</td> 
		</tr>
		<tr >
		{{ csrf_field() }}
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			<input type="date" name="fromcr"  style="width : 35%" required="true"  min="1990-01-01" 
      max="2030-01-01"  required="true" 
          value="{{ old('fromcr')}}" 
         /> ~<input type="date" name="tocr" min="1990-01-01" 
      max="2030-01-01" 
             value="{{ old('tocr')}}"   style="width : 35%"   /> 
         <input type="hidden" class="form-control" name="canID" id="canID"  
         value="{{$candi->id}}" 
         >         
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			 <input type="text" class="form-control" maxlength="200" name="companycr"
         value="{{ old('companycr')}}" id="companycr" required="true">    
			</td> 
		</tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Position</font></strong></td> 
            <td class="col-md-2" >
			  <input type="text" class="form-control" maxlength="200" name="positioncr"
          value="{{ old('positioncr')}}"   id="positioncr" >    
		</tr>
		    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Occupation</font></strong></td> 
            <td class="col-md-2" >
			 <select name='occupationcr'  style="width: 55%; height: 28px;">
           <option value="" >--------</option>
          <?php 
          foreach($subitemsIT as $en){
            
            echo "<option value='".$en->id."'";
            if(!empty( old('occupationcr')) && $en->id  ==  old('occupationcr')) echo " selected='selected'  ";
            echo ">".$en->name."</option>";
            
            
          }
          ?>
        </select>     
		</tr>
		 <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Detail </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
			   <textarea  name="detailcr" rows="5" class="form-control" style="resize: none;" required="true">{{ old('detailcr')}}</textarea>     
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