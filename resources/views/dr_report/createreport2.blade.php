
@extends('master')
@section('content')

  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    Reports List  </b>
                   <!-- dr_report.searchReport.blade  -->
                </div>
     
          <div class="container">
 <form method="get" action="{{ url('reportSearch') }}">

  <div class="row">
                <div class="col-sm-13"  > 
       <div class="col-sm-1"  > </div> 
    <div class="row" align="center" style="margin-right: 10%">
   

  <table class="table-condensed table-striped table-bordered" id="selecter">
    <tr>
       {{csrf_field()}}
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Company</font></strong></td> 
            <td ><input type="text" style="width: 100%;padding:0px;"  id="companysrc_searchdiv"  name="companysrc_searchdiv"
                value="{{ old('companysrc_searchdiv') }}"  ></td> 
           <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Customer's Importance</font></strong></td> 
            <td colspan="2" >
     
              </td>         
   </tr>

   

       
       


      
</table>
</div>
</div>
</div>

  <div class="row">
 <div class="col-sm-12"  > 
 <div class="col-sm-1"  > </div> 
 <div class = "col-sm-10"  >
 
    </div>
</div>
 
</div> 
<br>
<div class="row">
 <div align="center">
        <button type="submit">Search</button> 
            
</div> 
</div>
    </form>   
</div>  

 <br>


  <div class="content ">
      <div   style="margin-top:-1%;width: 100%">
        <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$reports->total()}} Reports</label>
          <a href="{{ URL::to('downloadDiv/xlsx/m2') }}"  style="float: right;" ><button  >Download Divisions</button></a>

  </div>

  <table class="table table-hover table-bordered" >
    <thead >
            
             <td nowrap bgcolor="#CACFD2"><strong>
              <font color="556B2F">
            @sortablelink('code', 'Code')</font></strong></td> 
               <td nowrap bgcolor="#CACFD2"><strong>
              <font color="556B2F">
            @sortablelink('companyname','Company')</font></strong></td> 
             <td nowrap bgcolor="#CACFD2"><strong>
              <font color="556B2F">
            @sortablelink('divisionname','Branch／Division')</font></strong></td> 
            <td nowrap bgcolor="#CACFD2"><strong><font color="556B2F">
             @sortablelink('introduce','Introduce')</font></strong></td>
              <td nowrap bgcolor="#CACFD2"><strong><font color="556B2F">
            @sortablelink('status','Status')</font></strong></td>    
            <td nowrap bgcolor="#CACFD2"><strong><font color="556B2F">
             @sortablelink('rate','Customer Importance')</font></strong>
            </td>
            <td nowrap bgcolor="#CACFD2"><strong><font color="556B2F">
            @sortablelink('mobile','TEL')</font></strong></td>
             
            <td nowrap bgcolor="#CACFD2"><strong><font color="556B2F">
            @sortablelink('pic_s','PIC(Sekisho)')</font></strong></td>   
                 
    </thead>
    <tbody>
    @foreach($reports as $value)
              
    @endforeach
    <tr>
      <td colspan="13">
        <div class="pagination">
          <!-- {!! str_replace('/?', '?', $division->render()) !!} -->
          {{ $division->appends(Request::except('page'))->links() }}
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