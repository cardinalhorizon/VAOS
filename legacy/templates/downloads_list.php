<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h1>Downloads</h1>

<?php 
if(!$allcategories)
{
	echo 'There are no downloads available!';
	return;
}

foreach($allcategories as $category)
{
?>
<p><h2><strong><?php echo $category->name?></strong></h2></p>
<ul>

<?php	
	# This loops through every download available in the category
	$alldownloads = DownloadData::GetDownloads($category->id);
	
	if(!$alldownloads)
	{
		echo 'There are no downloads under this category';
		$alldownloads = array();
	}
	
	foreach($alldownloads as $download)
	{
?>
	<li>
		<a href="<?php echo url('/downloads/dl/'.$download->id);?>">
			<?php echo $download->name?></a><br />
	      <?php echo $download->description?><br />
          <em>Downloaded <?php echo $download->hits?> times</em></li>
<?php
	}
?><br />
</ul>
	<?php
}
?>