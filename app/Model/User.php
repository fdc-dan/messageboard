<?php
    App::uses('AppModel', 'Model');

    class User extends AppModel { 
        public $name = 'User';
        public $useTable = 'users';
        public $validate = array(
            'email' => array(
                'notblank' => array(
                    'rule' => 'notBlank',
                    'message' => 'Email address is required field'
                ), 
                'unique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Email address is already exist'
                )
            ),
            'password' => array(
                'notBlank' => array(
                    'rule' => 'notBlank',
                    'message' => 'Password is required field'
                ),
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'message' => 'Please enter alphanumeric characters'
                )
            ),
            'name' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Name is required field'
                ),
                'lengthBetween' => array(
                    'rule' => array('lengthBetween', 5, 20),
                    'message' => 'Name must be 5 to 20 characters'
                )
            ),
            'gender' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Gender is required field'
                )
            ),
            'birthday' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Birthdate is required field'
                )
            ),
            'hubby' => array(
                'notBlank' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Hubby is required field'
                )
            ),

        );
    }

?>