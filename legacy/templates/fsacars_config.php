<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
[Log]
Log=<?php echo SITE_URL?>/action.php/acars/fsacars/pirep
Mail=
URL=<?php echo SITE_URL?>/index.php/profile/view
passwd=
[FSacars]
CompanyICAO=<?php echo $pilot->code ?>

CompanyName=<?php echo SITE_NAME?>

CompanySite=<?php echo SITE_URL?>

UnitSystem=GB
Remarks=RMK/
UseLocal=0
PilotNumber=<?php echo $pilotcode?>

CompanyIATA=FLIGHT #
CallsignUses=
AcarsSite=<?php echo SITE_URL?>/action.php/acars/fsacars/acars
StatusSite=<?php echo SITE_URL?>/action.php/acars/fsacars/status
FPSite=<?php echo SITE_URL?>/action.php/acars/fsacars/flightplans
AcarsUplinkResetSite=
AcarsUplinkSite=
Antic=
[Events]
UseCargo=0
FlapsEvent=1
ToutchDownEvent=1
TOLDPosEvent=1
TOCTODEvent=1
ComFreqEvent=1
GearEvent=1
FlightLengthEvent=1
VrV2Event=1
PIREPEvent=1
FlightPosEvent=1
N1Event=1
DurationEvent=1
FuelEvent=1
WeightEvent=1
MetarsEvent=1
DistLandEvent=1
[Realism]
NoSlew=0
NoPause=0
Crash=0
PIC=0
MinReset=0
MaxReset=0
Wave=
[SendLog]
Password=0
PilotNumber=1
Date=1
Hour=1
Callsign=1
IATAN=1
Regist=1
Depart=1
Arrival=1
Alternate=1
PlaneType=1
SpentFuel=1
IniFuel=0
EndFuel=0
Dur=1
Len=1
TD=1
ZFW=1
Log=1
Version=1