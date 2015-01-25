<?php $authorId = $questions[0]->userId ?>
<?php $i=0; foreach ($questions as $question) : $grav = md5(strtolower(trim($question->email))); ?>
<fieldset>
<?php if($question->type == 'a') : ?>
<?php if($this->di->session->get('user') != null && $authorId == $this->di->session->get('user')[0]->id) : ?>
<?php if($question->accepted == 1) : ?>
	<legend>
		<a href="<?=$this->url->create('questions/accept/'.$question->questionId.'/'.$authorId) ?>" title="Undo Answer"><i class="fa fa-check yes"></i></a>
	</legend>
<?php else : ?>
	<legend>
		<a href="<?=$this->url->create('questions/accept/'.$question->questionId.'/'.$authorId) ?>" title="Accept Answer"><i class="fa fa-check no"></i></a>
	</legend>
<?php endif; ?>
<?php elseif($question->accepted == 1) : ?>
			<i class="fa fa-check yes"></i>
<?php endif; ?>
<?php endif; ?>
<?php if($question->type == 'q' && $i < 1) : ?>
	<legend>
		<b><?=$answerCount?></b>
	</legend>
	<h3>
		<?=$header?>
	</h3>
	<?php endif; ?>
	<p>
		<?=$question->content?>
	</p>
	<p>
<?php if($question->type == 'q' && $i < 1) : ?>
<?php foreach ($tags as $tag) : ?>
		<a href="<?=$this->url->create('questions/tagged/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
<?php endforeach; ?>
<?php endif; ?>
	</p>
	<p>
		<?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>, <?=$question->username?>,
		<a href="<?=$this->url->create('users/id/' . $question->userId) ?>">
		<img src="http://www.gravatar.com/avatar/<?=$grav?>?s=32&amp;d=identicon&amp;r=PG" alt="<?=$question->username?>" width='32' height='32'></a>.
<?php if($question->modified != null) : ?>
	</br>
		Modifierad: <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?>.
<?php endif; ?>
<?php if($this->di->session->get('user') != null) : if($question->userId == $this->di->session->get('user')[0]->id) : ?>
	</br>
		<a href="<?=$this->url->create('questions/edit/'.$question->questionId) ?>">Modify</a> |
		<a href="<?=$this->url->create('questions/delete/'.$question->questionId) ?>">Remove</a>
<?php endif; endif;?>	
	</p>
<?php if (!empty($question->comments)) : ?>
<?php foreach ($question->comments as $comment) : ?>
	<fieldset>
		<legend>
			<a href="<?=$this->url->create('users/id/' . $comment->userId) ?>"><?=$comment->username?></a>, 
			<?=dateDiff(date("Y-m-d H:i:s"), (isset($comment->modified)?$comment->modified:$comment->posted));?>.
<?php if($this->di->session->get('user') != null) : if($comment->userId == $this->di->session->get('user')[0]->id) : ?>
			| <a href="<?=$this->url->create('questions/edit/'.$comment->questionId) ?>" title="Modify"><i class="fa fa-pencil-square-o"></i></a> |
			<a href="<?=$this->url->create('questions/delete/'.$comment->questionId) ?>" title="Remove"><i class="fa fa-times"></i></a> |
<?php endif; endif;?>
		</legend>
		<p>
			<?=$comment->content?>
		</p>
	</fieldset>
<?php endforeach; ?>
<?php endif; ?>
	<p>
		<a href="<?=$this->url->create('questions/comment/'.$question->questionId) ?>">Add your own Comment</a>
	</p>
</fieldset>
<?php if($i < 1) : ?>
<?php $i++ ?>
<?php endif; ?>
<?php endforeach; ?>
<?php if(isset($form)) : ?>
    <div>
		<?=$form?>
	</div>
<?php else : ?>
    <p>
		<b>Please log in to join the discussion!</b></br>
	</p>
<?php endif; ?>