<?php $grav = md5(strtolower(trim($user->email))); ?>
<fieldset>
	<legend>
		<b><?=$user->username?></b>
	</legend>
	<p>
		<img src="http://www.gravatar.com/avatar/<?=$grav?>?s=256" alt="<?=$user->username?>" width='256' height='256'>
	</p>
</fieldset>
<fieldset>
	<legend>
		<b>Questions</b>
	</legend>
<?php if($user->questions != null) : ?>
<?php foreach($user->questions as $question) : $answerString = $question->answers == 1 ? "answer" : "answers"; ?>
	<p>
<?php if ($question->answers == 0) : ?>
		<b><?=$question->answers?> <?=$answerString?></b>,
<?php elseif ($question->answers > 0) : ?>
		<b><?=$question->answers?> <?=$answerString?></b>,
<?php endif; ?>
		<a href="<?=$this->url->create('questions/id/'.$question->id)?>"><?=$question->title?></a>,
		<?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
<?php if($question->modified != null) : ?>, Modifierad: <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?><?php endif; ?>.
	</p>
<?php endforeach; ?>
<?php else : ?>
    <p>This user has not yet asked any questions.</p>
<?php endif; ?>	
</fieldset>
<fieldset>
	<legend>
		<b>Answers</b>
	</legend>
<?php if($user->answers != null) : ?>
<?php foreach($user->answers as $question) : $answerString = $question->answers == 1 ? "answer" : "answers"; ?>
	<p>
<?php if ($question->answers == 0) : ?>
		<b><?=$question->answers?> <?=$answerString?></b>,
<?php elseif ($question->answers > 0) : ?>
		<b><?=$question->answers?> <?=$answerString?></b>,
<?php endif; ?>
		<a href="<?=$this->url->create('questions/id/'.$question->belongTo)?>"><?=$question->title?></a>,
		<?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
<?php if($question->modified != null) : ?>, Modifierad: <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?><?php endif; ?>.
	</p>
<?php endforeach; ?>
<?php else : ?>
    <p>This user has not yet answered any questions.</p>
<?php endif; ?>
</fieldset>
<fieldset>
	<p>
<?php if($tools == true) : ?>
		<a href="<?=$this->url->create('users/edit') ?>">Modify Account</a>,
		<a href="<?=$this->url->create('users/password') ?>">Password</a>,
<?php endif; ?>
		<a href="<?=$this->url->create('users') ?>">Back</a>.
	</p>
</fieldset>