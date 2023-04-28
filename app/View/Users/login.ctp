

<div class="d-flex align-items-center justify-content-center vertical">
    <div class="col-5">
        <?php echo $this->Session->flash(); ?>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Login</h3>

                <?php echo $this->Form->create('User', array('controller'=>'users', 'action'=>'login')); ?>
                    <div class="form-group">
                        <?php echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => 'Enter email address', 'required' => true)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => 'Enter password', 'required' => true)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->button('Login', array('class' => 'btn btn-primary')); ?>
                    </div>
                <?php echo $this->Form->end(); ?>

                <center><small class="mt-3">Don't have account yet? <?php echo $this->Html->link('Click here to register', array('controller'=>'users', 'action'=>'create'))?> </small></center>
            </div>
        </div>
    </div>
</div>

