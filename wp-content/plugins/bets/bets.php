<?php
/*
Plugin Name: Sports Betting
Description: Тестовое задание для комании br-lab!
Author: Id_Denchik
*/

define( 'BETS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
//JBdump(BETS__PLUGIN_DIR);
require_once( BETS__PLUGIN_DIR . 'bets-functions.php' );

//Сбрасывае урл
register_activation_hook( __FILE__, 'best_install' ); 
function best_install() {
	// Запускаем функцию регистрации типа записи
	best_register_post_type_init();
	//добавляем роли
	add_role('capper', 'Каппер', array(
		'read' => true,
		
	));
	// Сбрасываем настройки ЧПУ, чтобы они пересоздались с новыми данными
	flush_rewrite_rules();
}
?>