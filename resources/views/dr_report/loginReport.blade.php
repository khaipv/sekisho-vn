
@extends('layouts.elements.master')
@section('content')

  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    Reports List  </b>
                   <!-- dr_report.loginReport.blade  -->
                </div>
     
          <div class="container">


   
                
        
  

  <div class="row">
 <div class="col-sm-12"  > 
 

 <br>


  <div class="content ">
      
  <table class="table table-hover table-bordered" >
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
      <td colspan="5">
        <div class="pagination">
            {{ $reports->appends(Request::except('page'))->links() }}   
      </td>
      </tr>
    </tbody>

      
</table>

</div>

<div class="col-sm-2"  > </div>

</div>
<br><br>
     <div style="  float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Order List   </b>
                   <!-- approve.approveDetail  -->
                </div><br><br><br><br><br><br>
				
<div class="content ">
             <div class="form-class row"  >
        <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$order->total()}} Orders</label>
  </div>
<table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
        
    <thead >
        <tr>
         <td style=" background-color: #CACFD2;" nowrap><strong>Code</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>Company</strong></td> 
          <td style=" background-color: #CACFD2;" nowrap><strong>Division</strong></td> 
         <td  align="left" style=" background-color: #CACFD2;" nowrap><strong>Title</strong></td> 
       <td style=" background-color: #CACFD2;" nowrap><strong>Progress</strong></td> 
           <td style=" background-color: #CACFD2;" nowrap><strong>Order Date(Plan)</strong></td> 
           <td style=" background-color: #CACFD2;" nowrap><strong>Pic Sekisho</strong></td> 
           
        </tr>
    </thead>
    <tbody>
    @foreach($order as $value)
        <tr>
            <td><a href="{{action('OrderController@show', $value->id)}}">{{ $value->code }}</a></td>
            <td align="left" nowrap>{{ $value->companyname }}</td>
            <td align="left" nowrap>{{ $value->divisionName }}</td>
            <td align="left" nowrap>{{ $value->title }}</td>
            <td align="left" nowrap>{{ $value->progressName }}</td>
            <td align="left" nowrap>{{ $value->orderDate }}</td>
          
            <td align="left" nowrap> {{ $value->pics }}</td>
              
        </tr>
    @endforeach
     <tr>
      <td colspan="13">
        <div class="pagination">
            {{ $order->appends(Request::except('page'))->links() }}   
      </td>
    </tr>
    </tbody>
</table>
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