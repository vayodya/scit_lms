<?php $line = $orders3[0]['User']; 
 //$this->Csv->addRow(array_keys($line));
$H1=array('Employee ID','Employee Name','Username','Email','User Role','Email Notification Status','Number of Sick leaves','Number of Annual leaves','Number of Casual leaves','Number of Lieu leaves');
 $this->Csv->addRow($H1);
 foreach ($orders3 as $order){
      $line = $order['User'];
      echo $this->Csv->addRow($line);
      //echo '</br>'; 
 } 
 $filename='UserList';
 echo  $this->Csv->render($filename);
?>