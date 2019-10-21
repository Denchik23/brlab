<?php
/**
 * Тема для BrLab
 *
 */

add_action( 'wp_enqueue_scripts', 'theme_brlab_scripts' );

function theme_brlab_scripts() {
    wp_register_style( 'main.min', get_template_directory_uri() . '/css/main.min.css', array(), '1.2', 'screen');
    wp_enqueue_style( 'main.min' );
    wp_enqueue_script( 'main.min', get_template_directory_uri() . '/js/main.min.js', array(), '1.0.0', true );
}

/*
 * Получает и выводит тип ставки
 */
function getbets_bidtype($post) {
    $out = '';
    if (is_object($post) && $post !== NULL) {
        $bidtype = get_the_terms($post, 'bidtype' );
        $ib = 0;
		if ($bidtype) {
			foreach( $bidtype as $termin ){
				if ($ib == 0) $out.= $termin->name; else $out.= ' ,'.$termin->name;   
				$ib++;
			}
		}
        return $out;
    } else {
        if (WP_DEBUG) {
            error_log('Нет объекта поста для функции getbets_bidtype. Функция выводит тип ставки');
        }
    }   
}

/*
 * Список для тика ставок для тега select при добавлении ставки
 */
function getBidtypeList() {
	$out = '<option value="0">Выберите тип ставки</option>';
	$terms = get_terms( [
		'taxonomy' => 'bidtype',
		'hide_empty' => false,
	]);
	if (!empty($terms->errors)) {	
		if (WP_DEBUG) {
            error_log($terms->get_error_message());
        }
	} else {
		foreach ($terms as $bidtype) {
			$out .= '<option value="'.$bidtype->name.'">'.$bidtype->name.'</option>';
		}
	}
	
	return $out;
}

/*
 * Обрезаем латинские символы через фильтр
 */
function trimLatinCharacters($arrayData) {
	$arrayData['post_content'] = preg_replace("/[a-z]/i", "", $arrayData['post_content']);
	return $arrayData;
}
add_filter( 'filter_add_best', 'trimLatinCharacters' );