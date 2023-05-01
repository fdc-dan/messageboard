
<h3>Message List</h3>

<div class="col-9 bg-white p-3">
    <?php foreach($participants as $participant) :?>
        <div class="conversation_box p-3">
            <div class="row">
                <div class="col-2 text-center message_profile">
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
                <div class="col-10 message_lastsent">
                    <div class="float-right">
                        <a href=""><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>
                    </div>
                    <h5>
                        <?php 
                            if($session_id == $sender_id) echo $participant['recipient']['recipient_name'];
                            else echo $participant['sender']['sender_name'];
                        ?>
                    </h5>
                    <p>
                        <?php 
                            if($session_id == $sender_id)  echo 'You: '.$participant['inbox']['last_message'];
                            else echo $participant['inbox']['last_message'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

