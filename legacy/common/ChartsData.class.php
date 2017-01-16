<?php
#replacement functions for OFC charts - Google Charts API - simpilot
#load Google Chart Wrapper library (3rd party)
require CORE_LIB_PATH . '/gChart/gChart.php';

class ChartsData extends CodonData    {

        public static function build_pireptable($pilotid, $days = '30', $color= '000000', $background = 'EFEFEF', $width = '950', $height = '260')   {

            #make sure $days is even - if not add 1
            if($days&1){$days++;}

            $output = array();
            #set current date
            $month = date("m");
            $day = date("d");
            $year = date("Y");
            #set todays date
            $output[] = date('m-d',mktime(0,0,0,$month,($day),$year));
            #loop through days
            for($i=1; $i<=($days-1); $i++){
                    $output[] = date('m-d',mktime(0,0,0,$month,($day-$i),$year));
            }
            #reverse the days for chart
            $output = array_reverse($output);

            #set baseline values
            $labels = array();
            $points = array();
            $xTotal = 0;
            $yTotal = 0;
            $dataPoint = array();
            $data = PIREPData::getIntervalDataByDays(array('p.pilotid'=>$pilotid), $days);

            if($data)   {
                foreach($data as $dat)  {

                    $points[] = $dat->total;
                    #get highest y-axis value for chart
                    if($dat->total > $total)    {
                        $yTotal = $dat->total;
                    }
                    $date = '';
                    $date = explode('-', $dat->ym);
                    $labels[] = $date[1].'-'.$date[2];
                    $xTotal++;

                    $z = new stdClass();
                    $z->label = $date[1].'-'.$date[2];
                    $z->point = $dat->total;
                    $dataPoint[] = $z;
                }
            }

            $fLabels = array();
            $fPoints = array();
            $arraySpot = '0';
            foreach($output as $num => $point)  {
                #display every x-axis label on charts 10 days or less
                if($days < 11)  {
                    if(in_array($point, $labels))   {
                        $fLabels[] = $dataPoint[$arraySpot]->label;
                        $fPoints[] = $dataPoint[$arraySpot]->point;
                        $arraySpot++;
                    }
                    else    {
                        $fLabels[] = $point;
                        $fPoints[] = '0';
                    }
                }
                #skip every other x-axis label on charts over 10 days
                else    {
                    if(in_array($point, $labels))   {
                        if($num&1){$fLabels[] = $dataPoint[$arraySpot]->label;}
                        else    {
                            $fLabels[] = '';
                        }
                        $fPoints[] = $dataPoint[$arraySpot]->point;
                        $arraySpot++;
                    }
                    else    {
                        if($num&1){$fLabels[] = $point;}
                        else    {
                            $fLabels[] = '';
                        }
                        $fPoints[] = '0';
                    }
                }
            }
            #build chart params
            $lineChart = new gLineChart($width,$height);
            $lineChart->addDataSet($fPoints);
            $lineChart->setColors(array($color));
            $lineChart->setVisibleAxes(array('x','y'));
            $lineChart->setDataRange(0,($yTotal+1));
            $lineChart->addAxisRange(0, 1, $days, 1);
            $lineChart->addAxisRange(1, 0, ($yTotal+1), 1);

            $lineChart->setlineMarkers('6');
            $lineChart->setlineWidth('1');

            $lineChart->setaxisStyle('0', $color, '10');
            $lineChart->addAxisLabel(0, $fLabels);

            $lineChart->setGridLines(100/(29), 100/($yTotal+1), 4, 2, 0, 0);

            $lineChart->setTitle("Last ".$days." Days PIREPS");
            $lineChart->setTitleOptions($color, '18');

            $lineChart->setChartMargins(array(20, 20, 20, 20));
            $lineChart->addBackgroundFill('bg', $background);

            return $lineChart->getUrl();
        }

}