<?php
class SMRC_FILTER extends WP_Widget {
    function __construct() {
        parent::__construct(
            'smrc_filter',

            __('Simple Recipe Filter widget', 'simple_recipe'),

            array()
        );
    }
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        SMRC_Helpers::load_template('filter/filters');

        echo $args['after_widget'];
    }

}