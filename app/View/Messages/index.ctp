
<h3>Message List</h3>
<div class="col-11  alert_wrap">
    <?php echo $this->Session->flash(); ?>
</div>
<div class="col-11 mb-5">
    <?php echo $this->Html->link('New Message',array('controller' => 'messages', 'action' => 'create'), array('class' => 'btn btn-primary float-right')); ?>
</div>

<!-- Parent div for conversations -->
<div class="col-11" id="conversationsData"></div>
<div class='col-11 text-center mt-3 mb-5'>
    <?php 

       echo $this->Form->button('Show More', array(
                'class' => 'btn btn-outline-primary btn-sm',
                'id' => 'showMoreButton'
            )); 
    ?>
</div>

<script>
    $(document).ready(function() {

        function displayConversations(data) {
            
            var html = '';

            $.each(data, function(key, value) {

                var session_id = '<?php echo AuthComponent::user('id') ?>';
                var sender_id = value.sender.sender_id;
                var profile = '';
                var profile_url = '';
                
                if(session_id == sender_id) {
                    profile = value.recipient.recipient_photo;
                } else {
                    profile = value.sender.sender_photo;
                }

                (profile === null) ? profile_url = '/img/users/placeholder.jpeg' : profile_url = '/img/users/'+profile;

                html+="<div class='inbox bg-white p-3'>";
                    html+="<div class='inboxLink' data-hash='"+value.inbox.inbox_hash+"'>";
                        html+="<div class='row'>";
                                html+="<div class='col-md-2 message_profile text-center'>";
                                    html+="<img src='"+profile_url+"' class='message-profile' alt=''>";
                                html+="</div>";
                                html+="<div class='col-md-10'>";
                                    if(session_id == sender_id) {
                                        html+="<p class='m-0'><strong>"+value.recipient.recipient_name+"</strong></p>";
                                    } else {
                                        html+="<p class='m-0'><strong>"+value.sender.sender_name+"</strong></p>";
                                    }
                                    // html+="<p>"+value.inbox.inbox_hash+"</p>";
                                    html+="<p>"+value.inbox.last_message+"</p>";
                                    html+="<small>"+value.inbox.modified+"</small>";
                                html+="</div>";
                        html+="</div>";
                    html+="</div>";
                    if(session_id == sender_id) {
                        html+="<div class='col-md-12 text-right'><a href='javasript:void(0)' class='deleteConversation btn btn-sm btn-outline-danger' data-id='"+value.inbox.id+"'><i class='fa fa-trash'></i> Delete</a></div>";
                    }
                html+="</div>";
            });

            return html;
        }

        $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getConversations')); ?>', function(data) {
            var ui = displayConversations(data);
            $("#conversationsData").html(ui);
        });

        // showmore view
        var offset = 0;

        $('#showMoreButton').click(function(e) {
            e.preventDefault(0);

            offset += 2;

            $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getConversations')); ?>',{offset:offset}, function(data) {
                var ui = displayConversations(data);
                $("#conversationsData").append(ui);
            });

        });

        $(this).on('click', '.inboxLink', function() {
            var messageHashUrl = $(this).attr('data-hash');
            window.location.href='message/'+messageHashUrl;
        });

        $(this).on('click', '.deleteConversation', function(e) {
            e.preventDefault(0);
            var conversaionId = $(this).attr('data-id');

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'deleteConversation')); ?>',
                type: 'POST',
                dataType: 'json',
                data: {conversaionId:conversaionId}, 
                success:function(response) {
                    console.log(response);
                }, 
                error:function(error) {
                    console.log(error);
                }
            });

        });
    });
</script>
