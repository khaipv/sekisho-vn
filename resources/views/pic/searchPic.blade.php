@extends('master')
@section('content')


  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   PIC List   </b>
                   <!-- approve.approveDetail  -->
                </div>
     
        
 <div class="container">
 <form method="GET" action="{{ url('picSearch') }}">
 <div class="row">
 <div class="col-sm-11"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" >
   <input name="_method" type="hidden" value="PATCH">
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Company</font></strong></td> 
            <td colspan="3" class="col-md-4" >
			<input type="text"  style="width: 100%" id="companysrc"  name="companysrc"
                value="{{ old('companysrc') }}"  >
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Division</font></strong></td> 
            <td colspan="3" class="col-md-4" >
			 <input type="text" style="width: 100%" a id="divisionsrc" name="divisionsrc"
                 value="{{ old('divisionsrc') }}"  >
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Pic's Name</font></strong></td> 
            <td colspan="1" class="col-md-1" >
			 <input type="text"   id="companyFistsrc" name="picFirstsrc"
              value="{{ old('picFirstsrc') }}">
			</td> 
			      <td colspan="1" class="col-md-1" >
			  <input type="text"   id="companyMidlesrc" name="picMidlesrc"
                    value="{{ old('picMidlesrc') }}">
			</td> 
			      <td colspan="1" class="col-md-1" >
			<input type="text"   id="companyLastsrc" name="picLastsrc"
                    value="{{ old('picLastsrc') }}">
			</td> 
		</tr>
    <tr>
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Pic's Email</font></strong></td> 
            <td colspan="1" class="col-md-1" >
       <input type="text"   id="emailsrcID" name="emailsrc"
              value="{{ old('emailsrc') }}">
      </td> 
	  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Pic's Phone</font></strong></td> 
            <td colspan="1" class="col-md-1" >
       <input type="text"   id="phoneID" name="phonesrc"
              value="{{ old('phonesrc') }}">
      </td> 
      
    </tr>

	    </table>
 </div>
</div>
</div>
<div class="row">
 <div align="center">
        <button type="submit">Search</button>             
</div> 
</div>
    </form>   
</div>
<div class="content ">
    
    <div class="col-sm-8" >
         <div class="form-class row" style="margin-top:-1%">
        <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$pic->total()}} Pics</label>
  </div>
  <table class="table table-hover table-bordered" >
    <thead >
            <td nowrap  bgcolor="#CACFD2" ><strong>ID</strong></td> 
          
                       <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('lastNameJ', 'PIC Name')</strong></td> 
                       <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('company_id', 'Company')</strong></td> 
                <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('division_id', 'Division')</strong></td> 
           <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('department', 'Department')</strong></td> 
            <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('position', 'Position')</strong></td> 
             <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('email', 'Email')</strong></td> 
              <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('mobile', 'Mobile')</strong></td> 
            
    </thead>
    <tbody>
    @foreach($pic as $value)
        <tr>
          <td>{{ $value->id }}</td>    
           <td>
            @if (strlen ($value->firstName) >0) 
               <a href="{{action('PicController@show', $value->id)}}">{{$value->firstName}}&nbsp;{{$value->midleName}}&nbsp;{{$value-> lastName}}</a>
            @else
               <a href="{{action('PicController@show', $value->id)}}">{{$value->firstNameJ}}{{$value->midleNameJ}}{{$value-> lastNameJ}}</a>
            @endif
           </td>
           <td nowrap="true" style="text-align: left;">{{ $value->companyName }}</td>    
            <td nowrap="true">{{ $value->divName }}</td> 
            <td nowrap="true"> {{ $value->department }}</td>    
      <td>{{ $value->position }}</td>       
            <td>{{ $value->email }}</td>
            <td>{{ $value->mobile }}</td>
        </tr>
    @endforeach
    <tr>
      <td colspan="13">
        <div class="pagination">
         {{ $pic->appends(Request::except('page'))->links() }}   
        </div>   
      </td>
    </tr>
    </tbody>
</table>
</div>
<div class="col-sm-2"  > </div>
</div>
 <script type="text/javascript">
</script>
@endsection