<div class="d-flex justify-content-center">
    <div class="alert_wrap">
        <?php echo $this->Session->flash(); ?>
    </div>
</div>

<nav class='mt-3' aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">Edit Profile</li>
    <li class="breadcrumb-item active">
        <?php echo $this->Html->link('Back to User Profile', array('controller' => 'users', 'action' => 'profile')); ?>
    </li>
  </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                    <?php 
                        echo $this->Form->create('User', array(
                                'controller' => 'users',
                                'action' => 'edit',
                                'type' => 'file'
                            ));
                    ?>
                    <div class="row">
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

                                echo $this->Form->input('image', array(
                                    'class' => 'form-control file-upload', 
                                    'id' => 'fileUpload',
                                    'type' => 'file', 
                                    'accept' => 'image/*',
                                    'value' => $user['User']['photo'],
                                    'label' => false,
                                    'required' => ($user['User']['photo'] == null) ? true:false
                                ));
                             ?>
                        </div>
                        <div class="col-lg-9">
                            <div class="form-group">
                                <?php 
                                    echo $this->Form->input('name', array(
                                            'class' => 'form-control',
                                            'id' => 'name', 
                                            'value' => $user['User']['name'],
                                            'required' => true
                                        )); 
                                ?>
                            </div>
                            <div class="form-group">
                                <?php 

                                    $birthdate = isset($user['User']['birthday']) ? $user['User']['birthday']:'';
                                    $formatBirthDate = $this->Time->format($birthdate, '%m/%d/%Y');

                                    
                                    echo $this->Form->input('birthdate', array(
                                        'class' => 'form-control',
                                        'id' => 'birthdateFormat',
                                        'value' => $formatBirthDate
                                    ));

                                ?>
                            </div>
                            <div class="form-group genderInput">
                                <?php
                                    $options = array(
                                        'male' => 'Male',
                                        'female' => 'Female'
                                    );
                            
                                    echo $this->Form->radio('gender', $options, array(
                                        'legend' => 'Gender',
                                        'default' => $user['User']['gender']
                                    ));
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="gender">Hubby</label>
                                <?php 
                                    echo $this->Form->textarea('hubby', array(
                                        'class' => 'form-control',
                                        'id' => 'hubby',
                                        'rows' => 7,
                                        'value' => $user['User']['hubby'],
                                        'required' => true
                                    )); 
                                ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->button('Save', array('class' => 'btn btn-primary')); ?>
                            </div>
                        </div>  
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {

        // jQUery Datepicker
        $('#birthdateFormat').datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2015'
        });

        // Profile preview
        var fileReadURL = function(input) {
            if(input.files && input.files[0]) {
                var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#fileUploadImage').attr('src', e.target.result);
                    }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#fileUpload").on('change', function(){
            fileReadURL(this);
        });
    });

</script>