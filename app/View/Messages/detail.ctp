
<div class="d-flex justify-content-center">
    <div class="col-10 p-0">
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Message Details</li>
            <li class="breadcrumb-item active">
                <?php echo $this->Html->link('Back to Message List', array('controller' => 'messages', 'action' => 'index')); ?>
            </li>
        </ol>
        </nav>
    </div>
</div>

<div class="d-flex justify-content-center">
    <div class="col-10 bg-white p-3 mb-3">
        <?php echo $this->Form->create(array('id' => 'newMessageForm')); ?>
            <div class="form-group">
                <?php 
                    $getInboxHashId = $this->params['pass'][0];
                    echo $this->Form->input('inbox_hash', array(
                        'id' => 'inboxhash',
                        'type' => 'hidden',
                        'value' => $getInboxHashId
                    ));
                ?>
            </div>
            <div class="form-group">
                <?php 
                    echo $this->Form->textarea('message', array(
                        'class' => 'form-control',
                        'id' => 'message',
                        'rows' => 3,
                        'placeholder' => 'Write your message',
                        'required' => true
                    )); 
                ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->button('Reply', array('class' => 'btn btn-primary')); ?>
            </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<div class="d-flex justify-content-center">
    <!-- Parent div for messages -->
    <div class="col-10 bg-white p-3" id="messagesData"></div>
</div>

<div class="d-flex justify-content-center">
    <div class='col-10 text-center mt-3 mb-5'>
        <?php 

        echo $this->Form->button('Show More', array(
                    'class' => 'btn btn-outline-primary btn-sm',
                    'id' => 'showMoreButton'
                )); 
        ?>
    </div>
</div>

<script>
    $(document).ready(function() {

        function displayMessages(data) {
            var html  = '';

            $.each(data, function(key,value) {

                var profileUrl = '';

                if(value.sender.photo === null) profileUrl = '/img/users/placeholder.jpeg';
                else profileUrl = '/img/users/'+value.sender.photo;
                
                html+="<div class='messages p-2'>";
                        html+="<div class='row'>";
                            html+="<div class='col-md-2 p-0 text-center'>";
                                html+="<a href='<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile'));?>/"+value.message.sender_id+"'><img class='message-profile' src='"+profileUrl+"' alt=''></a>";
                            html+="</div>";
                            html+="<div class='col-md-10 p-0'>";
                                html+="<p class='m-0'><strong>"+value.sender.name+"</strong></p>";
                                html+="<p class='m-0 new-message'>"+value.message.message+"</p>";
                                html+="<small>"+value.message.created+"</small>";
                        html+="</div>";
                    html+="</div>";
                html+="</div>";
            });

            return html;
        }


        // messages view
        let inboxHash = '<?php echo $this->params['pass'][0]; ?>';
        $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getMessages')); ?>', { inboxHash:inboxHash }, function(data) {
            var ui = displayMessages(data);
            $("#messagesData").html(ui);
        });
        


        // showmore view
        var offset = 0;

        $('#showMoreButton').click(function(e) {
            e.preventDefault(0);

            var inboxHash = '<?php echo $this->params['pass'][0]; ?>';
                offset += 10;

            $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getMessages')); ?>',{inboxHash:inboxHash, offset:offset}, function(data) {
                
                var ui = displayMessages(data);
                $("#messagesData").append(ui);
            });

        });


        // Reply messages
        $('#newMessageForm').on('submit', function(e) {
            e.preventDefault();

            var inboxHash = $('#inboxhash').val();
            var message = $('#message').val();

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'replyMessage')); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    inboxHash:inboxHash,
                    message:message
                }, 
                success:function(response) {
                    // console.log(response);
                    if(response.alert == 'success') {

                        $('#message').val('');
                        var newreply = displayMessages(response.data);
                        $("#messagesData").prepend(newreply);
                        
                    }
                }, 
                error:function(error) {
                    console.log(error);
                }
            });
            
        }); 
    });
</script>