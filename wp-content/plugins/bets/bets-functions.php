<?php

add_action( 'init', 'best_register_post_type_init' ); // Использовать функцию только внутри хука init
 
function best_register_post_type_init() {
	$labels = array(
		'name' => 'Cтавки',
		'singular_name' => 'Cтавки на спорт', // админ панель Добавить->Функцию
		'add_new' => 'Добавить ставку',
		'add_new_item' => 'Добавить новую ставки', // заголовок тега <title>
		'edit_item' => 'Редактировать ставку',
		'new_item' => 'Новая ставка',
		'all_items' => 'Все ставки',
		'view_item' => 'Просмотр ставок на сайте',
		'search_items' => 'Искать ставку',
		'not_found' =>  'Ставок не найдено.',
		'not_found_in_trash' => 'В корзине нет ставкок.',
		'menu_name' => 'Cтавки' // ссылка в меню в админке
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true, // показывать интерфейс в админке
		'has_archive' => true, 
		'menu_icon' => 'dashicons-image-filter', // иконка в меню
		'menu_position' => 20, // порядок в меню
		'supports' => array( 'title', 'editor', 'comments', 'author', 'custom-fields'),
                'capability_type' => 'best',
                'map_meta_cap' => true,
		'taxonomies' => array('bidtype')
	);
	register_post_type('best', $args);
}


// хук для регистрации
add_action( 'init', 'create_taxonomy_bidtype' );
function create_taxonomy_bidtype() {

    // список параметров:
    register_taxonomy( 'bidtype', [ 'best' ], [ 
        'label'                 => '', // определяется параметром $labels->name
        'labels'                => [
            'name'              => 'Тип ставки',
            'singular_name'     => 'Genre',
            'search_items'      => 'Search ставки',
            'all_items'         => 'All ставки',
            'view_item '        => 'View ставки',
            'parent_item'       => 'Parent ставки',
            'parent_item_colon' => 'Parent ставки:',
            'edit_item'         => 'Edit ставки',
            'update_item'       => 'Update ставки',
            'add_new_item'      => 'Add New ставки',
            'new_item_name'     => 'New Genre Name',
            'menu_name'         => 'Тип ставки',
        ],
        'description'           => '', // описание таксономии
        'public'                => true,
        // 'publicly_queryable'    => null, // равен аргументу public
        // 'show_in_nav_menus'     => true, // равен аргументу public
        // 'show_ui'               => true, // равен аргументу public
        // 'show_in_menu'          => true, // равен аргументу show_ui
        // 'show_tagcloud'         => true, // равен аргументу show_ui
        // 'show_in_quick_edit'    => null, // равен аргументу show_ui
        'hierarchical'          => false,

        'rewrite'               => true,
        //'query_var'             => $taxonomy, // название параметра запроса
        'capabilities'          => array('assign_terms' => 'edit_bests'),
        'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
        'show_in_rest'          => null, // добавить в REST API
        //'rest_base'             => null, // $taxonomy
        // '_builtin'              => false,
        //'update_count_callback' => '_update_post_term_count',
    ] );
}




// Подключаем локализацию в самом конце подключаемых к выводу скриптов, чтобы скрипт
// 'twentyfifteen-script', к которому мы подключаемся, точно был добавлен в очередь на вывод.
add_action( 'wp_enqueue_scripts', 'btajax_data', 99 );
function btajax_data(){
    wp_localize_script( 'main.min', 'btajax', 
        array(
            'url' => admin_url('admin-ajax.php')
        )
    );  

}

if( wp_doing_ajax() ){
    add_action( 'wp_ajax_best_action', 'best_action_callback' );
    add_action('wp_ajax_nopriv_best_action', 'best_action_callback');
}
function best_action_callback() {
    
    $backArray = array();
    $bet_vote = (isset($_POST['betvote'])? $_POST['betvote']: 0);
    $bet_id =  (isset($_POST['betid'])? $_POST['betid']: 0);
    
    if (!is_numeric($bet_vote) || $bet_vote == 0) {
        //error_log('Зашли в 1');
        $backArray['success'] = false;
        $backArray['html'] = '<div class="alert alert-danger" role="alert">Введено не верное значение!</div>';
    } elseif (empty($bet_id) || !is_numeric($bet_id) || $bet_id == "") {
        //error_log('Зашли в 2');
        $backArray['success'] = false;
        $backArray['html'] = '<div class="alert alert-danger" role="alert">Не верный id поста!</div>';
    } else {
        $intBet_vote = (int) $bet_vote;
        if ($intBet_vote >= 100 && $intBet_vote <= 1000) {
            $outadd = update_post_meta($bet_id , 'bet_vote', $bet_vote);
            if ($outadd === true) {
                $backArray['success'] = true;
                $backArray['html'] = '<div class="alert alert-success" role="alert">Запись в post meta bet_vote обновлена!</div>';
            } elseif($outadd === false) {
                $backArray['success'] = false;
                $backArray['html'] = '<div class="alert alert-danger" role="alert">Запись не добавлена или передано такое же значение поля!</div>';
            } else {
                $backArray['success'] = true;
                $backArray['html'] = '<div class="alert alert-success" role="alert">Запись в post meta bet_vote - '.$outadd.' добавлена!</div>';
            }
        } else {
            $backArray['success'] = false;
            $backArray['html'] = '<div class="alert alert-danger" role="alert">Введите значения от 100 до 1000!</div>';
        }  
    }
    
    echo json_encode($backArray);
    
    wp_die();
}

add_action('wp_footer', 'best_action_javascript', 99); // для фронта
function best_action_javascript() {
    ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {
        var data = {
                action: 'best_action',
                betvote: 0,
                betid: 0
        };
        var bet_buttom = document.getElementById('add_bet_vote');
        if (bet_buttom != null) {
            bet_buttom.onclick = function() {
                var bet_container = document.querySelector("#bet-container");
                var bet_input = bet_container.querySelector(".value_vote");
                var bet_vote = bet_input.value;
                var bet_id = this.getAttribute('bet_id');

                if (bet_vote ===  "" || isNaN(bet_vote)) {
                    alert('Введите значения от 100 до 1000');
                    bet_input.focus();
                    return;
                }
                let intBet_vote = Number(bet_vote);
                if (!(intBet_vote >= 100 && intBet_vote <= 1000)) {
                    alert('Введите значения от 100 до 1000');
                    bet_input.focus();
                    return;
                }
                data.betvote = bet_vote;
                data.betid = bet_id;
                //ajax
                //console.log(data);
                jQuery.post( btajax.url, data, function(response) {
                    //удаляем все alerts
                    console.log(response);
                    let alertDiv = document.querySelector('.alert');
                    if (alertDiv !== null) alertDiv.remove();
                    if (response.success) {
                        //Делаем кнопку недоступной
                        bet_buttom.setAttribute('disabled',false);
                    }
                    bet_container.insertAdjacentHTML('afterbegin', response.html);

                },'json');
            };
        }
        
        
    });
    </script>
    <?php
}