<!-- File: /app/View/Posts/edit.ctp -->
<h1>Edit Post
</h1><?php
  	echo $this->Form->create('Post', array('url' => array('action' => 'edit'), 'enctype' => 'multipart/form-data')); 
    echo $this->Form->input('title');
    echo $this->Form->input('body', array('rows' => '3'));
    for($i=1; $i<4; $i++)
    {
    	?>
             
             	<div>
            <?php
            
            $ProductImage = $this->Form->value('image'.$i);
          
            	if(empty($ProductImage) || $ProductImage==NULL)
            	{
            		$ProductImage = "/img/uploads/posts/no-icon.jpg";
            	}
            	else
            	{
            		$ProductImage = "/img/uploads/posts/".$ProductImage;
            	}
            
            
               
               echo $this->Form->input('image'.$i,array('type'=>'file','label' => false,'div' => false));
               echo $this->Form->input('hiddenimage'.$i,array('type'=>'hidden','value'=>$this->Form->value('image'.$i)));

                         
           	echo $this->Html->image($ProductImage,array('align'=>'absbottom','style'=>'max-height:100px'));
			
             	?>             
                </div>
                
                
                <?php } 
    echo $this->Form->end('Save Post');
    
    ?>
 