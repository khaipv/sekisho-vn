@extends('master')
@section('content')

     <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Edit Company   </b>
                   <!-- approve.approveDetail  -->
                </div>
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
<div class="container">
<form method="post" action="{{action('ClientControler@update', $id)}}">
   <div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" BORDER="0">
   <input name="_method" type="hidden" value="PATCH">
   {{csrf_field()}}
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Code</font></strong></td> 
            <td class="col-md-2" >
			 {{$client->code}}
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company's Name</font></strong></td> 
            <td class="col-md-2"  >
			 <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="company name" name="companyname"  value="{{$client->companyname}}">
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">URL</font></strong></td> 
            <td class="col-md-2" >
			 <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="url" name="url"
         value="{{$client->url}}">
			</td> 
		</tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status</font></strong></td> 
            <td class="col-md-2" >
      <select    name="status" >
               <option value="" >--------</option>
               
                <?php 
               foreach($master as $province){
                   if($province->type =='clientStatus') {
                echo "<option value='".$province->val."'";
                 if( $province->val  == $client->status )  echo " selected='selected'  ";
                echo ">".$province->name."</option>";
              }}
            ?>

            </select>
      </td> 
    </tr>

	    </table>
 </div>
</div>
 
</div> 


    <div class="content">
    <!-- <div class="col-md-6"></div> -->
      <button type="submit" class="btn">Update</button>
    </div>
    <br>
  </form>

<!-- <div class="content"> -->
 <div class="title m-b-md content">
                   Division List 

                </div>
           
    <div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-8" >

  <table class="table table-hover table-bordered" >

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
            <td align="left" nowrap="true">{{ $value->divisionname }}</td>
            <td align="left">{{ $value->introduceName }}</td>
            <td align="left"> {{ $value->advertiseName }}</td>  
            <td>{{ $value->rate2 }}</td> 
             <td>{{ $value->mobile }}</td>
             <td align="left">{{ $value->pics }}</td>


   
        </tr>
    @endforeach
 
    </tbody>
          <form method="GET" action="{{ url('addnew') }}">
           
           
       <input  id="companyinputid" name="companyid2" type="hidden"  value="{{$client->id}}">
                
           
      <div class="col-md-6">
                    <button class="btn">Add new Division</button>
      </div>
        </form> 
</table>
    <p align="right"> <a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;  </a> 
          </p>
</div>
<div class="col-sm-2"  > </div>

</div>
 


@endsection