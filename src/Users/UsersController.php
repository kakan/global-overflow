<?php
namespace Anax\Users;
/**
 * A controller for users and admin related events.
 */
class UsersController implements \Anax\DI\IInjectionAware {
	use \Anax\DI\TInjectable, \Anax\MVC\TRedirectHelpers;
	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
	}
	public function indexAction() {
		$this->di->theme->setTitle('Users');
		$all = $this->users->findAll();
		$this->views->add('users/view-all', array(
			'users' => $all,
			'header' => "Users"
		));
	}
	/**
	 * List user with id.
	 *
	 * @param int $id of user to display
	 *
	 * @return void
	 */
	public function idAction($id = null) {
		$user = $this->users->find($id);
		if ($user == null) {
			$this->redirectTo('users');
		}
		$tools = false;
		if ($this->di->session->get('user') != null) {
			if ($id == $this->di->session->get('user')[0]->id) {
				$tools = true;
			}
		}
		$questions       = $this->di->dispatcher->forward(array(
			'controller' => 'questions',
			'action' => 'getuserquestions',
			'params' => array(
				$id
			)
		));
		$user->questions = $questions;
		$answers         = $this->di->dispatcher->forward(array(
			'controller' => 'questions',
			'action' => 'getuseranswers',
			'params' => array(
				$id
			)
		));
		$user->answers   = $answers;
		$this->theme->setTitle("User " . $user->username);
		$this->views->add('users/view', array(
			'user' => $user,
			'tools' => $tools,
			'header' => $user->username
		));
	}
	public function mostactiveAction() {
		$users = $this->di->dispatcher->forward(array(
			'controller' => 'questions',
			'action' => 'mostactive'
		));
		if ($users == null) {
			return $users;
		}
		foreach ($users as $user) {
			$data             = $this->users->query()->where('id = ?')->execute(array(
				$user->userId
			));
			$user->username   = $data[0]->username;
			$user->registered = $data[0]->registered;
			$user->email      = $data[0]->email;
		}
		return $users;
	}
	public function registerAction() {
		$this->di->theme->setTitle('Registration');
		if ($this->di->session->get('user') != null) {
			$this->redirectTo('logout');
		}
		$form = $this->form->create(array(), array(
			'username' => array(
				'type' => 'text',
				'label' => 'Username:',
				'value' => isset($_SESSION['form-save']['username']['value']) ? $_SESSION['form-save']['username']['value'] : null
			),
			'email' => array(
				'type' => 'email',
				'label' => 'Email:',
				'value' => isset($_SESSION['form-save']['email']['value']) ? $_SESSION['form-save']['email']['value'] : null
			),
			'password' => array(
				'type' => 'password',
				'label' => 'Password:',
				'value' => isset($_SESSION['form-save']['password']['value']) ? $_SESSION['form-save']['password']['value'] : null
			),
			'registrera' => array(
				'type' => 'submit',
				'callback' => function($form) {
					$form->saveInSession = true;
					return true;
				}
			)
		));
		$this->views->add('users/form', array(
			'header' => 'Registration',
			'main' => $form->getHTML()
		));
		$status = $form->check();
		if ($status === true) {
			if ($_SESSION['form-save']['username']['value'] == '') {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username required.'
				));
				$this->redirectTo();
			} else if (5 > strlen($_SESSION['form-save']['username']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username needs to be atleast 5 characters long.'
				));
				$this->redirectTo();
			} else if (20 < strlen($_SESSION['form-save']['username']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username cannot be longer than 20 characters.'
				));
				$this->redirectTo();
			} else if ($this->users->findBy('username', $_SESSION['form-save']['username']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username already exists.'
				));
				$this->redirectTo();
			}
			if ($_SESSION['form-save']['email']['value'] == '') {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Email required.'
				));
				$this->redirectTo();
			} else if (5 > strlen($_SESSION['form-save']['email']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Email needs to be longer than 5 characters.'
				));
				$this->redirectTo();
			} else if (40 < strlen($_SESSION['form-save']['email']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Email cannot be longer than 40 characters.'
				));
				$this->redirectTo();
			}
			if ($_SESSION['form-save']['password']['value'] == '') {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Password required.'
				));
				$this->redirectTo();
			} else if (5 > strlen($_SESSION['form-save']['password']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Password needs to be atleast 5 characters.'
				));
				$this->redirectTo();
			} else if (30 < strlen($_SESSION['form-save']['password']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Password cannot be longer than 30 characters.'
				));
				$this->redirectTo();
			}
			$res = $this->users->save(array(
				'username' => $_SESSION['form-save']['username']['value'],
				'email' => $_SESSION['form-save']['email']['value'],
				'password' => md5(strtolower(trim($_SESSION['form-save']['password']['value']))),
				'registered' => date("Y-m-d H:i:s")
			));
			if ($res) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'You are now signed up, Sign in to start using our site.'
				));
				unset($_SESSION['form-save']);
			}
			$this->redirectTo('');
		} else if ($status === false) {
			$this->di->informer->setMessage(array(
				'type' => 'error',
				'message' => 'Something went wrong, try again.'
			));
			$this->redirectTo();
		}
	}
	public function loginAction() {
		$this->di->theme->setTitle('Login');
		if ($this->di->session->get('user') != null) {
			$this->redirectTo('logout');
		}
		$form   = $this->form->create(array(), array(
			'username' => array(
				'type' => 'text',
				'label' => 'Username:'
			),
			'password' => array(
				'type' => 'password',
				'label' => 'Password:'
			),
			'login' => array(
				'type' => 'submit',
				'callback' => function($form) {
					$form->saveInSession = true;
					return true;
				}
			)
		));
		$status = $form->check();
		if ($status === true) {
			$user = $this->users->query()->where('username = ?')->andWhere('password = ?')->execute(array(
				$_SESSION['form-save']['username']['value'],
				md5(strtolower(trim($_SESSION['form-save']['password']['value'])))
			));
			if ($user == null) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username or Password is incorrect.'
				));
				$this->redirectTo();
			}
			$this->di->session->set('user', $user);
			unset($_SESSION['form-save']);
			$this->redirectTo('');
		} else if ($status === false) {
			$this->di->informer->setMessage(array(
				'type' => 'error',
				'message' => 'Something went wrong, try again later.'
			));
			$this->redirectTo();
		}
		$this->views->add('users/form', array(
			'header' => 'Login',
			'main' => $form->getHTML()
		));
	}
	public function logoutAction() {
		if ($this->di->session->get('user') != null) {
			$this->di->session->set('user', null);
		}
		$this->redirectTo('');
	}
	public function selfAction() {
		if ($this->di->session->get('user') != null) {
			$user = $this->di->session->get('user');
			$this->redirectTo('users/id/' . $user[0]->id);
		}
		$this->redirectTo('users');
	}
	/**
	 * Account details.
	 *
	 * @return void.
	 */
	public function editAction() {
		if ($this->di->session->get('user') == null) {
			$this->redirectTo('');
		}
		$this->di->theme->setTitle('Modify');
		$user = $this->di->session->get('user');
		$form   = $this->form->create(array(), array(
			'username' => array(
				'type' => 'text',
				'label' => 'username:',
				'value' => isset($_SESSION['form-save']['username']['value']) ? $_SESSION['form-save']['username']['value'] : $user[0]->username
			),
			'email' => array(
				'type' => 'email',
				'label' => 'email:',
				'value' => isset($_SESSION['form-save']['email']['value']) ? $_SESSION['form-save']['email']['value'] : $user[0]->email
			),
			'spara' => array(
				'type' => 'submit',
				'callback' => function($form) {
					$form->saveInSession = true;
					return true;
				}
			)
		));
	$status = $form->check();
		if ($status === true) {
			if ($_SESSION['form-save']['username']['value'] == '') {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username required.'
				));
				$this->redirectTo();
			} else if (5 > strlen($_SESSION['form-save']['username']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username needs to be atleast 5 characters long.'
				));
				$this->redirectTo();
			} else if (20 < strlen($_SESSION['form-save']['username']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username cannot be longer than 20 characters.'
				));
				$this->redirectTo();
			} else if ($this->users->findBy('username', $_SESSION['form-save']['username']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Username already exists.'
				));
				$this->redirectTo();
			}
			if ($_SESSION['form-save']['email']['value'] == '') {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Email required.'
				));
				$this->redirectTo();
			} else if (5 > strlen($_SESSION['form-save']['email']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Email needs to be longer than 5 characters.'
				));
				$this->redirectTo();
			} else if (40 < strlen($_SESSION['form-save']['email']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Email cannot be longer than 40 characters.'
				));
				$this->redirectTo();
			}
			$data['username'] = $_SESSION['form-save']['username']['value'];
			$data['email']    = $_SESSION['form-save']['email']['value'];
			$data['id']       = $user[0]->id;
			$res              = $this->users->save($data);
			if ($res) {
				$this->di->informer->setMessage(array(
					'type' => 'success',
					'message' => 'User settings modified.'
				));
				unset($_SESSION['form-save']);
				unset($_SESSION['user']);
				$newUser = $this->users->query()->where('id = ?')->execute(array(
					$user[0]->id
				));
				$this->di->session->set('user', $newUser);
				$this->redirectTo();
			}
		} else if ($status === false) {
			$this->di->informer->setMessage(array(
				'type' => 'error',
				'message' => 'Something went wrong, try again!'
			));
			$this->redirectTo();
		}
		$this->views->add('users/form', array(
			'header' => "Modifiering",
			'userId' => $user[0]->id,
			'main' => $form->getHTML()
		));
	}
	public function passwordAction() {
		if ($this->di->session->get('user') == null) {
			$this->redirectTo('');
		}
		$this->di->theme->setTitle('Password');
		$user = $this->di->session->get('user');
		$form   = $this->form->create(array(), array(
			'passwordOld' => array(
				'type' => 'password',
				'label' => 'Your old Password:',
				'value' => isset($_SESSION['form-save']['passwordOld']['value']) ? $_SESSION['form-save']['passwordOld']['value'] : null
			),
			'password' => array(
				'type' => 'password',
				'label' => 'New Password:',
				'value' => isset($_SESSION['form-save']['password']['value']) ? $_SESSION['form-save']['password']['value'] : null
			),
			'save' => array(
				'type' => 'submit',
				'callback' => function($form) {
					$form->saveInSession = true;
					return true;
				}
			)
		));
		$status = $form->check();
		if ($status === true) {
			$res = $this->users->query()->where('id = ?')->andWhere('password = ?')->execute(array(
				$user[0]->id,
				md5(strtolower(trim($_SESSION['form-save']['passwordOld']['value'])))
			));
			if ($res == null) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Wrong Password.'
				));
				$this->redirectTo();
			} else if ($_SESSION['form-save']['passwordOld']['value'] == '') {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Old Password Required.'
				));
				$this->redirectTo();
			} else if (5 > strlen($_SESSION['form-save']['passwordOld']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Old Password needs to be atleast 5 characters.'
				));
				$this->redirectTo();
			} else if (30 < strlen($_SESSION['form-save']['passwordOld']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Old password cannot be longer than 30 characters.'
				));
				$this->redirectTo();
			} else if ($_SESSION['form-save']['password']['value'] == '') {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'New Password required.'
				));
				$this->redirectTo();
			} else if (5 > strlen($_SESSION['form-save']['password']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'New password needs to be atleast 5 character long '
				));
				$this->redirectTo();
			} else if (30 < strlen($_SESSION['form-save']['password']['value'])) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'New password cannot be longer than 30 characters.'
				));
				$this->redirectTo();
			}
			$data['password'] = md5(strtolower(trim($_SESSION['form-save']['password']['value'])));
			$data['id']       = $user[0]->id;
			$res              = $this->users->save($data);
			if ($res) {
				$this->di->informer->setMessage(array(
					'type' => 'success',
					'message' => 'Password updated you can now use your new password to login.'
				));
				unset($_SESSION['form-save']);
				$this->redirectTo('users/account');
			}
		} else if ($status === false) {
			$this->di->informer->setMessage(array(
				'type' => 'error',
				'message' => 'Something went wrong, try again.'
			));
			$this->redirectTo();
		}
		$this->views->add('users/form', array(
			'header' => "Password",
			'userId' => $user[0]->id,
			'main' => $form->getHTML()
		));
	}
}
?>