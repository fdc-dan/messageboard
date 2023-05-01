<?php

    App::uses('AppController', 'Controller');
 
    class MessagesController extends AppController {

        public function index() {

            $userid = $this->Auth->user('id');

            var_dump($this->Message->find('all'));
            exit();

            
        
        }

        public function detail() {
            
        }

        public function create() {
            
        }
    }
?>