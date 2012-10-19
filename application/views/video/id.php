<style type='text/css'>
header,footer {
	width:854px;
	margin:0 auto;
}
</style>
<div id='title'>
	<h1><?php echo $datas[0]->title; ?></h1>
</div>
<div id='player'>
<?php if ($ismobile) { ?>
	<iframe id='embed' src="http://flashservice.xvideos.com/embedframe/<?php echo $datas[0]->id; ?>" width="854" height="480"></iframe>
<?php } else { ?>
	<?php echo $datas[0]->embed_tag; ?>
<?php } ?>
</div>
<script type='text/x-ktempl' id='tag_list'>
	<li>
		<a href='/video/search_tag/{tag}'>{tag}</a>
		<?php if ($isadmin) { ?>
			<button class='delete_tag' data-vid='<?php echo $datas[0]->id; ?>' data-tag='{tag}'>Delete</button>
		<?php } ?>
	</li>
</script>
<div id='options'>
<label><input type='checkbox' class='opt chkbox' name='blackoutOnBlur' checked />非アクティブ時にブラックアウト</label>
</div>
<div id='info'>
	<ul id='tags'>
	<?php foreach ($datas as $data) { ?>
		<li>
			<a href='/video/search_tag/<?php echo $data->tag; ?>'><?php echo $data->tag; ?></a>
			<?php if ($isadmin) { ?>
				<button class='delete_tag' data-vid='<?php echo $datas[0]->id; ?>' data-tag='<?php echo $data->tag; ?>'>Delete</button>
			<?php } ?>
		</li>
	<?php } ?>
	<?php if ($isadmin) { ?>
		<input type='text' id='add_tag_text' maxlength='32' placeholder='New Tag' />
		<button id='add_tag'>Add</button>
	<?php } ?>
	</ul>
</div>
<script>XVS.videoId = '<?php echo $datas[0]->id; ?>';</script>
