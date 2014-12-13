<?php

//Temporary - don't judge me

$prefix = 'dpmm_';

$dpmm_meta_boxes = array(
    'id' => 'dpmm-meta-boxes',
    'title' => 'Menu Item Details',
    'page' => 'dpmm_type',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'THC %',
            'id' => $prefix . 'thc-amt',
            'type' => 'text',
            'std' => '%'
        ),
        array(
            'name' => 'CBD %',
            'id' => $prefix . 'cbd-amt',
            'type' => 'text',
            'std' => '%'
        ),
        array(
            'name' => 'Strain Type',
            'id' => $prefix . 'strain-type',
            'type' => 'radio',
            'options' => array(
                array('name' => 'Sativa', 'value' => 'Sativa'),
                array('name' => 'Indica', 'value' => 'Indica'),
                array('name' => 'Hybrid', 'value' => 'Hybrid'),
                array('name' => 'Varies', 'value' => 'Varies')
            )
        ),
    )
);