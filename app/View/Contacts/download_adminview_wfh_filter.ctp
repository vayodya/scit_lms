<?php
 $line= $orders7[0]['Work_from_homes'];
$H1=array('Employee ID','Employee Name','From Date','To Date','Note','Time','Status','Real Days','Accept ID'); 
//$this->Csv->addRow(array_keys($line));
$this->Csv->addRow($H1);
 foreach ($orders7 as $order){
      $line = $order['Work_from_homes'];
      echo $this->Csv->addRow($line);
      //echo '</br>'; 
 } 
 $filename='WfhRecord';
 echo  $this->Csv->render($filename);
?>