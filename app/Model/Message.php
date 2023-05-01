<?php
    App::uses('AppModel', 'Model');

    class Message extends AppModel { 
        public $name = 'Message';
        public $useTable = 'messages';
        public $validate = array(
            'message' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'message is required field'
                )
            )
        );
    }

?>