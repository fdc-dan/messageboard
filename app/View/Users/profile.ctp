<div class="row">
    <h3>User Details</h3>
    <div class="col-12  p-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <img src="" alt="">
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