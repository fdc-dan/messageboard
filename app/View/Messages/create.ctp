
<h3>New Message</h3>

<div class="col-10 alert_wrap">
    <?php echo $this->Session->flash(); ?>
</div>
<div class="col-10 bg-white p-3">
    <?php echo $this->Form->create('Message', array('controller'=>'messages', 'action'=>'create')); ?>
        <div class="form-group">
            <?php 
                echo $this->Form->input('recepient', array(
                    'class' => 'form-control',
                    'required' => true
                ));
            ?>
        </div>
        <div class="form-group">
            <?php 
                echo $this->Form->textarea('message', array(
                    'class' => 'form-control',
                    'id' => 'message',
                    'rows' => 3,
                    'placeholder' => 'Write your message',
                    'required' => true
                )); 
            ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->button('Send', array('class' => 'btn btn-primary')); ?>
        </div>
    <?php echo $this->Form->end(); ?>
</div>