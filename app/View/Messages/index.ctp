
<h3>Message List</h3>
<div class="alert_wrap">
    <?php echo $this->Session->flash(); ?>
</div>
<div class="col-10 mb-5 p-0">
    <?php echo $this->Html->link('New Message',array('controller' => 'messages', 'action' => 'create'), array('class' => 'btn btn-primary float-right')); ?>
</div>

<div class="col-10 bg-white">
    <?php foreach($participants as $participant) :?>
        <div class="inbox p-3 inboxLink" data-id="<?php echo $participant['inbox']['inbox_hash'];?>">
            <div class="row">
                <div class="col-md-2 message_profile text-center">
                    <?php
                        $session_id = AuthComponent::user('id');
                        $sender_id = $participant['sender']['sender_id'];

                        if($session_id == $sender_id) $profile_data = $participant['recipient']['recipient_photo'];
                        else $profile_data = $participant['sender']['sender_photo'];

                        if(empty($profile_data )) $profile_url = 'users/placeholder.jpeg';
                        else $profile_url = 'users/'.$profile_data;

                        echo $this->Html->image($profile_url, array(
                            'class' => "user-message-profile",
                            'alt' => AuthComponent::user('name')
                        ));
                    ?>
                </div>
                <div class='col-md-10'>
                    <h5>
                        <?php 
                            if($session_id == $sender_id) echo $participant['recipient']['recipient_name'];
                            else echo $participant['sender']['sender_name'];
                        ?>
                    </h5>
                    <p><?php  echo $participant['inbox']['last_message'];?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    $(document).ready(function() {
        $('.inboxLink').click(function() {
            var inboxHash = $(this).attr('data-id');
            window.location.href = 'message/'+inboxHash;
        });
    });
</script>
