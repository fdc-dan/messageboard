<?php echo $this->Session->flash(); ?>

<div class="d-flex align-items-center justify-content-center vertical">
    <div class="card col-5">
        <div class="card-body">
            <h3 class="card-title mb-3">Register</h3>

            <?php echo $this->Form->create('User', array('controller'=>'users', 'action'=>'create')); ?>
                <div class="form-group">
                    <?php echo $this->Form->input('name', array('class' => 'form-control', 'placeholder' => 'Name')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => 'Email Address')); ?>
                </div>
				<div class="form-group">
                    <?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => 'Password')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('confirm_password', array('class' => 'form-control', 'placeholder' => 'Confirm Password', 'type' => 'password')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->button('Register', array('class' => 'btn btn-primary')); ?>
                </div>
            <?php echo $this->Form->end(); ?>

			<center><small class="mt-3">If you already have account <?php echo $this->Html->link('Click here to login', array('controller'=>'users', 'action'=>'login'))?></small></center>
        </div>
    </div>
</div>
