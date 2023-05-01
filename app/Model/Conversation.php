<?php
    App::uses('AppModel', 'Model');

    class Conversation extends AppModel { 
        public $name = 'Conversation';
        public $useTable = 'conversations';
    }

?>