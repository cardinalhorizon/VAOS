<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
[WEB_CONFIG]
ADDRESS=<?php echo actionurl('/fsfk/pirep'); ?>

PORT=80

LOGIN_ENCODED=FALSE

USER=

PASSWORD=

TIMEFORMAT=LOCAL

DATETIME_FORMAT_STRING=dd\.mm\.yyyy HH\:nn

TIME_FORMAT_STRING=HH\:nn

PICTURE_ADDRESS=<?php echo Config::Get('FSFK_FTP_SERVER'); ?>

PICTURE_PORT=<?php echo Config::Get('FSFK_FTP_PORT'); ?>

PICTURE_USER=<?php echo Config::Get('FSFK_FTP_USER'); ?>

PICTURE_PASSWORD=<?php echo Config::Get('FSFK_FTP_PASS'); ?>

PICTURE_FTP_PASSIVE_MODE = <?php echo Config::Get('FSFK_FTP_PASSIVE_MODE'); ?>

PICTURE_TYPES=FlightMapJPG, FlightMapWeatherJPG, FlightMapTaxiOutJPG, FlightMapTaxiInJPG, FlightMapVerticalProfileJPG, FlightMapLandingProfileJPG

[DATA]
<FLIGHTDATA>
	<PilotID>$@$PilotID$@$</PilotID>
	<PilotName>$@$PilotName$@$</PilotName>
	<AircraftTitle>$@$AircraftTitle$@$</AircraftTitle>
	<AircraftType>$@$AircraftType$@$</AircraftType>
	<AircraftTailNumber>$@$AircraftTailNumber$@$</AircraftTailNumber>
	<AircraftAirline>$@$AircraftAirline$@$</AircraftAirline>
	<FlightNumber>$@$FlightNumber$@$</FlightNumber>
	<FlightLevel>$@$FlightLevel$@$</FlightLevel>
	<FlightType>$@$FlightType$@$</FlightType>~
	<Passenger>$@$Passenger$@$</Passenger>
	<Cargo>$@$Cargo$@$</Cargo>
	<ZFW>$@$ZFW$@$</ZFW>
	<OriginICAO>$@$OriginICAO$@$</OriginICAO>
	<OriginGate>$@$OriginGate$@$</OriginGate>
	<OriginRunway>$@$OriginRunway$@$</OriginRunway>
	<OriginTransitionAltitude>$@$OriginTransitionAltitude$@$</OriginTransitionAltitude>
	<DestinationICAO>$@$DestinationICAO$@$</DestinationICAO>
	<DestinationGate>$@$DestinationGate$@$</DestinationGate>
	<DestinationRunway>$@$DestinationRunway$@$</DestinationRunway>
	<DestinationTransitionAltitude>$@$DestinationTransitionAltitude$@$</DestinationTransitionAltitude>
	<AlternateICAO>$@$AlternateICAO$@$</AlternateICAO>
	<SID>$@$SID$@$</SID>
	<STARS>$@$STAR$@$</STARS>
	<FlightDistance>$@$FlightDistance$@$</FlightDistance>
	<RouteDistance>$@$RouteDistance$@$</RouteDistance>
	<OUTTime>$@$OUTTime$@$</OUTTime>
	<OFFTime>$@$OFFTime$@$</OFFTime>
	<ONTime>$@$ONTime$@$</ONTime>
	<INTime>$@$INTime$@$</INTime>
	<DayFlightTime>$@$DayFlightTime$@$</DayFlightTime>
	<NightFlightTime>$@$NightFlightTime$@$</NightFlightTime>
	<BlockTime>$@$BlockTime$@$</BlockTime>
	<FlightTime>$@$FlightTime$@$</FlightTime>
	<BlockFuel>$@$BlockFuel$@$</BlockFuel>
	<FlightFuel>$@$FlightFuel$@$</FlightFuel>
	<TOIAS>$@$TOIAS$@$</TOIAS>
	<LAIAS>$@$LAIAS$@$</LAIAS>
	<ONVS>$@$ONVS$@$</ONVS>
	<FlightScore>$@$FlightScore$@$</FlightScore>
	<FLIGHTPLAN>
	<![CDATA[
	$@$FlightPlan$@$
	]]>
	</FLIGHTPLAN>
	<COMMENT>
	<![CDATA[
	$@$Comment$@$
	]]>
	</COMMENT>
	<FLIGHTCRITIQUE>
	<![CDATA[
	$@$FlightCritique$@$
	]]>
	</FLIGHTCRITIQUE>
	<FLIGHTMAPS>
		<FlightMapJPG>$@$FlightMapJPG$@$</FlightMapJPG>
		<FlightMapWeatherJPG>$@$FlightMapWeatherJPG$@$</FlightMapWeatherJPG>
		<FlightMapTaxiOutJPG>$@$FlightMapTaxiOutJPG$@$</FlightMapTaxiOutJPG>
		<FlightMapTaxiInJPG>$@$FlightMapTaxiInJPG$@$</FlightMapTaxiInJPG>
		<FlightMapVerticalProfileJPG>$@$FlightMapVerticalProfileJPG$@$</FlightMapVerticalProfileJPG>
		<FlightMapLandingProfileJPG>$@$FlightMapLandingProfileJPG$@$</FlightMapLandingProfileJPG>
	</FLIGHTMAPS>
</FLIGHTDATA>
