<div class="d-flex justify-content-center">
    <div class="alert_wrap">
        <?php echo $this->Session->flash(); ?>
    </div>
</div>

<div class="d-flex justify-content-center">
    <div class="col-10">
        <div class="row p-3">
            <div class="col">
                <h3>Message List</h3>
            </div>
            <div class="col">
                <?php echo $this->Html->link('New Message',array('controller' => 'messages', 'action' => 'create'), array('class' => 'btn btn-primary float-right')); ?>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    <!-- Parent div for conversations -->
    <div class="col-10" id="conversationsData"></div>
</div>

<div class="d-flex justify-content-center">
    <div class='col-10 text-center mt-3 mb-5' id='showmore_wrapper'>
        <?php echo $this->Form->button('Show More', array('class' => 'btn btn-outline-primary btn-sm', 'id' => 'showMoreButton')); ?>
    </div>
</div>


<script>
    $(document).ready(function() {

        function displayConversations(data) {
            
            var html = '';

            $.each(data, function(key, value) {

                var session_id = '<?php echo AuthComponent::user('id') ?>';
                var sender_id = value.sender.id;
                var profile = '';
                var profile_url = '';
                
                if(session_id == sender_id) {
                    profile = value.recipient.photo;
                } else {
                    profile = value.sender.photo;
                }

                (profile === null) ? profile_url = '/img/users/placeholder.jpeg' : profile_url = '/img/users/'+profile;

                html+="<div class='inbox p-3' id='inbox-"+value.Conversation.id+"'>";
                    html+="<div class='inboxLink' data-hash='"+value.Conversation.inbox_hash+"'>";
                        html+="<div class='row'>";
                                html+="<div class='col-md-2 message_profile text-center'>";
                                    html+="<img src='"+profile_url+"' class='message-profile' alt=''>";
                                html+="</div>";
                                html+="<div class='col-md-10'>";
                                    if(session_id == sender_id) {
                                        html+="<p class='m-0'><strong>"+value.recipient.name+"</strong></p>";
                                    } else {
                                        html+="<p class='m-0'><strong>"+value.sender.name+"</strong></p>";
                                    }
                                    html+="<p>"+value.Conversation.last_message+"</p>";
                                    html+="<small>"+value.Conversation.modified+"</small>";
                                html+="</div>";
                        html+="</div>";
                    html+="</div>";
                    if(session_id == sender_id) {
                        html+="<div class='col-md-12 text-right'><a href='javasript:void(0)' class='deleteConversation btn btn-sm btn-outline-danger' data-id='"+value.Conversation.id+"'><i class='fa fa-trash'></i> Delete</a></div>";
                    }
                html+="</div>";
            });

            return html;
        }

        $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getConversations')); ?>', function(data) {
 
            if(data.length > 0) {
                
                var ui = displayConversations(data);
                $("#conversationsData").html(ui);

            } else {
                var noData = "<p class='text-center bg-white p-3'>NO DATA FOUND</p>";
                $("#conversationsData").html(noData);
                $('#showmore_wrapper').hide();
            }
        });

        // showmore view
        var offset = 0;
        
        $('#showMoreButton').click(function(e) {
            e.preventDefault(0);

            offset += 10;

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

                    if(response.alert == 'success') {
                        $('#inbox-'+conversaionId).fadeOut();
                    }
                }, 
                error:function(error) {
                    console.log(error);
                }
            });

        });
    });
</script>
