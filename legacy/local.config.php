<?php

/**
 * This is the phpVMS Main Configuration File
 *
 * This file won't be modified/touched by future versions
 * of phpVMS, you can change your settings here
 * 
 * There may also be additional settings in app.config.php
 * To change it, copy the line into this file here, for the
 * settings to take effect
 *
 */
Config::Set('DEBUG_MODE', false);
Config::Set('DEBUG_LEVEL', 1); // 1 logs query errors, 2 logs all queries
Config::Set('ERROR_LEVEL', E_ALL ^ E_NOTICE);

define('DBASE_USER', $_ENV['LEGACY_USERNAME']);
define('DBASE_PASS', $_ENV['LEGACY_PASSWORD']);
define('DBASE_NAME', $_ENV['LEGACY_DATABASE']);
define('DBASE_SERVER', $_ENV['LEGACY_HOST']);
define('DBASE_TYPE', 'mysqli');

define('VAOS_URL', $_ENV['APP_URL']);
define('TABLE_VAOS', $_ENV['DB_PREFIX']);
define('TABLE_PREFIX', $_ENV['LEGACY_PREFIX']);

define('SITE_URL', $_ENV['APP_URL']);

# Page encoding options
Config::Set('PAGE_ENCODING', 'ISO-8859-1');

# Clean URLs - set this to true, and then uncomment
# the lines indicated in the .htaccess file 
Config::Set('URL_REWRITE', false);

# Maintenance mode - this disables the site to non-admins
Config::Set('MAINTENANCE_MODE', false);
Config::Set('MAINTENANCE_MESSAGE', 'We are currently down for maintenance, please check back soon.');

/*	Whether you have the /admin/maintenance.php script added into cron.
	If you do, set this to true. This saves many DB calls since phpVMS will
	have to 'fake' a cron-job
	*/
Config::Set('USE_CRON', false);

Config::Set('CHECK_RELEASE_VERSION', true);
Config::Set('CHECK_BETA_VERSION', false);

# See more details about these in the docs
Config::Set('PAGE_EXT', '.htm');	# .htm is fine. You can still run PHP
Config::Set('PILOTID_OFFSET', 0);	# What # to start pilot ID's from
Config::Set('PILOTID_LENGTH', 4);	# Length of the Pilot ID
Config::Set('UNITS', 'nm');			# Your units: nm, mi or km
Config::Set('LOAD_FACTOR', '82');	# %age load factor 
Config::Set('CARGO_UNITS', 'lbs');

# After how long to mark a pilot inactive, in days
Config::Set('PILOT_AUTO_RETIRE', true);
Config::Set('PILOT_INACTIVE_TIME', 90);

# Automatically confirm pilots?
Config::Set('PILOT_AUTO_CONFIRM', false);

# Automatically calculate ranks?
Config::Set('RANKS_AUTOCALCULATE', true);

# For how many hours a pilot can edit their submitted PIREP (custom fields only)
Config::Set('PIREP_CUSTOM_FIELD_EDIT', '48');

# If someone places a bid, whether to disable that or not
Config::Set('DISABLE_SCHED_ON_BID', true);
Config::Set('DISABLE_BIDS_ON_BID', false);

# If you want to count transfer hours in rank calculations
Config::Set('TRANSFER_HOURS_IN_RANKS', false);

# The StatsData::UserOnline() function - how many minutes to check
Config::Set('USERS_ONLINE_TIME', 20);

# Google Map Options
Config::Set('MAP_WIDTH', '800px');
Config::Set('MAP_HEIGHT', '600px');
# Valid types are G_NORMAL_MAP, G_SATELLITE_MAP, G_HYBRID_MAP, G_PHYSICAL_MAP
Config::Set('MAP_TYPE', 'G_PHYSICAL_MAP');
Config::Set('MAP_LINE_COLOR', '#ff0000');
Config::Set('MAP_CENTER_LAT', '45.484400');
Config::Set('MAP_CENTER_LNG', '-62.334821');
Config::Set('MAP_ZOOM_LEVEL', 12);

# ACARS options
#  Minutes, flights to show on the ACARS
#  Default is 720 minutes (12 hours)
Config::Set('ACARS_LIVE_TIME', 720); 
Config::Set('ACARS_DEBUG', false);

# This is your airline's twitter account, if it exists
Config::Set('TWITTER_AIRLINE_ACCOUNT', '');
Config::Set('TWITTER_ENABLE_PUSH', false);
Config::Set('TWITTER_CONSUMER_KEY', '');
Config::Set('TWITTER_CONSUMER_SECRET', '');
Config::Set('TWITTER_OAUTH_TOKEN', '');
Config::Set('TWITTER_OAUTH_SECRET', '');

/*
  This is the unit of money. For non-dollars, use :
	Dollars ($), enter "$"
	Euro (�), enter "&#8364;"
	Yen (�), enter "&yen;"
	Pounds (�), enter "&pound;"
	
  For example, to set EUROS:
	Config::Set('MONEY_UNIT', '&#8364;');
 */

Config::Set('MONEY_UNIT', '$');
 
# FSPassengers Settings
# Units settings
Config::Set('WeightUnit', '1');   # 0=Kg 1=lbs
Config::Set('DistanceUnit', '2');   # 0=KM 1= Miles 2=NMiles
Config::Set('SpeedUnit', '1');   # 0=Km/H 1=Kts
Config::Set('AltUnit', '1');   # 0=Meter 1=Feet 
Config::Set('LiquidUnit', '2');   # 0=liter 1=gal 2=kg 3=lbs
Config::Set('WelcomeMessage', 'phpVMS/FSPAX ACARS'); # Welcome Message

/* FSFK Settings
	Your FTP Server, and path to the lib/images folder (from where the FTP connects from), IE
	ftp://phpvms.net/phpvms/lib/fsfk or ftp://phpvms.net/public_html/phpvms/lib/fsfk
	
	You want the path from when you connect to the FTP down to where the /lib/fsfk folder is 
    
    SECURITY NOTE! Make a separate FTP user and password ONLY for this, with access only to this folder
*/
Config::Set('FSFK_FTP_SERVER', '');
Config::Set('FSFK_FTP_PORT', '21');
Config::Set('FSFK_FTP_USER', '');
Config::Set('FSFK_FTP_PASS', '');
Config::Set('FSFK_FTP_PASSIVE_MODE', 'TRUE');
Config::Set('FSFK_IMAGE_PATH', '/lib/fsfk'); // web path from SITE_ROOT

# Options for the signature that's generated 
Config::Set('SIGNATURE_TEXT_COLOR', '#000');
Config::Set('SIGNATURE_SHOW_EARNINGS', true);
Config::Set('SIGNATURE_SHOW_RANK_IMAGE', true);
Config::Set('SIGNATURE_SHOW_COPYRIGHT', true);

# Avatar information
Config::Set('AVATAR_FILE_SIZE', 50000); 
Config::Set('AVATAR_MAX_WIDTH', 80);
Config::Set('AVATAR_MAX_HEIGHT', 80);

# Email Settings
Config::Set('EMAIL_FROM_NAME', '');
Config::Set('EMAIL_FROM_ADDRESS', '');

Config::Set('EMAIL_USE_SMTP', false);
# Add multiple SMTP servers by separating them with ;
Config::Set('EMAIL_SMTP_SERVERS', '');
Config::Set('EMAIL_SMTP_PORT', '25');
Config::Set('EMAIL_SMTP_USE_AUTH', false);
Config::Set('EMAIL_SMTP_SECURE', 'ssl'); # must be "ssl" for Google Apps
Config::Set('EMAIL_SMTP_USER', '');
Config::Set('EMAIL_SMTP_PASS', '');

