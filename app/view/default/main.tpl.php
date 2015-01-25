<h2>Latest Questions</h2>
<?php if ($questions != null) : ?>
<?php foreach ($questions as $question) : 
    $grav = md5(strtolower(trim($question->email))); 
    $extract = getExtract($question->content);
    $answerString = $question->answers == 1 ? "answer" : "answers";
?>
<table>
	<tr>
		<td>
<?php if ($question->answers == 0) : ?>
			<?=$question->answers?> <?=$answerString?>
<?php elseif ($question->answers > 0) : ?>
			<?=$question->answers?> <?=$answerString?>
<?php endif; ?>
		</td>
		<td>
			<a href="<?=$this->url->create('questions/id/'.$question->questionId)?>"><?=$question->title?></a>
		</td>
		<td>
			<?=$extract?>
		</td>
		<td>
<?php foreach ($question->tags as $tag) : ?>
			<a href="<?=$this->url->create('questions/tagged/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
<?php endforeach; ?>
		</td>
		<td>
			<a href="<?=$this->url->create('users/id/' . $question->userId) ?>"><img src="http://www.gravatar.com/avatar/<?=$grav?>?s=32&amp;d=identicon&amp;r=PG" alt="<?=$question->username?>" title="Username: <?=$question->username?>." width='32' height='32'></a>
		</td>
		<td>
			asked <?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?> 
<?php if($question->modified != null) : ?>
			modified <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?>
<?php endif; ?>
		</td>
	<tr>
</table>
<?php endforeach; ?>
<?php else : ?>
    <p>Aww, There are no questions... Be the first one to <a href="<?=$this->url->create('questions/ask')?>"><i class="fa fa-question"></i> ask</a> a question.<p>
<?php endif; ?>

<h2>Most Active Users</h2>
<?php if($users != null) : ?>
<table>
	<tr>
<?php $i=0; foreach ($users as $user) : $grav = md5(strtolower(trim($user->email))); $i++; ?>
		<td>
			<a href="<?=$this->url->create('users/id/' . $user->userId) ?>"><img src="http://www.gravatar.com/avatar/<?=$grav?>?s=32&amp;d=identicon&amp;r=PG" alt="<?=$user->username?>" title="Username: <?=$user->username?>." width='32' height='32'></a>
		</td>
<?php if($i%4 == 0) : ?>
    </tr>
	<tr>
<?php endif; ?>
<?php endforeach; ?>
	</tr>
</table>
<?php else : ?>
	<p>Aww, There are no users to be displayed... Be the first one to <a href="<?=$this->url->create('users/register')?>"><i class="fa fa-pencil-square-o"></i> register</a> and <a href="<?=$this->url->create('questions/ask')?>"><i class="fa fa-question"></i> ask</a> a a question.<p>
<?php endif; ?>

<h2>Most Used Tags</h2>
<?php if($tags != null) : ?>
<table>
	<tr>
<?php $i=0; foreach ($tags as $tag) : $i++?>
		<td>
			<a href="<?=$this->url->create('questions/tagged/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a> <span class="quiet">x <?=$tag->tagCount?></span>
		</td>
<?php if($i%4 == 0) : ?>
    </tr>
	<tr>
<?php endif; ?>
<?php endforeach; ?>
	</tr>
</table>
<?php else : ?>
	<p>Aww, There are no tags... Be the first one to <a href="<?=$this->url->create('questions/ask')?>"><i class="fa fa-question"></i> ask</a> a question, and create some tags.<p>
<?php endif; ?>