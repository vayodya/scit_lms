<!DOCTYPE html>
<html>

<head>
    <?php echo $this->element("menu"); ?>
	<meta charset='UTF-8'>
	
	<title>Leave Report</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--	<link rel="stylesheet" href="css/style.css">-->
        <?php
            echo $this->Html->css(array('tableStyle.css'));
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

	<h2 style="text-align: center">Leave Report Table</h2>
  
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
			<th></th>
			<th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        
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
                        Casual Leave
                    </td>
                    <td colspan="3" style="text-align: center">
                        Annual Leave
                    </td>
                    <td colspan="3" style="text-align: center">
                        Sick Leave
                    </td>
                    <td style="text-align: center">
                        No Pay Leave
                    </td>
                    <td style="text-align: center">
                        Work From Home
                    </td>
		</tr>
		<tr>
                    <td>
                        Entitled
                    </td>
                    <td>
                        Consumed
                    </td>
                    <td>
                        Remaining
                    </td>
                    <td>
                        Entitled
                    </td>
                    <td>
                        Consumed
                    </td>
                    <td >
                        Remaining
                    </td>
                     <td>
                         Entitled
                    </td>
                    <td>
                        Consumed
                    </td>
                    <td>
                        Remaining
                    </td>
                    <td>
                        Consumed
                    </td>
                    <td>
                        Consumed
                    </td>
		</tr>
                <?php 
                        foreach ($allEmployeeLeavesInfo as $value) { //starting for each loop
                ?>
                <tr>
                    <td style="text-align: center">
                        <?php 
                          echo $value['Eid'];  
                        ?>
                    </td>
                    <td>
                        <?php
//                        foreach ($allUsersDetails as $value) {
                            echo $value['EmployeeName'];
//                        }
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['casual']['entitled'];  
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['casual']['consumed'];  
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['casual']['remaining'];  
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['annual']['entitled'];  
                        ?>
                    </td>
                     <td style="text-align: center">
                        <?php 
                          echo $value['annual']['consumed'];  
                        ?>
                    </td>
                    <td style="text-align: center"> 
                        <?php 
                          echo $value['annual']['remaining'];  
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['sick']['entitled'];  
                        ?>
                    </td>
                    <td style="text-align: center">
                        <?php 
                          echo $value['sick']['consumed'];  
                        ?>
                    </td>
                    <td style="text-align: center">
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
                    
		</tr>
                <?php
                    } //closing foreach loop
                ?>
                
		</tbody>
	</table>
	
	</div>
    <div>
        <?php
//            $line= $orders[0]['leave_record'];
//            $H1=array('Employee ID','Employee Name','From Date','To Date','Leave Type','Leave Comment','Leave Time','Status','Real Day(s)','Accept ID');
//            $this->Csv->addRow($H1);
//            foreach ($orders as $order){
//                 $line = $order['leave_record'];
//                 $this->Csv->addRow($line);
//            } 
//            $filename='myName';
//            echo  $this->Csv->render($filename);
        ?>
        
       
        
    </div>
		
</body>

</html>

<style>
    table {
        
        margin-left: -75px !important; 
    }
    h2{
        color: black;
        font-family: "Times New Roman", Times, serif;
    }
</style>