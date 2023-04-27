<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    //Allow page to view in sessions
    public function beforeFilter() {
        $this->Auth->allow('create','thankyou');
    }

	public function login() {

		if($this->Auth->user('id')) return $this->redirect($this->Auth->redirectUrl());
    
        if($this->request->is('post')) {

            if($this->Auth->login()) {

				$userid = $this->Auth->user('id');
				$datenow = date("Y-m-d H:i:s");
				$data = array('last_login_time' => $datenow);
				
				$this->User->id = $userid;

				if($this->User->save($data)) return $this->redirect($this->Auth->redirectUrl());

            } else $this->Session->setFlash('Invalid Username or Password', 'default', array('class' => 'alert alert-danger'));
        }
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function create() {

		if($this->request->is('post')) {

			$name = $this->request->data['User']['name'];
			$email = $this->request->data['User']['email'];
			$password = $this->request->data['User']['password'];
			$confirmpass = $this->request->data['User']['confirm_password'];

			
			$data = array(
				'name' => $name,
				'email' => $email, 
				'password' => AuthComponent::password($password)
			);

			$userData['User'] = $data;
			$this->User->clear();
			$this->User->set($userData);

			if($this->User->save()) {

				$this->Session->setFlash('The Users has been created', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'create'));

			} else $this->set('errors', $this->User->validationErrors);
			
		}

	}

	public function profile() {
		
		$userid = $this->Auth->user('id');
		$user = $this->User->findById($userid);

		$this->set('user', $user);
		
	}

	public function update() {

	}

	public function email() {

		if($this->request->is(array('post', 'put'))) {
			
			$userid = $this->Auth->user('id');
			$new_email = $this->request->data['User']['email'];

			$conditions = array(
				"User.id" => $userid,
				"User.email" => $new_email
			);

			$track_current_email = $this->User->find('count', array('conditions' => $conditions));

			if($track_current_email == 0) {

				$data = array(
					'id' => $userid, 
					'email' => $new_email
				);

				if($this->User->save($data)) {

					$this->Session->setFlash('Email address was successfully changed', 'default', array('class' => 'alert alert-success text-center'));
					return $this->redirect(array('action' => 'email'));

				} else $this->set('errors', $this->User->validationErrors);

			} else $this->set('errors', $this->User->validationErrors['email'] = 'You enter your current email address');
			
		}
	}

	public function password() {

		if($this->request->is(array('post', 'put'))) {

			$userid = $this->Auth->user('id');
			$old_password = $this->request->data['User']['old_password'];
			
			$conditions = array(
				"User.id" => $userid,
				"User.password" => AuthComponent::password($old_password)
			);

			$track_old_password = $this->User->find('count', array('conditions' => $conditions));

			if($track_old_password > 0) {

				$new_password = $this->request->data['User']['new_password'];
				$data = array(
					'id' => $userid, 
					'password' => AuthComponent::password($new_password)
				);

				if($this->User->save($data)) {

					$this->Session->setFlash('Password was successfully changed', 'default', array('class' => 'alert alert-success text-center'));
					return $this->redirect(array('action' => 'password'));

				} else $this->set('errors', $this->User->validationErrors);


			} else $this->Session->setFlash('Incorrect old password', 'default', array('class' => 'alert alert-danger text-center'));

		}
	}

}
