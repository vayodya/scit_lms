<?php echo $this->element("menu"); ?></body>
<center><h2>New Password</h2></center>
    <?php
/*
    echo $this->Form->create('users'); 
    echo $this->Form->input('Employee Name',array('type' => 'text','name'=> 'EmpName','required'=>'required','placeholder'=>'Employee Name','title'=>' Employee name is required.'));
    echo $this->Form->input('Password',array('type' => 'password','name'=> 'password','required'=>'required','title'=>'A password is required.'));
    echo $this->Form->input('confirmpassword',array('type' => 'password','name'=> 'confirmpassword','required'=>'required','title'=>'A confirm password is required.'));
    echo $this->Form->input('Email',array('type' => 'text','name'=> 'email','required'=>'required','placeholder'=>'Email','title'=>'An email is required.'));     
       echo $this->Captcha->input();
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo $this->Form->submit('Save Changes');
    echo $this->Form->button('Cancel', array('type'=>'reset'));
    echo $this->Form->end();
*/
    
echo $this->Session->flash();
echo $this->Form->create("users"); 
echo $this->Form->input('Username',array('type' => 'text','name'=> 'username','required'=>'required','placeholder'=>'Username','title'=>' Username is required.'));
echo $this->Form->input('Password',array('type' => 'password','name'=> 'password','required'=>'required','title'=>'A password is required.'));
echo $this->Form->input('confirmpassword',array('type' => 'password','name'=> 'confirmpassword','required'=>'required','title'=>'A confirm password is required.'));
echo $this->Form->input('Email',array('type' => 'text','name'=> 'email','required'=>'required','placeholder'=>'Email','title'=>'An email is required.'));
echo $this->Html->image($this->Html->url(array('controller'=>'users', 'action'=>'captcha'), true),array('id'=>'img-captcha','vspace'=>2));
//echo '<p><a href="#" id="a-reload">Can\'t read? Reload</a></p>';
echo '<p>Enter security code shown above:</p>';
echo $this->Form->input('user.captcha',array('autocomplete'=>'off','label'=>false,'class'=>'','required'=>'required'));
echo $this->Form->submit(__(' Submit ',true));
echo $this->Form->button('Cancel', array('type'=>'reset'));
echo $this->Form->end();
?>
<script>
$('#a-reload').click(function() {
	var $captcha = $("#img-captcha");
    $captcha.attr('src', $captcha.attr('src')+'?'+Math.random());
	return false;
});
</script>

