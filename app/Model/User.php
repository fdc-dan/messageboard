<?php
    App::uses('AppModel', 'Model');

    class User extends AppModel { 
        public $name = 'User';
        public $useTable = 'users';
        public $validate = array(
            'username' => array(
                'notBlank' => array(
                    'rule' => array('notBlank')
                ),
            ),
            'password' => array(
                'notBlank' => array(
                    'rule' => array('notBlank')
                ),
            ),
            'name' => array(
                'notBlank' => array(
                    'rule' => array('notBlank')
                ),
            ),
        );
    }

?>