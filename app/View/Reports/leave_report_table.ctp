<!DOCTYPE html>
<html>

<head>
    <?php echo $this->element("menu"); ?>
	<meta charset='UTF-8'>
	
	<title>Leave Report</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--	<link rel="stylesheet" href="css/style.css">-->
        <?php
            echo $this->Html->css(array('tableStyle.css', 'jquery-ui.css', 'lms.css'));
            echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms'));
        ?>

</head>

<body>
    <div id="login">
        <div id="semilog">
            <?php 
                echo '&nbsp;'.$loguser.' '.'&nbsp;';
            ?>
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
        
	<div id="page-wrap">
        <p>
            E - Entitled </br>
            C - Consumed </br>
            R - Remaining
        </p>
	<h2 style="text-align: center">Leave Summary</h2>
        
	<table>
		<thead>
		<tr>
			<th style="text-align: center">Year</th>
			<th style="text-align: center"><?php echo date("Y") ?></th>
			<th></th>
			<th></th>
			<th></th>
			<th> </th>
			<th></th>
			<th></th>
			<th colspan="3"></th>
            <th></th>
            <th></th>
            <th colspan="4">
            </th>
		</tr>
		</thead>
		<tbody>
		<tr>
                    <td rowspan="2">
			Employee ID			
                    </td>
                    <td rowspan="2">
                        Employee Name
                    </td>
                    <td colspan="3" style="text-align: center">
                        Casual
                    </td>
                    <td colspan="3" style="text-align: center">
                        Annual
                    </td>
                    <td colspan="3" style="text-align: center">
                        Sick
                    </td>
                    <td style="text-align: center">
                        No Pay
                    </td>
                    <td style="text-align: center">
                        WFH
                    </td>
                    <td colspan="3" style="text-align: center">
                        Liue
                    </td>                    
		</tr>
		<tr>
                    <td id="color1" style="text-align: center;">
                        E
                    </td>
                    <td id="color2" style="text-align: center;">
                        C
                    </td>
                    <td id="color3" style="text-align: center">
                        R
                    </td>
                    <td id="color1" style="text-align: center">
                        E
                    </td>
                    <td id="color2" style="text-align: center">
                        C
                    </td>
                    <td id="color3" style="text-align: center">
                        R
                    </td>
                     <td id="color1" style="text-align: center">
                         E
                    </td>
                    <td id="color2" style="text-align: center">
                        C
                    </td>
                    <td id="color3" style="text-align: center">
                        R
                    </td>
                    <td style="text-align: center">
                        C
                    </td>
                    <td style="text-align: center">
                        C
                    </td>
                     <td id="color1" style="text-align: center">
                         E
                    </td>
                    <td id="color2" style="text-align: center">
                        C
                    </td>
                    <td id="color3" style="text-align: center">
                        R
                    </td>
				</tr>
                <?php 
                foreach ($allEmployeeLeavesInfo as $value) : //starting for each loop
                	if (isset($value['Eid'])) :
                ?>
                <tr>
                    <td style="text-align: center">
                        <?php echo $value['Eid']; ?>
                    </td>
                    <td>
                        <?php echo $value['EmployeeName']; ?>
                    </td>
                    <td id="color1"style="text-align: center;">
                        <?php  echo $value['casual']['entitled']; ?>
                    </td>
                    <td id="color2"style="text-align: center; ">
                        <?php 
                          echo $value['casual']['consumed'];  
                        ?>
                    </td>
                    <td id="color3"style="text-align: center;">
                        <?php 
                          echo $value['casual']['remaining'];  
                        ?>
                    </td>
                    <td id="color1"style="text-align: center;">
                        <?php 
                          echo $value['annual']['entitled'];  
                        ?>
                    </td>
                    <td id="color2" style="text-align: center;" > 
                        <?php 
                          echo $value['annual']['consumed'];  
                        ?>
                    </td>
                    <td id="color3" style="text-align: center"> 
                        <?php 
                          echo $value['annual']['remaining'];  
                        ?>
                    </td>
                    <td id="color1"style="text-align: center">
                        <?php 
                          echo $value['sick']['entitled'];  
                        ?>
                    </td>
                    <td id="color2"style="text-align: center">
                        <?php 
                          echo $value['sick']['consumed'];  
                        ?>
                    </td>
                    <td id="color3" style="text-align: center">
                        <?php 
                          echo $value['sick']['remaining'];  
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['nopay']['consumed'];  
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['wofk_from_home'];  
                        ?>
                    </td>
                    <td id="color1"style="text-align: center; width: 65px;">
                        <div style="width: 30px; display: inline-block; height: 20px;">
                        	<input type="text" value="<?=$value['live']['entitled']; ?>" disabled="disabled" style="margin-left: 0px; height: 16px; width: 22px; font-size: 10pt; border-radius: 0px;" 
                        			id="liue-input_<?= $value['Eid']; ?>" data-empid="<?= $value['Eid']; ?>" data-liue-leaves="<?= $value['live']['entitled']; ?>">
                        </div>
                        <div style="" class=leave-summary-liue-leave>
                        	<a href="#" onclick="return false;" class="report-lieu-edit" data-empid="<?= $value['Eid']; ?>" id="liue-edit_<?= $value['Eid']; ?>">
                        		<span class="ui-icon ui-icon-pencil"></span>
                        	</a>
                        </div>
                        <div class="hidden">
                        	<a href="#" onclick="return false;" data-empid="<?= $value['Eid']; ?>" id="liue-save_<?= $value['Eid']; ?>" class="report-lieu-save" >
                        		<span class="ui-icon ui-icon-check"></span>
                        	</a>
                        	<a href="#" onclick="return false;" data-empid="<?= $value['Eid']; ?>" id="liue-cancel_<?= $value['Eid']; ?>" class="liue-leaves" >
                        		<span class="ui-icon ui-icon-close"></span>
                        	</a>
                        	</div>
                        </td>
                    <td id="color2"style="text-align: center">
                        <?= $value['live']['consumed']; ?>
                    </td>
                    <td id="color3" style="text-align: center">
                        <?= $value['live']['remaining']; ?>
                    </td>
				</tr>
                <?php
                	endif;
				endforeach; //closing foreach loop
                ?>
                
		</tbody>
	</table>
        <div style="text-align: center">
            <font color ="#205081">
            <?php
                echo $this->Html->link('Download', array(
                    'controller' => 'Reports', 'action' => 'exportLeaveRecord','full_base' => true,
                    )
                );
            ?>
            </font>
        </div>
	</div>
    
</body>

</html>

<style>
    table {
        
/*        margin-left: -75px !important; */
        margin-left: 0px !important;
    }
    h2{
        color: black;
        font-family: "Times New Roman", Times, serif;
    }
    #color1{
        background: #eee;
    }
    #color2{
        background: #FFE5B4;
    }
    #color3{
        background:  #FFDFDD;
    }
</style>