@extends('layouts.elements.master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Edit Report  </b>
                   <!-- approve.approveDetail  -->
  </div>
<div class="container">
   <form method="post" action="{{action('DailyReportController@update', $reports->id )}}"
    autocomplete="off" >
{{ csrf_field() }}
 <input name="_method" type="hidden" value="PATCH">
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
   
	
     <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Creator</font></strong></td> 
            <td colspan="3" class="col-md-5" >
     {{ Auth::user()->name }}
      </td> 
     </tr>
       <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date, Time</font></strong></td> 
            <td colspan="3"   >
    <input type="date"  required="true"   id="canJob" name="date" 
             value="{{$reports->date}}" style="width: 25%"   />
              <input type="time" required="true"  style="width: 15%" placeholder="hh:mm" value="{{$reports->from}}"  min="00:00" max="24:00"   onchange="validateHhMm(this);"  name="fromTime"/>  ~
              <input type="time" required="true"  style="width: 15%" placeholder="hh:mm" value="{{$reports->to}}"  min="00:00" max="24:00"   onchange="validateHhMm(this);"  name="toTime"/> 
      </td> 
     </tr>
    <tr>
          <td  style="background: #7EC0EE;width: 20%" ><strong><font color="556B2F">
          Company </font><font style="color:red">*</font></strong></td> 
          {{csrf_field()}}
          <td colspan="3"  >
              <select     name="client">
      
            @foreach($client as $client)
              <option value="{{$client->id}}"  @if( $client->id == $reports->companyID ) selected="selected" @endif     >{{ $client->companyname }}</option>
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
         <option value="{{$division->id}}"   @if( $division->id ==$reports->divisionID ) selected="selected" @endif   >{{ $division->divisionname }}</option>
           @endforeach
      </select>
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Other</font></strong></td> 
            <td  id="otherID">
       <textarea  name="other" rows="2" class="form-control" 
       style="resize: none;">{{$reports->other}}</textarea>
      </td> 
               
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Customer</font></strong></td> 
            <td  >
       <textarea  name="customer" rows="5" class="form-control" style="resize: none;">{{$reports->customer}}</textarea>
      </td> 
               
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Visitor</font></strong></td> 
            <td  >
       <textarea  name="visitor" rows="2" class="form-control" style="resize: none;">{{$reports->visitor}}</textarea>
      </td> 
               
    </tr>
    <tr>
      <td  style="background: #7EC0EE" ><strong><font color="556B2F">Kind/ Way</font></strong></td> 
      <td  >
      <select    name="kind">
      <option>------</option>
                @foreach($kind as $kind)
                 
                     <option value="{{$kind->id}}"   @if($reports->kind ==$kind->id ) selected="selected" @endif   >{{ $kind->name }}</option>
                 
                @endforeach
      </select>
       <select    name="way">
      <option>------</option>
                @foreach($dr_master as $dr_master)
                    @if($dr_master->type='way')
                     <option value="{{$dr_master->id}}"   @if($reports->way ==$dr_master->id ) selected="selected" @endif   >{{ $dr_master->name }}</option>
                  @endif
                @endforeach
      </select>
      </td> 
      
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">
           Title</font></strong></td> 
            <td  >
   <input type="text" placeholder="Title"  style="width: 80%" 
          value="{{$reports->title}}"  class="form-control" name="title" id="title" >
    
      </td> 
    </tr>
   
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Detail</font></strong></td> 
            <td  >
       <textarea  name="detail" rows="30" class="form-control" style="resize: none;">{{$reports->detail}}</textarea>
      </td> 
               
  
       
    </tr>
    
    
    
    
     
    

    
  
  

    
    
    
     
    
     
    
      </table>
      <p align="right"> <a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;  </a> 
          </p>
</div>
</div>     
</div>    

    <div class="content">
      
      <input type="submit" class="btn btn-default" style=" background-color: #DCDCDC;" value="Update">


      
    </div>
	<br>
	<br>
	<br>
  </form>
  <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
</div>

<script type="text/javascript">
    document.getElementById("myFrame").onload = function() {myFunction()};

function myFunction() {
   var divisionID= $("select[name='division']").val();
  // alert(nationalflag);
  if (divisionID>1){
          //  alert(nationalflag);
            document.getElementById("otherID").style.display="none";
            document.getElementById("otherID").style.display="none";
            
          // 
          } else
          { document.getElementById("otherID").style.display="block";
            document.getElementById("otherID").style.display="block";
            
        }
}
      function validateHhMm(inputField) {
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputField.value);

        if (isValid) {
            inputField.style.backgroundColor = '#bfa';
        } else {
            inputField.style.backgroundColor = '#fba';
        }

        return isValid;
    }

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
          if (province_id == 10000) {
            //alert(national_id);
            document.getElementById("otherID").style.display="block";
           
            
          // 
          } else
          { 
          
            document.getElementById("otherID").style.display="none";
        }
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