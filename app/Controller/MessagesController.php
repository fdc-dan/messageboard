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
                                                  WHERE (inbox.sender_id = $userid OR inbox.recipient_id = $userid)"
                                                ); 

            $this->set('participants', $query);
        }

        public function detail() {

 
        }

        public function getMessages() {
        
            $this->autoRender = false;

            if($this->request->is(array('get'))) {

                $inboxHash =  $this->request->query['inboxHash'];

                $messages = $this->Message->query("SELECT message.*, 
                                                          sender.* 
                                                   FROM messages message JOIN users sender
                                                   ON message.sender_id = sender.id  
                                                   WHERE message.inbox_hash = $inboxHash
                                                   ORDER BY message.id DESC
                                                ");

                return json_encode($messages);
            }
         
        }

        public function replyMessage() {

            $this->autoRender = false;

            if($this->request->is(array('post'))) {
                
                $userid = $this->Auth->user('id');
                $index_hash = $this->request->data['indexHash'];
                $message = $this->request->data['message'];
                $ip = $this->request->clientIp();

                $data = array(
                    'sender_id' => $userid,
                    'inbox_hash' => $index_hash,
                    'message' => $message,
                    'created_ip' => $ip
                );

                if($this->Message->save($data)) {

                    $response = array('alert' => 'success', 'message' => 'Message Sent');

                } else $response = array('alert' => 'error', 'message' => 'Unable to sent message');

                return json_encode($response);

            }
        }
    }
?>