<?php
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

class Task_Manager_Builder {

    /**
     * Registers the task custom post type
     */
    public static function register_cpt_task_manager() {

        $labels = array(
            'name' => __( 'Task', 'task' ),
            'singular_name' => __( 'Task', 'task' ),
            'add_new' => __( 'New Task', 'task' ),
            'add_new_item' => __( 'New Task', 'task' ),
            'edit_item' => __( 'Edit task ', 'task' ),
            'new_item' => __( 'New task', 'task' ),
            'view_item' => __( 'View task', 'task' ),
            'not_found' => __( 'No task found', 'task' ),
            'not_found_in_trash' => __( 'No task in the trash', 'task' ),
            'menu_name' => __( 'Task Manager', 'task' ),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'Tasks',
            'supports' => array( 'title' ),
            'menu_icon' => 'dashicons-code-standards',
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => false,
            'can_export' => true,
        );

        register_post_type( 'o-task-manager', $args );
    }

    /**
     * Adds the metabox for the task_ CPT
     */
    public static function get_task_manager_metabox() {

        $screens = array( 'o-task-manager' );

        foreach ( $screens as $screen ) {

            add_meta_box(
                    'o-task-manager-box',
                    __( 'Orion Task Manager', 'Orion_task_manager' ),
                    'Task_Manager_Builder::get_task_manager_matabox',
                    $screen
            );
        }
    }

    public static function get_task_manager_matabox(){
      ?>
        <div class='block-form'>
            <?php
            $begin = array(
                'type' => 'sectionbegin',
                'id' => 'task-datasource-container',
            );

            $assigne = array(
                'title' => __( 'Assigne', 'task' ),
                'name' => 'o-task-manager[assigne]',
                'type' => 'select',
                'desc' => __( 'Who to assign the task to?', 'task' ),
                'default' => '',
                'options' => array()
            );

            $project = array(
                'title' => __( 'Projects', 'task' ),
                'name' => 'o-task-manager[project]',
                'type' => 'select',
                'desc' => __( 'Select the project to assign', 'task' ),
                'default' => '',
                'options' => array(
                  
                )
            );

            $subproject = array(
                'title' => __( 'Subproject', 'task' ),
                'name' => 'o-task-manager[subproject]',
                'type' => 'select',
                'desc' => __( 'Select a subproject', 'task' ),
                'default' => '',
                'options' => array(
                    
                )
            );

            $dependencies = array(
                'title' => __( 'Dependencies', 'task' ),
                'name' => 'o-task-manager[dependencies]',
                'type' => 'select',
                'desc' => __( 'Select dependencies', 'task' ),
                'default' => '',
                'options' => array(
                    
                )
            );

            $date = array(
                'title' => __( 'Due date', 'task' ),
                'name' => 'o-task-manager[date]',
                'type' => 'datetime-local',
                'desc' => __( 'Set due date', 'task' ),
                'default' => '',
            );


            $end = array( 'type' => 'sectionend' );
            $details = array(
                $begin,
                $assigne,
                $project,
                $subproject,
                $dependencies,
                $date,
                $end,
            );
            echo o_admin_fields( $details );
            ?>
        </div>
        <?php
        return;
    }

    /**
     * function register annonce
     */
    public static function save_post_task( $post_id ){
        $meta_key = 'o-task-manager';
        $data_post   = wp_unslash( $_POST );
        if ( isset( $data_post[ $meta_key ] ) && ! empty( $data_post[ $meta_key ] ) ) {
            
            update_post_meta( $post_id, $meta_key, $data_post[ $meta_key ] );

        }
    }
}

    