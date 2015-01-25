<?php
namespace Anax\Questions;
/**
 * A controller for users and admin related events.
 */
class QuestionsController implements \Anax\DI\IInjectionAware {
	use \Anax\DI\TInjectable, \Anax\MVC\TRedirectHelpers;
	/**
	 * Initialize the controller.
	 *
	 * @return void.
	 */
	public function initialize() {
		$this->questions = new \Anax\Questions\Question();
		$this->questions->setDI($this->di);
	}
	public function indexAction() {
		$this->di->theme->setTitle('Questions');
		$questions = $this->questions->query('*, csgo_question.id AS questionId')->join('user', 'csgo_question.userID=csgo_user.id')->where('type = ?')->orderBy('csgo_question.id DESC')->execute(array(
			'q'
		));
		$tags      = $this->di->dispatcher->forward(array(
			'controller' => 'tags',
			'action' => 'getalltags',
			'params' => array()
		));
		foreach ($questions as $question) {
			$tagArray = array();
			foreach ($tags as $tag) {
				if ($tag->postId == $question->questionId) {
					$tagArray[] = $tag;
				}
			}
			$question->tags    = $tagArray;
			$res               = $this->questions->query('COUNT(*) AS answerCount')->where('belongTo = ?')->andWhere('type = ?')->execute(array(
				$question->belongTo,
				'a'
			));
			$question->answers = $res[0]->answerCount;
		}
		$this->views->add('questions/view-all', array(
			'questions' => $questions,
			'header' => "Questions"
		));
	}
	public function latestAction() {
		$questions = $this->questions->query('*, csgo_question.id AS questionId')->join('user', 'csgo_question.userID=csgo_user.id')->where('type = ?')->orderBy('csgo_question.posted DESC')->limit(3)->execute(array(
			'q'
		));
		$tags      = $this->di->dispatcher->forward(array(
			'controller' => 'tags',
			'action' => 'getalltags',
			'params' => array()
		));
		foreach ($questions as $question) {
			$tagArray = array();
			foreach ($tags as $tag) {
				if ($tag->postId == $question->questionId) {
					$tagArray[] = $tag;
				}
			}
			$question->tags    = $tagArray;
			$res               = $this->questions->query('COUNT(*) AS answerCount')->where('belongTo = ?')->andWhere('type = ?')->execute(array(
				$question->belongTo,
				'a'
			));
			$question->answers = $res[0]->answerCount;
		}
		return $questions;
	}
	public function mostactiveAction() {
		$users = $this->questions->query('userId, COUNT(*) AS postCount')->groupBy('userId')->orderBy('postCount DESC')->limit(4)->execute();
		return $users;
	}
	/**
	 * Show questions with specific id.
	 *
	 * @param int $id of user to display.
	 *
	 * @return void.
	 */
	public function idAction($id = null) {
		$questions = $this->questions->query('*, csgo_question.id AS questionId')->join('user', 'csgo_question.userId=csgo_user.id')->where('belongTo = ?')->andWhere('type <> ?')->orderBy('csgo_question.id ASC')->execute(array(
			$id,
			'c'
		));
		if ($questions == null) {
			$this->redirectTo('questions');
		}
		$this->theme->setTitle($questions[0]->title);
		$tools = false;
		if ($this->di->session->get('user') != null) {
			if ($questions[0]->userId == $this->di->session->get('user') [0] ->id) {
				$tools = true;
			}
		}
		$comments = $this->questions->query('*, csgo_question.id AS questionId')->join('user', 'csgo_question.userId=csgo_user.id')->where('belongTo = ?')->andWhere('type = ?')->execute(array(
			$id,
			'c'
		));
		foreach ($questions as $question) {
			$commentArray = array();
			foreach ($comments as $comment) {
				if ($comment->commentTo == $question->questionId) {
					$commentArray[] = $comment;
				}
			}
			$question->comments = $commentArray;
		}
		$counter = 0;
		foreach ($questions as $question) {
			if ($question->type == 'a') {
				$counter++;
			}
		}
		if ($counter == 1) {
			$answerCount = $counter . " Answer";
		} else {
			$answerCount = $counter . " Answers";
		}
		$tags = $this->di->dispatcher->forward(array(
			'controller' => 'tags',
			'action' => 'getalltags',
			'params' => array(
				$id
			)
		));
		if ($this->di->session->get('user') != null) {
			$form   = $this->form->create(array(), array(
				'belongTo' => array(
					'type' => 'hidden',
					'value' => $id
				),
				'content' => array(
					'type' => 'textarea',
					'label' => '<b>Your Answer</b>',
					'required' => true,
					'validation' => array(
						'not_empty'
					),
					'value' => isset($_SESSION['form-save']['content']['value']) ? $_SESSION['form-save']['content']['value'] : null
				),
				'post' => array(
					'type' => 'submit',
					'class' => 'button success-button',
					'value' => 'Post Your Answer',
					'callback' => function($form) {
						$form->saveInSession = true;
						return true;
					}
				),
				'reset' => array(
					'type' => 'reset',
					'class' => 'button default-button'
				)
			));
			// Check the status of the form
			$status = $form->check();
			if ($status === true) {
				if ($_SESSION['form-save']['belongTo']['value'] != $id) {
					$this->di->informer->setMessage(array(
						'type' => 'warning',
						'message' => 'Warning! You do not have permission to do that.'
					));
					unset($_SESSION['form-save']);
					$this->redirectTo('questions');
				}
				$now = date("Y-m-d H:i:s");
				$this->questions->save(array(
					'userId' => $this->di->session->get('user') [0] ->id,
					'type' => 'a',
					'content' => $_SESSION['form-save']['content']['value'],
					'posted' => $now
				));
				$lastId = $this->questions->lastInsertId();
				$this->questions->save(array(
					'id' => $lastId,
					'belongTo' => $id
				));
				$this->di->informer->setMessage(array(
					'type' => 'success',
					'message' => 'Success! Your answer has been posted.'
				));
				unset($_SESSION['form-save']);
				$this->redirectTo('questions/id/' . $id);
			} else if ($status === false) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'Something went wrong. Please try again.'
				));
				$this->redirectTo();
			}
			$this->views->add('questions/view', array(
				'questionId' => $id,
				'questions' => $questions,
				'answerCount' => $answerCount,
				'tags' => $tags,
				'tools' => $tools,
				'header' => $questions[0]->title,
				'form' => $form->getHTML()
			));
		} else {
			$this->views->add('questions/view', array(
				'questionId' => $id,
				'questions' => $questions,
				'answerCount' => $answerCount,
				'tags' => $tags,
				'tools' => $tools,
				'header' => $questions[0]->title
			));
		}
	}
	public function getuserquestionsAction($id = null) {
		if ($id == null) {
			$this->redirectTo('users');
		}
		$questions = $this->questions->query()->where('userId = ?')->andWhere('type = ?')->execute(array(
			$id,
			'q'
		));
		if ($questions == null) {
			return null;
		}
		foreach ($questions as $question) {
			$res               = $this->questions->query('COUNT(*) AS answerCount')->where('belongTo = ?')->andWhere('type = ?')->execute(array(
				$question->belongTo,
				'a'
			));
			$question->answers = $res[0]->answerCount;
		}
		return $questions;
	}
	public function getuseranswersAction($id = null) {
		if ($id == null) {
			$this->redirectTo('users');
		}
		$answers = $this->questions->query()->where('userId = ?')->andWhere('type = ?')->execute(array(
			$id,
			'a'
		));
		if ($answers == null) {
			return null;
		}
		foreach ($answers as $answer) {
			$res           = $this->questions->query('title')->where('belongTo = ?')->andWhere('type = ?')->execute(array(
				$answer->belongTo,
				'q'
			));
			$answer->title = $res[0]->title;
		}
		foreach ($answers as $answer) {
			$res             = $this->questions->query('COUNT(*) AS answerCount')->where('belongTo = ?')->andWhere('type = ?')->execute(array(
				$answer->belongTo,
				'a'
			));
			$answer->answers = $res[0]->answerCount;
		}
		return $answers;
	}
	public function taggedAction($slug = null) {
		$questions = $this->questions->query('*, csgo_question.id AS questionId')->join('user', 'csgo_question.userID=csgo_user.id')->join('tag', 'csgo_question.id=csgo_tag.postId')->where('csgo_question.type = ?')->andWhere('csgo_tag.tag = ?')->orderBy('csgo_question.id DESC')->execute(array(
			'q',
			$slug
		));
		if ($questions == null) {
			$this->redirectTo('questions');
		}
		$this->di->theme->setTitle($slug);
		$tags = $this->di->dispatcher->forward(array(
			'controller' => 'tags',
			'action' => 'getalltags',
			'params' => array()
		));
		foreach ($questions as $question) {
			$tagArray = array();
			foreach ($tags as $tag) {
				if ($tag->postId == $question->questionId) {
					$tagArray[] = $tag;
				}
			}
			$question->tags    = $tagArray;
			$res               = $this->questions->query('COUNT(*) AS answerCount')->where('belongTo = ?')->andWhere('type = ?')->execute(array(
				$question->belongTo,
				'a'
			));
			$question->answers = $res[0]->answerCount;
		}
		$this->views->add('questions/view-all', array(
			'questions' => $questions,
			'header' => "Tagged Questions"
		));
	}
	public function commentAction($id = null) {
		$this->theme->setTitle('Add a Comment');
		if ($this->di->session->get('user') == null) {
			$this->redirectTo('signin');
		}
		$questions = $this->questions->query()->where('id = ?')->andWhere('type <> ?')->execute(array(
			$id,
			'c'
		));
		if ($questions == null) {
			$this->redirectTo('questions');
		}
		$form   = $this->form->create(array(), array(
			'belongTo' => array(
				'type' => 'hidden',
				'value' => $questions[0]->belongTo
			),
			'commentTo' => array(
				'type' => 'hidden',
				'value' => $id
			),
			'content' => array(
				'type' => 'textarea',
				'label' => '<b>Your Comment</b>',
				'required' => true,
				'validation' => array(
					'not_empty'
				),
				'value' => isset($_SESSION['form-save']['content']['value']) ? $_SESSION['form-save']['content']['value'] : null
			),
			'post' => array(
				'type' => 'submit',
				'class' => 'button success-button',
				'value' => 'Post Your Comment',
				'callback' => function($form) {
					$form->saveInSession = true;
					return true;
				}
			),
			'reset' => array(
				'type' => 'reset',
				'class' => 'button default-button'
			)
		));
		// Check the status of the form
		$status = $form->check();
		if ($status === true) {
			if ($_SESSION['form-save']['belongTo']['value'] != $questions[0]->belongTo || $_SESSION['form-save']['commentTo']['value'] != $id) {
				$this->di->informer->setMessage(array(
					'type' => 'warning',
					'message' => 'Warning! This action is not allowed.'
				));
				unset($_SESSION['form-save']);
				$this->redirectTo('questions/id/' . $questions[0]->belongTo);
			}
			$now = date("Y-m-d H:i:s");
			$this->questions->save(array(
				'userId' => $this->di->session->get('user') [0] ->id,
				'belongTo' => $_SESSION['form-save']['belongTo']['value'],
				'commentTo' => $_SESSION['form-save']['commentTo']['value'],
				'type' => 'c',
				'content' => $_SESSION['form-save']['content']['value'],
				'posted' => $now
			));
			$this->di->informer->setMessage(array(
				'type' => 'success',
				'message' => 'Success! Your comment has been posted.'
			));
			unset($_SESSION['form-save']);
			$this->redirectTo('questions/id/' . $questions[0]->belongTo);
		} else if ($status === false) {
			$this->di->informer->setMessage(array(
				'type' => 'error',
				'message' => 'Something went wrong. Please try again.'
			));
			$this->redirectTo();
		}
		// Prepare the page content
		$this->views->add('questions/form', array(
			'main' => $form->getHTML()
		));
	}
	public function askAction() {
		$this->theme->setTitle('Ask a Question');
		if ($this->di->session->get('user') == null) {
			$this->redirectTo('login');
		}
		$form   = $this->form->create(array(), array(
			'title' => array(
				'type' => 'text',
				'label' => '<b>Title</b>',
				'required' => true,
				'autofocus' => true,
				'validation' => array(
					'not_empty'
				),
				'value' => isset($_SESSION['form-save']['title']['value']) ? $_SESSION['form-save']['title']['value'] : null
			),
			'tags' => array(
				'type' => 'text',
				'label' => '<b>Tags</b>',
				'placeholder' => 'At least one tag, separated with commas, a maximum of five tags allowed',
				'required' => true,
				'validation' => array(
					'not_empty'
				),
				'value' => isset($_SESSION['form-save']['tags']['value']) ? $_SESSION['form-save']['tags']['value'] : null
			),
			'content' => array(
				'type' => 'textarea',
				'label' => '<b>Your Question</b>',
				'required' => true,
				'validation' => array(
					'not_empty'
				),
				'value' => isset($_SESSION['form-save']['content']['value']) ? $_SESSION['form-save']['content']['value'] : null
			),
			'post' => array(
				'type' => 'submit',
				'class' => 'button success-button',
				'value' => 'Post Your Question',
				'callback' => function($form) {
					$form->saveInSession = true;
					return true;
				}
			),
			'reset' => array(
				'type' => 'reset',
				'class' => 'button default-button'
			)
		));
		// Check the status of the form
		$status = $form->check();
		if ($status === true) {
			$now  = date("Y-m-d H:i:s");
			$tags = $this->di->dispatcher->forward(array(
				'controller' => 'tags',
				'action' => 'processtags',
				'params' => array(
					$_SESSION['form-save']['tags']['value']
				)
			));
			if (!$tags) {
				$this->di->informer->setMessage(array(
					'type' => 'error',
					'message' => 'We were unable to process your tag string.'
				));
				$this->redirectTo();
			}
			$this->questions->save(array(
				'userId' => $this->di->session->get('user') [0] ->id,
				'type' => 'q',
				'title' => $_SESSION['form-save']['title']['value'],
				'content' => $this->di->textFilter->doFilter($_SESSION['form-save']['content']['value'], 'shortcode, markdown'),
				'posted' => $now
			));
			$lastId = $this->questions->lastInsertId();
			$this->questions->save(array(
				'id' => $lastId,
				'belongTo' => $lastId
			));
			$this->di->dispatcher->forward(array(
				'controller' => 'tags',
				'action' => 'settags',
				'params' => array(
					$lastId,
					$this->di->session->get('user') [0] ->id,
					$tags
				)
			));
			$this->di->informer->setMessage(array(
				'type' => 'success',
				'message' => 'Success! Your question has been posted.'
			));
			unset($_SESSION['form-save']);
			$this->redirectTo('questions/id/' . $lastId);
		} else if ($status === false) {
			$this->di->informer->setMessage(array(
				'type' => 'error',
				'message' => 'Something went wrong. Please try again.'
			));
			$this->redirectTo();
		}
		// Prepare the page content
		$this->views->add('questions/form', array(
			'main' => $form->getHTML()
		));
	}
	public function editAction($id) {
		$this->theme->setTitle('Edit Content');
		if ($this->di->session->get('user') == null) {
			$this->redirectTo('');
		}
		$content = $this->questions->query('*, csgo_question.id AS questionId')->join('user', 'csgo_question.userId=csgo_user.id')->where('csgo_question.id = ?')->andWhere('userId = ?')->execute(array(
			$id,
			$this->di->session->get('user') [0] ->id
		));
		if ($content[0] == null) {
			$this->redirectTo('questions');
		}
		$type     = "Answer";
		$question = array();
		if ($content[0]->type == 'q') {
			$tags      = $this->di->dispatcher->forward(array(
				'controller' => 'tags',
				'action' => 'getalltags',
				'params' => array(
					$id
				)
			));
			$tagString = $this->di->dispatcher->forward(array(
				'controller' => 'tags',
				'action' => 'gettagstring',
				'params' => array(
					$tags
				)
			));
			$type      = "Question";
			$question  = array(
				'title' => array(
					'type' => 'text',
					'label' => '<b>Title</b>',
					'required' => true,
					'autofocus' => true,
					'validation' => array(
						'not_empty'
					),
					'value' => isset($_SESSION['form-save']['title']['value']) ? $_SESSION['form-save']['title']['value'] : $content[0]->title
				),
				'tags' => array(
					'type' => 'text',
					'label' => '<b>Tags</b>',
					'placeholder' => 'At least one tag, separated with commas, a maximum of five tags allowed',
					'required' => true,
					'validation' => array(
						'not_empty'
					),
					'value' => isset($_SESSION['form-save']['tags']['value']) ? $_SESSION['form-save']['tags']['value'] : $tagString
				)
			);
		}
		$elements = array(
			'content' => array(
				'type' => 'textarea',
				'label' => '<b>Your ' . $type . '</b>',
				'required' => true,
				'validation' => array(
					'not_empty'
				),
				'value' => isset($_SESSION['form-save']['content']['value']) ? $_SESSION['form-save']['content']['value'] : strip_tags($content[0]->content)
			),
			'post' => array(
				'type' => 'submit',
				'class' => 'button success-button',
				'value' => 'Save',
				'callback' => function($form) {
					$form->saveInSession = true;
					return true;
				}
			),
			'reset' => array(
				'type' => 'reset',
				'class' => 'button default-button'
			)
		);
		$editForm = array_merge($question, $elements);
		$form     = $this->form->create(array(), $editForm);
		// Check the status of the form
		$status   = $form->check();
		if ($status === true) {
			if ($content[0]->type == 'q') {
				$tags = $this->di->dispatcher->forward(array(
					'controller' => 'tags',
					'action' => 'processtags',
					'params' => array(
						$_SESSION['form-save']['tags']['value']
					)
				));
				if (!$tags) {
					$this->di->informer->setMessage(array(
						'type' => 'error',
						'message' => 'We were unable to process your tag string.'
					));
					$this->redirectTo();
				}
				$this->di->dispatcher->forward(array(
					'controller' => 'tags',
					'action' => 'replacetags',
					'params' => array(
						$id,
						$this->di->session->get('user') [0] ->id,
						$tags
					)
				));
			}
			$now        = date("Y-m-d H:i:s");
			$contentStr = $_SESSION['form-save']['content']['value'];
			if ($content[0]->type != 'c') {
				$contentStr = $this->di->textFilter->doFilter($_SESSION['form-save']['content']['value'], 'shortcode, markdown');
			}
			$this->questions->save(array(
				'id' => $id,
				'title' => isset($_SESSION['form-save']['title']['value']) ? $_SESSION['form-save']['title']['value'] : null,
				'content' => $contentStr,
				'modified' => $now
			));
			$this->di->informer->setMessage(array(
				'type' => 'success',
				'message' => 'Success! Your post has been modiefied.'
			));
			unset($_SESSION['form-save']);
			$this->redirectTo('questions/id/' . $content[0]->belongTo);
		} else if ($status === false) {
			$this->di->informer->setMessage(array(
				'type' => 'error',
				'message' => 'Something went wrong. Please try again.'
			));
			$this->redirectTo();
		}
		// Prepare the page content
		$this->views->add('questions/form', array(
			'main' => $form->getHTML()
		));
	}
	public function deleteAction($id) {
		// different outcomes for questions, answers, and comments
		if ($this->di->session->get('user') == null) {
			$this->redirectTo('');
		}
		$content = $this->questions->query()->join('user', 'csgo_question.userId=csgo_user.id')->where('csgo_question.id = ?')->andWhere('userId = ?')->execute(array(
			$id,
			$this->di->session->get('user') [0] ->id
		));
		if ($content == null) {
			$this->di->informer->setMessage(array(
				'type' => 'warning',
				'message' => 'Warning! You do not have permission to delete this post.'
			));
			$this->redirectTo('questions');
		}
		if ($content[0]->type == 'q') {
			$this->questions->deleteAll('belongTo', $id);
			$this->di->dispatcher->forward(array(
				'controller' => 'tags',
				'action' => 'deletetags',
				'params' => array(
					$id
				)
			));
			$this->di->informer->setMessage(array(
				'type' => 'success',
				'message' => 'Success! Your question and all related posts have been deleted.'
			));
			$this->redirectTo('questions');
		} else if ($content[0]->type == 'a') {
			$this->questions->deleteAll('id', $id);
			$this->questions->deleteAll('commentTo', $id);
			$this->di->informer->setMessage(array(
				'type' => 'success',
				'message' => 'Success! Your answer has been deleted.'
			));
			$this->redirectTo('questions/id/' . $content[0]->belongTo);
		} else if ($content[0]->type == 'c') {
			$this->questions->deleteAll('id', $id);
			$this->di->informer->setMessage(array(
				'type' => 'success',
				'message' => 'Success! Your comment has been deleted.'
			));
			$this->redirectTo('questions/id/' . $content[0]->belongTo);
		}
	}
	public function acceptAction($questionId = null, $authorId = null) {
		if ($questionId == null || $authorId == null) {
			//$this->redirectTo('questions');
		}
		if ($this->di->session->get('user') == null) {
			$this->redirectTo('login');
		}
		if ($authorId != $this->di->session->get('user') [0] ->id) {
			$this->di->informer->setMessage(array(
				'type' => 'warning',
				'message' => 'Warning! You do not have permission to do that.'
			));
			$this->redirectTo('questions');
		}
		$answer = $this->questions->query()->where('id = ?')->andWhere('type = ?')->execute(array(
			$questionId,
			'a'
		));
		if ($answer != null) {
			if ($answer[0]->accepted == 1) {
				$this->questions->save(array(
					'id' => $answer[0]->id,
					'accepted' => null
				));
				$this->di->informer->setMessage(array(
					'type' => 'success',
					'message' => 'Success! The confirmed answer has been revoked.'
				));
				$this->redirectTo('questions/id/' . $answer[0]->belongTo);
			} else {
				$questions = $this->questions->query()->where('belongTo = ?')->andWhere('type = ?')->execute(array(
					$answer[0]->belongTo,
					'a'
				));
				foreach ($questions as $question) {
					$this->questions->save(array(
						'id' => $question->id,
						'accepted' => null
					));
				}
				$this->questions->save(array(
					'id' => $answer[0]->id,
					'accepted' => 1
				));
				$this->di->informer->setMessage(array(
					'type' => 'success',
					'message' => 'Success! The answer has been accepted.'
				));
				$this->redirectTo('questions/id/' . $answer[0]->belongTo);
			}
		} else {
			$this->redirectTo('questions');
		}
	}
	
}
?>