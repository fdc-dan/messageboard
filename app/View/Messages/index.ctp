
<h3>Message List</h3>

<div class="col-9 bg-white p-3">
    <div class="conversation_box p-3">
        <div class="row">
            <div class="col-2 text-center message_profile">
                <?php
                    echo $this->Html->image('users/placeholder.jpeg', array(
                        'class' => "user-message-profile",
                        'alt' => AuthComponent::user('name')
                    ));
                ?>
            </div>
            <div class="col-10 message_lastsent">
                <div class="float-right">
                    <a href=""><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>
                </div>
                <h5>Mark</h5>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit nihil unde minus, quia laboriosam optio asperiores recusandae, vero sit delectus neque fugiat? Tempora praesentium magni voluptas repudiandae quisquam eaque quod.</p>
            </div>
        </div>
    </div>

    <div class="conversation_box p-3">
        <div class="row">
            <div class="col-2 text-center message_profile">
                <?php
                    echo $this->Html->image('users/placeholder.jpeg', array(
                        'class' => "user-message-profile",
                        'alt' => AuthComponent::user('name')
                    ));
                ?>
            </div>
            <div class="col-10 message_lastsent">
                <div class="float-right">
                    <a href=""><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>
                </div>
                <h5>Mark</h5>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit nihil unde minus, quia laboriosam optio asperiores recusandae, vero sit delectus neque fugiat? Tempora praesentium magni voluptas repudiandae quisquam eaque quod.</p>
            </div>
        </div>
    </div>
    
</div>

