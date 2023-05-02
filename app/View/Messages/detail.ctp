
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

<!-- Parent div for messages -->
<div class="col-10 bg-white p-3" id="messagesData"></div>
<div class='col-10 text-center mt-3'>
    <?php 

       echo $this->Form->button('Show More', array(
                'class' => 'btn btn-outline-primary btn-sm',
                'id' => 'showMoreButton'
            )); 
    ?>
</div>

<script>
    $(document).ready(function() {

        // Messages view
        let inboxHash = '<?php echo $this->params['pass'][0]; ?>';
        
        $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getMessages')); ?>', { inboxHash:inboxHash }, function(data) {
            
            var htmlData  = '';

            if(data.length > 0) {

                $.each(data, function(key,value) {

                    var profile_url = '';

                    if(value.sender.photo === null) profile_url = '/img/users/placeholder.jpeg';
                    else profile_url = '/img/users/'+value.sender.photo;
                    
                    htmlData+="<div class='messages p-2'>";
                            htmlData+="<div class='row'>";
                                htmlData+="<div class='col-md-2 text-center'>";
                                    htmlData+="<img src='"+profile_url+"' class='message-profile' alt=''>";
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

        $('#showMoreButton').click(function(e) {
            e.preventDefault(0);

            var limit = 5;

            $.post('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'getMessages')); ?>',{limit:limit}, function(response) {
                console.log(response);
            });

        });


        // Reply messages
        $('#newMessageForm').on('submit', function(e) {
            e.preventDefault();

            let indexHash = $('#inboxhash').val();
            let message = $('#message').val();

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'replyMessage')); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    indexHash:indexHash,
                    message:message
                }, 
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