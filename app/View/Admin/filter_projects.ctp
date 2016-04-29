<meta charset="utf-8" />
<?php
echo $this->Html->css(array('jquery-ui-1.10.3'));

echo $this->Html->script(array('jquery-ui-1.10.3'));
?>
<style>
    .toggler {
        margin-left: 10px;
        width: auto;
        height: auto;
    }
    #button {
        padding: .5em 3em;
        text-decoration: none;
        margin-top:25px;
        margin-left:36px;
        width: 200px;
    }
    #effect {
        position: fixed;
        width: 263px; 
        height: auto;
        padding: 0.4em;
        margin-left:33px;
        margin-top:75px;
        background-color: #BEBEBE;
    }
    #effect h3 {
        margin: 0;
        padding: 0.4em;
        text-align: center;
    }
</style>
<!--	<link rel="stylesheet" href="css/style.css">-->
        <?php
            echo $this->Html->css(array('lms.css'));
            echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms'));

        ?>
<?php echo $this->element("menu"); ?>
<div id="login">
    <div id="semilog">
        <?php echo '&nbsp;' . $loguser . ' ' . '&nbsp;'; ?>
    </div>
    <?php
    //echo $this->Html->link('Sign Out ', array('controller' => 'Users','action' => 'logout'));
    $de_img = $this->Html->image('../images/Logout-Icon.jpg', array('alt' => 'Sign Out', 'title' => 'Sign Out'));
    echo $this->Html->link($de_img, array('controller' => 'Users', 'action' => 'logout'), array('escape' => false));
    $delete_img = $this->Html->image('../images/User_Edit.jpg', array('alt' => 'Delete', 'title' => 'Edit Profile'));
    echo $this->Html->link($delete_img, array('action' => 'edit', 'controller' => 'Users', 'action' => 'profileedit'), array('escape' => false)) . '&nbsp;';
    $notify_img = $this->Html->image('../images/imagesnoti.png', array('alt' => 'Notify', 'title' => 'Notification Setting'));
    echo $this->Html->link($notify_img, array('action' => 'edit', 'controller' => 'Users', 'action' => 'email_notify'), array('escape' => false));
    ?>
</div>

<?php if (isset($_SESSION['rand'])) : ?>
<div class="alert alert-block" >
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php
        echo $_SESSION['rand'];
        unset($_SESSION['rand']);
    ?>
</div>
<?php endif; ?>

<?php if (isset($errorMessage)) : ?>
<div class="alert alert-block" >
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <font color="red">
        <?php echo $errorMessage; ?>
    </font>
</div>
<?php endif; ?>
    <font color  = "#205081" size="3"> <b><center>  Employee/Project Requests</center> </b> </font>

<center>
 <form  style="margin: auto;" id="UserViewProjectDetailsForm" method="GET" accept-charset="utf-8" >
 <div style="display:none;">
    <input type="hidden" name="_method" value="GET">
    </div>
    <div class="input text">
    <label for="searchId"></label>
    <input name="inputValue" id="searchId" placeholder="            Type employee name or project name        " class="searchClass" type="text">
    </div>
    <div class="submit"><input class="subButton" type="button" id="buttonId" value="Filter"></div>
    </form>
    </center>

<table>
    <tr>
        <th>Employee Name</th>
        <th>Project Name</th>
        <th>Action</th>
    </tr>
    <tr>
        <?php foreach($project_detail as $project):?>
        <td><?php echo $project['User']['EmpName']; ?></td>
        <td><?php echo $project['pro']['pro_name']; ?></td>
        <td class="viewProjecttd">
        <div class="viewProjectDive">
            <button class="viewButton" data-tdid="<?= $project['emp']['id']; ?>" >Delete</button>
        </div>
         </td>

    </tr>
<?php endforeach; ?>
</table>



