@extends('master')
@section('content')
    <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px"> Create  Candiates     </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
   
  <form method="post" action="{{url('candidate')}}" autocomplete="off">
 {{csrf_field()}}

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
 <div class="col-sm-1"  > </div> 
 <div class = "col-sm-10"  >
  <table class="table table-hover table-bordered" >
<input type="hidden" name="_method" value="POST">
		<tr >
            <td  style="background: #7EC0EE"  width="16%"><strong><font color="556B2F">
			 Name </font><font style="color:red">*</font></strong></td> 
            <td class="col-md-3" colspan="3" >
			 <input style="width: 33%" id="FirstName" name="firstName" value="{{ old('firstName') }}" type="text" placeholder="First Name"  />
             <input style="width: 32%" placeholder="Midle Name" id="MiddleName" name="midleName" value="{{ old('midleName')}}" type="text"  />
             <input style="width: 32%" id="LastName" placeholder="Last Name" name="lastName" value="{{ old('lastName')}}" type="text"  />
			</td> 
				</tr>
				<tr >
			 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			  Name （JP/VN）</font></strong></td> 
             <td class="col-md-3" colspan="3" >
			            <input style="width: 33%" id="FirstName" name="firstNameJ" value="{{ old('firstNameJ')}}"  type="text"   />
                        <input style="width: 32%" id="FirstName" name="midleNameJ" value="{{ old('midleNameJ') }}" type="text"   />
                        <input style="width: 32%" id="LastName" name="lastNameJ" value="{{ old('lastNameJ')}}" type="text"   />
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Birthday</font></strong></td> 
        <td style="width: 28%">
       <input type="date" name="birthday"   min="1950-01-01"      />
         </td>
       <td  colspan="2" >
        <table class="table"  style="  margin: -7px">
          
           
        
       <td  style="background: #7EC0EE"  width="20%"  nowrap="true"><strong><font color="556B2F">
       Marital Status</font></strong></td> 
            <td     width="30%"  nowrap="true">
     
            <input type="radio" name="married"
              <?php if ( old('married')=="Single") echo "checked";?>
            value="Single">Single &nbsp; 
           
            <input type="radio" name="married"
            <?php if ( old('married')=="Married") echo "checked";?>
            value="Married">Married
           
          
      </td> 
      <td  style="background: #7EC0EE" nowrap="true" ><strong><font color="556B2F">
      Sex </font><font style="color:red">*</font></strong></td> 
           <td     nowrap="true">
          
            <input type="radio" name="sex"
            <?php if ( old('sex')=="31") echo "checked";?>
            value="31">Female &nbsp; 
          
            <input type="radio" name="sex"
            <?php if ( old('sex')=="32") echo "checked";?>
            value="32">Male
          
      </td> 

          

        </table>
     
      </td> 
           
      
			  
			
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Email </font></strong></td> 
            <td class="col-md-1" >
			 <input type="email" class="form-control form-control-lg" id="lgFormGroupInput"  name="email" value="{{ old('email')}}"  >

			</td> 
       <td  style="background: #7EC0EE;width: 6%" nowrap="true" ><strong><font color="556B2F">
     Email Status</font></strong></td> 
           <td     nowrap="true">
   <select name='contact'  style="width: 55%; height: 28px;">
             
            <?php 
              foreach($emailStatus as $en){
             if($en->type =='emailStatus'){
                echo "<option value='".$en->id."'";
                 if( $en->id  == old('contact') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select>
          
      </td> 
    </tr>
    <tr>
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Mobile </font></strong></td> 
            <td class="col-md-1"  >
			  <input type="mobile" class="form-control form-control-lg" id="lgFormGroupInput"  name="mobile" value="{{ old('mobile')}}"  >
			</td> 
			  <td  style="background: #7EC0EE" nowrap="true" ><strong><font color="556B2F">
     Mobile Status</font></strong></td> 
           <td     nowrap="true">
   <select name='mobileStatus'  style="width: 55%; height: 28px;">
       
            <?php 
              foreach($mobileStatus as $en){
             if($en->type =='mobileStatus'){
                echo "<option value='".$en->id."'";
                 if( $en->id  == old('mobileStatus') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select>
          
      </td> 
		</tr>
		
		
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      University </font><font style="color:red">*</font></strong></td>
      <td class="col-md-1" colspan="3" >
                <select class="form-control" required="true"  name="university" >
                    <option value="" >--------</option>
                    
                @foreach($university as $university)
                    <option value="{{$university->id}}"   @if( $university->id == old('university')
                    && !is_null(old('university')) ) 
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
                    <option value="{{$major->val}}"  @if( $major->val == old('majors') ) selected="selected" @endif     >{{ $major->name }}</option>
                @endforeach
            </select>
      </td> 
    </tr>
     <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Graduate year </font></strong></td> 
            <td class="col-md-1" >
       <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="graduate" value="{{ old('graduate')}}" oninput="this.value=this.value.replace(/[^0-9]/g,'');" >
      </td> 
           
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Foreigner </font></strong></td>   
      <td class="col-md-1" colspan="3" >
             
                <input type="checkbox" name="foreigner" value="1"
                <?php  if (old('foreigner')=="1")  echo "checked";?>
               > 
              
      </td> 
    </tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Current address </font></strong></td> 
            <td class="col-md-1"  >
			<select class="form-control"   name="address">
                 <option value="464" >--------</option>
                @foreach($province as $province)
                 @if($province->Id == 148 || $province->Id == 179)
                   <option value="464" >----------------</option>
                    <option value="{{$province->Id}}"  @if( old('address')  ==$province->Id
                       && !is_null(old('address'))
                     ) selected="selected" @endif     >{{ $province->Name }}</option>
                       @else 
                        <option value="{{$province->Id}}"  @if( old('address')  ==$province->Id
                       && !is_null(old('address'))
                     ) selected="selected" @endif     >{{ $province->Name }}</option>
                      @endif   
                @endforeach
               
            </select>
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Place of birth </font></strong></td> 
            <td class="col-md-1" >
			 			<select class="form-control"   name="province">
               <option value="464" >--------</option>
                @foreach($birthPlace as $province)
                 @if($province->Id == 148 || $province->Id == 179)
                 <option value="464" >----------------</option>
                    <option value="{{$province->Id}}"  @if( old('province')  ==$province->Id 
                     && !is_null(old('province'))  )
                       selected="selected" @endif     >{{ $province->Name }}</option>
                 @else     
                   <option value="{{$province->Id}}"  @if( old('province')  ==$province->Id 
                     && !is_null(old('province'))  )
                       selected="selected" @endif     >{{ $province->Name }}</option> 
                 @endif     
                @endforeach
                
            </select>
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			 English level </font></strong></td> 
            <td class="col-md-1"  >
			 <select name='english'  style="width: 55%; height: 28px;">
            <option value="" <?php if(empty($english)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($english_levels as $en){
             if($en->type =='eng'){
                echo "<option value='".$en->id."'";
                 if( $en->id  == old('english') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select> 
           
           <input style="width: 55%" id="toeic" placeholder="Toeic" name="toeic" value="{{ old('toeic')}}" onkeypress='validateToeic(event)'    />
			</td> 
			  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Japanese level </font></strong></td> 
            <td class="col-md-1" >
			 			  <select name='japanese'  style="height: 28px; width: 160px">
            <option value="1500" <?php if(empty($japanese)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($japanese_levels as $ja) {
                if($ja->type =='jp'){
                echo "<option value='".$ja->id."'";
                   if( $ja->id  == old('japanese') ) { echo " selected='selected'  ";  } 
                echo ">".$ja->name."</option>";
              }
            }
            ?>
          </select>
			</td> 
		</tr>
      <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Korean level </font></strong></td> 
            <td class="col-md-1"  >
       <select name='korean'  style="width: 55%; height: 28px;">
            <option value="" <?php if(empty($korean)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($korean as $korean){
             if($korean->type =='korean'){
                echo "<option value='".$korean->id."'";
                 if( $korean->id  == old('korean') ) echo " selected='selected'  ";
                echo ">".$korean->name."</option>";
                  
                  }
              }
            ?>
          </select> 
           
          
      </td> 
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Chinese level </font></strong></td> 
            <td class="col-md-1" >
              <select name='chinese'  style="height: 28px; width: 160px">
            <option value="" <?php if(empty($chinese)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($chinese as $chinese) {
                if($chinese->type =='chinese'){
                echo "<option value='".$chinese->id."'";
                   if( $chinese->id  == old('chinese') ) { echo " selected='selected'  ";  } 
                echo ">".$chinese->name."</option>";
              }
            }
            ?>
          </select>
      </td> 
    </tr>
     <tr >
                  <td style="background: #7EC0EE" class="col-md-1"><strong><font color="556B2F">
       Workplace desire </font></strong></td> 
            <td class="col-md-1"  >
       <select name='workplace'  style="width: 159px; height: 28px;">
          <option value=''>---------</option>
            <?php 
              foreach($workplace as $en){
             if($en->type =='wplace'){
                echo "<option value='".$en->id."'";
                 if( $en->id  == old('workplace') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
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
                
               echo "<div class='col-sm-8'>
               
               <input type='checkbox' name='workplace_select[]' value='".$copor->Id."'";
                if(in_array($copor->id, old('workplace_select', [])))  echo "checked";
                echo "> ".$copor->Name."</div>  ";
                
                }
              }
              ?>
    </div>
     <div class='col-sm-4'>
      <?php 
              foreach($workplaceLstT as $copor){
                if ($copor->CountryCode=="T" && !is_null($copor->Id) ) {
                
               echo " 
               
               <input type='checkbox' name='workplace_select[]' value='".$copor->Id."'";
                if(in_array($copor->id, old('workplace_select', [])))  echo "checked";
                echo "> ".$copor->Name." <br>  ";
                
                }
              }
              ?>
     </div>
      <div class='col-sm-4'>
       <?php 
              foreach($workplaceLstN as $copor){
                if ($copor->CountryCode=="N" && !is_null($copor->Id)) {
                
               echo " 
               
               <input type='checkbox' name='workplace_select[]' value='".$copor->Id."'";
                if(in_array($copor->id, old('workplace_select', [])))  echo "checked";
                echo "> ".$copor->Name." <br> ";
                
                }
              }
              ?>
     </div>
    </tr>
  </table>
</td> 
       <tr >
         <tr>
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Pre-Interview </font></strong></td>   
               <td class="col-md-1" >
               <input type="checkbox" name="mandan" value="1" 
                <?php  if (old('mandan')=="1")  echo "checked";?>
               > 
         <input type="date" name="mandanDate"  value="{{ old('mandanDate') }}" min="2016-01-01"/>
      </td> 
              <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
        Staff Pre-interview </font></strong></td>   
            <td class="col-md-1"  >

              <select class="form-control"   name="pic_s" id="pic_slst">
           <option value=''>---------</option>
             @foreach($users as $users)
        
                    <option value="{{$users->id}}"  @if( old('pic_s')  ==$users->id ) selected="selected" @endif     >{{ $users->name }}</option>
        
         @endforeach
           
    </select>
     
      </td> 
      
    </tr>
     <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Entering </font></strong></td>   
            <td class="col-md-1"  >
               <input type="checkbox" name="workcheck" value="1"
                <?php  if (old('workcheck')=="1")  echo "checked";?>
               > 
               <input type="date" name="workDate" value=  "{{ old('workDate') }}" min="2016-01-01"   />
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
                   if( $source->id  == old('source') ) { echo " selected='selected'  ";  } 
                echo ">".$source->name."</option>";
              }
            }
            ?>
      </td> 
      <td class="col-md-1" colspan="2" >
         <input  class="form-control form-control-lg" id="otherSource" name="sourceTxt" type="text"  value="{{ old('sourceTxt') }}"   />
      </td>
    </tr>
   

      
   
    <tr>
      
  
            <td style="background: #7EC0EE"   class="col-md-1">
               <strong><font color="556B2F">
       Occupation </font></strong>
    <button type="button" id="buttonOcc" onclick="showOcc()">Hide Occupation</button>
  </td> 
      <td class="col-md-1" colspan="3" >      
          <div id="divOcc">
            
               <label style="text-align: left;width: 100%">Left=Desire : Right=Experience </label> 
             &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">IT-Software </label> 
                        <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='1'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->val."'";
                if(in_array($copor->val, old('itemsIT_select', [])))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->exp."'";
                if(in_array($copor->exp, old('itemsIT_select', [])))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
              <input type="text" placeholder="Other" style="width: 80%" 
        value="{{ old('other_subIT')}}"   class="form-control" name="other_subIT" id="other_subIT" >
               &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">Manufacturing </label>  
                        <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='2'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->val."'";
                if(in_array($copor->val, old('itemsIT_select', [])))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->exp."'";
                if(in_array($copor->exp, old('itemsIT_select', [])))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
 <input type="text" placeholder="Other" style="width: 80%" 
        value="{{ old('other_man')}}"   class="form-control" name="other_man" id="other_man" >
                       &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">Technology </label> <br>
                        <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='3'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->val."'";
                if(in_array($copor->val, old('itemsIT_select', [])))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->exp."'";
                if(in_array($copor->exp, old('itemsIT_select', [])))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
                            <input type="text" placeholder="Other" style="width: 80%" 
          value="{{ old('other_subTech')}}"  class="form-control" name="other_subTech" id="other_subTech" >
                       &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">Admin </label> <br>
                        <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='4'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->val."'";
                if(in_array($copor->val, old('itemsIT_select', [])))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->exp."'";
                if(in_array($copor->exp, old('itemsIT_select', [])))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>
                            <input type="text" placeholder="Other"  style="width: 80%" 
          value="{{ old('other_admin')}}"  class="form-control" name="other_admin" id="other_admin" >
                       &nbsp;&nbsp;&nbsp;<label style="text-align: left;width: 100%">Other </label> <br>
                        <?php 
              foreach($subitemsIT as $copor){
                 if($copor->code =='5'){
               echo "<div class='col-sm-4'>
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->val."'";
                if(in_array($copor->val, old('itemsIT_select', [])))  echo "checked";
                echo "> 
               <input type='checkbox' name='itemsIT_select[]' value='".$copor->exp."'";
                if(in_array($copor->exp, old('itemsIT_select', [])))  echo "checked";
                echo "> ".$copor->name."</div>";
              }
              }
              ?>

         <input type="text" placeholder="Other"  style="width: 80%" 
          value="{{old('other_subLab')}}"  class="form-control" name="other_subLab" id="other_subLab" >
      </td> 
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
                    <option value="{{$gkind1->gcode}}"   @if( old($arrayGKindName[$i]) ==$gkind1->gcode ) selected="selected" @endif     >{{ $gkind1->name }}</option>
                @endforeach
            </select>
        <select    name={{$arrayKindName[$i]}} id="kind1ID" style="width: 30%" >
                <option value="" >--------</option>
            </select>
              &nbsp; {{$i+10}}  
       <select    name={{$arrayGKindName[$i+10]}} onchange="getComboA(this  )" >
            <option value="" >--------</option>
                @foreach($gkinds as $gkind1)
                    <option value="{{$gkind1->gcode}}"   @if( old($arrayGKindName[$i+10]) ==$gkind1->gcode ) selected="selected" @endif     >{{ $gkind1->name }}</option>
                @endforeach
            </select>
        <select    name={{$arrayKindName[$i+10]}} id="kind1ID" style="width: 30%" >
                <option value="" >--------</option>
            </select>
    </tr> 
    
    <br> 
    
     @endfor 
      </table>
</td> 
       </tr >
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Note</font></strong></td>   
           <td class="col-md-3" colspan="3" >
             <!--  <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="interview" value="{{ old('interview')}}" > -->
             <textarea  name="plan" rows="4" class="form-control" style="resize: none;"></textarea>
      </td> 
    </tr>
     <tr >
            <td    class="col-md-1"><strong><font color="556B2F">
       Evaluation/評価</font></strong></td>   
           <td class="col-md-3" colspan="3" >
           
      </td> 
    </tr>
    <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Evaluation Date
</font></strong></td>   
          
            <td class="col-md-3" colspan="3" >
       <input type="date" name="evalDate"   min="1970-01-01"      />
         </td>
    
    </tr>
      <tr>
        <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
      Japanese </font></strong></td> 
            <td class="col-md-1" colspan="3" >
              <select name='jpeval'  style="height: 28px; width: 20%;">
            <option value="1500" <?php if(empty($jpeval)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($jpeval as $ja) {
                if($ja->type =='jpeval'){
                echo "<option value='".$ja->id."'";
                   if( $ja->id  == old('jpeval') ) { echo " selected='selected'  ";  } 
                echo ">".$ja->name."</option>";
              }
            }
            ?>
          </select>
      </td> 
    </tr>
      <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       English </font></strong></td> 
            <td class="col-md-1" colspan="3" >
       <select name='engeval'  style="width: 20%; height: 28px;">
            <option value="" <?php if(empty($engeval)) echo 'selected' ?>>--------</option>
            <?php 
              foreach($engeval as $en){
             if($en->type =='engeval'){
                echo "<option value='".$en->id."'";
                 if( $en->id  == old('engeval') ) echo " selected='selected'  ";
                echo ">".$en->name."</option>";
                  
                  }
              }
            ?>
          </select> 
           
        
      </td> 
    </tr>
  <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
       Total Evaluation</font></strong></td>   
           <td class="col-md-3" colspan="3" >
             <!--  <input type="text" class="form-control form-control-lg" id="lgFormGroupInput"  name="interview" value="{{ old('interview')}}" > -->
             <textarea  name="evaluation" rows="5" class="form-control" style="resize: none;"></textarea>
      </td> 
    </tr>
     
    
          </table>
             
 </div>
</div>
</div> 
        <div class="content">
      <input type="submit" class="btn btn-default" style=" background-color: #DCDCDC;" value="Create New Candidate">
       
	  <br>
	  <br>
	  <br>
    </div>
   
  </form>
   <iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
</div>
<script type="text/javascript">
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
  document.getElementById("myFrame").onload = function() { hiddenFunction()};
function hiddenFunction() {
   var divMajor = document.getElementById("divMajor");
   divMajor.style.display = "none";
   var divOcc = document.getElementById("divOcc");
   divOcc.style.display = "none";
   var divwpID = document.getElementById("wpID");
   divwpID.style.display = "none";
   
   
 }
 
  $("select[name='workplace']").change(function(){
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
      document.getElementById('buttonOcc').innerText = 'Hide Occupation';
  } else {
    x.style.display = "none";
      document.getElementById('buttonOcc').innerText = 'Show Occupation';
  }
   }
 
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
                $.each(data, function(key, value){
                    $("select[name='district']").append(
                        "<option value=" + value.Id + ">" + value.Name + "</option>"
                    );
                });
            }
        });
    });
        $("select[name='district']").change(function(){
        var district_id = $(this).val();
        var token = $("input[name='_token']").val();

        $.ajax({
            url: urlWard,
            method: 'POST',
            data: {
                id: district_id,
                _token: token
            },
            success: function(data) {
               
                $("select[name='slbWard'").html('');
                $.each(data, function(key, value){
                        $("select[name='slbWard']").append(
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
            document.getElementById("districtID").style.display="none";
            document.getElementById("wardID").style.display="none";
          // 
          } else
          { document.getElementById("provinceID").style.display="block";
            document.getElementById("districtID").style.display="block";
            document.getElementById("wardID").style.display="block";
        }

        });
     $( function() {
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1960:1999',
        dateFormat : 'yy-mm-dd',
        defaultDate: '01-01-1985'
    });
  } );

     function validate(evt) 
     {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
   var regex = /[0-9]|\+/;
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