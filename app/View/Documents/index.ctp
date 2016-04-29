<?php echo $this->Form->create('Document',array('action'=>'search'));?>
    <fieldset>
        <legend><?php __('Post Search');?></legend>
    <?php echo $this->Form->input('Search.EmpName');
        echo $this->Form->submit('Search');
    ?>
    </fieldset>
<?php echo $this->Form->end();?>