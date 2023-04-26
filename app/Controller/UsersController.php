<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    //Allow page to view w/ out login
    public function beforeFilter() {
        $this->Auth->allow('create');
    }
    
	public function login() {


		if($this->request->is('post')) {

			$email = $this->request->data['User']['email'];
			$password = $this->request->data['User']['password'];

			$conditions = array(
				'email' => $email,
				'password' => AuthComponent::password($password),
			);

			$data = $this->User->find('first',array('conditions' => $conditions));

			//var_dump($data['User']['email']);

			if($data) {

				$this->Auth->login($data);
				return $this->redirect($this->Auth->redirectUrl());

			} else $this->Session->setFlash('Invalid Username or Password', 'default', array('class' => 'alert alert-danger'));

			// if($this->Auth->user('id')){ 
			// redirect to index
			// }
		}

		// if($this->request->is('post')) {

		// 	$this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);

		// 	if($this->Auth->login($this->request->data)) {
		// 		return $this->redirect($this->Auth->redirectUrl());
		// 	} else {
		// 		$this->Session->setFlash('Invalid Username or Password', 'default', array('class' => 'alert alert-danger'));
		// 	}
		// }

	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}


	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

	public function create() {
		if ($this->request->is('post')) {

			$this->User->create();
			$this->request->data['User']['password'] =  AuthComponent::password($this->request->data['User']['password']);


			if ($this->User->save($this->request->data)) {

				$this->Session->setFlash('The Users has been created', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'create'));

			} else $this->set('errors', $this->Contact->validationErrors);
		}
	}

	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}

	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
