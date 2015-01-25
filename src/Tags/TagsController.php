<?php
namespace Anax\Tags;
/**
 * A controller for users and admin related events.
 */
class TagsController implements \Anax\DI\IInjectionAware {
	use \Anax\DI\TInjectable, \Anax\MVC\TRedirectHelpers;
	/**
	 * Initialize the controller.
	 *
	 * @return void.
	 */
	public function initialize() {
		$this->tags = new \Anax\Tags\Tag();
		$this->tags->setDI($this->di);
	}
	public function indexAction() {
		$this->di->theme->setTitle('Tags');
		$all = $this->tags->query('tag, COUNT(*) AS count')->groupBy('tag')->orderBy('count DESC')->execute();
		$this->views->add('tags/view-all', array(
			'tags' => $all,
			'header' => "Tags"
		));
	}
	public function mostusedAction() {
		$tags = $this->tags->query('tag, COUNT(*) AS tagCount')->groupBy('tag')->orderBy('tagCount DESC')->limit(4)->execute();
		return $tags;
	}
	public function getalltagsAction($id = null) {
		if ($id != null) {
			$tags = $this->tags->query('tag')->groupBy('tag')->where('postId = ?')->orderBy('id ASC')->execute(array(
				$id
			));
		} else {
			$tags = $this->tags->query()->execute();
		}
		return $tags;
	}
	public function processtagsAction($string = null) {
		if ($string != null) {
			$string  = strtolower($string);
			$rawTags = explode(',', $string);
			$tags    = array();
			foreach ($rawTags as $tag) {
				$tag    = trim($tag);
				$tag    = str_replace(' ', '-', $tag);
				$tag    = substr($tag, 0, 30);
				$tags[] = $tag;
			}
			$tags = array_unique($tags);
			$tags = array_slice($tags, 0, 5);
			return $tags;
		}
		return false;
	}
	public function gettagstringAction($tags = null) {
		if ($tags != null) {
			$tagArray = array();
			foreach ($tags as $tag) {
				$tagArray[] = $tag->tag;
			}
			$tagString = implode(', ', $tagArray);
			return $tagString;
		}
		return false;
	}
	public function settagsAction($postId = null, $userId = null, $tags = null) {
		if ($postId != null && $userId != null && $tags != null) {
			foreach ($tags as $tag) {
				$this->tags->create(array(
					'postId' => $postId,
					'userId' => $userId,
					'tag' => $tag
				));
			}
			return true;
		}
		return false;
	}
	public function replacetagsAction($postId = null, $userId = null, $tags = null) {
		if ($postId != null && $userId != null && $tags != null) {
			$this->tags->deleteAll('postId', $postId);
			foreach ($tags as $tag) {
				$this->tags->create(array(
					'postId' => $postId,
					'userId' => $userId,
					'tag' => $tag
				));
			}
			return true;
		}
		return false;
	}
	public function deletetagsAction($id = null) {
		if ($id != null) {
			$this->tags->deleteAll('postId', $id);
			return true;
		}
		return false;
	}
}
?>