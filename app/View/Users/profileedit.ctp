<?php echo $this->element("menu"); ?>
<script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
<div id="login">
    <div id="semilog">
    <?php 
    echo '&nbsp;'.$loguser.' '.'&nbsp;';?>
    </div>
    <?php //echo $this->Html->link('Sign Out ', array('controller' => 'Users','action' => 'logout'));
    $de_img = $this->Html->image('../images/Logout-Icon.jpg',array('alt' => 'Sign Out', 'title'=>'Sign Out'));
    echo $this->Html->link($de_img, array('controller' => 'Users','action' => 'logout'), array('escape' => false));
    $delete_img = $this->Html->image('../images/User_Edit.jpg',array('alt' => 'Delete', 'title'=>'Edit Profile'));
    echo $this->Html->link($delete_img, array('action' => 'edit','controller' => 'Users','action' => 'profileedit'), array('escape' => false)).'&nbsp;';
    $notify_img = $this->Html->image('../images/imagesnoti.png',array('alt' => 'Notify', 'title'=>'Notification Setting'));
    echo $this->Html->link($notify_img, array('action' => 'edit','controller' => 'Users','action' => 'email_notify'), array('escape' => false));
    ?>
</div>
</body>


    <?php
echo $this->Session->flash();
echo $this->Form->create('users',array( 'enctype' => 'multipart/form-data','onsubmit'=>'return validation()')); ?>

<div id = "latitle">
<center><p id="pl"><font color ="white"><?php echo __('Edit your profile'); ?></font></p></center></div>
 

<div id ="method1"><a id="noline" href="#" onclick='pwdCheck()' title='If and only if click this.'> Change Password</a></div>
	<div id ="cont1">    <div id='w_f_body'>    <?php 
echo $this->Form->input('Current Password',array('type' => 'password','id' => 'cpwd'/*,'name'=>'pwd_n'*/));?>
            


<?php echo $this->Form->input('New Password',array('type' => 'password','id' => 'pwd'/*,'name'=>'pwd_n'*/));
 
            
            

echo $this->Form->input('Re-Password',array('type' => 'password','id' => 'npwd'/*,'name'=>'npwd_n'*/));
?></div>
            <label id="err2"><font color ="red"></font></label>
            <div id ="close1"><a id ="noline" href="#" onclick='close1()' > Close</a></div>
</div>

<div id ="method2"><a id="noline" href="#" onclick='emailCheck()'> Change Email</a></div>
<div id ="cont2">       
<div id='w_f_body'>
 <?php

echo $this->Form->input('Email',array('type' =>'text','id' => 'em','title'=>'Please Enter valid Email address.'));?>
</div><label id="err3"><font color ="red"></font></label>
    <div id ="close2"><a id="noline" href="#" onclick='close2()' > Close</a></div>
</div>
<div id="pro_ali"><?php

echo $this->Form->input('pro_picture', array('type' => 'file','label' =>'Profile Picture'));?> </div><?php 
echo $this->Html->image($this->Html->url(array('controller'=>'users', 'action'=>'captcha'), true),array('id'=>'img-captcha','vspace'=>2));
//echo '<p><a href="#" id="a-reload">Can\'t read? Reload</a></p>'; ?>
<div id="lable_ec"> <?php echo '<p>Enter security code shown above</p>';?> </div> <?php
echo $this->Form->input('user.captcha',array('autocomplete'=>'off','name'=> 'cptr','label'=>false,'class'=>'','required'=>'required'));?>
<div id="u_e_button"><?php echo $this->Form->submit(__(' Submit ',true));?> </div><?php 
//echo $this->Form->button('Cancel', array('type'=>'reset'));
?>
<label id="errs"><font color ="red"></font></label>
<?php
echo $this->Form->end();
?>

<script>
var x=0;
var y =0;

$('#a-reload').click(function() {
	var $captcha = $("#img-captcha");
    $captcha.attr('src', $captcha.attr('src')+'?'+Math.random());
	return false;
});

function pwdCheck(){
//alert("xx");
	document.getElementById('method1').style.display = "none";
document.getElementById('cont1').style.display = "inline-table";
x=1;


}

function emailCheck(){
//alert("xx");
	document.getElementById('method2').style.display = "none";
document.getElementById('cont2').style.display = "inline-table";
y=1;

}
function pwdValid(){
    
    if((document.getElementById('cpwd').value =="" )){
        document.getElementById('err2').innerHTML = 'Current Password is Required';       
        return false;
    }
    
    var n = document.getElementById('pwd').value;
    if((document.getElementById('pwd').value =="" )){
        document.getElementById('err2').innerHTML = 'New Password can not be empty';
        return false;
    }   
        
    else if(n.length< 8){
         document.getElementById('err2').innerHTML ="password length must atleast 8 characters";
        return false;
    }   
     else if(document.getElementById('pwd').value !== document.getElementById('npwd').value )  { 
        document.getElementById('err2').innerHTML ="password confirmation is required";
        return false; 
    }else
        return true;

	

}

function emailValid(){
    if(document.getElementById('em').value == ""){

       document.getElementById('err3').innerHTML ="Email can not be empty";
      // alert("x1");
        return false;
    }else if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById('em').value)))
        {
            document.getElementById('err3').innerHTML ="Enter an valid email address";
            //alert("x2");
        return false;
        }
    else
        return true;

}

function isFill(){
    var pwd = document.getElementById('pwd').value;
    var em = document.getElementById('em').value;
    var pro_pic = document.getElementById('usersProPicture').value;
    
    if(pwd != "" | em != "" | pro_pic != ""){
        return true;
    }else
        document.getElementById('errs').innerHTML ="There are no changes to submit";
            //document.getElementById("btnPlaceOrder").disabled = true; 
        return false;
    
    
}

function validation(){
    
    document.getElementById('err2').innerHTML ="";
    document.getElementById('err3').innerHTML ="";
    document.getElementById('errs').innerHTML ="";
    
   
    if(x == 1 && y==1){
	if(emailValid() && pwdValid() && isFill())
		return true;
	else
		return false; 
	
    }else{
        if(x==1){
            if(pwdValid() && isFill())
                return true;
            else
                return false;

        }else if(y==1){
            if(emailValid() && isFill())
                return true;
            else
                return false;
        }else{
            if(isFill())
            return true;
        else
            return false;
        }
    }
}

function close1(){
document.getElementById('cont1').style.display = "none";
document.getElementById('method1').style.display = "block";
document.getElementById('pwd').value = '';
document.getElementById('npwd').value = '';

x = 0;

}

function close2(){
document.getElementById('cont2').style.display = "none";
document.getElementById('method2').style.display = "block";
document.getElementById('em').value = '';
y = 0;

}

</script>