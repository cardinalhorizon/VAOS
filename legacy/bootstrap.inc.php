<?php

/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

function pre_module_load() {
    
    if (is_dir(CORE_PATH.'/local.config.php')) {
        Debug::showCritical('core/local.config.php is a folder, not a file. Please delete and create as a file');
        die();
    }

    if (!file_exists(CORE_PATH.'/local.config.php') || filesize(CORE_PATH.'/local.config.php') == 0) {
        Debug::showCritical('phpVMS has not been installed yet! Goto <a href="install/install.php">install/install.php</a> to start!');
        exit;
    }

    SiteData::loadSiteSettings();
    Auth::StartAuth();
    
    # Set a "authuser" super variable, so it's available in every template...
    if(Auth::LoggedIn() === true) {
        Template::set('authuser', Auth::$userinfo);
    } else {
        Template::set('authuser', null);
    }
}

function post_module_load() {
    
    /* Misc tasks which need to get done */

    /* If the setting to auto-retired pilots is on, then do that
    and only check every 24 hours
    */
    if (Config::Get('USE_CRON') == false) {
        if (Config::Get('PILOT_AUTO_RETIRE') == true) {
            $within_timelimit = CronData::check_hoursdiff('find_retired_pilots', '24');
            if ($within_timelimit === false) {
                PilotData::findRetiredPilots();
                CronData::set_lastupdate('find_retired_pilots');
            }
        }

        if (Config::Get('CLOSE_BIDS_AFTER_EXPIRE') === false) {
            $within_timelimit = CronData::check_hoursdiff('check_expired_bids', '24');
            if ($within_timelimit === false) {
                SchedulesData::deleteExpiredBids();
                CronData::set_lastupdate('check_expired_bids');
            }
        }

        /* Expenses, make sure they're all populated */
        $within_timelimit = CronData::check_hoursdiff('populate_expenses', 18);
        if ($within_timelimit === false) {
            FinanceData::updateAllExpenses();
            CronData::set_lastupdate('populate_expenses');
        }
        
        /* And finally, clear expired sessions */
        Auth::clearExpiredSessions();
    }
    
    if (Config::Get('TWITTER_AIRLINE_ACCOUNT') != '') {
        $within_timelimit = CronData::check_hoursdiff('twitter_update', '3');
        if ($within_timelimit === false) {
            ActivityData::readTwitter();
            CronData::set_lastupdate('twitter_update');
        }
    }

    // @TODO: Clean ACARS records older than one month
    if (Config::Get('MAINTENANCE_MODE') == true && !Auth::LoggedIn() && !
        PilotGroups::group_has_perm(Auth::$usergroups, FULL_ADMIN)) {
        
        Template::Show('maintenance.tpl');
        
        die();
    }

    return true;
}

/**
 * Return the full URL to an admin path
 * 
 * @param mixed $path
 * @return
 */
function adminurl($path) {
    if ($path[0] != '/') $path = '/' . $path;
    return SITE_URL . '/admin/index.php' . $path;
}

/**
 * Return the full URL to a path
 * 
 * @param mixed $path
 * @return
 */
function url($path) {
    
    if ($path[0] != '/') $path = '/' . $path;

    if (Config::Get('URL_REWRITE') == true) {
        return SITE_URL . $path;
    }

    return SITE_URL . '/index.php' . $path;
}

function cndebug($txt) {
    Debug::log($txt);
}

function adminaction($path) {
    if ($path[0] != '/') $path = '/' . $path;
    return SITE_URL . '/admin/action.php' . $path;
}

/**
 * Get the proper url to the action.php script
 * 
 * @param mixed $path
 * @return string
 */
function actionurl($path) {
    
    if ($path[0] != '/') $path = '/' . $path;
    return SITE_URL . '/action.php' . $path;
}

function fileurl($path) {
    $url = SITE_URL;

    if ($path[0] != '/') $path = '/' . $path;

    return $url . $path;
}


function html_url($title, $url) {
    return '<a href="' . url($url) . '" >' . $title . '</a>';
}
