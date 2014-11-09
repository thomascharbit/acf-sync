<?php
/*
Plugin Name: ACF Sync
Plugin URI: https://github.com/FreshFlesh
Description: Sync ACF Group fields between different WordPress installs
Version: 1.0.0
Author: Thomas Charbit
Author URI: https://twitter.com/thomascharbit
Author Email: thomas.charbit@gmail.com
*/


if ( ! defined( 'WPINC' ) ) {
    die;
}

class ACFSync {


    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/


    /**
     * Initializes the plugin
     */

    function __construct() {
       
       // Sync fields on admin_init if needed
        add_action('admin_init', array( $this, 'check_acf_fields_version' ) );

        // On prod or staging, let ACF simply read fields from JSON
        if ( defined( 'WP_ENV' ) && 'development' != WP_ENV ) {

            // Don't save fields to JSON
            add_filter('acf/settings/save_json', '__return_null', 99 );
            
            // Don't show ACF UI
            add_filter('acf/settings/show_admin', '__return_false');
        }


    } // end constructor


    
    /*--------------------------------------------*
     * Core Functions
     *--------------------------------------------*/


    /*
    *  check_acf_fields_version
    *
    *  Compare fields version and import newer version if needed
    *
    *  @param   n/a
    *  @return  n/a
    */
    
    public function check_acf_fields_version() {

        if ( defined( 'ACF_FIELDS_VERSION' ) ) {

            $db_version = get_option( 'acf_fields_version' );

            // Import fields from local JSON if they are newer than in DB
            if ( version_compare( ACF_FIELDS_VERSION, $db_version ) > 0 ) {
                $this->import_json_field_groups();
                update_option( 'acf_fields_version', ACF_FIELDS_VERSION );
            }

        }

    }


    /*
    *  import_json_field_groups
    *
    *  Parse json load points paths and import all JSON files
    *
    *  @param   n/a
    *  @return  n/a
    */

    private function import_json_field_groups() {

        // Tell ACF NOT to save to local JSON while we delete groups in DB
        add_filter('acf/settings/save_json', '__return_null', 99 );

        // Remove previous field groups
        $args = array(
            'post_type'      => 'acf-field-group',
            'post_status'    => 'any',
            'posts_per_page' => -1
        );

        $query = new WP_Query( $args );

        foreach ( $query->posts as $acf_group ) {
            wp_delete_post( $acf_group->ID, true);
        }

        // Find local JSON load points directories
        $json_paths = acf_get_setting('load_json');

        foreach ($json_paths as $json_path) {

            $dir = new DirectoryIterator( $json_path );

            foreach( $dir as $file ) {
                
                if ( !$file->isDot() && 'json' == $file->getExtension() ) {

                    $json = json_decode( file_get_contents( $file->getPathname() ), true );
                    $this->import_json_field_group( $json );

                }

            }

        }

    }


    /*
    *  import_json_field_group
    *
    * import ACF field group from JSON data
    *
    *  @param   $json (array)
    *  @return  n/a
    */

    private function import_json_field_group( $json ) {
        
        // What follows is basically a copy of import() in ACF admin/settings-export.php

        // if importing an auto-json, wrap field group in array
        if( isset($json['key']) ) {
            $json = array( $json );
        }
        
        // vars
        $added   = array();
        $ignored = array();
        $ref     = array();
        $order   = array();
        
        foreach( $json as $field_group ) {
            
            // remove fields
            $fields = acf_extract_var($field_group, 'fields');
         
            // format fields
            $fields = acf_prepare_fields_for_import( $fields );

            // save field group
            $field_group = acf_update_field_group( $field_group );

            // add to ref
            $ref[ $field_group['key'] ] = $field_group['ID'];
            
            // add to order
            $order[ $field_group['ID'] ] = 0;
            
            
            // add fields
            foreach( $fields as $field ) {
                
                // add parent
                if( empty($field['parent']) ) {
                    
                    $field['parent'] = $field_group['ID'];
                    
                } elseif( isset($ref[ $field['parent'] ]) ) {
                    
                    $field['parent'] = $ref[ $field['parent'] ];
                        
                }
                
                // add field menu_order
                if( !isset($order[ $field['parent'] ]) ) {
                    
                    $order[ $field['parent'] ] = 0;
                    
                }
                
                $field['menu_order'] = $order[ $field['parent'] ];
                $order[ $field['parent'] ]++;
                
                // save field
                $field = acf_update_field( $field );

                // add to ref
                $ref[ $field['key'] ] = $field['ID'];
                
            }
            
        }
    }

}

new ACFSync();
