<h2>Tags</h2>
<?php if($tags != null) : ?>
<table>
	<tr>
<?php $i=0; foreach ($tags as $tag) : $i++?>
		<td>
			<a href="<?=$this->url->create('questions/tagged/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a> <span class="quiet">x <?=$tag->count?></span>
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