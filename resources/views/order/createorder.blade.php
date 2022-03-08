@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
    <br></br>
                   <b style="font-size: 18px; margin-left: 220px  ">   New Order  </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">

  <form method="post" action="{{url('order')}}" autocomplete="off">
  <input name="_method" type="hidden" value="PATCH">
  {{csrf_field()}}
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
<br>
<div class="container">
<div class="row" style="margin-left:6em">


 <div class = "col-sm-11"  style="width: 88%" >
       
<table class="table-condensed table-striped table-bordered" id="selecter">
   
		<label   style="text-align: left;  ">
       [Basic info] <br>
    
    
  </label>
    <tr>
          <td  style="background: #7EC0EE;width: 20%" ><strong><font color="556B2F">
          Company </font><font style="color:red">*</font></strong></td> 
          {{csrf_field()}}
          <input style="margin-left: 77px;" type="checkbox" name="priority" id="priority"  value="1" > 
          <label>High Priority</label><br/><br/>
          <td colspan="3"  >
              <select  required="true"  name="client">
        <option value="" selected="selected" >--------</option>
            @foreach($client as $client)
              <option value="{{$client->id}}"   @if( session('forms.ssClientID')  ==$client->id ) selected="selected" @endif   >{{ $client->companyname }}</option>
            @endforeach
        </select>
         <span class="text-danger">{{ $errors->first('client') }}</span> 
          </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Division </font><font style="color:red">*</font></strong></td> 
            <td colspan="3"  >
       <select  style="width: 60%"   name="division">
                           @foreach($division as $division)
             @if( Session::has('forms.ssClientID'))
                             @if( Session::get('forms.ssClientID') == $division->companyid)
         <option value="{{$division->id}}"   @if( session('forms.ssdivisionID')  ==$division->id ) selected="selected" @endif   >{{ $division->divisionname }}</option>
          @endif
         @endif
                @endforeach
      </select>
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Pic </font><font style="color:red">*</font></strong></td> 
            <td  >
       <select    name="pic">
          <option value="{{$user->id}}">{{$user->name}}</option>
                @foreach($pic as $pic)
                     <option value="{{$pic->id}}">{{ $pic->name }}</option>
                @endforeach
      </select>
      </td> 
               
    </tr>
    <tr>
      <td  style="background: #7EC0EE" ><strong><font color="556B2F">Type </font><font style="color:red">*</font></strong></td> 
      <td  >
      <select    name="type">
      <option></option>
                @foreach($type as $type)
                 @if($type->type =='type')
                     <option value="{{$type->id}}"   @if( session('forms.ssTypeID')  ==$type->id ) selected="selected" @endif   >{{ $type->name }}</option>
                 @endif
                @endforeach
      </select>
      </td> 
      <td  style="background: #7EC0EE" ><strong><font color="556B2F">Invoice</font></strong>     </td> 
       <td  colspan="2" >
      
      <input type="checkbox" name="invoiceCK" value="1" onclick="OnChangeCheckbox (this)" >  
     
        <input type="text"   id="invoiceDateID"  
        value="{{ old('invoiceDate')}}" name="invoiceDate" style="visibility: hidden;" >
                
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">
            Order Date </font><font style="color:red">*</font></strong></td> 
            <td  >
   <input type="text"  id="datepicker" value="{{ old('orderDate')}}" name="orderDate"  >
      </td>
        <td  style="background: #7EC0EE;width: 12%" ><strong><font color="556B2F">Progress </font><font style="color:red">*</font></strong></td> 
            <td  >
      <select    name="progress">
                @foreach($progress as $progress)
                 @if($progress->type =='progress')
                                 <option value="{{$progress->code}}"   @if( session('forms.ssProgressID')  ==$progress->code ) selected="selected" @endif   >{{ $progress->name }}</option>
                 @endif
                @endforeach
      </select>
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Introduce Date </font><font style="color:red">*</font></strong></td> 
            <td  >
       <input type="text"  id="introducePriority"
      value="{{ old('introducePriority')}}"  name="introducePriority"  > 
      </td>
       
    </tr>
    <tr> 
        <td colspan="4"  >
        <label   style="text-align: left; ">
         [Detail info]   
        </label>
      </td>
        </tr>
        <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Project Title </font><font style="color:red">*</font></strong></td> 
            <td colspan="3"  >
      <input type="text"  id="lgFormGroupInput" value="{{ old('title')}}" name="title" style="width :80%" >
      </td> 
                 
    </tr>
    <tr>
    <td  style="background: #7EC0EE" ><strong><font color="556B2F">Working Date</font></strong></td> 
            <td  >
       <input type="text"   id="workingDate"
        value="{{ old('workingDate')}}" name="workingDate"  > 
      </td> 
       <td  style="background: #7EC0EE" nowrap="true"><strong><font color="556B2F">Recruitment Number</font><font style="color:red">*</font></strong></td> 
            <td  >
        <input  id="FirstName"  name="recruit_num" type="text" 
        value="{{ old('recruit_num')}}" onkeypress='validateNum(event)' />
      </td> 
    </tr>
      <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Working place</font></strong></td> 
            <td colspan="3"  >
       <input type="text"  id="workingPlaceID"  style="width :80%"
         value="{{ old('workingPlace')}}" name="workingPlace" >
      </td> 
    </tr>
    <tr>
             <td  style="background: #7EC0EE" ><strong><font color="556B2F">Age </font><font style="color:red">*</font></strong></td> 
            <td  >
       <input type="text"  id="lgFormGroupInput" 
        value="{{ old('age')}}" name="age"  >
      </td> 
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Sex </font><font style="color:red">*</font></strong></td> 
            <td  >
       <select  style="width: 80%"   name="sex">
      @foreach($sex as $sex)
                @if($sex->type =='sex')
                     <option value="{{$sex->id}}"   @if( session('forms.ssSexID')  ==$sex->id ) selected="selected" @endif   >{{ $sex->name }}</option>
                @endif
      @endforeach
         </select>
      </td>  
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Japanese Level</font></strong></td> 
            <td  >
       <select  style="width: 80%"   name="japanese">
                @foreach($japanese as $japanese)
                @if($japanese->type =='jp')
                    
                          <option value="{{$japanese->id}}"   @if( session('forms.ssJapaneseID')  ==$japanese->id ) selected="selected" @endif   >{{ $japanese->name }}</option>
                @endif
                @endforeach
      </select>
      </td> 
                  <td  style="background: #7EC0EE" ><strong><font color="556B2F">English Level</font></strong></td> 
            <td  >
      <select   style="width: 80%" name="english">
                @foreach($english as $english)
                 @if($english->type =='eng')
                     <option value="{{$english->id}}"   @if( session('forms.ssEnglishID')  ==$english->id ) selected="selected" @endif   >{{ $english->name }}</option>
                    @endif
                @endforeach
      </select>
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Indispensable Skill</font></strong></td> 
             <td colspan="3"  >
      <input type="text"  id="lgFormGroupInput" style="width :80%"
        value="{{ old('indispensable')}}" name="indispensable" >
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Other Skill</font></strong></td> 
             <td colspan="3"  >
       <input type="text"  id="lgFormGroupInput" style="width :80%"
        value="{{ old('skill')}}"  name="skill" >
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE;width: 15%" ><strong><font color="556B2F">
            Salary (From)</font></strong></td> 
            <td   >
       <input type="text"  id="salaryFromID"  style="width: 70%"
        value="{{ old('salaryFrom')}}" name="salaryFrom"
         onkeypress='validateNum(event)' > &nbsp;
        
     
      
     <select    name="unitSaFrom"  >
                @foreach($unitSaFrom as $unitSaFrom)
                    <option value="{{$unitSaFrom->id}}"   @if( session('forms.ssunitSaFromID')  ==$unitSaFrom->id ) selected="selected" @endif   >{{ $unitSaFrom->code }}</option>
                @endforeach
      </select>
      </td> 
        <td  style="background: #7EC0EE;width: 17%" ><strong><font color="556B2F">
            Salary (To)</font></strong></td> 
            <td    >
       <input type="text"  id="salaryToID"  
        value="{{ old('salaryTo')}}" name="salaryTo"  onkeypress='validateNum(event)' 
         > &nbsp;
        
     
      
      <select     name="unitSaTo"  >
                @foreach($unitSaTo as $unitSaTo)
                    <option value="{{$unitSaTo->id}}"   @if( session('forms.ssunitSaToID')  ==$unitSaTo->id ) selected="selected" @endif   >{{ $unitSaTo->code }}</option>
                @endforeach
      </select>
     </td> 
       </tr>
        <tr>
        <td  style="background: #7EC0EE" ><strong><font color="556B2F">Introduce Fee  (From)</font></strong></td> 
            <td   >
      <input type="text"   id="introduceFeeID"  name="introduceFee" style="width: 70%"
          value="{{ old('introduceFee')}}" onkeypress='validateNum(event)' > &nbsp;
      
     <select     name="unitFrom"  >
                @foreach($unitFrom as $unitFrom)
                    <option value="{{$unitFrom->id}}"   @if( session('forms.ssunitFromID')  ==$unitFrom->id ) selected="selected" @endif   >{{ $unitFrom->code }}</option>
                @endforeach
      </select>
      </td> 
               
    </tr>
   <tr>
            <td  style="background: #7EC0EE;width: 10%" ><strong><font color="556B2F">Partner</font></strong></td> 
            <td  >
      <input type="text"  id="partnerID"  name="partner"
          value="{{ old('partner')}}"  >
      </td> 
                  <td  style="background: #7EC0EE;width: 11%" ><strong><font color="556B2F">Partner Fee</font></strong></td> 
            <td  >
      <input type="text"  id="partnerFeeID"  name="partnerFee"
          value="{{ old('partnerFee')}}" onkeypress='validateNum(event)' >
      </td> 
    </tr> 
  <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Warranty Period</font></strong></td> 
                    <td  >
      <input type="text"    id="warrantlyPeriod"
                value="{{ old('warrantlyPeriod')}}" name="warrantlyPeriod"  >
      </td> 
       <td  style="background: #7EC0EE" ><strong><font color="556B2F">Warranty</font></strong></td> 
                   <td  >
       <select    name="warranty">
        <option></option>
                @foreach($warranty as $warranty)
                 @if($warranty->type =='warranty')
                    <option value="{{$warranty->id}}"   @if(  old('warranty')  ==$warranty->id ) selected="selected" @endif   >{{ $warranty->name }}</option>
                 @endif
                @endforeach
      </select>
      </td>   
    </tr>

    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Note</font></strong></td> 
             <td colspan="3"  >
      <input type="text"  id="lgFormGroupInput" style="width :80%" 
          value="{{ old('note')}}" name="note" >
      </td> 
    </tr> 
    
    
    
     
    

    
  
  

    
    
    
     
    
     
    
      </table>
</div>
</div>     
</div>    

    <div class="content">
      <input type="hidden" name="_method" value="POST">
      <input type="submit" class="btn btn-default" style=" background-color: #DCDCDC;" value="Create">


      
    </div>
	<br>
	<br>
	<br>
  </form>
</div>

<script type="text/javascript">

       var url = "{{ url('/showDivision') }}";
    
    $("select[name='client']").change(function(){
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
            
                $("select[name='division'").html('');
                $.each(data, function(key, value){
                    $("select[name='division']").append(
                        "<option value=" + value.id + ">" + value.divisionname + "</option>"
                    );
                      $("#workingPlaceID").val(data[0].address);

                });
            }
        });
    });

      var url2 = "{{ url('/showAddressByDiv') }}";
    
    $("select[name='division']").change(function(){

        var division_id = $(this).val();

        var token = $("input[name='_token']").val();

        $.ajax({
            url: url2,
            method: 'POST',
            data: {
                id: division_id,
                _token: token
            },
            success: function(data) {
            
               

                $.each(data, function(key, value){
                 $("#workingPlaceID").val(value.address);
                

                });
            }
        });
    });
        
   $( function() {
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',
       
    });
  } );
      $( function() {
    $( "#workingDate" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',
        
    });
  } );
       $( function() {
    $( "#introducePriority" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',
        
    });
  } );
       $( function() {
    $( "#invoiceDateID" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',

        
    });
  } );

function validateNum(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
var input = document.getElementById('introduceFeeID');
  input.addEventListener('keyup', function(e)
  {
    input.value = format_number(this.value);
  });
  

  
var inputPartnerFee = document.getElementById('partnerFeeID');
  inputPartnerFee.addEventListener('keyup', function(e)
  {
    inputPartnerFee.value = format_number(this.value);
  });
  var salaryFromval = document.getElementById('salaryFromID');
  salaryFromval.addEventListener('keyup', function(e)
  {
    salaryFromval.value = format_number(this.value);
  });

       var unitSaToval = document.getElementById('salaryToID');
  unitSaToval.addEventListener('keyup', function(e)
  {
    unitSaToval.value = format_number(this.value);
  });



  
  /* Function */
  function format_number(number, prefix, thousand_separator, decimal_separator)
  {
    var thousand_separator = thousand_separator || ',',
      decimal_separator = decimal_separator || '.',
      regex   = new RegExp('[^' + decimal_separator + '\\d]', 'g'),
      number_string = number.replace(regex, '').toString(),
      split   = number_string.split(decimal_separator),
      rest    = split[0].length % 3,
      result    = split[0].substr(0, rest),
      thousands = split[0].substr(rest).match(/\d{3}/g);
    
    if (thousands) {
      separator = rest ? thousand_separator : '';
      result += separator + thousands.join(thousand_separator);
    }
    result = split[1] != undefined ? result + decimal_separator + split[1] : result;
    return prefix == undefined ? result : (result ? prefix + result : '');
  };
       function OnChangeCheckbox (invoiceCK) {
            if (invoiceCK.checked) {
     var currentDt = new Date();
    var mm = currentDt.getMonth() + 1;
    var dd = currentDt.getDate();
    var yyyy = currentDt.getFullYear();
    var date = yyyy+"-"+mm+"-"+dd;
          document.getElementById('invoiceDateID').style.visibility = 'visible';
          document.getElementById('invoiceDateID').value= date;

            
            }
            else {
              document.getElementById('invoiceDateID').style.visibility = 'hidden';
            }
        }
</script>
<style>
div.lal { 
  background: #E6E6FA;
  height: 40px;
  width: 110%;
}
#lal label {display:block; width:100%; height:30px; text-align:right;}

div.b {
    text-align: left;
}

div.c {
    text-align: right;
} 

div.d {
    text-align: justify;
} 
</style>
@endsection