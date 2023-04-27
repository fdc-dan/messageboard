<div class="row">
    <h3>Update User Detail</h3>
    <div class="col-12  p-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <img src="" alt="">
                        <?php echo $this->Html->image('placeholder-user.jpeg', array('alt' => AuthComponent::user('name'), 'class' => "img-fluid")); ?>
                    </div>
                    <div class="col-lg-8">
                        <?php echo $this->Form->create(); ?>
                            <div class="form-group">
                                <?php echo $this->Form->input('name', array('class' => 'form-control')); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('birthdate', array('class' => 'form-control')); ?>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <?php 
                                    $options = array(
                                        'type' => 'radio',
                                        'legend'=> false,
                                        'label' => 'Gender',
                                        'class' => 'required',
                                        'default'=> 0,
                                        'before' => '<div class="d-inline p-1">',
                                        'separator' => '</div><div class="d-inline p-1">',
                                        'after' => '</div>',
                                        'options' => array('male' => 'Male', 'female' => 'Female'),
                                    );
                                    echo $this->Form->input('gender', $options);
                                ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->textarea('hubby', array('class' => 'form-control')); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->button('Update', array('class' => 'btn btn-primary', 'row' => 5)); ?>
                            </div>
                        <?php echo $this->Form->end(); ?>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>