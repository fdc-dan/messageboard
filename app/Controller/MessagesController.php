<?php

    App::uses('AppController', 'Controller');
 
    class MessagesController extends AppController {


         // View Conversation
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

        // Create Conversation
        public function create() {

            if($this->request->is(array('post'))) {

                $this->loadModel('Conversation');

                $sender = $this->Auth->user('id');
                $recepient = $this->request->data['Message']['recepient'];
                $conversationId = date('Hisumd').$sender;
                $last_message = $this->request->data['Message']['message'];
                $ip = $this->request->clientIp();

                $conditions = array('conditions' => array(
                    'sender_id' => $sender,
                    'recipient_id' => $recepient
                ));

                $findExistingConversation = $this->Conversation->find('count', $conditions);

                if($findExistingConversation == 0) {
                    $data = array(
                        'sender_id' => $sender,
                        'recipient_id' => $recepient,
                        'inbox_hash' => $conversationId,
                        'last_message' => $last_message,
                        'created_ip' => $ip
                    );

                    if($this->Conversation->save($data)) {

                        $new_message = array(
                            'sender_id' => $sender,
                            'inbox_hash' => $conversationId,
                            'message' => $last_message,
                            'created_ip' => $ip
                        );
        
                        if($this->Message->save($new_message)) {

                            $this->Session->setFlash('Messae Sent', 'default', array('class' => 'alert alert-success'));
                            return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
                        
                        } else $this->Session->setFlash('Unable to send message', 'default', array('class' => 'alert alert-danger'));

                    } else $this->Session->setFlash('Unable to send message', 'default', array('class' => 'alert alert-danger'));

                } else $this->Session->setFlash('You already have an existing message to this recepient', 'default', array('class' => 'alert alert-warning'));
            }
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
                $inbox_hash = $this->request->data['indexHash'];
                $message = $this->request->data['message'];
                $ip = $this->request->clientIp();

                $data = array(
                    'sender_id' => $userid,
                    'inbox_hash' => $inbox_hash,
                    'message' => $message,
                    'created_ip' => $ip
                );

                if($this->Message->save($data)) {

                    $this->loadModel('Conversation');

                    $findConversationId = $this->Conversation->find('first', array('inbox_hash' => $inbox_hash ));

                    $conversationId = $findConversationId['Conversation']['id'];


                    $update_conversation = array(
                        'id' =>  $conversationId,
                        'inbox_hash' => $inbox_hash,
                        'last_message' => $message,
                        'modified_ip' => $ip
                    );

                    if($this->Conversation->save($update_conversation)) {

                        $response = array('alert' => 'success', 'message' => 'Message Sent');

                    } else $response = array('alert' => 'error', 'message' => 'Unable to sent message');

                } else $response = array('alert' => 'error', 'message' => 'Unable to sent message');

                return json_encode($response);

            }
        }
    }
?>