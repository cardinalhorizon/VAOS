<?php
/**
 * English Language Strings for phpVMS
 * 
 * @author Nabeel Shahzad <http://www.phpvms.net>
 * 
 * You can use this file to create your own translations
 * Format is
 * 
 * key=>string
 * 
 * Key must be lower case, no underscores.  These are mainly for the
 * admin panel.
 */
 
$trans = array(
	
	/* Define some core language stuff */
	'invalid.php.version'				=> 'You are not running PHP 5.0+',
	'database.connection.failed'		=> 'Database connection failed',
	'error'								=> 'An error has occured (%s)', /* %s is the error string */
	
	/*
	 * Module language replacements
	 */
	
	/* Email stuff */
	'email.inuse'						=> 'This email address is already in use',
	'email.register.accepted.subject'	=> 'Your registration was accepted!',
	'email.register.rejected.subject'	=> 'Your registration was denied!',
	'email.pilot.retired.subject'		=> 'You\'ve been marked as retired',
	
	/* Expenses */
	
	
	
	/* Registration Errors */
	'password.wrong.length'				=> 'Password is less than 5 characters',
	'password.no.match'					=> 'The passwords do not match',
	'password.changed'					=> 'Password has been successfully changed',
	
	/* Pilots Info */
	'pilot.deleted'						=> 'Pilot had been deleted',
	
	/* Awards */
	'award.exists'						=> 'The pilot already has this award!',
	'award.deleted'						=> 'Award deleted!',
	
	/* Groups */
	'group.added'						=> 'The group %s has been added', /* %s is group name */
	'group.saved'						=> 'The group %s has been saved', /* %s is group name */
	'group.no.name'						=> 'You must enter a name for the group',
	'group.pilot.already.in'			=> 'This user is already in this group!',
	'group.add.error'					=> 'There was an error adding this user',
	'group.user.added'					=> 'User has been added to the group!',
	
	/* Pages */
	'page.add.title'					=> 'Add Page',
	'page.edit.title'					=> 'Edit Page', 
	'page.exists'						=> 'This page already exists!',
	'page.create.error'					=> 'There was an error creating the file',
	'page.edit.error'					=> 'There was an error saving content',
	'page.error.delete'					=> 'There was an error deleting the page!',
	'page.deleted'						=> 'The page was deleted',
	
	/* News */
	'news.add.title'					=> 'Add News',
	'news.edit.title'					=> 'Edit News',
	'news.updated.success'				=> 'News edited successfully!',
	'news.updated.error'				=> 'There was an error editing the news item',
	'news.delete.error'					=> 'There was an error deleting the item',
	'news.item.deleted'					=> 'News item deleted',
	
	/* Settings */
	'settings.add.field'				=> 'Add Field',
	'settings.edit.field'				=> 'Edit Field',
	'pirep.field.add'					=> 'Add PIREP Field',
	'pirep.field.edit'					=> 'Edit PIREP Field',
	
	/* PIREPS */
	'pireps.view.recent'				=> 'Recent Reports',
    
    
    /* Activity Feed Language strings, you can fill 
        in the $<column name> from the PIREP table */
    'activity.new.pirep' => 'has filed a PIREP from $depicao to $arricao',
	
    /* You can use $<column name> from the pilot's table */
    'activity.new.pilot' => 'has just joined, welcome aboard!',
    
    'activity.pilot.promotion' => 'has been promoted to $rank!',
	
	/*
	 * Template language replacements
	 */
	 
	
);