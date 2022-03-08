
@extends('master')
@section('content')

  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    Division List  </b>
                   <!-- approve.approveDetail  -->
                </div>
     
          <div class="container">
 <form method="get" action="{{ url('divisionSearch') }}">

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
       <select   style="width: 66%" name="ratesrc" id="rateList">
         <option></option>
           @foreach($master as $master)
         @if($master->type =='Imp')
                   <option value="{{$master->code}}"   @if( session('forms.ssratesrc')  ==$master->code ) selected="selected" @endif   >{{ $master->name }}</option>
         @endif
         @endforeach
       </select>
              </td>         
   </tr>
  
      <tr>
         <td  style="background: #7EC0EE  "   ><strong><font color="556B2F">&nbsp; Status  &nbsp;</font></strong></td> 
            <td    >
       {{csrf_field()}}
            <input type="checkbox" name='status' value="1" 
               <?php  if (old('status')=="1")  echo "checked";?> >  Include Not Alive
                
              
      </td>   
           <td  style="background: #7EC0EE"  ><strong><font color="556B2F">PIC(SEKISHO)</font></strong></td> 
           <td  colspan="2" >
   <select   style="width: 66%" name="pic_s" id="pic_slst">
             <option></option>
             @foreach($users as $users)
                    <option value="{{$users->id}}"   @if( session('forms.sspic_s')  ==$users->id ) selected="selected" @endif   >{{ $users->name }}</option>
         @endforeach
           
    </select>
              </td>   
                 
   </tr>
    <tr>
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Division</font></strong></td> 
            <td >
              <input type="text"   a id="divisionsrc_searchdiv" name="divisionsrc_searchdiv"
                  value="{{ old('divisionsrc_searchdiv') }}"  >  </td> 
           <td  style="background: #7EC0EE"  ><strong><font color="556B2F">HR Introduce</font></strong></td> 
            <td  >
         <select     name="introducesrc" id="rateList">
           <option></option>
         @foreach($introduce as $instroduce)
         @if($instroduce->type =='Introduce')
                     <option value="{{$instroduce->code}}"   @if( session('forms.ssintroducesrc')  ==$instroduce->code ) selected="selected" @endif   >{{ $instroduce->name }}</option>
         @endif
         @endforeach
      </select>
    </td>
    <td >
 &nbsp;&nbsp;&nbsp;&nbsp; O ：  Introduced about HR <br>
　&nbsp; X ：  Haven't ever introduced about HR <br>
　NG： Don't Introduce <br>

              </td>         
   </tr>
   <tr>
     
   </tr>
        <tr>
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Country</font></strong></td> 
            <td >
                   <select   style="width: 100%" name="national">
              <option  > </option>
                @foreach($national as $national)
                  <option value="{{$national->Id}}"   @if( session('forms.ssnational')  ==$national->Id ) selected="selected" @endif   >{{ $national->CommonName }}</option>
                @endforeach
            </select>
             </td> 
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Province/ District </font></strong></td> 
            <td colspan="2" >
               <select   style="width: 40%" name="province">
              <option  > </option>
                @foreach($province as $province)
                   <option value="{{$province->Id}}"   @if( session('forms.ssprovince')  ==$province->Id ) selected="selected" @endif   >{{ $province->Name }}</option>
                @endforeach
            </select> 
             <select  style="width: 40%"   name="district">
        <option  > </option>
                 @foreach($districtDB as $districtDB)
                   <option value="{{$districtDB->Id}}"   @if( session('forms.ssdistrict')  ==$districtDB->Id ) selected="selected" @endif   >{{ $districtDB->Name }}</option>
                @endforeach
            </select>
          </td> 
       </tr>
       
    <tbody>
    
        <tr>
            
        </tr>
   

    </tbody>

      
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
        <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$division->total()}} Divisions</label>
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
    @foreach($division as $value)
              <tr <?php if( !is_null( $value->status )): ?> style="background-color:#DCDCDC;text-align: right;" 
              <?php else : ?> style="text-align: right;" <?php endif; ?> >
            <td nowrap><a href="{{action('DivisionController@show', $value->id)}}">{{ $value->code }}</a></td>
            <td align="left" nowrap>{{ $value->clientName }}</td>
            <td align="left" nowrap>{{ $value->divisionname }}</td>
            <td align="center" nowrap>{{ $value->introduceName }}</td>
              <td nowrap>{{ $value->statusName }}</td> 
            <td nowrap>{{ $value->rate2 }}</td> 
             <td nowrap>{{ $value->mobile }}</td>
                         
             <td align="left" nowrap>{{ $value->pics }}</td>

        </tr>
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