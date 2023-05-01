<?php

    App::uses('AppController', 'Controller');
 
    class MessagesController extends AppController {


        public function index() {
            
            $userid = $this->Auth->user('id');

            $this->loadModel('Conversation');

            $query = $this->Conversation->query("SELECT inbox.*,
                                                        sender.id as sender_id,
                                                        sender.name as sender_name,
                                                        sender.photo as sender_photo,
                                                        recipient.id as recipient_id,
                                                        recipient.name as recipient_name,
                                                        recipient.photo as recipient_photo
                                                FROM conversations inbox 
                                                JOIN users sender ON inbox.sender_id = sender.id
                                                JOIN users recipient ON inbox.recipient_id = recipient.id
                                                WHERE (inbox.sender_id = $userid OR inbox.recipient_id = $userid)
            "); 

            // var_dump($participants);
            // exit();

            $this->set('participants', $query);
        }

        public function detail() {
            
        }

        public function create() {
            
        }
    }
?>