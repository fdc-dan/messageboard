<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    //Allow page to view in sessions
    public function beforeFilter() {
        $this->Auth->allow('create');
    }

	public function login() {

		if($this->Auth->user('id')) return $this->redirect($this->Auth->redirectUrl());
    
        if($this->request->is('post')) {

            if($this->Auth->login()) {

				// Update last time login
				$datenow = date("Y-m-d H:i:s");
				$data = array(
					'id' => $this->Auth->user('id'),
					'last_login_time' => $datenow
				);

				if($this->User->save($data)) {
					return $this->redirect($this->Auth->redirectUrl());
				}

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
			$ip = $ip = $this->request->clientIp();

			if($password == $confirmpass) {
			
				$data = array(
					'name' => $name,
					'email' => $email, 
					'password' => AuthComponent::password($password),
					'created_ip' => $ip 
				);


				if($this->User->save($data)) {	

					$get_data = $this->User->read(null, $this->User->id);
					$set_data = array(
						'id' => $get_data['User']['id'],
						'email' => $get_data['User']['email'],
						'name' => $get_data['User']['name']
					);
					
					if($this->Auth->login($set_data)) {
						return $this->redirect(array('controller' => 'users', 'action' => 'thankyou'));
					}

				} else $this->set('errors', $this->User->validationErrors);
			
			} else $this->Session->setFlash('Unmatched password', 'default', array('class' => 'alert alert-warning'));
			
		}

	}

	// Profile details page
	public function profile($userid) {
		
		$conditions = array(
			'conditions' => array('User.id =' => $userid),
			'fields' => array(
				'User.*',
				'TIMESTAMPDIFF(YEAR, User.birthday, CURRENT_DATE()) as age'
			)
		);

		$user = $this->User->find('first', $conditions);

		$this->set('user', $user);
		
	}

	// Profile form
	public function edit() {

		$userid = $this->Auth->user('id');
		$user = $this->User->findById($userid);
		$this->set('user', $user);


		if($this->request->is(array('post', 'put'))) {

			$name = $this->request->data['User']['name'];
			$birthdate = date('Y-m-d', strtotime($this->request->data['User']['birthdate']));
			$gender = $this->request->data['User']['gender'];
			$hubby = $this->request->data['User']['hubby'];
			$ip = $this->request->clientIp();

			$input_img = $this->request->data['User']['image'];
			$file_type = $input_img['type'];

			if(empty($input_img['name'])) {

				$data = array(
					'id' => $userid,
					'name' => $name,
					'gender' => $gender,
					'birthday' => $birthdate,
					'hubby' => $hubby, 
					'modified_ip' => $ip,
				);

				if($this->User->save($data)) {

					$this->Session->setFlash('Updated Successfuly', 'default', array('class' => 'alert alert-success'));
					return $this->redirect(array('controller' => 'users', 'action' => 'profile', $userid));
				
				} else $this->set('errors', $this->User->validationErrors);
			
			} else {

				if(in_array($file_type, ['image/jpeg','image/jpg','image/png','image/gif'])) {

			
					$file = basename($input_img['name']);
					$filename = time().'_'.$file;
					$upload_path = WWW_ROOT. 'img/users' . DS . $filename;
	
	
					if(move_uploaded_file($input_img['tmp_name'], $upload_path)) {
	
						$data = array(
							'id' => $userid,
							'photo' => $filename,
							'name' => $name,
							'gender' => $gender,
							'birthday' => $birthdate,
							'hubby' => $hubby, 
							'updated_ip' => $ip,
						);
	
						if($this->User->save($data)) {
							
							$this->Session->setFlash('Updated Successfuly', 'default', array('class' => 'alert alert-success'));
							return $this->redirect(array('controller' => 'users', 'action' => 'profile', $userid));
						
						} else $this->set('errors', $this->User->validationErrors);
					}
	
				} else $this->Session->setFlash('Invalid photo extension', 'default', array('class' => 'alert alert-success'));
			}

		} 

	}

	// Track old email
	public function trackCurrentEmail($id, $email) {
		$conditions = array("User.id" => $id, "User.email" => $email);
		return  $this->User->find('count', array('conditions' => $conditions));
	}
	
	// Track existing email
	public function trackExistingEmail($email) {
		$conditions = array("User.email" => $email);
		return  $this->User->find('count', array('conditions' => $conditions));
	}

	public function changeEmail() {
		
		$this->autoRender = false;

		if($this->request->is(array('post', 'put'))) {

			$userid = $this->Auth->user('id');
			$new_email = $this->request->data['email'];	

			if($this->trackCurrentEmail($userid, $new_email) == 0 ) {

				if($this->trackExistingEmail($new_email) == 0) {
					$data = array(
						'id' => $userid, 
						'email' => $new_email
					);

					if($this->User->save($data)) {
						$response = array('alert' => 'success', 'message' => 'Update email successfully');
					}

				} else $response = array('alert' => 'error', 'message' => 'Email is already exist'); 

			} else $response = array('alert' => 'error', 'message' => 'Unable to update. You enter old email');

			return json_encode($response);
		}
	}

	// Track old password
	public function trackOldPassword($id, $old_password) {

		$conditions = array("User.id" => $id, "User.password" => AuthComponent::password($old_password));
		return $this->User->find('count', array('conditions' => $conditions));
	}

	public function changePassword() {

		$this->autoRender = false;

		if($this->request->is(array('post', 'put'))) {
			
			$userid = $this->Auth->user('id');
			$old_pass = $this->request->data['oldPass'];

			if($this->trackOldPassword($userid, $old_pass) > 0) {

				$new_pass = $this->request->data['newPass'];
				$data = array(
					'id' => $userid, 
					'password' => AuthComponent::password($new_pass)
				);

				if($this->User->save($data)) {
					$response = array('alert' => 'success', 'message' => 'Password was successfully changed');
				} 

			} else $response = array('alert' => 'error', 'message' => 'Incorrect old password');

			return json_encode($response);
		} 
		
	}	

	public function thankyou() {
		
		$userid = $this->Auth->user('id');
		$datenow = date("Y-m-d H:i:s");
		$last_login_time = array(
			'id' => $userid,
			'last_login_time' => $datenow
		);

		if($this->User->save($last_login_time)) {
			$message = 'Thank you for registering!';
			$this->set('message', $message);
		} 

	}

}
