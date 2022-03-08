@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
    <br></br>
                   <b style="font-size: 18px; margin-left: 220px  ">    Clone Order  </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
  


           <form method="post" action="{{action('OrderController@update',$id)}}">
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
<div class="container">
<div class="row" style="margin-left:6em">
 <div class = "col-sm-11"  style="width: 88%" >
 <table class="table-condensed table-striped table-bordered" id="selecter">
   <input name="_method" type="hidden" value="PATCH">
    <label   class="col-sm-12 col-form " style="text-align: left ">
       [Basic info] <br>
     
    
  </label>
 <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Company *</font></strong></td> 
            <td colspan="3"  >
    <select    name="client" id="clientslt">
      <option value="{{$order->clientID}}">{{$order->clientName}}</option>
                @foreach($client as $client)
                    <option value="{{$client->id}}"     >{{ $client->companyname }}</option>
                @endforeach
        </select>
      </td> 
</tr>
    <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Division *</font></strong></td> 
            <td colspan="3"  >
      <select   name="division" id="divisionslt">
        <option value="{{$order->divisionID}}">{{$order->divisionName}}</option>
                 @foreach($division as $division)
                    <option value="{{$division->id}}">{{ $division->divisionname }}</option>
                @endforeach      
      </select>
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Pic *</font></strong></td> 
            <td  >
       <select  id=piclst   name="pic">
        <option value="{{$order->pic_s}}">{{$order->picName}}</option>
                @foreach($pic as $pic)
                    <option value="{{$pic->id}}">{{ $pic->name }}</option>
                @endforeach
      </select>
      </td> 
               
    </tr>
   <tr >

      <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Type *</font></strong></td> 
            <td  >
      <select    name="type">
        <option value="{{$order->type}}">{{$order->typeName}}</option>
                @foreach($type as $type)
                 @if($type->type =='type')
                    <option value="{{$type->id}}">{{ $type->name }}</option>
                 @endif
                @endforeach
      </select>
      </td> 
          <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Invoice</font></strong>     </td> 
       <td  colspan="2" >
      <input type="checkbox" name="invoiceCK" onclick="OnChangeCheckbox (this)"
       <?php if( $order->invoiceCK == '1' ) {  echo "checked"; } ?> value="1" > 
            
               <input type="text"   id="invoiceDateID"  
                <?php if( $order->invoiceCK <> '1' ) {  echo "style='visibility: hidden;' "; } ?>
        value="{{$order->invoiceDate}}" name="invoiceDate"   >
             
      </td> 
    </tr>
      <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
            Order Date *</font></strong></td> 
            <td  >
      <input type="text"  id="datepicker" 
        value="{{$order->orderDate}}"  name="orderDate"  > 
      </td>
        <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Progress *</font></strong></td> 
            <td  >
      <select   id="progresslst" name="progress">
          <option value="{{$order->progress}}">{{$order->progressName}}</option>
                @foreach($progress as $progress)
                 @if($progress->type =='progress')
                    <option value="{{$progress->code}}">{{ $progress->name }}</option>
                 @endif
                @endforeach
      </select>
      </td> 
    </tr>
      <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
            Introduce Date *</font></strong></td> 
            <td  >
        <input type="text"  id="introducePriority"
      value="{{$order->introduceDate}}" name="introducePriority"  > 
      </td>
   
    </tr>
    <tr> 
        <td colspan="4"  >
        <label   class="col-sm-12 col-form " style="text-align: left;margin-left: -20px ">
         [Detail info]   
        </label>
      </td>
        </tr>
    
    <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Project Title</font></strong></td> 
            <td colspan="3"  >
       <input type="text"  id="lgFormGroupInput"  style="width: 95%"   name="title" value="{{$order->title}}" >
      </td> 
                 
    </tr>
  <tr>
    <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
      Working Date</font></strong></td> 
            <td  >
       <input type="text"   id="workingDate"
      value="{{$order->workingDate}}"  name="workingDate"  > 
      </td> 
   <td  style="background: #7EC0EE" nowrap="true" ><strong><font color="556B2F">Recruitment Number*</font></strong></td> 
            <td  >
        <input  id="FirstName"  name="recruit_num" type="text" 
        onkeypress='validateNum(event)' value="{{$order->recruit_num}}"  />
      </td> 

  </tr>
  <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Working place</font></strong></td> 
            <td colspan="3"  >
      <input type="text"  id="lgFormGroupInput" value="{{$order->workingPlace}}" style="width: 90%" 
         name="workingPlace" >
      </td> 
    </tr>
    <tr >
             <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Age *</font></strong></td> 
            <td  >
      <input type="text"  id="lgFormGroupInput" 
   value="{{$order->age}}"  name="age"  >
      </td> 
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Sex *</font></strong></td> 
            <td  >
       <select  style="width: 80%"  id="sexlst" name="sex">
        <option value="{{$order->sex}}">{{$order->sexName}}</option>
         @foreach($sex as $sex)
                @if($sex->type =='sex')
                    <option value="{{$sex->id}}">{{ $sex->name }}</option>
                @endif
         @endforeach
         </select>
      </td>  
    </tr> 
  <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Japanese Level</font></strong></td> 
            <td  >
       <select  style="width: 80%" id="japaneselst"  name="japanese">
          <option value="{{$order->JLevel}}">{{$order->jpName}}</option>
                @foreach($japanese as $japanese)
                @if($japanese->type =='jp')
                    <option value="{{$japanese->id}}">{{ $japanese->name }}</option>
                @endif
                @endforeach
      </select>
      </td> 
                  <td  style="background: #7EC0EE"  ><strong><font color="556B2F">English Level</font></strong></td> 
            <td  >
      <select   style="width: 80%" id="englishlst" name="english">
         <option value="{{$order->ELevel}}">{{$order->engName}}</option>
                @foreach($english as $english)
                 @if($english->type =='eng')
                    <option value="{{$english->id}}">{{ $english->name }}</option>
                    @endif
                @endforeach
      </select>
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Indispensable Skill</font></strong></td> 
             <td colspan="3"  >
      <input type="text"  id="lgFormGroupInput"  style="width: 95%" 
        value="{{$order->indispensable}}" name="indispensable" >
      </td> 
    </tr>
   <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Other Skill</font></strong></td> 
             <td colspan="3"  >
        <input type="text"  id="lgFormGroupInput"  style="width: 95%" 
        value="{{$order->skill}}" name="skill" >
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE;width: 16.8%"  ><strong><font color="556B2F">Salary (From)</font></strong></td> 
            <td  >
       <input type="text"   id="salaryFromID"  style="width : 60%" 
        value="{{number_format($order->salaryFrom)}}" name="salaryFrom"
         onkeypress='validateNum(event)' > &nbsp;
       
      
     <select    name="unitSaFrom"  >

                @foreach($unitSaFrom as $unitSaFrom)
               <option value="{{$unitSaFrom->id}}" 
                @if( $order->unitSaFrom ==$unitSaFrom->id ) selected="selected" @endif >
                {{ $unitSaFrom->code }}</option>
                @endforeach
      </select>
        
      </td> 
           <td  style="background: #7EC0EE;width: 15%"  ><strong><font color="556B2F">Salary (To)</font></strong></td> 
            <td  >
       <input type="text"   id="salaryToID"  style="width : 60%" 
        value="{{ number_format($order->salaryTo) }}" name="salaryTo"
         onkeypress='validateNum(event)' > &nbsp;
     
      
     <select    name="unitSaTo"  >

                @foreach($unitSaTo as $unitSaTo)
               <option value="{{$unitSaTo->id}}" 
                @if( $order->unitSaTo ==$unitSaTo->id ) selected="selected" @endif >
                {{ $unitSaTo->code }}</option>
                @endforeach
      </select>
        
      </td> 
    </tr>
    <tr>
        <td  style="background: #7EC0EE;"   ><strong><font color="556B2F">Introduce Fee (From)</font></strong></td> 
            <td  >
       <input type="text"  id="introduceFeeID"  style="width : 60%" 
        value="{{number_format($order->introduceFee)}}" name="introduceFee"
         onkeypress='validateNum(event)' > &nbsp;
       
     <select    name="unitFrom"  >
                @foreach($unitFrom as $unitFrom)
                    <option value="{{$unitFrom->id}}"    @if( $order->unitFrom ==$unitFrom->id ) selected="selected" @endif >{{ $unitFrom->code }}</option>
                @endforeach
      </select>
      </td> 
                 
    </tr>
     <tr >
            <td  style="background: #7EC0EE;width: 10.7%"  ><strong><font color="556B2F">Partner</font></strong></td> 
            <td  >
       <input type="text"  id="partnerID"  
        value="{{$order->partner}}" name="partner"
         >
      </td> 
                  <td  style="background: #7EC0EE;width: 9.5%"  ><strong><font color="556B2F">Partner Fee</font></strong></td> 
            <td  >
       <input type="text"  id="partnerFeeID"  
        value="{{number_format($order->partnerfee)}}" name="partnerFee"
         onkeypress='validateNum(event)' >
      </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Warranty Period</font></strong></td> 
            <td  >
        <input type="text"    id="warrantlyPeriod"
             value="{{$order->warrantyPeriod}}" name="warrantlyPeriod"  > 
      </td> 
  
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Warranty</font></strong></td> 
             <td colspan="3"  >
      <select  id="warrantylst"  name="warranty">
          <option value="{{$order->warranty}}">{{$order->warrantyName}}</option>
                @foreach($warranty as $warranty)
                 @if($warranty->type =='warranty')
                    <option value="{{$warranty->id}}">{{ $warranty->name }}</option>
                 @endif
                @endforeach
      </select>
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Note</font></strong></td> 
             <td colspan="3"  >
      <input type="text"  id="lgFormGroupInput" 
          value="{{$order->note}}" name="note" >
      </td> 
    </tr>
   </table>
</div>
</div>     
</div>    
  <td> <input  id="companyinputid" name="actionID" type="hidden"  value={{$actionID}}></td>

    <div class="form-group row">
      <div class="col-md-6"></div>
      <input type="submit" class="btn btn-default" style=" background-color: #DCDCDC;"  value="Create">
    </div>
    <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
  </form>
   <p align="right"> <a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;  </a> 
          </p>
</div>

<script type="text/javascript">
   var clientarr =[];
$('#clientslt option').each(function(){
     if($.inArray(this.value, clientarr) >-1){
      $(this).remove();
   }else{
      clientarr.push(this.value);
   }
});
  var divisionarr =[];
$('#divisionslt option').each(function(){
     if($.inArray(this.value, divisionarr) >-1){
      $(this).remove();
   }else{
      divisionarr.push(this.value);
   }
});
  var picarr =[];
$('#piclst option').each(function(){
     if($.inArray(this.value, picarr) >-1){
      $(this).remove();
   }else{
      picarr.push(this.value);
   }
});
var sexarr =[];
$('#sexlst option').each(function(){
   if($.inArray(this.value, sexarr) >-1){
      $(this).remove()
   }else{
      sexarr.push(this.value);
   }
});
var japanesearr =[];
$('#japaneselst option').each(function(){
   if($.inArray(this.value, japanesearr) >-1){
      $(this).remove()
   }else{
      japanesearr.push(this.value);
   }
});
var japanesearr =[];
$('#englishlst option').each(function(){
   if($.inArray(this.value, japanesearr) >-1){
      $(this).remove()
   }else{
      japanesearr.push(this.value);
   }
});
var progressarr =[];
$('#progresslst option').each(function(){
   if($.inArray(this.value, progressarr) >-1){
      $(this).remove()
   }else{
      progressarr.push(this.value);
   }
});
var warrantyarr =[];
$('#warrantylst option').each(function(){
   if($.inArray(this.value, warrantyarr) >-1){
      $(this).remove()
   }else{
      warrantyarr.push(this.value);
   }
});
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
    $( "#warrantlyPeriod" ).datepicker({
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
  
  var partnerFeeL = document.getElementById('partnerFeeID');
var input = document.getElementById('introduceFeeID');
var salaryFromID_in = document.getElementById('salaryFromID');

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
      
            var salaryToval = document.getElementById('salaryToID');
  salaryToval.addEventListener('keyup', function(e)
  {
    salaryToval.value = format_number(this.value);
  });
   
  
  /* With Prefix */

  
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



 
</script>
@endsection