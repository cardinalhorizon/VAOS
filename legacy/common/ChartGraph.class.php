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


/**
 * Graphing class, so I don't have to kill myself every
 * time I have to create a new graph
 */

class ChartGraph {

    # Options
    public $source = 'gchart';
    public $type;
    public $orig_type;
    protected $pInvalid;

    # Chart
    public $pChart;
    public $pCache;
    public $data = array();
    public $labels = array();

    # Titles
    public $x_title;
    public $y_title;
    public $chart_title;

    # Sizing
    public $x;
    public $y;


    /**
     * Constructor for chart graph
     *
     * @param string $source "pchart" or "gchart" (to use Google Chart)
     * @param string $type Type of graph (view function to see options)
     * @param int $width width
     * @param int $height Height
     * @return object Returns chart object
     *
     */
    public function __construct($source, $type, $width, $height) {
        $this->data = array();
        $this->x = $width;
        $this->y = $height;

        if (function_exists('gd_info')) {
            $this->pChart = new pChart($this->x, $this->y);
            $this->setFontSize(8);
            $this->pChart->loadColorPalette(SITE_ROOT . '/core/lib/pchart/tones-7.txt');
            #$this->pChart->reportWarnings();
        } else  $this->pInvalid = true;


        $this->setType($source, $type);
    }

    public function setFontSize($size = 8) {
        $this->pChart->setFontProperties(SITE_ROOT . '/lib/fonts/tahoma.ttf', $size);
    }

    /**
     * Set the type of graph
     * 
     * @var $source gchart (google chart) or pchart (php chart)
     * @var $type - barx, bary, pie, pie3d
     */
    public function setType($source = 'gchart', $type = 'barx') {

        if ($this->pInvalid == true && $source == 'pchart') {
            $this->lastError = 'Cannot use pChart, GD is not installed';
            $source = 'gchart';
        }

        # Blank source type uses default
        if ($source != '') {
            $this->source = $source;
        }

        $this->orig_type = $type;

        if ($this->source == 'gchart') {
            # Translate the graph types to the proper
            #	types for the Google Chart
            if ($type == 'barx') $this->type = 'barx';
            elseif ($type == 'bary') $this->type = 'bary';
            elseif ($type == 'pie') $this->type = 'p';
            elseif ($type == 'pie3d') $this->type = 'p3';
            else  $this->type = $type;
        } else {
            $this->type = $type;
        }

    }

    public function setTitles($chart_title, $x_title = '', $y_title = '') {
        $this->chart_title = $chart_title;
        $this->x_title = $x_title;
        $this->y_title = $y_title;
    }

    public function setSize($width, $height) {
        $this->x = $width;
        $this->y = $height;
    }

    public function AddData($data, $x_labels = '', $y_labels = '') {
        //$data = array('data'=>$data, 'x_labels'=>$x_labels, 'y_labels'=>$y_labels);
        $this->data = $data;
        $this->labels = $x_labels;
    }

    public function GenerateGraph($filename = '') {
        # Check if GD is installed
        #	If not, then default to gchart, otherwise use pchart
        #
        if ($this->pInvalid == true) {
            $this->setType('gchart', $this->orig_type);
        }

        if ($this->source == 'pchart') {
            return $this->pChart($filename);
        } elseif ($this->source == 'gchart') {
            return $this->GoogleChart();
        }
    }

    protected function pChart($filename) {
        $pData = new pData;
        $count = 1;

        # Give it a minute and a half
        @set_time_limit(30);
        $this->setFontSize(8);

        if (!$this->data || count($this->data) == 0) {
            return;
        }

        $pData->AddPoint($this->data, 'dataset');
        $pData->AddPoint($this->labels, 'labels');
        $pData->AddAllSeries();
        $pData->SetAbsciseLabelSerie('labels');

        $pData->SetYAxisName($this->y_title);
        $pData->SetXAxisName($this->x_title);

        # Set the file name:
        if ($filename == '') $filename = md5(implode('', $this->data));

        # Create a "frame"
        $this->pChart->drawBackground(255, 255, 255);
        $this->pChart->drawFilledRoundedRectangle(0, 0, $this->x, $this->y, 5, 255, 255,
            255);
        $this->pChart->drawRoundedRectangle(5, 5, $this->x - 5, $this->y - 5, 5, 230,
            230, 230);

        if ($this->type == 'pie') {
            $this->pChart->loadColorPalette('tones-1.txt');
            $this->pChart->drawBasicPieGraph($pData->GetData(), $pData->GetDataDescription(),
                200, 200, 120, PIE_PERCENTAGE);
            $this->pChart->drawPieLegend($this->x - 150, 30, $pData->GetData(), $pData->
                GetDataDescription(), 250, 250, 250);
        } elseif ($this->type == 'pie3d') {
            $this->pChart->loadColorPalette('tones-1.txt');
            $this->pChart->drawPieGraph($pData->GetData(), $pData->GetDataDescription(), 200,
                200, 120, PIE_PERCENTAGE, true);
            $this->pChart->drawPieLegend($this->x - 150, 30, $pData->GetData(), $pData->
                GetDataDescription(), 250, 250, 250);
        } elseif ($this->type == 'line') {
            $this->pChart->setGraphArea(90, 30, $this->x - 30, $this->y - 50);
            $this->pChart->drawScale($pData->GetData(), $pData->GetDataDescription(),
                SCALE_NORMAL, 0, 0, 0, true);
            $this->pChart->drawTreshold(0, 143, 55, 72, true, true);
            $this->pChart->drawGrid(4, true);

            //$this->pChart->drawLegend(90,35,$pData->GetDataDescription(),255,255,255);
            //$this->pChart->drawFilledLineGraph($pData->GetData(), $pData->GetDataDescription(), 20, true);
            //$this->pChart->drawCubicCurve($pData->GetData(), $pData->GetDataDescription());
            //$this->pChart->drawLimitsGraph($pData->GetData(), $pData->GetDataDescription());

            $this->pChart->drawFilledCubicCurve($pData->GetData(), $pData->
                GetDataDescription(), .01, 20, true);
            $this->pChart->drawPlotGraph($pData->GetData(), $pData->GetDataDescription(), 3,
                2);
            $this->pChart->drawTreshold(0, 143, 55, 72, true, true);
        } elseif ($this->type = 'bar') {
            $this->pChart->drawBarGraph($pData->GetData(), $pData->GetDataDescription(), true);
        }

        $this->setFontSize(11);
        $w = strlen($this->chart_title) * 1.5;
        @$this->pChart->drawTitle(0, 23, $this->chart_title, 0, 0, 0, $this->x);

        //$this->pCache->WriteToCache($filename,$pData->GetData(),$this->pChart);
        $this->pChart->Render(SITE_ROOT . '/core/cache/' . $filename . '.png');
        echo '<img src="' . SITE_URL . '/core/cache/' . $filename . '.png" />';
    }


    /**
     * Create a Google Chart chart
     */
    protected function GoogleChart() {
        $chart = new GoogleChart(null, $this->type);

        # Loop through every set data
        //foreach($this->data as $set)
        //{
        $values = @implode(',', $this->data);
        $labels = @implode('|', $this->labels);

        $chart->loadData($values);
        $chart->setLabels($labels, 'bottom');
        //}

        $chart->dimensions = $this->x . 'x' . $this->y;

        return $chart->draw(false);
    }
}
