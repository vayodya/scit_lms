<hi>WELCOME HOME PAGE</h1>
<p>
    <?php 
        echo $this->Html->link('Home',array('controller' => 'posts', 'action' => 'index'));
        echo $this->Html->link('Leave',array('controller' => 'posts', 'action' => 'add'));
			echo $this->Html->link('Apply Leave',array('controller' => 'posts', 'action' => 'add'));
			echo $this->Html->link('Leave Report',array('controller' => 'LeaveRecords', 'action' => 'leavereport'));
			//echo $this->Html->link('Working Calender',array('controller' => 'LeaveRecords', 'action' => 'wcalender'));
                        echo $this->Html->link( 'Working Calender', 'http://lms/full_calendar' );
        echo $this->Html->link('Working From Home',array('controller' => 'WorkFromHomes', 'action' => 'WorkFromHome'));
                        echo $this->Html->link('Report',array('controller' => 'WorkFromHomes', 'action' => 'wfhreport'));
        echo $this->Html->link('Admin',array('controller' => 'posts', 'action' => 'add'));
        echo $this->Html->link('Contact',array('controller' => 'Contacts', 'action' => 'contact'));
        echo $this->Html->link('Log Out',array('controller' => 'users', 'action' => 'logout'));
    ?>
</p>