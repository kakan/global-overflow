<table>
    <?php $grav = md5(strtolower(trim($user->email))); ?>
    <tr>
        <td><img src="http://www.gravatar.com/avatar/<?=$grav?>?s=256&amp;d=identicon&amp;r=PG" alt="Gravatar" width='256' height='256'></td>
        <td><?=$user->username?></td>
    </tr>
</table>

<h2>Questions</h2>
<?php if($user->questions != null) : ?>
<?php foreach($user->questions as $question) : $answerString = $question->answers == 1 ? "answer" : "answers"; ?>
<?php if ($question->answers == 0) : ?>
	<?=$question->answers?> <?=$answerString?>
<?php elseif ($question->answers > 0) : ?>
	<?=$question->answers?> <?=$answerString?>
<?php endif; ?>
    <a href="<?=$this->url->create('questions/id/'.$question->id)?>"><?=$question->title?></a>
	asked <?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
<?php if($question->modified != null) : ?>
	modified <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?>
<?php endif; ?>
<?php endforeach; ?>
<?php else : ?>
    <p>This user has not yet asked any questions.<p>
<?php endif; ?>

<h2>Answers</h2>
<?php if($user->answers != null) : ?>
<?php foreach($user->answers as $question) : $answerString = $question->answers == 1 ? "answer" : "answers"; ?>
<?php if ($question->answers == 0) : ?>
	<?=$question->answers?> <?=$answerString?>
<?php elseif ($question->answers > 0) : ?>
	<?=$question->answers?> <?=$answerString?>
<?php endif; ?>
	<a href="<?=$this->url->create('questions/id/'.$question->belongTo)?>"><?=$question->title?></a>
	asked <?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
<?php if($question->modified != null) : ?>
	modified <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?>
<?php endif; ?>
<?php endforeach; ?>
<?php else : ?>
    <p>This user has not yet answered any questions.<p>
<?php endif; ?>

<?php if($tools == true) : ?>
    <a href="<?=$this->url->create('users/edit') ?>" class="button default-button">Edit</a>
    <a href="<?=$this->url->create('users/password') ?>" class="button default-button">Change password</a>
<?php endif; ?>
 
<div class="back-link">
    <a href='<?=$this->url->create('users')?>'><i class="fa fa-angle-double-left"></i> Back to user list...</a>
</div>