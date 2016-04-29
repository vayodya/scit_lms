<?php
 $line= $orders2[0]['Work_from_homes'];
 $H1=array('Employee ID','Employee Name','From Date','To Date','Work From Home Comment','Time','Status','Real Day(s)','Accept ID');
 //$this->Csv->addRow(array_keys($line));
 $this->Csv->addRow($H1);
 foreach ($orders2 as $order){
      $line = $order['Work_from_homes'];
      echo $this->Csv->addRow($line);
      //echo '</br>'; 
 } 
 $filename='WFHRecord';
 echo  $this->Csv->render($filename);
?>