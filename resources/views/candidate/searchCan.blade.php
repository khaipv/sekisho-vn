@extends('master')
@section('content')


<div style="margin-top: -4%; float: left;margin-left: 1%;" >
 <b style="font-size: 18px">   Candidate List  </b>
 <!-- approve.approveDetail  -->
</div>





<div   class="container">
 <form method="GET" action="{{ url('canSearch') }}">
   <div class="row">
     <div class="col-sm-12"  > 
       <div class="col-sm-1"  > </div> 
       <div class = "col-sm-10"  >
        <table class="table table-hover table-condensed table-striped table-bordered" id="selecter">
         <input name="_method" type="hidden" value="PATCH">

         <tr >
          <td  style="background: #7EC0EE"  ><strong><font color="556B2F">University</font></strong></td> 
          <td colspan="2" class="col-md-2" >
            <select class="form-control"   name="canUni" >
             <option value="" selected="selected" >--------</option>
             @foreach($university as $university)
             <option value="{{$university->id}}"  
               @if( $university->id == old('canUni') && !is_null(old('canUni')) ) 
               selected="selected" @endif     >{{ $university->eName }}</option>
               @endforeach
               
             </select>
           </td> 
         </tr>
         <tr >
          <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
          English level </font></strong></td> 
          <td colspan="1" class="col-md-2"  >
           <select name='canEng'  style="width: 150px; height: 28px;">
            <option value="" >--------</option>
            <?php 
            foreach($english_levels as $en){
             if($en->type =='eng'){
              echo "<option value='".$en->id."'";
              if( $en->id  == old('canEng') ) echo " selected='selected'  ";
              echo ">".$en->name."</option>";
              
            }
          }
          ?>
        </select>
        ~
        <select name='canEngTo'  style="width: 150px; height: 28px;">
          <option value="">--------</option>
          <?php 
          foreach($english_levels_To as $en){
           if($en->type =='eng'){
            echo "<option value='".$en->id."'";
            if( $en->id  == old('canEngTo') ) echo " selected='selected'  ";
            echo ">".$en->name."</option>";
            
          }
        }
        ?>
      </select>
    </td> 
    <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
    Korean level </font></strong></td> 
    <td colspan="1" class="col-md-2"  >
     <select name='korean'  style="width: 90px; height: 28px;">
      <option value="" >--------</option>
      <?php 
      foreach($korean as $en){
       if($en->type =='korean'){
        echo "<option value='".$en->id."'";
        if( $en->id  == old('korean') ) echo " selected='selected'  ";
        echo ">".$en->name."</option>";
        
      }
    }
    ?>
  </select>
  ~
  <select name='koreanTo'  style="width: 90px; height: 28px;">
    <option value="">--------</option>
    <?php 
    foreach($koreanTo as $en){
     if($en->type =='korean'){
      echo "<option value='".$en->id."'";
      if( $en->id  == old('koreanTo') ) echo " selected='selected'  ";
      echo ">".$en->name."</option>";
      
    }
  }
  ?>
</select>
</td> 
</tr>
<tr>
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Japanese level </font></strong></td> 
  <td colspan="1" class="col-md-2" >
    <select name='canJap'  style="height: 28px; width: 150px">
     <option value="" >--------</option>
     <?php 
     foreach($japanese_levels as $ja) {
       if($ja->type =='jp'){
        echo "<option value='".$ja->id."'";
        if( $ja->id  == old('canJap') ) { echo " selected='selected'  ";  } 
        echo ">".$ja->name."</option>";
      }
    }
    ?>
  </select>
  ~
  <select name='canJapTo'  style="height: 28px; width: 150px">
   <option value="" >--------</option>
   <?php 
   foreach($japanese_levels_To as $ja) {
     if($ja->type =='jp'){
      echo "<option value='".$ja->id."'";
      if( $ja->id  == old('canJapTo') ) { echo " selected='selected'  ";  } 
      echo ">".$ja->name."</option>";
    }
  }
  ?>
</select>
</td> 
<td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
Chinese level </font></strong></td> 
<td colspan="1" class="col-md-2"  >
 <select name='chinese'  style="width: 90px; height: 28px;">
  <option value="" >--------</option>
  <?php 
  foreach($chinese as $en){
   if($en->type =='chinese'){
    echo "<option value='".$en->id."'";
    if( $en->id  == old('chinese') ) echo " selected='selected'  ";
    echo ">".$en->name."</option>";
    
  }
}
?>
</select>
~
<select name='chineseTo'  style="width: 90px; height: 28px;">
  <option value="">--------</option>
  <?php 
  foreach($chineseTo as $en){
   if($en->type =='chinese'){
    echo "<option value='".$en->id."'";
    if( $en->id  == old('chineseTo') ) echo " selected='selected'  ";
    echo ">".$en->name."</option>";
    
  }
}
?>
</select>
</td> 
</tr>
<tr>
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Place of birth </font></strong></td> 
  <td  >
    <select class="form-control"   name="canBirth">
      <option value="">--------</option>
      <?php 
      foreach($birthPlace as $en){
        
        echo "<option value='".$en->Id."'";
        if( $en->Id  == old('canBirth') 
          && !is_null(old('canBirth'))
        ) echo " selected='selected'  ";
          echo ">".$en->Name."</option>";
        
        
      }
      ?>
      
    </select>
  </td> 
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Current Address </font></strong></td> 
  <td class="col-md-1" >
    <select class="form-control"   name="cancurrAdd">
      <option value="">--------</option>
      <?php 
      foreach($currAdd as $en){
        
        echo "<option value='".$en->Id."'";
        if( $en->Id  == old('cancurrAdd')   && !is_null(old('cancurrAdd')) ) echo " selected='selected'  ";
        echo ">".$en->Name."</option>";
        
        
      }
      ?>

    </select>
  </td> 
</tr>
<tr >
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Age</font></strong></td> 
 <td colspan="1" class="col-md-1" >
   <input type="text"   id="canJob" name="ageMin"
   style="width: 30%"   value="{{ old('ageMin') }}">
   ~
   <input type="text"   id="canJob" name="ageMax"
   style="width: 30%"   value="{{ old('ageMax') }}">
 </td> 
 <td  style="background: #7EC0EE"  ><strong><font color="556B2F">Sex</font></strong></td> 
 <td class="col-md-1"  >
   <select name='canSex'  style="width: 159px; height: 28px;">
    <option value="">--------</option>
    <?php 
    foreach($sex as $en){
     if($en->type =='sex'){
      echo "<option value='".$en->id."'";
      if( $en->id  == old('canSex') ) echo " selected='selected'  ";
      echo ">".$en->name."</option>";
      
    }
  }
  ?>
</select>
</td> 
</td> 
</tr>   
<tr >
 
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Graduate year </font></strong></td> 
  
  <td colspan="1" class="col-md-1" >
   <input type="number"   id="graduateMin" name="graduateMin"
   style="width: 30%"   value="{{ old('graduateMin') }}">
   ~
   <input type="number"   id="graduateMax" name="graduateMax"
   style="width: 30%"   value="{{ old('graduateMax') }}">
   
 </td>
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
 Marital Status</font></strong></td> 
 <td class="col-md-1"  >
   <select name='married'  style="width: 159px; height: 28px;">
    <option value="">--------</option>
    <option value="Married" <?php 
    if(   old('married') =="Married" ) echo " selected='selected'  ";
    ?> >Married</option>
    <option value="Single"  
    <?php 
    if(   old('married') =="Single" ) echo " selected='selected'  ";
    ?>
    >Single</option>
    
  </select>
</td> 
</tr>
<tr >
 
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Pre-Interview</font></strong></td> 
  
  <td colspan="1" nowrap="true"  >
   <input type="date" name="interviewDateFrom" value="{{ old('interviewDateFrom')}}"  
   min="1970-01-01"     /> ~
   <input type="date" name="interviewDateTo"  value="{{ old('interviewDateTo')}}"   min="1970-01-01"    /> 
 </td>
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
 Staff Pre-interview</font></strong></td> 
 <td class="col-md-1"  >
  <select class="form-control"   name="pic_s" id="pic_slst">
   <option value=''>---------</option>
   @foreach($users as $users)
   
   <option value="{{$users->id}}"  @if( old('pic_s')  ==$users->id ) selected="selected" @endif     >{{ $users->name }}</option>
   
   @endforeach
   
 </select>
</td> 
</tr>
<tr >
 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
 Foreigner</font></strong>
</td>  
<td>
  <input type="checkbox" name="foreigner" value="1"
  <?php  if (old('foreigner')=="1")  echo "checked";?>
  > 
  
</td>   
</tr>
<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Candidate's Name</font></strong></td> 
  <td colspan="1" class="col-md-1" >
   <input type="text"   id="companyFistsrc" name="canFirstsrc" placeholder="First Name" 
   value="{{ old('canFirstsrc') }}">
 </td> 
 <td colspan="1" class="col-md-1" >
  <input type="text"   id="companyMidlesrc" name="canMidlesrc"  placeholder="Midle Name" 
  value="{{ old('canMidlesrc') }}">
</td> 
<td colspan="1" class="col-md-1" >
  <input type="text"   id="companyLastsrc" name="canLastsrc" placeholder="Last Name" 
  value="{{ old('canLastsrc') }}">
</td> 
</tr>
<tr>
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">No, Email, Phone</font></strong></td> 
  <td colspan="1" class="col-md-1" >
   <input type="text"   id="canNoFrom" name="canNoFrom" 
   value="{{ old('canNoFrom') }}" style="width: 40%">
   ~
   <input type="text"   id="canNoTo" name="canNoTo"  
   value="{{ old('canNoTo') }}" style="width: 40%">

 </td> 
 <td colspan="1" class="col-md-1" >
  <input type="text"   id="canEmail" name="canEmail"  placeholder="Email" 
  value="{{ old('canEmail') }}">
</td> 
<td colspan="1" class="col-md-1" >
  <input type="text"   id="canPhone" name="canPhone" placeholder="Phone" 
  value="{{ old('canPhone') }}">
</td> 
</tr>
<tr >
  <td    class="col-md-1">
    <button type="button" id="buttonMajor" onclick="showMajor()">Show Major</button>
  </td> 
  <td class="col-md-1" colspan="3" >      
    <div id="divMajor">
      <?php 
      foreach($majors as $copor){
       if($copor->code =='1'){
         echo "<div class='col-sm-4'>
         
         <input class='major_selectClass' type='checkbox'  name='major_select[]' value='".$copor->val."'";
         if(in_array($copor->val, old('major_select', [])))  echo "checked";
         echo "> ".$copor->name."</div>";
       }
     }
     ?>
   </div>
 </td> 
</tr>
<tr >
  <td  class="col-md-1"><strong><font color="556B2F">
  Workplace desire </font></strong></td> 
  <td class="col-md-1"  >
   <select name='canWorkplace'  style="width: 159px; height: 28px;">
    <option value=''>---------</option>
    <?php 
    foreach($workplace as $en){
     if($en->type =='wplace'){
      echo "<option value='".$en->id."'";
      if( $en->id  == old('canWorkplace') ) echo " selected='selected'  ";
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
<div id="wplaceLst">
 <tr >
  <td    class="col-md-1">
    
  </td> 
  <td class="col-md-1" colspan="1" >      
    <div id="divWP">  
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
 </td> 

 <td class="col-md-1" colspan="1" >      
  <div id="divWP2">
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
</td>
<td class="col-md-1" colspan="1" >      
  <div id="divWP3">
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
</td>  
</tr>
<tr >
  <td    class="col-md-1">
    <button type="button" id="buttonOcc" onclick="showOcc()">Show Occupation</button>
  </td> 
  <td class="col-md-1" colspan="3" >      
   <label style="text-align: left;width: 100%">Left=Desire : Right=Experience </label>  <br>
   <div id="divOcc">
     <div class="form-class row" style="margin-top: 10px;margin-left: 10px">
      &nbsp;&nbsp;&nbsp;<label style="text-align: left;">IT-Software </label> <br>
      
      <div class="row">
        
        <?php 
        foreach($subitemsIT as $copor){
         if($copor->code =='1'){
           echo "<div class='col-sm-4' >
           <input type='checkbox' class='occClass' name='itemsIT_select[]' value='".$copor->val."'";
           if(in_array($copor->val, old('itemsIT_select', [])))  echo "checked";
           echo ">
           <input type='checkbox' class='occClass'  name='itemsIT_select[]' value='".$copor->exp."'";
           if(in_array($copor->exp, old('itemsIT_select', [])))  echo "checked";
           echo "> ".$copor->name."</div>      ";
         }
       }
       ?>
       
       
       <div class="row-col-sm-4" style="width: 260px;margin-left: 10px">
        <input type="text" placeholder="Other" 
        value="{{ old('other_subIT')}}"   class="form-control" name="other_subIT" id="other_subIT" >
      </div>
    </div>
  </div>

  <div class="form-class row" style="margin-top: 10px;margin-left: 10px">
    &nbsp;&nbsp;&nbsp;<label style="text-align: left;">Manufacturing </label> <br>
    
    <div class="row">
      <div class="checkbox-group required">
        <?php 
        foreach($subitemsTech as $copor){
         if($copor->code =='2'){
          echo "<div class='col-sm-4'>
          <input type='checkbox' class='occClass'  name='itemsTech_select[]' value='".$copor->val."'";
          if(in_array($copor->val, old('itemsTech_select', [])))  echo "checked";
          echo "> 
          <input type='checkbox' class='occClass'  name='itemsTech_select[]' value='".$copor->exp."'";
          if(in_array($copor->exp, old('itemsTech_select', [])))  echo "checked";
          echo "> ".$copor->name."</div>";
        }
      }
      ?>  
      
    </div>
    <div class="row-col-sm-4" style="width: 260px;margin-left: 10px">
     <input type="text" placeholder="Other" 
     value="{{ old('other_manu')}}"  class="form-control" name="other_manu" id="other_manu" >
   </div>
 </div>
</div>

<div class="form-class row" style="margin-top: 10px;margin-left: 10px">
  &nbsp;&nbsp;&nbsp;<label style="text-align: left;">Technology </label> <br>
  
  <div class="row">
    <div class="checkbox-group required">
      <?php 
      foreach($subitemsLab as $copor){
       if($copor->code =='3'){
        echo "<div class='col-sm-4'>
        <input type='checkbox' class='occClass'  name='subitemsLab_select[]' value='".$copor->val."'";
        if(in_array($copor->val, old('subitemsLab_select', [])))  echo "checked";
        echo ">
        <input type='checkbox' class='occClass'  name='subitemsLab_select[]' value='".$copor->exp."'";
        if(in_array($copor->exp, old('subitemsLab_select', [])))  echo "checked";
        echo "> ".$copor->name."</div>";
      }
    }
    ?>  
    
  </div>
  <div class="row-col-sm-4" style="width: 260px;margin-left: 10px">
   <input type="text" placeholder="Other" 
   value="{{ old('other_subTech')}}"  class="form-control" name="other_subTech" id="other_subTech" >
 </div>
</div>
</div>
<div class="form-class row" style="margin-top: 10px;margin-left: 10px">
  &nbsp;&nbsp;&nbsp;<label style="text-align: left;">Admin </label> <br>
  
  <div class="row">
    <div class="checkbox-group required">
      <?php 
      foreach($subitemsLab as $copor){
       if($copor->code =='4'){
        echo "<div class='col-sm-4'>
        <input type='checkbox' class='occClass'  name='subitemsLab_select[]' value='".$copor->val."'";
        if(in_array($copor->val, old('subitemsLab_select', [])))  echo "checked";
        echo ">
        <input type='checkbox' class='occClass'  name='subitemsLab_select[]' value='".$copor->exp."'";
        if(in_array($copor->exp, old('subitemsLab_select', [])))  echo "checked";
        echo "> ".$copor->name."</div>";
      }
    }
    ?>  
    
  </div>
  <div class="row-col-sm-4" style="width: 260px;margin-left: 10px">
   <input type="text" placeholder="Other" 
   value="{{ old('other_admin')}}"  class="form-control" name="other_admin" id="other_admin" >
 </div>
</div>
</div>

<div class="form-class row" style="margin-top: 10px;margin-left: 10px">
  &nbsp;&nbsp;&nbsp;<label style="text-align: left;">Other </label> <br>
  
  <div class="row">
    <div class="checkbox-group required">
      <?php 
      foreach($subitemsLab as $copor){
       if($copor->code =='5'){
        echo "<div class='col-sm-4'>
        <input type='checkbox' class='occClass'  name='subitemsLab_select[]' value='".$copor->val."'";
        if(in_array($copor->val, old('subitemsLab_select', [])))  echo "checked";
        echo ">
        <input type='checkbox' class='occClass'  name='subitemsLab_select[]' value='".$copor->exp."'";
        if(in_array($copor->exp, old('subitemsLab_select', [])))  echo "checked";
        echo "> ".$copor->name."</div>";
      }
    }
    ?>  
    
  </div>
  <div class="row-col-sm-4" style="width: 260px;margin-left: 10px">
   <input type="text" placeholder="Other" 
   value="{{ old('other_subLab')}}"  class="form-control" name="other_subLab" id="other_subLab" >
 </div>
</div>
</div>
</div>
</div>
</td> 
</tr>

<tr >
  <td    class="col-md-1">
    <button type="button" id="buttonOcc" onclick="showSkill()">Show Skill</button>
  </td> 
  <td class="col-md-1" colspan="3" >      
   
   <div id="divSkill">
    <?php 
    foreach($can_gkind as $gkind)
    {
     echo "<br>";
     echo  "<label style='text-align: left;width: 90%''>".$gkind->name." </label> <br>";
     
     foreach($can_kind as $kind){
       if($kind->gcode ==$gkind->gcode){
        echo "<div class='col-sm-4'>
        <input type='checkbox' class='skillClass'  name='skill_select[]' value='".$kind->code."'";
        if(in_array($kind->code, old('skill_select', [])))  echo "checked";
        echo ">".$kind->name."</div>";
      }
    }
    
  }
  ?>  
</div>


</tr>
<tr >
  <td   class="col-md-1"><strong><font color="556B2F">
  Evaluation/評価   </font></strong></td> 
</tr>
<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  Japanese</font></strong></td> 
  <td colspan="1" class="col-md-2"  >
   <select name='jpeval'  style="width: 150px; height: 28px;">
    <option value="" >--------</option>
    <?php 
    foreach($jpeval as $en){
     if($en->type =='jpeval'){
      echo "<option value='".$en->id."'";
      if( $en->id  == old('jpeval') ) echo " selected='selected'  ";
      echo ">".$en->name."</option>";
      
    }
  }
  ?>
</select>
~
<select name='jpevalTo'  style="width: 150px; height: 28px;">
  <option value="">--------</option>
  <?php 
  foreach($jpevalTo as $en){
   if($en->type =='jpeval'){
    echo "<option value='".$en->id."'";
    if( $en->id  == old('jpevalTo') ) echo " selected='selected'  ";
    echo ">".$en->name."</option>";
    
  }
}
?>
</select>
</td> 
</tr>
<tr >
  <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
  English </font></strong></td> 
  <td colspan="1" class="col-md-2"  >
   <select name='engeval'  style="width: 150px; height: 28px;">
    <option value="" >--------</option>
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
~
<select name='engevalTo'  style="width: 150px; height: 28px;">
  <option value="">--------</option>
  <?php 
  foreach($engevalTo as $en){
   if($en->type =='engeval'){
    echo "<option value='".$en->id."'";
    if( $en->id  == old('engevalTo') ) echo " selected='selected'  ";
    echo ">".$en->name."</option>";
    
  }
}
?>
</select>
</td> 
</tr>
</div>
</table>
</div>
</div>
</div>
<div class="row">
</div>


<div align="center">
  <button type="submit" name="searchBtn" value ="searchBtn">Search</button>    
  <input type="checkbox" name="contact" value="1"
  <?php  if (old('contact')=="1")  echo "checked";?>
  > 
  <label for="contact">Include Can't Contact</label>    
  <button type="submit"  name="csvBtn" value ="csvBtn" style="float: right;margin-right: 10%">Download</button>
</div> 
</form>   
</div>
<div class="content ">
 <div class="col-sm-8" >
  <div class="form-class row" style="margin-top:-1%">
    <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$candi->total()}} Candidates</label> 
  </div>


  <table class="table table-hover table-bordered" >
    <thead >
      <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('code', 'NO')
      </strong></td> 
      <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('lastName', 'Name')
      </strong></td> 
      <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('birth2', 'Birth Day' )
      </strong></td> 
      <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('sex', 'sex' )
      </strong></td> 
    </strong></td> 
    <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('email', 'Email' )
    </strong></td> 
  </strong></td> 
  <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('mobile', 'Mobile' )
  </strong></td> 
</strong></td> 

<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('universityName', 'University' )
</strong></td> 
</strong></td> 
<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('major', 'Major' )
</strong></td> 
</strong></td> 
<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('graduates', 'Graduated Year' )
</strong></td> 
</strong></td> 


</strong></td> 
<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('jLevel', 'Japanese level' )
</strong></td> 
<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Toeic', 'Toeic')
</strong></td> 
</strong></td> 
<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('eLevel', '  English level' )
</strong></td> 
<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('birthPlace', 'Place of birth' )
</strong></td> 
<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Current Address', 'Current Address' )
</strong></td> 



<td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('plan', 'Note' )
</strong></td> 
</thead>
<tbody>
  @foreach($candi as $value)
  <tr  <?php if($value->contact == 1): ?> style="background-color:#DCDCDC;text-align: right;" 
    <?php else : ?> style="text-align: right;" <?php endif; ?> >
    <td>{{ $value->code }}</td>      
    <td align="left" nowrap>
      @if (strlen ($value->firstName) >0 or strlen ($value->lastName) >0) 
      <a href="{{action('CandidateController@show', $value->id)}}" >{{$value->firstName}}&nbsp;{{$value->midleName}}&nbsp;{{$value-> lastName}}</a>
      @else
      <a href="{{action('CandidateController@show', $value->id)}}">{{$value->firstNameJ}}{{$value->midleNameJ}}{{$value-> lastNameJ}}</a>
      @endif
    </td>
    <td nowrap>{{ $value->birth2 }}</td>
    <td align="left" nowrap>{{ $value->sexName }}</td>
    <td  align="left" nowrap>{{ $value->email }}</td>
    <td align="right" nowrap>{{ $value->mobile }}</td>
    
    <td align="left" nowrap>{{ $value->uName }}</td>
    <td align="left" nowrap>{{ $value->majorName }}</td>
    <td align="right" nowrap>{{ $value->graduates }}</td>

    <td align="left" nowrap>{{ $value->jLevelName }}</td>
    <td align="left" nowrap>{{ $value->toeic }}</td>
    <td align="left" nowrap>{{ $value->eLevelName }}</td>
    
    <td align="left" nowrap>{{ $value->birth_Place }}</td>
    <td align="left" nowrap>{{ $value->currentAdd }}</td>
    <td align="left" nowrap>{{ $value->plan }}</td>  
    
  </tr>
  @endforeach
  <tr>
    <td colspan="10">
      <div class="pagination">
       {{ $candi->appends(Request::except('page'))->links() }}     
     </div>   
   </td>
 </tr>
</tbody>
</table>
</div>
<div class="col-sm-2"  > </div>
<iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
</div>
<style>
  table tr td{
   padding:2px !important;
   
 }
</style>
<script type="text/javascript">
    localStorage.setItem('result', "1000");
  document.getElementById("myFrame").onload = function() { hiddenFunction()};
  function hiddenFunction() {
   var divMajor = document.getElementById("divMajor");
   divMajor.style.display = "none";
   var divOcc = document.getElementById("divOcc");
   divOcc.style.display = "none";
   var divWP = document.getElementById("divWP");
   var divWP2 = document.getElementById("divWP2");
   var divWP3 = document.getElementById("divWP3");
   divWP.style.display = "none";
   divWP2.style.display = "none";
   divWP3.style.display = "none";
   var divSkill = document.getElementById("divSkill");
   divSkill.style.display = "none";
   

   // var major_select = document.getElementsByName('major_select');
   
   var checkedValue = null; 
   var inputElements = document.getElementsByClassName('major_selectClass');
   var workplace_selectClass = document.getElementsByClassName('workplace_selectClass');
   var occClass = document.getElementsByClassName('occClass');
   for(var i=0; inputElements[i]; ++i){
    if(inputElements[i].checked){
      divMajor.style.display = "block";
    }
  }
  for(var i=0; workplace_selectClass[i]; ++i){
    if(workplace_selectClass[i].checked){
      divWP.style.display = "block";
    }
  }
  for(var i=0; occClass[i]; ++i){
    if(occClass[i].checked){
      divOcc.style.display = "block";
    }
  }
  

}
$("select[name='canWorkplace']").change(function(){
  var workplace_id = $(this).val();
  
  if (workplace_id == 62 || workplace_id== 65) { 
   divWP.style.display = "block";
   divWP2.style.display = "block";
   divWP3.style.display = "block";
 } else 
 {   divWP.style.display = "none";
 divWP2.style.display = "none";
 divWP3.style.display = "none";
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
function showMajor() {
  var x = document.getElementById("divMajor");
  if (x.style.display === "none") {
    x.style.display = "block";
    document.getElementById('buttonMajor').innerText = 'Hide Major';
  } else {
    x.style.display = "none";
    document.getElementById('buttonMajor').innerText = 'Show Major';
  }
}
function showSkill() {
  var x = document.getElementById("divSkill");
  if (x.style.display === "none") {
    x.style.display = "block";
    document.getElementById('buttonSkill').innerText = 'Hide Skill';
  } else {
    x.style.display = "none";
    document.getElementById('buttonSkill').innerText = 'Show Skill';
  }
}
</script>
@endsection