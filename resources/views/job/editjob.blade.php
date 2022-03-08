@extends('master')
@section('content')

     <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Edit Job </b>
                   <!-- approve.approveDetail  -->
                </div>
      
<div class="container">
<form method="post" action="{{action('HisjobController@update', $job->id)}}">
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
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date </font><font style="color:red">*</font></strong></td> 
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
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company </font><font style="color:red">*</font></strong></td> 
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
         <?php 
         foreach($subitemsIT as $occedit) {
          
          echo "<option value='".$occedit->id."'";
           if( $occedit->id  == old('occedit')  ) { echo " selected='selected'  ";  } 
            elseif( old('occedit')==null && $occedit->id  == $job->idoc ) { echo " selected='selected'  ";  } 
         
      
          echo ">".$occedit->name."</option>";
          
        }
        ?>   
        </select>  
       
      </td> 
    </tr>
       <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Detail </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-2" >
       <textarea  name="detailedit" rows="5" class="form-control" style="resize: none;" required="true">@if(!empty( old('detailedit') )){{old('detailedit')}}@else{{$job->detail}}@endif</textarea>   
       
      </td> 
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
          <button style="margin-left: 10px" type="submit"  >Update</button>
      </td> 
    </tr>
  </table>

  </div>
</div>
</div>
  </form>

 


@endsection