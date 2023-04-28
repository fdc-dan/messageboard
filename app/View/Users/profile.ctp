<div class="row">
    <h3>User Details</h3>
    <div class="col-12  p-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="dropdown float-right">
                            <a class="nav-link dropdown-toggle btn btn-secondary btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </a>
                            <div class="dropdown-menu p-2" aria-labelledby="dropdownMenuLink">
                                <?php echo $this->Html->link('Edit profile', '', array('class' => 'dropdown-item')); ?>
                                <?php echo $this->Html->link('Change Email', '', array('class' => 'dropdown-item', 'id' => 'changeEmailBtn')); ?>
                                <?php echo $this->Html->link('Change Password', '', array('class' => 'dropdown-item', 'id' => 'changePassBtn')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <?php echo $this->Html->image('placeholder-user.jpeg', array('alt' => AuthComponent::user('name'), 'class' => "img-fluid")); ?>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4><?php echo ucwords($user['User']['name']); ?></h4>
                                <p class="m-0">
                                    Gender: 
                                    <?php echo ucfirst($user['User']['gender']); ?>
                                </p>
                                <p class="m-0">
                                    Birthdate: 
                                    <?php 
                                        $birthdate = $user['User']['birthday'];
                                        echo $this->Time->format($birthdate, '%B %d, %Y');
                                    ?>
                                </p>
                                <p class="m-0">
                                    Joined: 
                                    <?php 
                                        $joined = $user['User']['created'];
                                        echo $this->Time->format($joined, '%B %d, %Y');
                                    ?>
                                </p>
                                <p class="m-0">
                                    Last Login: 
                                    <?php 
                                        $last_login_time = $user['User']['last_login_time'];
                                        echo $this->Time->format($last_login_time, '%B %d, %Y');
                                    ?>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p>
                                    Hubby:<br/>
                                    <?php echo $user['User']['hubby']; ?>
                                </p>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="changeEmailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create(array('id' => 'changeEmailForm')); ?>
                    <div class="form-group">
                        <?php echo $this->Form->input('email', array('class' => 'form-control', 'id' => 'email', 'placeholder' => 'Enter new email address', 'requried' => true)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->button('Save Changes', array('class' => 'btn btn-primary')); ?>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="changePassModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create(array('id' => 'changePassForm')); ?>
                    <div class="form-group">
                        <?php echo $this->Form->input('old_pass', array('class' => 'form-control', 'id' => 'old_pass', 'placeholder' => 'Enter old password', 'type' => 'password', 'required' => true)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('new_pass', array('class' => 'form-control', 'id' => 'new_pass', 'placeholder' => 'Enter new password', 'type' => 'password', 'required' => true)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->button('Save', array('class' => 'btn btn-primary')); ?>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        //AJAX on change email
        $('#changeEmailBtn').click(function(e) {
            e.preventDefault(0);
            $('#changeEmailModal').modal('show');
        });

        $('#changeEmailForm').on('submit', function(e) {
            e.preventDefault(0);

            var email = $('#email').val();

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'changeEmail')); ?>',
                type: 'POST',
                dataType: 'json',
                data: {email:email},
                success:function(response) {

                    switch(response.alert) {
                        case 'success':
                            alert(response.message);
                            window.location.href = '/profile';
                            break;

                        case 'error':
                            alert(response.message);
                            break;

                        case 'exist':
                            alert(response.message);
                            break;

                        default:
                            console.log(response);

                    }
                }, 
                error:function(error) {
                    console.log(error);
                }
            });
        
        });

        //AJAX on change password
        $('#changePassBtn').click(function(e) {
            e.preventDefault(0);

            $('#changePassModal').modal('show');
        });


        $('#changePassForm').on('submit', function(e) {
            e.preventDefault(0);

            var oldPass = $('#old_pass').val();
            var newPass = $('#new_pass').val();
            
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'changePassword')); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    oldPass:oldPass,
                    newPass:newPass
                }, 
                success:function(response) {

                    switch(response.alert) {
                        case 'success':
                            alert(response.message);
                            window.location.href = '/profile';
                            break;

                        case 'error':
                            alert(response.message);
                            break;

                        default:
                            console.log(response);

                    }
                }, 
                error:function(error) {
                    console.log(error);
                }
            });
        });

    });
</script>
