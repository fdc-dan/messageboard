<div class="row">
    <h3>Edit Profile</h3>
    <div class="col-12  p-3">
        <div class="card">
            <div class="card-body">
                <?php echo $this->Form->create(array('type' => 'file', 'id' => 'profileUpdateForm')); ?>
                    <div class="row">
                        <div class="col-lg-3">
                            <?php 

                                echo $this->Html->image('placeholder-user.jpeg', array(
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
                                    'required' => true
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

                                    $birthdate = $user['User']['birthday'];
                                    $formatBirthDate = $this->Time->format($birthdate, '%m/%d/%Y');

                                    echo $this->Form->input('birthdate', array(
                                        'class' => 'form-control',
                                        'id' => 'birthdate',
                                        'value' => $formatBirthDate
                                    )); 

                                    echo date_format($user['User']['birthday'], 'Y');
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                
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
        $('#birthdate').datepicker();

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



        $('#profileUpdateForm').on('submit', function(e) {
            e.preventDefault(0);


        });
    });

</script>