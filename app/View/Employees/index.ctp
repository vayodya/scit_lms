<h1>Blog posts</h1>
<?php echo $this->Html->link('logout', array('controller' => 'users','action' => 'logout')); ?>

 <div >
            	
                     <h2><font color ="blue" > Personal Details</font></h2></br></br>
                        <h3><font color ="red"><ol><?php foreach($detail as $a){
                        
                    echo 'ID :',$a['Employee']['ID'];
                    echo "</br>";
                    echo "</br>";
                    
                    echo 'Name :',$a['Employee']['user_name'];
                    echo "</br>";
                    echo "</br>";
                    
                  
                    
                    
                    
                    }
                    ?>  </font> </h3> 