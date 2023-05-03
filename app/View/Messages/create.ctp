
<div class="d-flex justify-content-center">
    <div class="alert_wrap">
        <?php echo $this->Session->flash(); ?>
    </div>
</div>

<div class="d-flex justify-content-center">
    <div class="col-10 p-0">
        <nav class='mt-3' aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">New Message</li>
                <li class="breadcrumb-item active">
                    <?php echo $this->Html->link('Back to Message List', array('controller' => 'messages', 'action' => 'index')); ?>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="d-flex justify-content-center">
    <div class="col-10 bg-white p-3">
        
        <h3 class='mb-3'>New Message</h3> 

        <?php echo $this->Form->create('Message', array('controller'=>'messages', 'action'=>'create')); ?>
            <div class="form-group">
                <?php 
                    echo $this->Form->input('find_recepient', array(
                            'class' => 'form-control',
                            'placeholder' => 'Search for recepient',
                            'id' => 'findRecepient', 
                            'label' => 'Recepient'
                        )); 
                ?>
            </div>
            <!-- List of recepient -->
            <div id='recipient_wrapper'></div>

            <div class="form-group">
                <?php 
                    echo $this->Form->input('recipient', array(
                        'class' => 'form-control',
                        'id' => 'recipient_id',
                        'type' => 'hidden',
                        'required' => true
                    ));
                ?>
            </div>
            <div class="form-group">
                <?php 
                    echo $this->Form->textarea('message', array(
                        'class' => 'form-control',
                        'id' => 'message',
                        'rows' => 4,
                        'placeholder' => 'Write your message',
                        'required' => true
                    )); 
                ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->button('Send', array('class' => 'btn btn-primary')); ?>
            </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#findRecepient').on('keyup', function() {

            var recepientName = $(this).val();

            $.getJSON('<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'findRecepient')); ?>', { recepientName:recepientName }, function(data) {
               
                var html = '';
                
                $.each(data, function(key, value) {

                    var profile_url = '';

                    if(value.User.photo === null) profile_url = '/img/users/placeholder.jpeg';
                    else profile_url = '/img/users/'+value.User.photo;

                    html+="<div class='recipient_list recipientLink' dataId='"+value.User.id+"' dataName='"+value.User.name+"'>";
                            html+="<div class='box'>";
                                html+="<img src='"+profile_url+"'>";
                            html+="</div>";
                            html+="<div class='box p-0'>";
                                html+="<p>"+value.User.name+"</p>";
                            html+="</div>";
                    html+="</div>";

                });

                $('#recipient_wrapper').html(html);
            });

        });

        $(this).on('click', '.recipientLink', function(e) {
            e.preventDefault(0);

            var recepientId = $(this).attr('dataId');
            var recepientName = $(this).attr('dataName');

            if(recepientId) {
                $('#recipient_id').val(recepientId);
                $('#findRecepient').val(recepientName);
                $('.recipient_list').hide();
            }
        });
    });

</script>