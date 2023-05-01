<div class="row">
    <div class="col-12 p-3">
        <div class="d-flex align-items-center justify-content-center vertical">
            <h3 class="text-center">
                <?php echo $message; ?> <br/>
                <?php echo  $this->Html->link('Back to homepage', array('controller' => 'messages', 'action' => 'index'), array('class' => 'btn btn-primary mt-3')); ?>
            </h3>
        </div>
    </div>
</div>
