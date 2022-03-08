@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Create Company   </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
  <form method="post" action="{{url('client')}}">
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


<div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" >

		<tr >
		
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company's Name</font></strong></td> 
            <td class="col-md-2" >
			 {{csrf_field()}}
			 <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="company name" name="companyname">
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">URL</font></strong></td> 
            <td class="col-md-2" >
			{{csrf_field()}}
			 <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="url" name="url">
			</td> 
		</tr>
      <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status</font></strong></td> 
            <td class="col-md-2" >
      {{csrf_field()}}
           <select  name="master"  style="width: 55%; height: 28px;">
               
           <option value="" >--------</option>
            <?php 
              foreach($master as $en){
             if($en->type =='clientStatus'){
                echo "<option value='".$en->id."'";
                 if( $en->val == old('master') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select>
      </td> 
    </tr>
		

	    </table>
 </div>
</div>
 
</div> 

  
    <div align="center">
      
      <input type="submit" class="btn  " value="Create">
    </div>
     <div align="right">    
            
</div>
  </form>
</div>
@endsection