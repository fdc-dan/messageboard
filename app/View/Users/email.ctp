<div class="row">
    <div class="col-12 p-3">
        <div class="d-flex justify-content-center">
            <div class="col-5">
                <?php echo $this->Session->flash(); ?>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-3">Change Email</h3>

                        <?php echo $this->Form->create('User', array('controller' => 'user', 'action' => 'email')); ?>
                            <div class="form-group">
                                <?php echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => 'Enter new email address')); ?>
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

