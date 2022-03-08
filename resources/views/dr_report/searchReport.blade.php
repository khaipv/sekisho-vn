
@extends('layouts.elements.master')
@section('content')

  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    Reports List  </b>
                   <!-- dr_report.searchReport.blade1  -->
                </div>
     
          <div class="container">


   
                
        
  

  <div class="row">
      @if(session()->has('message'))
            <div style="text-align: center;" class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
 <div class="col-sm-12"  > 
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-10"  >
   <form method="get" action="{{ url('reportSearch') }}">
 <table class="table-condensed table-striped table-bordered" style="width: 80%" id="selecter">
  <tr>
       {{csrf_field()}}
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Visit Date</font></strong></td> 
             <td colspan="2" >
              <input type="date" value="{{ old('fromDate') }}"    id="appfrom" name="fromDate" 
        
       
           style="width: 25%"> 
           ~  <input type="date"  value="{{ old('toDate') }}"   id="appfrom" name="toDate" 
        
       
           style="width: 25%"> 
             </td>        
   </tr>
    <tr>
       {{csrf_field()}}
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Company</font></strong></td> 
             <td colspan="2" >
              <input type="text" style="width: 90%;padding:0px;"  id="companysrc_searchrp"  name="companysrc_searchrp"     value="{{ old('companysrc_searchrp') }}"  ></td>        
   </tr>
   <tr>
        <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Division</font></strong></td> 
            <td colspan="2" >
      <input type="text" style="width: 90%;padding:0px;"  id="divisionsrc_searchrp"  name="divisionsrc_searchrp"                value="{{ old('divisionsrc_searchrp') }}"  >
              </td>  
   </tr>
   <tr>
     <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
     Creater </font></strong></td> 
        <td colspan="2" >
         <select    name="pics"  style="width: 170px" >
                  <option></option>
                @foreach($users as $users)
                    <option value="{{$users->id}}"    @if( $users->id == old('pics') ) selected="selected" @endif   >{{ $users->name }}</option>
                @endforeach
                </select>
      </td>
   </tr>
   

       
       


      
</table>
<div align="center" style="margin-right: 30%">
        <button type="submit">Search</button> 
            
</div> 
    </div>
 
<br>
 
    </form>   
</div>  

 <br>


  <div class="content ">
      

  <table class="table table-hover table-bordered" style="margin-left: 40px">
    <thead >
            

<td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap>
  <strong>@sortablelink('date','Visit Day')</strong></td>
<td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Company</strong> </td>
<td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Division</strong> </td>
<td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Title</strong> </td>
<td class="fixheader" bgcolor="#CACFD2" rowspan="2" nowrap><strong>Creator</strong> </td>
                 
    </thead>
    <tbody>
    @foreach($reports as $value)
      <tr>

            <td nowrap="true"><a href="{{action('DailyReportController@show', $value->id)}}">
            {{ $value->date }} ({{substr( $value->from,0,5 )}} ~ {{ substr( $value->to,0,5 ) }})</a></td>
            
            <td align="left" nowrap>
              @if($value->companyID<>10000)
              {{ $value->companyname }}
              @else
              {{ $value->other }}
              @endif
            </td>
            <td align="left" nowrap>{{ $value->divisionname }}</td>
            <td align="left" nowrap>{{ $value->title }}</td>
              <td nowrap>{{ $value->userName }}</td> 
           

        </tr> 
    @endforeach
     <tr>
    <td colspan="10">
        <div class="pagination">
         {{ $reports->appends(Request::except('page'))->links() }}     
         </div>   
      </td>
    </tr>
    </tbody>

      
</table>

</div>

<div class="col-sm-2"  > </div>

</div>
    

  <script type="text/javascript">
    var url = "{{ url('/showDistrict') }}";
    var urlWard = "{{ url('/showWard') }}";
    $("select[name='province']").change(function(){
        var province_id = $(this).val();
        var token = $("input[name='_token']").val();

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                id: province_id,
                _token: token
            },
            success: function(data) {
              //   alert(url);
                $("select[name='district'").html('');
				 $("select[name='district']").append(
                        "<option value=''" + ">" + '' + "</option>"
                    );
                $.each(data, function(key, value){
                    $("select[name='district']").append(
                        "<option value=" + value.Id + ">" + value.Name + "</option>"
                    );
                });
            }
        });
    });

        $("select[name='national']").change(function(){

          var national_id = $(this).val();
         

          if (national_id >1) {
            //alert(national_id);
            document.getElementById("provinceID").style.display="none";
           // document.getElementById("districtID").style.display="none";
            //
          // 
          } else
          { document.getElementById("provinceID").style.display="block";
          //  document.getElementById("districtID").style.display="block";
            
        }

        });



</script>
@endsection