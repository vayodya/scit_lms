<form action="/files/add" enctype="multipart/form-data" method="post">
    <?php echo $this->Form->input('File', array('type' => 'file')); ?>
    <?php echo $this->Form->submit('Upload'); ?>
</form>