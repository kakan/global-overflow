<?=$header?>
<?=$main?>
<?php if(isset($userId)) : ?>
<fieldset>
    <a href="<?=$this->url->create('users/id/'.$userId)?>">Back</a>.
</fieldset>
<?php endif; ?>