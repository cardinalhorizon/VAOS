<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Activity Feed</h3>
<?php

foreach($allactivities as $activity) {
    
    /*  Use the activity->type to determine the type of activity (duh?)
        Constants are in app.config.php 
        
        Like here, I put a specific link to the PIREP at the end of the message
        You can use the if/else's to add specific images?
        
        $activity->refid is the ID of the thing it's referring to, so if it's a
        new PIREP, the refid will be the ID of the PIREP
        
        $activity-> also contains all the fields about the pilot who this notice
        is about        
     */
           
    $link_title = '';
    $link_href = '';
    if($activity->type == ACTIVITY_NEW_PIREP) {
        
        $link_href = url('/pireps/view/'.$activity->refid);
        $link_title = 'View Flight Report';
        
    } elseif($activity->type == ACTIVITY_TWITTER) {
        
        $link_href = 'http://twitter.com/#!/'.Config::get('TWITTER_AIRLINE_ACCOUNT').'/status/'.$activity->refid;
        $link_title = 'View Tweet';
        
    } elseif($activity->type == ACTIVITY_NEW_PILOT) {
        $link_href = url('/profile/view/'.$activity->pilotid);
        $link_title = 'View Profile';
    }
?>
    <p>
        <?php
        /*  Example, if it's a twitter status update (ACTIVITY_TWITTER),
            then show an image (in this case, a small Twitter icon) */
        if($activity->type == ACTIVITY_TWITTER) {
            echo '<img src="'.fileurl('/lib/images/twitter.png').'" alt="twitter update" />';
        }
        
        ?>
        <?php
        /*  If there is a pilot associated with this feed update, show their name
            and a link to their profile page */ 
        if($activity->pilotid != 0) { 
            $pilotCode = PilotData::getPilotCode($activity->code, $activity->pilotid);
            ?>
        
            <a href="<?php echo url('/profile/view/'.$activity->pilotid);?>">
            <?php echo $pilotCode.' '.$activity->firstname.' '.$activity->lastname?>
            </a> 
    
        <?php 
        } /* End if pilot ID != 0 */ 
        ?>
        
        <?php 
            /* Show the activity message itself */
            echo stripslashes($activity->message); 
        ?>
        
        <?php
        if($link_href != '') {
            echo ' <a href="'.$link_href.'">'.$link_title.'</a>';
        }
        ?>
    </p>
<?php
}
?>