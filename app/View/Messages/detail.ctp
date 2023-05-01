
<div class="col-10 bg-white p-3 mb-3">
    <?php echo $this->Form->create(array('id' => 'newMessageForm')); ?>
        <div class="form-group">
            <?php 
                $getInboxHashId = $this->params['pass'][0];
                echo $this->Form->input('inbox_hash', array(
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

<!-- Parent div for messages -->
<div class="col-10 bg-white p-3" id="messagesData"></div>


<script>
    $(document).ready(function() {

        // Messages view
        let inboxHash = <?php echo $this->params['pass'][0] ?>;
        
        $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getMessages')); ?>', { inboxHash:inboxHash }, function(data) {
            
            console.log(data.length);

            var htmlData  = '';

            if(data.length > 0) {

                $.each(data, function(key,value) {
                    
                    htmlData+="<div class='messages p-2'>";
                            htmlData+="<div class='row'>";
                                htmlData+="<div class='col-md-2 text-center'>";
                                    htmlData+="<img src='/img/users/"+value.sender.photo+"' class='message-profile' alt=''>";
                                htmlData+="</div>";
                                htmlData+="<div class='col-md-10'>";
                                    htmlData+="<p>"+value.message.message+"</p>";
                                    htmlData+="<small>"+value.message.created+"</small>";
                            htmlData+="</div>";
                        htmlData+="</div>";
                    htmlData+="</div>";
                    
                });

            } else {
                htmlData+="<div class='col-12'>";
                        htmlData+="<p class='text-center m-0'>No message found</p>";
                htmlData+="</div>";
            }

            $("#messagesData").html(htmlData);
        });


        // Reply messages
        $('#newMessageForm').on('submit', function() {
            let message = $('#message').val();

            console.log('message');
        }); 
    });
</script>