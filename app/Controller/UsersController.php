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

	// Profile details page
	public function profile() {
		
		$userid = $this->Auth->user('id');
		$user = $this->User->findById($userid);

		$this->set('user', $user);
		
	}

	// Profile form
	public function edit() {

		$userid = $this->Auth->user('id');
		$user = $this->User->findById($userid);

		$this->set('user', $user);

	}

	public function editProfile() {
		
		$this->autoRender = false;

		if($this->request->is(array('post', 'put'))) {

			$userid = $this->Auth->user('id');
			$name = $this->request->data['User']['name'];
			$birthdate = $this->request->data['User']['birthdate'];
			$hubby = $this->request->data['User']['hubby'];

			$input_img = $this->request->data['User']['image'];
			$file_type = $input_img['type'];



			if(in_array($file_type, ['image/jpeg','image/jpg','image/png'])) {

			
				$file = basename($input_img['name']);
				$filename = time().'_'.$file;
				$upload_path = WWW_ROOT. 'img/users' . DS . $filename;


				if(move_uploaded_file($input_img['tmp_name'], $upload_path)) {

					$data = array(
						'id' => $userid,
						'photo' => $filename,
						'name' => $name,
						'birthday' => $birthdate,
						'hubby' => $hubby
						'modified_ip' => $this->request->clientIp()
					);

					if($this->User->save($data)) {
						$response = array('alert' => 'error', 'message' => 'User successfully updated');
					} 
				} else return 0;

			} else $response = array('alert' => 'error', 'message' => 'Invalid photo extension');

			return json_encode($response);

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

}
