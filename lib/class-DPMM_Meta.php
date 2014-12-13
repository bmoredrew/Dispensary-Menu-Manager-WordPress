<?php

/**
 * Custom Meta Class
 *
 * @class DPMM_Meta
 * @since 1.0
 */

class DPMM_Meta
{
	public static $instance;

	public static function init()
	{
		if ( is_null( self::$instance ) )
			self::$instance = new DPMM_Meta();
		return self::$instance;
	}

	private function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'add' ));
		add_action( 'save_post', array( $this, 'save' ));
	}

	public function meta_store($post)
	{

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
	}

	public function add()
	{
	    global $dpmm_meta_boxes;

	    add_meta_box($dpmm_meta_boxes['id'], $dpmm_meta_boxes['title'], array($this, 'show'), $dpmm_meta_boxes['page'], $dpmm_meta_boxes['context'], $dpmm_meta_boxes['priority']);
	}

	public function show()
	{
	    global $dpmm_meta_boxes, $post;
	    
	    echo '<input type="hidden" name="dpmm_meta_box_nonce" value="', wp_create_nonce( basename(__FILE__) ), '" />';
	    echo '<table class="form-table">';
	    foreach ($dpmm_meta_boxes['fields'] as $field) {
	        
	        $meta = get_post_meta($post->ID, $field['id'], true);
	        echo '<tr>',
	                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
	                '<td>';
	        switch ($field['type']) {
	            case 'text':
	                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="10" style="width:20%" />';
	                break;
	            case 'radio':
	                foreach ($field['options'] as $option) {
	                    echo '&nbsp;<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
	                }
	                break;
	        }
	        echo     '</td><td>',
	            '</td></tr>';
	    }
	    echo '</table>';
	}

	public function save($post_id) 
	{
	    global $dpmm_meta_boxes;
	    
	    if ( !isset( $_POST['dpmm_meta_box_nonce'] ) 
	    	|| !wp_verify_nonce($_POST['dpmm_meta_box_nonce'], basename(__FILE__))) {
	        return $post_id;
	    }

	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return $post_id;
	    }

	    if ('page' == $_POST['post_type']) {
	        if (!current_user_can('edit_page', $post_id)) {
	            return $post_id;
	        }
	    } elseif (!current_user_can('edit_post', $post_id)) {
	        return $post_id;
	    }
	    foreach ($dpmm_meta_boxes['fields'] as $field) {
	        $old = get_post_meta($post_id, $field['id'], true);
	        $new = $_POST[$field['id']];
	        if ($new && $new != $old) {
	            update_post_meta($post_id, $field['id'], $new);
	        } elseif ('' == $new && $old) {
	            delete_post_meta($post_id, $field['id'], $old);
	        }
	    }
	}

}

DPMM_Meta::init();