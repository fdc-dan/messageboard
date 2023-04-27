<div class="row">
    <div class="col-12 p-3">
        <div class="d-flex justify-content-center">
            <div class="col-5">
                <?php echo $this->Session->flash(); ?>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Change Password</h3>

                        <?php echo $this->Form->create('User', array('controller'=>'users', 'action'=>'password')); ?>
                            <div class="form-group">
                                <?php echo $this->Form->input('old_password', array('class' => 'form-control', 'placeholder' => 'Enter old password', 'type' => 'password')); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('new_password', array('class' => 'form-control', 'placeholder' => 'Enter new password', 'type' => 'password')); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->button('Submit', array('class' => 'btn btn-secondary')); ?>
                            </div>
                        <?php echo $this->Form->end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>