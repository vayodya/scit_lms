<title><?php echo $title_for_layout; ?></title>
<script>
    
     $(document).ready(function(){
       
       $('#Img1').mouseover(function()
       {
          $(this).css("cursor","pointer");
          $(this).animate({width: "300px",height: "310px"}, 'slow');
           $("#details").hide();
           
       });
    
    $('#Img1').mouseout(function()
      {   
          $(this).animate({width: "120px",height: "130px"}, 'fast');
          $("#details").show();
       });
   });
  
</script>

<?php echo $this->element("menu"); ?>	

<div id="login">
    <div id="semilog">
        <font color="green"><?php echo '&nbsp;'.$loguser;?></font>
    </div>
    
    <?php 
    $de_img = $this->Html->image('../images/Logout-Icon.jpg',array('alt' => 'Sign Out', 'title'=>'Sign Out'));
    echo $this->Html->link($de_img, array('controller' => 'Users','action' => 'logout'), array('escape' => false/*, 'id' =>'logout'*/ ));
        
    $delete_img = $this->Html->image('../images/User_Edit.jpg',array('alt' => 'Delete', 'title'=>'Edit Profile'));
    echo $this->Html->link($delete_img, array('action' => 'edit','controller' => 'Users','action' => 'profileedit'), array('escape' => false/*, 'id'=>'pedit'*/)).'&nbsp;';
        
    $notify_img = $this->Html->image('../images/imagesnoti.png',array('alt' => 'Notify', 'title'=>'Notification Setting'));
    echo $this->Html->link($notify_img, array('action' => 'edit','controller' => 'Users','action' => 'email_notify'), array('escape' => false/*, 'id'=>'noti'*/));
    ?>

    <?php if( $Role == 'CEO') { ?>
        <?php
        $newnotff=$this->Html->image('../images/rsz_newnotiff.png',array('alt' => 'Notify', 'title'=>'New Request'));
        echo $this->Html->link($newnotff, array('action' => 'edit','controller' => 'Admins','action' => 'leave_request'), array('escape' => false, 'style'=>'margin-right: 20px;'));
        ?>
        <div class="notiff">
           <font color="red" style="float: right; text-align: left; width: 45px;"><b> <?php echo $notff;?> </b></font>
        </div>
    <?php }elseif($Role != 'admin' || $Role == 'normal'){ ?>
        <?php 
        $newnotff=$this->Html->image('../images/rsz_newnotiff.png',array('alt' => 'Notify', 'title'=>'New Request'));
        echo $this->Html->link($newnotff, array('action' => 'edit','controller' => 'Admins','action' => 'leave_request'), array('escape' => false, 'style'=>'margin-right: 20px;'));
        ?>
        <div class="notiff">
           <font color="red" style="float: right; text-align: left; width: 45px;"><b> <?php echo $notff2;?> </b></font>
        </div>
    <?php } ?>
</div>
<div id="fake_bod"> <br/>
    <font color = "#FFFFFF" > 
        <center>Personal Details</center>
    </font><br/><br/>  
</div>
    
<div id="fake_body">
    <?php foreach($detail as $a):?>
        <center>  
            <?php        
            echo "</br>";  
            echo $this->Html->image('../profile_pictures/' . $a['User']['pro_picture'],array('id'=>'Img11', 'style' => 'max-width: 246px; max-height: 130px;'));
            echo "</br>";
            echo "</br>";
            ?>
        </center>
        <table id = "details" style="width:298px">
            <tr id ="t">
                <td id ="trs">Employee ID</td>
                <td id="trs">:</td>
                <td id="trs"><?php echo $a['User']['EmpId'];?></td>
            </tr>
            <tr>           
                <td id="trs" width:"100px">Employee Name</td>
                <td id="trs">:</td>
                <td id="trs"> <?php echo $a['User']['EmpName'];?> </td>
            </tr>
            <tr>           
                <td id="trs">Joined Date</td>
                <td id="trs">:</td>
                <td id="trs"> <?php echo $a['User']['join_date'];?> </td>
            </tr>
            <tr>           
                <td id="trs">Designation</td>
                <td id="trs">:</td>
                <td id="trs">
                    <?php 
                    switch ($a['User']['role']) :
                        case 'CEO':
                            echo "CEO";
                            break;
                        case 'admin':
                            echo "Admin";
                            break;
                        case 'pm':
                            echo "Manager";
                            break;
                        case 'tl':
                            echo "tl";
                            break;
                        default:
                            break;
                    endswitch;
                    ?>
                </td>
            </tr>
            <tr>          
                <td id="trs"> 
                    <font size = '-2'>Email Address</font>
                </td>
                <td id ="trs">:</td>
                <td id="trs"> 
                    <font size = '-2'><?php echo $a['User']['email'];?> </font>
                </td>
            </tr>
        </table>
    <?php endforeach; ?>
</div>
                            
   <div id="fake_body3"> </br><font color = "#FFFFFF" > <center>
                
                     Your Projects</center></font></h2></br></br>  </div>
    
    <?php if($pro_cunt>4){?>
        <div id ="fake_body4">
    <?php }else{ ?>
        <div id ="fake_body4_4">
    <?php } ?>
  
                  
             
           <center>             <?php   if($marks == true && count($information)>0){
                                            for($i = 0; $i<count($information); $i++){$for_val=$information[$i]['pro_description'];
                    
                                                     echo "</br>";        
                    
                    
                                                     echo "</br>"; ?>
                    
                 <div id ="project_list">
                    
                                                    <?php echo $this->Html->image('../images/icon_portfolio_hover.png');?>
                 </div>
                     <div id ="project_list_name" ><?php /*<div id="abc" onmouseover="onMouse(5)" onmouseout="outMouse(5)">*/ ?>
                                                    <?php echo ' '.$information[$i]['pro_name'];?>                                                    
                                                    <?php $simple5=$information[$i]['pro_description']; ?>
                                                    <?php /*<font color="green"><p id="demo5"></p></font></div>*/ ?>
                 </div>
                                                    <?php echo "</br>";
                                                     echo "</br>";
                                                     
                                                      
                                            }  
                                             
                                           
           
                                        } else{ 
                                            echo "</br>";
                                            echo "</br>";
                                            echo "</br>";
                                            echo 'Currently you have No projects';
                                            }
                    ?></center>   </div>
<div id="fake_body2"> </br><font color = "#FFFFFF" > <center>
                     Leave Details</center></font></h2></br></br>  </div>
    

    
   
    <div id="fake_body1">
        <?php echo '</br>'.'</br>'; ?>
        <center> <b><?php  echo '      Remaining leaves ' ?></b></center> 
        <?php echo '</br>'; ?>
        <?php /*
   <div id="re_leave11">
        <?php   echo $this->Html->image('../images/icon_anu.png')?>
   </div>
   <div id="re_leave1" onmouseover="onMouse(1)" onmouseout="outMouse(1)">
               <?php   echo '  Sick leaves '.($no_sick_lv-$a1-$aa1)?> <font color="green"> <?php echo '    ('.$no_sick_lv.')' ?> </font><p id="demo1"></p> 
   </div>**/
    ?>   
   <?php echo '</br>'.'</br>'; ?>
   <div id="re_leave22">
     <?php  echo $this->Html->image('../images/icon_anu.png')?>
   </div>
   <div id="re_leave2" onmouseover="onMouse(2)" onmouseout="outMouse(2)">
        <?php   echo '  Casual leaves '.($no_cas_lv-$a3-$aa3)?> <font color="green"> <?php /*echo '  ('.$no_cas_lv.')' */?><p id="demo2"></p></font>
   <?php $simple2=$no_cas_lv; ?>
    </div>
     
   <?php echo '</br>'.'</br>'; ?>
   <div id="re_leave33">    
        <?php  echo $this->Html->image('../images/icon_anu.png')?>
   </div>
   <div id="re_leave3" onmouseover="onMouse(3)" onmouseout="outMouse(3)">
         <?php   echo'  Annual leaves '.($no_ann_lv-$a2-$aa2)?> <font color="green"> <?php /*echo '  ('.$no_ann_lv.')' */?><p id="demo3"></p></font>
<?php $simple3=$no_ann_lv; ?>
    </div>
   
  
  <?php echo '</br>'.'</br>'; ?>
   <div id="re_leave33">    
        <?php  echo $this->Html->image('../images/icon_anu.png')?>
   </div>
   <div id="re_leave3" onmouseover="onMouse(4)" onmouseout="outMouse(4)">
         <?php if($no_liv_lv > 0){  echo'  Lieu leaves '.($no_liv_lv-$a5-$aa5);$simple4="You have lieu leave(s) since work on holiday(s)  ";}else{echo'  Lieu leaves 0';$simple4="Currently not define.";}?><font color="green"> <p id="demo4"></p></font>
   
    </div>
<?php echo '</br>'.'</br>'; ?>
   </div>
<div id="free_sp"></div>
  
                   
    
    
    
 </body>
 <script>
function onMouse(p){

    var jArray='<?php echo $for_val; ?>'; 
    var simple5 = '<?php echo $simple5; ?>';
    var simple4 = '<?php echo $simple4; ?>';
    var simple3 = '<?php echo $simple3; ?>';
    var simple2 = '<?php echo $simple2; ?>';
    coo5=simple5;
    coo4=simple4;
    coo3="Initialy you have "+simple3+" annual leaves.";
    coo2="Initialy you have "+simple2+" casual leaves.";

    if(p==1)
    document.getElementById("demo1").innerHTML=coo
    if(p==2)
    document.getElementById("demo2").innerHTML=coo2
    if(p==3)
    document.getElementById("demo3").innerHTML=coo3
    if(p==4)
    document.getElementById("demo4").innerHTML=coo4
    
    if(p==5){
        for(var i=0;i<6;i++){
        //alert(jArray[i]['pro_description']);
    }
        document.getElementById("demo5").innerHTML=coo5
    }
}

function outMouse(p){
    if(p==1)
    document.getElementById("demo1").innerHTML=""
    if(p==2)
    document.getElementById("demo2").innerHTML=""
    if(p==3)
    document.getElementById("demo3").innerHTML=""
    if(p==4)
    document.getElementById("demo4").innerHTML=""

    if(p==5)
    document.getElementById("demo5").innerHTML=""
}


</script>
 
 </html>