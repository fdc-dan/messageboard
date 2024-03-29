<?php

    App::uses('AppController', 'Controller');
 
    class MessagesController extends AppController {
 
        public function index() {
        
        }

        // View Conversation
        public function getConversations() {

            $this->autoRender = false;

            if($this->request->is(array('get'))) {

                $this->loadModel('Conversation');

                $userid = $this->Auth->user('id');

                $offset = isset($this->request->query['offset']) ? $this->request->query['offset']:0;

                $conditions = array(
                    'joins' => array(
                        array(
                            'table' => 'users',
                            'alias' => 'sender',
                            'conditions' => array(
                                'Conversation.sender_id = sender.id'
                            )
                        ),
                        array(
                            'table' => 'users',
                            'alias' => 'recipient',
                            'conditions' => array(
                                'Conversation.recipient_id = recipient.id'
                            )
                        )
                    ),
                    'fields'=>array(
                        'Conversation.*',
                        'DATE_FORMAT(Conversation.modified, "%M %d, %Y %h:%i %p") as conversationDate',
                        'sender.id',
                        'sender.name',
                        'sender.photo',
                        'recipient.id',
                        'recipient.name',
                        'recipient.photo'
                    ),
                    'conditions' => array(
                        'OR' => array(
                            'Conversation.sender_id =' => $userid,
                            'Conversation.recipient_id =' => $userid
                        )
                    ),
                    'order' => 'Conversation.modified DESC',
                    'limit' => 10,
                    'offset' => $offset
                );
                
                $data = $this->Conversation->find('all', $conditions);

                return json_encode($data);
            }
        }

        // Create Conversation
        public function create() {

            $this->loadModel('Conversation');
            
            if($this->request->is(array('post'))) {

                $sender = $this->Auth->user('id');
                $recipient = $this->request->data['Message']['recipient'];
                $conversationId = date('HisvmdY').$sender;
                $last_message = $this->request->data['Message']['message'];
                $ip = $this->request->clientIp();

                $findExistingConversation = $this->Conversation->query("SELECT COUNT(id) as count FROM conversations WHERE is_delete = 0 AND ((sender_id = $sender AND recipient_id = $recipient ) OR (sender_id = $recipient AND recipient_id = $sender))");

                if($findExistingConversation[0][0]['count'] == 0) {

                    $data = array(
                        'sender_id' => $sender,
                        'recipient_id' => $recipient,
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

                            $this->Session->setFlash('Message Successfully Sent', 'default', array('class' => 'alert alert-success'));
                            return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
                        
                        } else $this->Session->setFlash('Unable to send message', 'default', array('class' => 'alert alert-danger'));

                    } else $this->Session->setFlash('Unable to send message', 'default', array('class' => 'alert alert-danger'));

                } else $this->Session->setFlash('You already have an existing message to this recipient', 'default', array('class' => 'alert alert-warning'));
            }
        }

        // Search Recepient
        public function findRecepient() {
            
            $this->autoRender = false;
            $this->loadModel('User');

            if($this->request->is(array('get'))) {

                $userid = $this->Auth->user('id');
                $recepient = $this->request->query['recepientName'];


                $conditions = array('conditions' => array(
                                    array (
                                        'User.name LIKE' => '%'.$recepient.'%',
                                        'NOT' => array ('User.id' => $userid)
                                    )
                                )
                            );

                $users = $this->User->find('all', $conditions);

                return json_encode($users);
            }

        }

        public function deleteConversation() {
            
            $this->autoRender = false;

            if($this->request->is(array('post','put'))) {

                $this->loadModel('Conversation');

                $conversation_id = $this->request->data['conversaionId'];
                $ip = $this->request->clientIp();
                $data = array(
                    'id' => $conversation_id,
                    'is_delete' => 1,
                    'modified_ip' => $ip
                );
            

                // $this->Conversation->delete($conversation_id); 

                if($this->Conversation->save($data)) {

                    // Delete messages under this conversation
                    $getInboxHash = $this->Conversation->findById($conversation_id);
                    $setInboxHash = $getInboxHash['Conversation']['inbox_hash'];
                    $conditions = array('conditions' => array(
                            'inbox_hash' => $setInboxHash
                    ));
                
                    $findMessages = $this->Message->find('all', $conditions);
    
                    foreach($findMessages as $messages) {
    
                        $messageId = $messages['Message']['id']; 
                        $messageData = array(
                            'id' => $messageId, 
                            'is_delete' => 1,
                            'modified_ip' => $ip
                        );
    
                        $this->Message->save($messageData);
                    }

                    $response = array('alert' => 'success', 'message' => 'Delete Conversation');

                } else $response = array('alert' => 'error', 'message' => 'Unable to delete conversation');

                return json_encode($response);

            }
        }

        public function detail() {
            
        }

        public function getMessages() {
        
            $this->autoRender = false;

            if($this->request->is(array('get'))) {

                $inboxHash =  $this->request->query['inboxHash'];

                $offset = isset($this->request->query['offset']) ? $this->request->query['offset']:0;
                
                $conditions = array(
                    'joins' => array(
                        array(
                            'table' => 'users',
                            'alias' => 'sender',
                            'conditions' => array(
                                'Message.sender_id = sender.id'
                            )
                        )
                    ),
                    'fields'=>array(
                        'Message.*',
                        'DATE_FORMAT(Message.created, "%M %d, %Y %h:%i %p") as messageDate',
                        'sender.*'
                    ),
                    'conditions' => array(
                        'Message.inbox_hash =' => $inboxHash
                    ),
                    'order' => 'Message.created DESC',
                    'limit' => 10,
                    'offset' => $offset
                );

                $messages = $this->Message->find('all', $conditions);

                return json_encode($messages);
            }
         
        }

        public function replyMessage() {

            $this->autoRender = false;
            $this->loadModel('Conversation');

            if($this->request->is(array('post'))) {
                
                $userid = $this->Auth->user('id');
                $inbox_hash = $this->request->data['inboxHash'];
                $message = $this->request->data['message'];
                $ip = $this->request->clientIp();

                $data = array(
                    'sender_id' => $userid,
                    'inbox_hash' => $inbox_hash,
                    'message' => $message,
                    'created_ip' => $ip
                );

                if($this->Message->save($data)) {

                    $findConversationId = $this->Conversation->find('first', array('conditions' => array('inbox_hash =' => $inbox_hash)));
                    $conversationId = $findConversationId['Conversation']['id'];

                    $update_conversation = array(
                        'id' =>  $conversationId,
                        'last_message' => $message,
                        'modified_ip' => $ip
                    );

                    if($this->Conversation->save($update_conversation)) {

                        $lastSaveMessage = $this->Message->read(null, $this->Message->id);
                        $lastSaveMessageId = $this->Message->id;

                        $conditions = array(
                            'joins' => array(
                                array(
                                    'table' => 'users',
                                    'alias' => 'sender',
                                    'conditions' => array(
                                        'Message.sender_id = sender.id'
                                    )
                                )
                            ),
                            'fields'=>array(
                                'Message.*',
                                'DATE_FORMAT(Message.created, "%M %d, %Y %h:%i %p") as messageDate',
                                'sender.*'
                            ),
                            'conditions' => array(
                                'Message.id =' => $lastSaveMessageId
                            )
                        );

                        $lastMessageQuery = $this->Message->find('all', $conditions);

                        $response = array('alert' => 'success', 'message' => 'Message Sent', 'data' => $lastMessageQuery);

                    } else $response = array('alert' => 'error', 'message' => 'Unable to sent message');

                } else $response = array('alert' => 'error', 'message' => 'Unable to sent message');

                return json_encode($response);

            }
        }
    }
?>