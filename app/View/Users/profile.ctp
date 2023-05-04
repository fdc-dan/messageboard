<div class="d-flex justify-content-center">
    <div class="alert_wrap">
        <?php echo $this->Session->flash(); ?>
    </div>
</div>

<nav class='mt-3' aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">User Profile</li>
  </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if(AuthComponent::user('id')  == $user['User']['id']) : ?>
                        <div class="col-12">
                            <div class="dropdown float-right">
                                <a class="nav-link dropdown-toggle btn" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <div class="dropdown-menu p-2" aria-labelledby="dropdownMenuLink">
                                    <?php echo $this->Html->link('Edit profile', array('controller' => 'users', 'action' => 'edit'), array('class' => 'dropdown-item')); ?>
                                    <?php echo $this->Html->link('Change Email', '', array('class' => 'dropdown-item', 'id' => 'changeEmailBtn')); ?>
                                    <?php echo $this->Html->link('Change Password', '', array('class' => 'dropdown-item', 'id' => 'changePassBtn')); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-3">
                        <?php 
                            
                            $profile_url = '';
                            $profile_data = $user['User']['photo'];

                            if(empty($profile_data )) $profile_url = 'users/placeholder.jpeg';
                            else $profile_url = 'users/'.$profile_data;

                            echo $this->Html->image($profile_url, array(
                                'class' => "img-fluid mb-2",
                                'id' => 'fileUploadImage',
                                'alt' => AuthComponent::user('name')
                            ));
                        ?>
                    </div>
                    <div class="col-lg-9">
                                <h4>
                                    <?php 
;
                                        $getDateToday = date_create('today');
                                        $getBirthdate = date_create($user['User']['birthday']);
                                        $getAge = date_diff($getBirthdate, $getDateToday)->y;

                                        $getUserName = ucwords($user['User']['name']);

                                        if($getAge > 0) echo $getUserName.', '.$getAge; 
                                        else echo $getUserName;
                                    ?>
                                </h4>
                                <p class="m-0">
                                    <strong>Gender:</strong>
                                    <?php echo ucfirst($user['User']['gender']); ?>
                                </p>
                                <p class="m-0">
                                    <strong>Birthdate:</strong>
                                    <?php 
                                        $birthdate = $user['User']['birthday'];
                                        echo $this->Time->format($birthdate, '%B %d, %Y');
                                    ?>
                                </p>
                                <p class="m-0">
                                    <strong>Joined:</strong>
                                    <?php 
                                        $joined = $user['User']['created'];
                                        echo $this->Time->format($joined, '%B %e, %Y - %H:%M %p');
                                    ?>
                                </p>
                                <p>
                                    <strong>Last Login:</strong>
                                    <?php 
                                        $last_login_time = $user['User']['last_login_time'];
                                        echo $this->Time->format($last_login_time, '%B %e, %Y - %H:%M %p');
                                    ?>
                                </p>
                                <p>
                                    <strong>Hubby:</strong><br/>
                                    <?php echo $user['User']['hubby']; ?>
                                </p>
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
                        <?php echo $this->Form->button('Save', array('class' => 'btn btn-primary')); ?>
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

            let email = $('#email').val();

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'changeEmail')); ?>',
                type: 'POST',
                dataType: 'json',
                data: {email:email},
                success:function(response) {

                    switch(response.alert) {
                        case 'success':
                            alert(response.message);
                            $('#changeEmailModal').modal('hide');
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

        //AJAX on change password
        $('#changePassBtn').click(function(e) {
            e.preventDefault(0);

            $('#changePassModal').modal('show');
        });

        $('#changePassForm').on('submit', function(e) {
            e.preventDefault(0);

            let oldPass = $('#old_pass').val();
            let newPass = $('#new_pass').val();
            
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
                            $('#changePassModal').modal('hide');
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
