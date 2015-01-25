<h2>Users:</h2>
<?php if($users != null) : ?>
<?php $i=0; foreach ($users as $user) : $grav = md5(strtolower(trim($user->email))); $i++; ?>
	<fieldset class="select_25">
		<legend>
			<b><?=$user->username?></b>
		</legend>
		<p>
			<a href="<?=$this->url->create('users/id/' . $user->id) ?>">
			<img src="http://www.gravatar.com/avatar/<?=$grav?>?s=32" alt="<?=$user->username?>" width='32' height='32'></a>
		</p>
	</fieldset>
<?php endforeach; ?>
<?php else : ?>
    <p>No activity on record.<p>
<?php endif; ?>