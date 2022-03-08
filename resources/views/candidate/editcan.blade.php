@extends('master')
@section('content')
   <div style="margin-top: -4%; float: left;margin-left: 1%;  " >
                   <b style="font-size: 18px">  Edit Candiate  </b>
                   <!-- approve.approveDetail  -->
                </div>
                 <div style="margin-top: -4%; float: center ;margin-right:  8%;" >
                   
 </div>
<div   >
<div class="container">
 <form method="post" action="{{action('CandidateController@update', $id)}}" autocomplete="off" >
 {{csrf_field()}}
 <input name="_method" type="hidden" value="PATCH">
     <div class="form-group row">
       <div class="col-sm-2"  > </div>

  </div>


   <div class="form-group row">
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


    </div>
   <div class="row">
 <div class="col-sm-15"  > 
 <div class="col-sm-1"    > </div> 
 <div class = "col-sm-10"  >
  <table class="table  table-bordered"  >
    <tr>
      <p style="margin-top: -2.4%" align="right">
<a href="javascript:history.back()">Back &nbsp;&nbsp;&nbsp;  </a> 
 </p>
    </tr>
     <tr >
     <td  style="background: #7EC0EE"  width="15%"><strong><font color="556B2F">
       Name </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-3" colspan="3" >
       <input style="width: 33%" id="FirstName" name="firstName" 
       value="{{$candi->firstName}}" type="text"   />
             <input style="width: 32%" id="MiddleName" name="midleName" value="{{ $candi->midleName}}" type="text"  />
             <input style="width: 32%" id="LastName" name="lastName" value="{{$candi->lastName}}" type="text"  />
      </td> 
        </tr>
    <tr >
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
        Name （JP/VN）</font></strong></td> 
             <td class="col-md-3" colspan="3" >
                  <input style="width: 33%" id="FirstName" name="firstNameJ" value="{{ $candi -> firstNameJ }}"  type="text"   />
                        <input style="width: 32%" id="FirstName" name="midleNameJ" 
                        value="{{ $candi -> midleNameJ }}" type="text"   />
                        <input style="width: 32%" id="LastName" name="lastNameJ" value="{{ $candi -> lastNameJ }}" type="text"   />

      </td> 
    </tr>
  <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Birthday </font></strong></td> 
            <td class="col-md-1"  >
      <input type="date" name="birthday"  value="{{ $candi -> birth2 }}" min="1950-01-01"   class="form-control" />
      </td> 
      
     <td  colspan="2" >
        <table class="table"  style="  margin: -7px">
          
           
        
       <td  style="background: #7EC0EE"  width="20%"  nowrap="true"><strong><font color="556B2F">
       Marital Status</font></strong></td> 
            <td     width="30%"  nowrap="true">
     
           <input type="radio" name="married"
            <?php  if ($candi->married=="Single")  echo "checked";?>
            value="Single">Single  &nbsp; 
           
             <input type="radio" name="married"
            <?php if ($candi->married=="Married")  echo "checked";?>
            value="Married">Married
           
          
      </td> 
      <td  style="background: #7EC0EE" nowrap="true" ><strong><font color="556B2F">
      Sex </font><font style="color:red">*</font></strong></td> 
           <td     nowrap="true">
          
            <input type="radio" name="sex"
            <?php if ($candi->sex=="31") echo "checked";?>
            value="31">Female  &nbsp; 
          
           <input type="radio" name="sex"
            <?php  if ($candi->sex=="32") echo "checked";?>
            value="32">Male
          
      </td> 

          

        </table>
     
      </td> 
           
       
      
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Email </font> </strong></td> 
            <td    width="30%"  >
       <input type="email" class="form-control form-control-lg" id="lgFormGroupInput"  name="email" value="{{ $candi -> email }}"  >

      </td> 
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Email Status </font></strong></td> 
      <td class="col-md-1" >
           <select name='contact'  style="height: 28px; width: 160px">
            
           <?php 
              foreach($emailStatus as $contact) {
                if($contact->type =='emailStatus'){
                echo "<option value='".$contact->id."'";
                   if( $contact->id  == $candi->contact ) { echo " selected='selected'  ";  } 
                echo ">".$contact->name."</option>";
              }
            }
            ?>
      </td> 
    </tr>
  <tr>
   <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Mobile </font></strong></td> 
            <td class="col-md-1"  >
  <input  class="form-control form-control-lg" id="mobile" name="mobile" type="text" onkeypress='validate(event)' value="{{ $candi -> mobile }}"  />

      </td> 
     <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Mobile Status </font></strong></td> 
      <td class="col-md-1" >
           <select name='mobileStatus'  style="height: 28px; width: 160px">
           
           <?php 
              foreach($mobileStatus as $contact) {
                if($contact->type =='mobileStatus'){
                echo "<option value='".$contact->id."'";
                   if(!is_null($candi->mobilestatus) && $contact->id  == $candi->mobilestatus ) { echo " selected='selected'  ";  } 
                echo ">".$contact->name."</option>";
              }
            }
            ?>
      </td> 
  </tr>
  
    
    
     <tr >

            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      University </font></strong></td> 
             <td class="col-md-1" colspan="3" >
                <select class="form-control" required="true"  name="university" >
                  <option value="" >--------</option>
       
                  
                @foreach($university as $university)
                    <option value="{{$university->id}}"   @if( $university->id == $candi->university  ) 
          selected="selected" @endif     >{{ $university->eName }}</option>
                @endforeach
                
            </select>
      </td> 
       </tr>
         <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Majors </font><font style="color:red">*</font></strong></td> 
             <td class="col-md-1" colspan="3" >

     <select class="form-control"   name="majors" >
      <option value="" >--------</option>
                @foreach($majors as $major)
                    <option value="{{$major->val}}"  @if( $major->val == $candi->majors ) selected="selected" @endif     >{{ $major->name }}</option>
                @endforeach
            </select>
      </td> 

  
    </tr>
    <tr>
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Graduate year </font></strong></td> 
            <td class="col-md-1" >
       <input type="text" min="2000"  class="form-control form-control-lg" id="lgFormGroupInput"  name="graduate" value="{{ $candi -> graduates }}"  oninput="this.value=this.value.replace(/[^0-9]/g,'');" >
      </td> 
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Foreigner </font></strong></td>   
      <td class="col-md-1"  >
            
                <input type="checkbox" name="foreigner" 
                <?php if( $candi->foreigner == '1' ) {  echo "checked"; } ?>  value="1"> Foreigner
              
      </td>   
      
</tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Current address </font></strong></td> 
            <td class="col-md-1"  >
      <select class="form-control"   name="currentAdd" >
         <option value="" >--------</option>
          <?php 
               foreach($province as $province){
                 if( $province->Id  == 148 || $province->Id == 179)
                  echo "  <option value='' >--------</option>  ";
                echo "<option value='".$province->Id."'";
                 if( $province->Id  == $candi->currentAdd ) echo " selected='selected'  ";
                echo ">".$province->Name."</option>";
              }
            ?>
      
            </select>
      </td> 
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Place of birth </font></strong></td> 
            <td class="col-md-1" >
            <select class="form-control"   name="birthPlace" >
               <option value="" >--------</option>
               
                <?php 
               foreach($birthPlace as $province){
                 if( $province->Id  == 148 || $province->Id == 179)
                  echo "  <option value='' >--------</option>  ";
                echo "<option value='".$province->Id."'";
                 if( $province->Id  == $candi->birthPlace ) echo " selected='selected'  ";
                echo ">".$province->Name."</option>";
              }
            ?>

            </select>
      </td> 
      
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       English level </font></strong></td> 
            <td class="col-md-1"  >
                 <select name='english'  style="width: 159px; height: 28px;">
                 <option value="20" >--------</option>
                @foreach($english_levels as $type)
                 @if($type->type =='eng')
                    <option value="{{$type->id}}"   
                      @if($type->id ==$candi->eLevel) selected="selected" @endif 
                            >{{ $type->name }}</option>
                 @endif
                @endforeach
            </select>
          <input style="width: 159px; height: 28px;" id="toeic" placeholder="Toeic" name="toeic" 
          value="{{ $candi->toeic}}" onkeypress='validateToeic(event)'    />
            </td> 
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Japanese level </font></strong></td> 
            <td class="col-md-1" >
              <select class="form-control" id="japaneselst"   name="japanese">
                  <option value="1500" >--------</option>
                @foreach($japanese_levels as $type)
                 @if($type->type =='jp')
                    <option value="{{$type->id}}"
                      @if($type->id ==$candi->jLevel) selected="selected" @endif 
                      >{{ $type->name }}</option>
                 @endif
                @endforeach
            </select>

           </td> 
      
    </tr>
      <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Korean level</font></strong></td> 
            <td class="col-md-1"  >
                 <select name='korean'  style="width: 159px; height: 28px;">
                 <option value="" >--------</option>
                @foreach($korean as $type)
                 @if($type->type =='korean')
                    <option value="{{$type->id}}"   
                      @if($type->id ==$candi->korean) selected="selected" @endif 
                            >{{ $type->name }}</option>
                 @endif
                @endforeach
            </select>
            </td> 
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Chinese level </font></strong></td> 
            <td class="col-md-1" >
              <select class="form-control" id="chineselst"   name="chinese">
                  <option value="" >--------</option>
                @foreach($chinese as $type)
                 @if($type->type =='chinese')
                    <option value="{{$type->id}}"
                      @if($type->id ==$candi->chinese) selected="selected" @endif 
                      >{{ $type->name }}</option>
                 @endif
                @endforeach
            </select>

           </td> 
      
    </tr>
     <tr >
                  <td  class="col-md-1" style="background: #7EC0EE"><strong><font color="556B2F">
       Workplace desire </font></strong></td> 
            <td class="col-md-1"  >
       <select name='workPlace' id="workplace_select"  style="width: 159px; height: 28px;">
          <option value=''>---------</option>
            <?php 
              foreach($workplace as $en){
           if($en->type =='wplace'){
                echo "<option value='".$en->id."'";
                 if( $en->id  ==  $candi->workPlace ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  
              }}
            ?>
          </select>
      </td> 

       <td class="col-md-1" colspan="2" >
         <input  class="form-control form-control-lg" id="otherWorkplace" name="workplaceTxt" type="text" placeholder="Workplace" value="{{ old('otherWorkplace') }}"   />
      </td>
     </tr>
      
         <tr id="wpID">
         
            <td  style="background: #7EC0EE"  class="col-md-1">
    
  </td> 
   <td colspan="3">
    <table>
      <tr>
      <div class='col-sm-4'>
                      <?php 
              foreach($workplaceLst as $copor){
                   if ($copor->CountryCode=="B" && !is_null($copor->Id)) {
               echo "<div  >
               
               <input type='checkbox' name='workplace_select[]' value='".$copor->Id."'";
                if(in_array($copor->Id,$workplace_selected ))  echo "checked";
                echo "> ".$copor->Name."</div>";
              
              }}
              ?>
            </div>
      
     <div class='col-sm-4'>
                      <?php 
              foreach($workplaceLst as $copor){
                   if ($copor->CountryCode=="T" && !is_null($copor->Id)) {
               echo "<div  >
               
               <input type='checkbox' name='workplace_select[]' value='".$copor->Id."'";
                if(in_array($copor->Id,$workplace_selected ))  echo "checked";
                echo "> ".$copor->Name."</div>";
              
              }}
              ?>
            </div>
       
            <div class='col-sm-4'>
                      <?php 
              foreach($workplaceLst as $copor){
                   if ($copor->CountryCode=="N" && !is_null($copor->Id)) {
               echo "<div  >
               
               <input type='checkbox' name='workplace_select[]' value='".$copor->Id."'";
                if(in_array($copor->Id,$workplace_selected ))  echo "checked";
                echo "> ".$copor->Name."</div>";
              
              }}
              ?>
            </div>
       
      </tr>
    </table>
       </td> 
    </tr>
     
     
    <tr>
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Pre-Interview </font></strong></td>   
            <td class="col-md-1"  >
               <input type="checkbox" name="mandan" 
                <?php if( $candi->mandan == '1' ) {  echo "checked"; } ?> value="1"> 
                <input type="date" name="mandanDate"  value="{{$candi->mandanDate}}" min="2016-01-01" />
      </td> 
        
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
        Staff Pre-interview </font></strong></td>   
            <td class="col-md-1"  >
              <select class="form-control"   name="pic_s" id="pic_slst">
                  <option value="" >--------</option>
                @foreach($users as  $type)
                    <option value="{{$type->id}}"
                      @if($type->id ==$candi->staff) selected="selected" @endif 
                      >{{ $type->name }}</option>
                @endforeach
            </select>
            </td>  
    </tr>
    <tr>
      <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Entering </font></strong></td>   
            <td class="col-md-1"  >
               <input type="checkbox" name="workcheck" 
                <?php if( $candi->workcheck == '1' ) {  echo "checked"; } ?>  value="1"> 
                <input type="date" name="workDate"  value="{{$candi->workDate}}" min="2016-01-01"  />
 
      </td> 
    </tr>
  <tr >     
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Source</font></strong></td> 
            <td class="col-md-1" >
           <select name='source'  style="height: 28px; width: 300px">
            <option value="" >---------</option>
           <?php 
              foreach($source as $source) {
                if($source->type =='source'){
                echo "<option value='".$source->id."'";
                   if( $source->id  == $candi->source ) { echo " selected='selected'  ";  } 
                echo ">".$source->name."</option>";
              }
            }
            ?>
      </td> 
         <td class="col-md-1" colspan="2" >
         <input   class="form-control form-control-lg" id="otherSource" name="sourceTxt" type="text"  value="{{$candi->sourceTxt}}"   />
      </td>
    </tr>
      <tr>
            <td    class="col-md-1" style="background: #7EC0EE">
              <strong><font color="556B2F">
       Occupation </font></strong>
    <button type="button" id="buttonOcc" onclick="showOcc()">Hide  Occupation</button>
  </td> 
      
         
          
             
            <td   colspan="3" >
               <div id="divOcc">
                 <label style="text-align: left;width: 100%">Left=Desire : Right=Experience </label> 
                 &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">IT-Software </label> <br>
              <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='1'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->val."'";
               if(in_array($copor->val,$occupation))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->exp."'";
               if(in_array($copor->exp,$experience))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
               <input type="text" placeholder="Other" style="width: 80%" 
        value="{{$candi->otherIT}}"   class="form-control" name="other_subIT" id="other_subIT" >
              
            &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">Manufacturing </label> <br>

            <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='2'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsTech_select[]' value='".$copor->val."'";
              if(in_array($copor->val,$occupation))  echo "checked";
                echo ">
               <input type='checkbox' name='itemsTech_select[]' value='".$copor->exp."'";
              if(in_array($copor->exp,$experience))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>  
                <input type="text" placeholder="Other" style="width: 80%" 
          value="{{$candi->otherManu}}"    class="form-control" name="other_nanu" id="other_nanu" >
             &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">Technology </label> <br>

             <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='3'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemOther_select[]'
                value='".$copor->val."'";
              if(in_array($copor->val,$occupation))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemOther_select[]'
                value='".$copor->exp."'";
              if(in_array($copor->exp,$experience))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
                 <input type="text" placeholder="Other" style="width: 80%" 
          value="{{$candi->otherTech}}"    class="form-control" name="other_subTech" id="other_subTech" >
                     &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 80%">Admin </label> <br>

             <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='4'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]'
                value='".$copor->val."'";
              if(in_array($copor->val,$occupation))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]'
                value='".$copor->exp."'";
              if(in_array($copor->exp,$experience))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
              <br> <br/>
               <input type="text" placeholder="Other Other" style="width: 80%" 
          value="{{$candi->otherAdmin}}"  class="form-control" name="other_admin" id="other_admin" >
              &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 80%">Other </label> <br>

             <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='5'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]'
                value='".$copor->val."'";
              if(in_array($copor->val,$occupation))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]'
                value='".$copor->exp."'";
              if(in_array($copor->exp,$experience))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
              <br> <br/>
             <input type="text" placeholder="Other" style="width: 80%" 
          value="{{$candi->other}}"  class="form-control" name="other_subLab" id="other_subLab" >
           
             </div>
              
      </td> 

    </tr>
     <tr  >
         
             <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Skills</font></strong></td> 
      <td colspan="3">
    <table>
      
     @for ($i = 1; $i <= 10; $i++)
      <tr>
      {{$i}} @if( $i<10 )   &nbsp;   @endif
       <select    name={{$arrayGKindName[$i]}} onchange="getComboA(this  )" >
            <option value="" >--------</option>
                @foreach($gkinds as $gkind1)
                    <option value="{{$gkind1->gcode}}"  
                     @if( count($arrKindEdit)  > $i 
                     &&  $arrKindEdit[$i -1]-$gkind1->gcode >0  
                     && $arrKindEdit[$i -1] - $gkind1->gcode <1000 ) 
                      selected="selected" @endif   
                        >{{ $gkind1->name }}</option>
                @endforeach
            </select>
        <select    name={{$arrayKindName[$i]}} id="kind1ID" style="width: 30%" >
           <option value="" >--------</option>
           @foreach($kinds as $kinde)
          @if( count($arrKindEdit)  > $i 
          && $arrKindEdit[$i -1]-$kinde->gcode >0  
          && $arrKindEdit[$i -1] - $kinde->gcode <1000)
            <option value="{{$kinde->code}}"   @if(  $arrKindEdit[$i-1] ==$kinde->code ) selected="selected" @endif     >{{ $kinde->name }}</option>
          @endif  
         @endforeach  

            </select>
              &nbsp; {{$i+10}}  
       <select    name={{$arrayGKindName[$i+10]}} onchange="getComboA(this  )" >
            <option value="" >--------</option>
                @foreach($gkinds as $gkind1)
                    <option value="{{$gkind1->gcode}}"  
                        @if( count($arrKindEdit)  > $i +10
                     &&  $arrKindEdit[$i +9]-$gkind1->gcode >0  
                     && $arrKindEdit[$i +9] - $gkind1->gcode <1000 ) 
                      selected="selected" @endif    
                        >{{ $gkind1->name }}</option>
                @endforeach
            </select>
       <select    name={{$arrayKindName[$i+10]}} id="kind1ID" style="width: 30%" >
           <option value="" >--------</option>
           @foreach($kinds as $kinde)
          @if( count($arrKindEdit)  > $i+10 
          && $arrKindEdit[$i +9]-$kinde->gcode >0  
          && $arrKindEdit[$i +9] - $kinde->gcode <1000)
            <option value="{{$kinde->code}}"   @if(  $arrKindEdit[$i+9] ==$kinde->code ) selected="selected" @endif     >{{ $kinde->name }}</option>
          @endif  
         @endforeach  

            </select>
    </tr> 
    
    <br> 
    
     @endfor 
      </table>
</td> 
       </tr >
     <tr>
       
    
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Note</font></strong></td>   
           <td class="col-md-3" colspan="3" >
             <!--  <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="interview" value="{{ old('interview')}}" > -->
             <textarea  rows="4" name="plan" class="form-control" style="resize: none;"   >{{ $candi -> plan }}</textarea>
      </td> 
     
     
 </tr>
 <tr>
       
    
           <td    class="col-md-1"><strong><font color="556B2F">
        Evaluation/評価</font></strong></td>   
           
     
     
 </tr>
<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Evaluation Date </font></strong></td> 
            <td class="col-md-1"  >
      <input type="date" name="evalDate"  value="{{ $candi -> evalDate }}"  style="width: 60%" class="form-control" />
      </td> 
    </tr>
     
   <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Japanese</font></strong></td> 
            <td class="col-md-1"  >
                 <select name='jpeval'  style="width: 159px; height: 28px;">
                 <option value="" >--------</option>
                @foreach($jpeval as $type)
                 @if($type->type =='jpeval')
                    <option value="{{$type->id}}"   
                      @if($type->id ==$candi->jpeval) selected="selected" @endif 
                            >{{ $type->name }}</option>
                 @endif
                @endforeach
            </select>
        </td>
      </tr>
         <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       English</font></strong></td> 
            <td class="col-md-1"  >
                 <select name='engeval'  style="width: 159px; height: 28px;">
                 <option value="" >--------</option>
                @foreach($engeval as $type)
                 @if($type->type =='engeval')
                    <option value="{{$type->id}}"   
                      @if($type->id ==$candi->engeval) selected="selected" @endif 
                            >{{ $type->name }}</option>
                 @endif
                @endforeach
            </select>
        </td>
      </tr>
       <tr>
       
    
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Total Evaluation</font></strong></td>   
           <td class="col-md-3" colspan="3" >
             <!--  <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="interview" value="{{ old('interview')}}" > -->
             <textarea  rows="5" name="evaluation" class="form-control" style="resize: none;"   >{{ $candi -> evaluation }}</textarea>
      </td> 
     
     
 </tr>
              </table>
              

</div>
 </div> 
</div> 
        <div class="content">
  
      <input type="submit" class="btn btn-default" style=" background-color: #DCDCDC;" value="Update Candidate">
       
    <br>
    <br>
    <br>
    </div>
  </form>
  </div>
   <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
</div>
 <script type="text/javascript">
  
  document.getElementById("myFrame").onload = function() { hiddenFunction()};
  <!-- kind start  -->
    var urlkind = "{{ url('/showKind') }}";
 
 function getComboA(selectObject) {
   var x = event.target;
  var value = selectObject.value;  
  //alert( x.name);
   var gkindID = selectObject.value; 
 //  alert(x.name.substring(5));
        var token = $("input[name='_token']").val();
              
        $.ajax({
            url: urlkind,
            method: 'POST',
            data: {
                id: gkindID,
                _token: token
            },
            success: function(data) {
               let counter = 0;
               name1="select[name='kind";
               name1+=x.name.substring(5)+"']";
                 
                $(name1).html('');
                $.each(data, function(key, value){
                 
                     $(name1).append(
                        "<option value=" + value.code + ">" + value.name + "</option>"
                    );
                    counter++;
                });
                if (counter==0) {
                  $(name1).append(
                        "<option value=" + null + ">" +   "--------</option>"
                    );
                }
            }
        });
}
   
  <!-- kind end  -->
function hiddenFunction() {
   var wpVal=document.getElementById("workplace_select").value;
     
 
    if (wpVal == 62 || wpVal== 65) { 
     
           
          } else 
        {      document.getElementById('wpID').style ='visibility:collapse';
          }
 }
  $("select[name='workPlace']").change(function(){
          var workplace_id = $(this).val();
         
          if (workplace_id == 62 || workplace_id== 65) { 
           document.getElementById('wpID').style ='visibility:visible';
          } else 
        {   
         document.getElementById('wpID').style ='visibility:collapse';
          }
});
   function showOcc() {

      var x = document.getElementById("divOcc");   
  if (x.style.display === "none") {
      
    x.style.display = "block";
      document.getElementById('buttonOcc').innerText = 'HideDesire - Experience';
  } else {   
    x.style.display = "none";
      document.getElementById('buttonOcc').innerText = 'Show Occupation';
  }
   }
 
   $( function() {
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1960:1999',
        dateFormat : 'yy-mm-dd',
        defaultDate: '01-01-1985'
    });
  } );
   
var japanesearr =[];
$('#japaneselst option').each(function(){
   if($.inArray(this.value, japanesearr) >-1){
      $(this).remove()
   }else{
      japanesearr.push(this.value);
   }
});
var englisharr =[];
$('#englishlst option').each(function(){
   if($.inArray(this.value, englisharr) >-1){
      $(this).remove()
   }else{
      englisharr.push(this.value);
   }
});
   function validate(evt) 
     {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
    var regex = /[0-9]|\+/;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  } }
 function validateToeic(evt) 
     {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
   var regex = /[0-9]|\-/;
  var regexJ = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();

  }
if(regexJ.test(key)) {
     theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
}
else {
    console.log("No Japanese characters");
}

   }
     localStorage.setItem('result', "10000");
</script>
@endsection