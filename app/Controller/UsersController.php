<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public function beforeFilter() {
    parent::beforeFilter();
    // Allow users to register and logout.
    $this->Auth->allow('add', 'logout','app_userregistration','app_userlogin','email','confirm1','profile_mode','app_userforgotpwd','resetpass','forgetpwd');
}

	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
        public function email() {
        $this->layout = 'ajax';
        $l = new CakeEmail('smtp');
        $l->config('smtp')->emailFormat('html')->template('default', 'default')->subject('invitation')->to("pranabesh@avainfotech.com")->send('sdagdf');
        $this->set('smtp_errors', "none");
        $response['error'] = '0';
        $response['msg'] = 'Success';
        $this->set('response', $response);
        $this->render('ajax');
    }
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
                            $id = base64_encode($this->User->getLastInsertID());
                               $url = FULL_BASE_URL . $this->webroot . 'Users/confirm1/' . $id;
                            $message = "You have successfully created your account. 
                                    Please click on the following link to activate your account.<br><a href=" . $url . ">Click here to verify your email</a>";
                            $l = new CakeEmail('smtp');
                            $l->config('smtp')->emailFormat('html')->template('default', 'default')->subject('Active link')->to($this->request->data['User']['email'])->send($message);
                            $this->set('smtp_errors', "none");
                           /* $response['error'] = '1';
                            $response['msg'] = 'Register successfully';
                            $this->set('response', $response);*/
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}
         public function confirm1($id = null) {
        $this->layout = "ajax";
        $i = base64_decode($id);
        $this->User->id = $i;
        $check = $this->User->find('first', array('conditions' => array('User.id' => $i), 'fields' => array('status')));
        if ($check['User']['status'] == '1') {
//                $response="Account already verified !!!";
            $this->Session->setFlash(__('Already verified'));
            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
        } else {
            $this->request->data['User']['status'] = 1;
            if ($this->User->save($this->request->data)) {
//                    $response="Account verify Successfully!!! !!!";
                $this->Session->setFlash(__('Verified account successfully'));
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        }
//            $this->set('response',$response);
//            $this->render('ajax'); 
    }
        public function app_userregistration(){
            configure::write('debug',0);
            $this->layout = 'ajax';
//            ob_start();
//            var_dump($this->request->data);
//            $c = ob_get_clean();
//            $fc = fopen('files' . DS . 'detail.txt', 'w');
//            fwrite($fc, $c);
//            fclose($fc);
            
            if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) { 
//                            debug($this->request->data);
                              $id = base64_encode($this->User->getLastInsertID());
                               $url = FULL_BASE_URL . $this->webroot . 'Users/confirm1/' . $id;
                            $message = "You have successfully created your account. 
                                    Please click on the following link to activate your account.<br><a href=" . $url . ">Click here to verify your email</a>";
                            $l = new CakeEmail('smtp');
                            $l->config('smtp')->emailFormat('html')->template('default', 'default')->subject('Active link')->to($this->request->data['User']['email'])->send($message);
                            $this->set('smtp_errors', "none");
                            $response['error'] = '1';
                            $response['msg'] = 'Register successfully';
                            $response['id']=$this->User->getLastInsertID();
                            $response['username']=$this->request->data['User']['username'];
                            $response['longitude']=$this->request->data['User']['longitude'];
                            $response['latitude']=$this->request->data['User']['latitude'];
                            $response['status']=$this->request->data['User']['status'];
                             $this->set('response', $response);
			} else {
				$response['error'] = '0';
                                $response['msg'] = 'Sorry please try again';
                                $this->set('response', $response);
			}
		}else {
				$response['error'] = '0';
                                $response['msg'] = 'No Input found';
                                $this->set('response', $response);
			}
                $this->render('ajax');
        }
public function app_userlogin(){
     $this->layout = 'ajax';
            ob_start();
            var_dump($this->request->data);
            $c = ob_get_clean();
            $fc = fopen('files' . DS . 'detail.txt', 'w');
            fwrite($fc, $c);
            fclose($fc);
     if ($this->request->is('post')) {
        if ($this->Auth->login()) {
                 $response['error'] = '1';
                 $response['msg'] = 'Successfully Logged in';
                 $response['id'] = $this->Auth->User('id');
                 $this->set('response', $response);
	     } else {
                    $response['error'] = '0';
                    $response['msg'] = 'Sorry please try again';
                    $this->set('response', $response);
                     }
        }else {
                $response['error'] = '0';
                $response['msg'] = 'No Input found';
                $this->set('response', $response);
        }
  $this->render('ajax');
    
}        

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	public function login() {
    if ($this->request->is('post')) {
        if ($this->Auth->login()) {
             $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('Invalid username or password, try again'));
    }
}

public function logout() {
    return $this->redirect($this->Auth->logout());
}
public function admin_login() {
 if ($this->request->is('post')) {
        if ($this->Auth->login()) {
             $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('Invalid username or password, try again'));
    }

	}
public function admin_logout() {
    return $this->redirect($this->Auth->logout());
}
public function profile_mode() {
//    debug($this->request->data);
//    exit;
//    $this->loadModel('Profilemode');
//    if($this->request->is('post')){
//        
//    }

	}
        
    public function app_userforgotpwd() {
        configure::write('debug',0);
        $this->layout = 'ajax';
        $this->User->recursive = -1;
//        $this->request->data['User']['email']="ajay_p@avainfotech.com";
        if (!empty($this->data)) {
            if (empty($this->data['User']['email'])) {
                $this->Session->setFlash('Please Provide Your Email Address that You used to Register with Us');
            } else {
                $email = $this->request->data['User']['email'];
                $fu = $this->User->find('first', array('conditions' => array('User.email' => $email)));
                if ($fu) {
                    if ($fu['User']['status'] == '1') {
                        $key = Security::hash(String::uuid(), 'sha512', true);
                        $hash = sha1($fu['User']['username'] . rand(0, 100));
                        $url = Router::url(array('controller' => 'Users', 'action' => 'resetpass'), true) . '/' . $key . '#' . $hash;
                        $ms = "<p>You are receiving this email as you have requested a change of password
                                                    <br/> If you have not requested this change please ignore this email.
                                                    Click the link below to reset your password...</p><p style='width:100%;'> 
                                                    <a href=" . $url . " style='text-decoration:none'><b>Click me to reset your password.</b></a></p>";
                        $fu['User']['tokenhash'] = $key;
                        $this->User->id = $fu['User']['id'];
                        if ($this->User->saveField('tokenhash', $fu['User']['tokenhash'])) {
                            $l = new CakeEmail('smtp');
                            $l->emailFormat('html')->template('default', 'default')->subject('Reset Your Password')->to($fu['User']['email'])->send($ms);
                            $this->set('smtp_errors', "none");
                            $response['msg'] = 'Check Your Email To Reset your password';
                            $response['error'] = '1';
                            $this->set('response', $response);
                        } else {
                            $response['msg'] = 'Error Generating Reset link';
                            $response['error'] = '0';
                            $this->set('response', $response);
                        }
                    } else {
                        $response['error'] = '0';
                        $response['msg'] = 'This Account is not Active yet.Check Your mail to activate it';
                        $this->set('response', $response);
                    }
                } else {
                    $response['error'] = '0';
                    $response['msg'] = 'Email does Not Exist';
                    $this->set('response', $response);
                }
            }
        }
        $this->render('ajax');
    }
  public function resetpass($token = null) {
        $this->User->recursive = -1;
        if (!empty($token)) {
            $u = $this->User->findBytokenhash($token);
            if ($u) {
                $this->User->id = $u['User']['id'];
                if (!empty($this->data)) {
                    if ($this->data['User']['password'] != $this->data['User']['password_confirm']) {
                        $this->Session->setFlash("Both the passwords are not matching...");
                        return;
                    }
                    $this->User->data = $this->data;
                    $this->User->data['User']['email'] = $u['User']['email'];
                    $new_hash = sha1($u['User']['email'] . rand(0, 100)); //created token
                    $this->User->data['User']['tokenhash'] = $new_hash;
                    if ($this->User->validates(array('fieldList' => array('password', 'password_confirm')))) {
                        if ($this->User->save($this->User->data)) {
                            $this->Session->setFlash('Password Has been Updated');
                            $this->redirect("/");
                        }
                    } else {
                        $this->set('errors', $this->User->invalidFields());
                    }
                }
            } else {
                $this->Session->setFlash('Token Corrupted, Please Retry.the reset link <a style="cursor: pointer; color: rgb(0, 102, 0); text-decoration: none; background: url("http://files.adbrite.com/mb/images/green-double-underline-006600.gif") repeat-x scroll center bottom transparent; margin-bottom: -2px; padding-bottom: 2px;" name="AdBriteInlineAd_work" id="AdBriteInlineAd_work" target="_top">work</a> only for once.');
            }
        } else {
            $this->Session->setFlash('Pls try again...');
            $this->redirect(array('/'));
        }
    }
        public function forgetpwd() {
        $this->User->recursive = -1;

        if (!empty($this->data)) {
            if (empty($this->data['User']['email'])) {
                $this->Session->setFlash('Please Provide Your Email Address that You used to Register with Us');
            } else {
                $email = $this->request->data['User']['email'];
                $fu = $this->User->find('first', array('conditions' => array('User.email' => $email)));
                if ($fu) {
                    if ($fu['User']['status'] == "1") {
                        $key = Security::hash(String::uuid(), 'sha512', true);
                        $hash = sha1($fu['User']['email'] . rand(0, 100));
                        $url = Router::url(array('controller' => 'users', 'action' => 'resetpass'), true) . '/' . $key . '#' . $hash;
                        $ms = "<p>You are receiving this email as you have requested a change of password
                                                    <br/> If you have not requested this change please ignore this email.
                                                    Click the link below to reset your password...</p><p style='width:100%;'> 
                                                    <a href=" . $url . " style='text-decoration:none'><b>Click me to reset your password.</b></a></p>";
                        $fu['User']['tokenhash'] = $key;
                        $this->User->id = $fu['User']['id'];
                        if ($this->User->saveField('tokenhash', $fu['User']['tokenhash'])) {
                            $l = new CakeEmail('smtp');
                            $l->emailFormat('html')->template('default', 'default')->subject('Reset Your Password')->to($fu['User']['email'])->send($ms);
                            $this->set('smtp_errors', "none");
                            $this->Session->setFlash(__('Check Your Email To Reset your password', true));
                            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
                        } else {
                            $this->Session->setFlash("Error Generating Reset link");
                        }
                    } else {
                        $this->Session->setFlash('This Account is Blocked. Please Contact to Administrator...');
                    }
                } else {
                    $this->Session->setFlash('Email does Not Exist');
                }
            }
        }
    }

}
