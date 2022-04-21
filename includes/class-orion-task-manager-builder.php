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

        register_post_type( 'o_task_manager', $args );
    }

    /**
    * Adds a submenu page under a custom post type parent.
    */
    public static function settings_my_custom_menu_page() {
        add_submenu_page(
            'edit.php?post_type=o_task_manager',
            __( 'Settings Task Manager', 'task' ),
            __( 'Settings', 'task' ),
            'manage_options',
            'settings_task',
            'Task_Manager_Builder::task_manager_settings_page'
        );
    }

    /**
    * Display callback for the submenu page.
    */
    public static function task_manager_settings_page() {
        $token = get_option('access_token'); 
        ?>
        <div class="wrap">
            <h1><?php _e( 'Settings Task Manager', 'task' ); ?></h1>
            <p><?php _e( 'Configure your ASANA task management', 'task' ); ?></p>
        <?php
            if( $token != '' ){
                $submit = 'UPDATE';
                $token = 'XXXX-XXXX-XXXX-XXX';
                _e( 'ASANA ACTIVE', 'task' );
            }
            else{
                $submit = 'SAVE';
                _e( 'Activated ASANA <a href="https://app.asana.com/" target="_blank">https://app.asana.com/</a>', 'task' );
            }
        ?>
        </div>
        <div class='block-form'>
           <?php
            $begin = array(
                'type' => 'sectionbegin',
                'id' => 'task-datasource-container',
            );

            $tokens = array(
                'title' => __( 'Token Access', 'task' ),
                'name' => 'tokens',
                'type' => 'text',
                'desc' => __( 'Enter the token', 'task' ),
                'default' => $token,
            );

            $btn = array(
                'title' => __( $submit, 'task' ),
                'type' => 'button',
                'id'  => 'submit',
                'default' => '',
            );

            $end = array( 'type' => 'sectionend' );
            $details = array(
                $begin,
                $tokens,
                $btn,
                $end,
            );
            ?>
            <form method="post" action="">
                <?php
                echo o_admin_fields( $details );
                ?>
            </form>
          </div>
        <?php
        //print_r( get_users( array('role' => 'orion_emploi') ) );
        get_json_calendar() ;
    }

    /**
     * Adds the metabox for the task_ CPT
     */
    public static function get_task_manager_metabox() {

        $screens = array( 'o_task_manager' );

        foreach ( $screens as $screen ) {

            add_meta_box(
                    'o_task_manager_box',
                    __( 'Task Configuration', 'Orion_task_manager' ),
                    'Task_Manager_Builder::get_task_manager_matabox',
                    $screen
            );
        }

    }

    // Création des colonnnes personnalisées
    public static function task_manager_colonne($columns) {
        unset( $columns['date'] );
        return array_merge($columns, 
        array(
            'assignee' => __('Assigne'),
            'due_date' => __('Due date')

        ));
    }

    // Affichage des données
    public static function data_colonne($name) {
        global $post;
        switch ($name) {
            case 'assignee':
                $task = get_post_meta($post->ID, 'o_task_manager');
                //var_dump($task);
                _e(get_user_asana_name($task[0]['assigne']));
            break;
            case 'due_date':
                $task = get_post_meta($post->ID, 'o_task_manager');
               _e(date("d/m/Y à H:i", strtotime($task[0]['date'])));
            break;
            default: '';
        }
    }


    public static function get_task_manager_matabox(){
        $user_asana = get_user_for_asana();
        $tasks = array('' => 'Choise') + get_all_task();
      ?>
        <div class='block-form'>

            <?php
            $begin = array(
                'type' => 'sectionbegin',
                'id' => 'task-datasource-container',
            );

            $assigne = array(
                'title' => __( 'Assigne', 'task' ),
                'name' => 'o_task_manager[assigne]',
                'type' => 'select',
                'desc' => __( 'Who to assign the task to?', 'task' ),
                'default' => '',
                'options' => $user_asana
            );

            $project = array(
                'title' => __( 'Projects', 'task' ),
                'name' => 'o_task_manager[project]',
                'type' => 'select',
                'desc' => __( 'Select the project to assign', 'task' ),
                'default' => '',
                'options' => get_asana_projet()
            );

            $subtask = array(
                'title' => __( 'SubTask', 'task' ),
                'name' => 'o_task_manager[subproject]',
                'type' => 'select',
                'desc' => __( 'Select a subproject', 'task' ),
                'default' => '',
                'options' => $tasks
            );

            $dependencies = array(
                'title' => __( 'Dependencies', 'task' ),
                'name' => 'o_task_manager[dependencies]',
                'type' => 'select',
                'desc' => __( 'Select dependencies', 'task' ),
                'default' => '',
                'options' => $tasks
            );

            $date = array(
                'title' => __( 'Due date', 'task' ),
                'name' => 'o_task_manager[date]',
                'type' => 'datetime-local',
                'desc' => __( 'Set due date', 'task' ),
                'default' => '',
            );

            $assignecodage = array(
                'title' => __( 'Assigne Codage', 'task' ),
                'name' => 'o_task_manager[assignecodage]',
                'type' => 'select',
                'desc' => __( 'Who to assign the codage task to?', 'task' ),
                'default' => '',
                'options' => $user_asana
            );

            $assignesuivi = array(
                'title' => __( 'Assigne Suivi', 'task' ),
                'name' => 'o_task_manager[assignesuivi]',
                'type' => 'select',
                'desc' => __( 'Who to assign the suivi task to?', 'task' ),
                'default' => '',
                'options' => $user_asana
            );

            $assignetest = array(
                'title' => __( 'Assigne Test', 'task' ),
                'name' => 'o_task_manager[assignetest]',
                'type' => 'select',
                'desc' => __( 'Who to assign the test task to?', 'task' ),
                'default' => '',
                'options' => $user_asana
            );


            $end = array( 'type' => 'sectionend' );
            $details = array(
                $begin,
                $assigne,
                $project,
                $subtask,
                $dependencies,
                $assignecodage,
                $assignesuivi,
                $assignetest,
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
        $meta_key = 'o_task_manager';
        $data_post   = wp_unslash( $_POST );
        if ( isset( $data_post[ $meta_key ] ) && ! empty( $data_post[ $meta_key ] ) ) {
            
            update_post_meta( $post_id, $meta_key, $data_post[ $meta_key ] );

        }
    }
}