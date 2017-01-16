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

class OFCharts extends CodonData {
    protected static $chart;
    protected static $x_axis;
    protected static $y_axis;

    protected static $data_set = array();

    protected static $range;

    protected static function init() {
        include_once CORE_LIB_PATH . '/php-ofc-library/open-flash-chart.php';

        self::$chart = new open_flash_chart();
        self::$y_axis = new y_axis();
        self::$x_axis = new x_axis();
    }

    protected static function get_range($values) {
        # Determine a max
        $range = array('max' => $values[0], 'min' => $values[1], );

        foreach ($values as $v) {
            if ($v > $range['max']) $range['max'] = $v;

            if ($v < $range['min']) $range['min'] = $v;
        }

        if ($range['max'] == $range['min']) {
            $step = ceil($range['max'] / 2);
        } else {
            $diff = intval(abs($range['max'] - $range['min']));
            $step = ceil(($diff / 90));
        }

        $range['max'] += $step;
        $range['min'] -= $step;

        return $range;
    }

    public static function add_data_set($titles, $values, $line_title = '', $color =
        '#3D5C56') {
        self::$data_set[] = array('line_title' => $line_title, 'titles' => $titles,
            'values' => $values, 'color' => $color, );
    }

    public static function create_pie_graph($title) {
        self::init();

        $d = array();
        foreach (self::$data_set as $data) {
            $d[] = new pie_value($data['values'], $data['titles']);
        }

        $pie = new pie();
        $pie->start_angle(35)->add_animation(new pie_fade())->add_animation(new
            pie_bounce(4)) // ->label_colour('#432BAF') // <-- uncomment to see all labels set to blue
            ->gradient_fill()->tooltip('#val# of #total#<br>#percent# of 100%')->colours(array
            ('#1F8FA1', // <-- blue
            '#848484', // <-- grey
            '#CACFBE', // <-- green
            '#DEF799' // <-- light green
            ));

        $pie->set_values($d);

        self::$chart->add_element($pie);
        self::show_chart($title);
    }

    public static function create_area_graph($title) {
        self::init();

        $d = new solid_dot();
        $d->size(3)->halo_size(1)->colour('#3D5C56');

        $range_values = array();
        foreach (self::$data_set as $data) {
            if (!is_array($data['values'])) continue;

            $area = new area();
            // set the circle line width:
            $area->set_width(2);
            $area->set_default_dot_style($d);
            $area->set_colour($data['color']);
            $area->set_fill_colour($data['color']);
            $area->set_fill_alpha(.3);
            $area->on_show(new line_on_show('pop-up', 2, 0.5));
            $area->set_key($data['line_title'], 10);
            $area->set_values($data['values']);

            # Since there should be an even number on the xaxis for all sets
            $x_axis_titles = $data['titles'];

            # Add our values into a big bucket so we can get the highest and lowest
            $range_values = array_merge($range_values, $data['values']);

            self::$chart->add_element($area);
        }

        $x_labels = new x_axis_labels();
        $x_labels->set_labels($x_axis_titles);
        $x_labels->set_vertical();
        self::$x_axis->set_labels($x_labels);

        $range = self::get_range($range_values);
        self::$y_axis->set_range($range['min'], $range['max']);

        self::show_chart($title);
    }


    /**
     * Create a single line graph
     *
     * @param string $title Title of the graph
     * @param array $values Array of values
     * @param array $titles Array of titles
     * @return none
     *
     */
    public static function create_line_graph($title) {
        self::init();

        $d = new solid_dot();
        $d->size(3)->halo_size(1)->colour('#3D5C56');

        $range_values = array();
        foreach (self::$data_set as $data) {
            if (!is_array($data['values'])) continue;

            $line = new line();
            $line->set_default_dot_style($d);
            $line->set_values($data['values']);
            $line->set_width(2);
            $line->set_key($data['line_title'], 10);
            $line->set_colour($data['color']);

            # Since there should be an even number on the xaxis for all sets
            $x_axis_titles = $data['titles'];

            # Add our values into a big bucket so we can get the highest and lowest
            $range_values = array_merge($range_values, $data['values']);

            self::$chart->add_element($line);
        }

        $x_labels = new x_axis_labels();
        $x_labels->set_labels($x_axis_titles);
        $x_labels->set_vertical();
        self::$x_axis->set_labels($x_labels);

        $range = self::get_range($range_values);
        self::$y_axis->set_range($range['min'], $range['max']);

        self::show_chart($title);
    }

    protected static function show_chart($title) {
        $title = new title($title);

        self::$chart->set_title($title);
        self::$chart->set_y_axis(self::$y_axis);
        self::$chart->set_x_axis(self::$x_axis);
        self::$chart->set_bg_colour('#FFFFFF');

        #echo '<pre>';
        echo self::$chart->toPrettyString();
    }
}
