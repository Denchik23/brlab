<?php
/*
Plugin Name: Sports Betting
Description: Тестовое задание для комании br-lab!
Author: Id_Denchik
*/

define( 'BETS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
//JBdump(BETS__PLUGIN_DIR);
require_once( BETS__PLUGIN_DIR . 'bets-functions.php' );

//права для капперов
function bets_add_caps(){
    $admin = get_role( 'administrator' );
    $admin->add_cap( 'read_best' );
    $admin->add_cap( 'read_private_best' );
    $admin->add_cap( 'edit_best' );
    $admin->add_cap( 'edit_bests' );
    $admin->add_cap( 'edit_others_bests' );
    $admin->add_cap( 'edit_published_bests' );
    $admin->add_cap( 'edit_private_bests' );
    $admin->add_cap( 'delete_bests' );
    $admin->add_cap( 'delete_best' );
    $admin->add_cap( 'delete_others_bests' );
    $admin->add_cap( 'delete_published_best' );
    $admin->add_cap( 'delete_best' );
    $admin->add_cap( 'delete_private_best' );
    $admin->add_cap( 'publish_bests' );
    $admin->add_cap( 'moderate_best_comments' );
    
    $moderator = get_role( 'moderator' );
    $moderator->add_cap( 'read_best' );
    $moderator->add_cap( 'read_private_best' );
    $moderator->add_cap( 'edit_best' );
    $moderator->add_cap( 'edit_bests' );
    $moderator->add_cap( 'edit_others_bests' );
    $moderator->add_cap( 'edit_published_bests' );
    $moderator->add_cap( 'edit_private_bests' );
    $moderator->add_cap( 'delete_best' );
    $moderator->add_cap( 'delete_bests' );
    $moderator->add_cap( 'delete_others_bests' );
    $moderator->add_cap( 'delete_published_bests' );
    $moderator->add_cap( 'delete_private_best' );
    $moderator->add_cap( 'publish_bests' );
    $moderator->add_cap( 'moderate_best_comments' );
    
    
    $capper = get_role( 'capper' );
    $capper->add_cap( 'read_best' );
    $capper->add_cap( 'read_private_best' );
    $capper->add_cap( 'edit_best' );
    $capper->add_cap( 'edit_bests' );
    $capper->add_cap( 'edit_published_bests' );
    $capper->add_cap( 'edit_private_bests' );
    $capper->add_cap( 'delete_best' );
    $capper->add_cap( 'delete_bests' );
    $capper->add_cap( 'publish_bests' );
}
add_action( 'admin_init', 'bets_add_caps');


//Сбрасывае урл
register_activation_hook( __FILE__, 'best_install' ); 
function best_install() {
    // Запускаем функцию регистрации типа записи
    best_register_post_type_init();
    //добавляем роли
    add_role('capper', 'Каппер', array(
        'read' => true,
    ));
    add_role('moderator', 'Модератор', array(
        'read' => true,
    ));
    // Сбрасываем настройки ЧПУ, чтобы они пересоздались с новыми данными
    flush_rewrite_rules();
}

//Очистка 
function bets_remove_roles(){
    //проверяем, существует ли роль, перед тем как ее удалить
    if( get_role('capper') ){
        remove_role( 'capper' );
    }
    if( get_role('moderator') ){
        remove_role( 'moderator' );
    }

    $admin = get_role( 'administrator' );

    $caps = array(
        'read_best',
        'read_private_best',
        'edit_best',
        'edit_bests',
        'edit_others_bests',
        'edit_published_bests',
        'edit_private_bests',
        'delete_bests',
        'delete_best',
        'delete_others_bests',
        'delete_published_best',
        'delete_best',
        'delete_private_best',
        'publish_bests',
        'moderate_best_comments'
    );

    foreach ( $caps as $cap ) {
        $admin->remove_cap( $cap );
    }	
}
register_deactivation_hook( __FILE__, 'bets_remove_roles' );

?>