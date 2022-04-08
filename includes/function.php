<?php 

if ( isset( $_POST['tokens'] ) && ! empty( $_POST['tokens'] ) ) {
	$data_post   = wp_unslash( $_POST['tokens'] );
    update_option( 'access_token', $data_post );
}