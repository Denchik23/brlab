<?php
get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-2">Ставки</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Ставка</th>
                  <th scope="col">Играть</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                        $args = array( 'post_type'   => 'best', );              
                        $lastposts = get_posts( $args );
                        //JBdump($lastposts, 0);
                        if (!empty($lastposts)) {
                            foreach( $lastposts as $post ){ 
                                setup_postdata($post);
                                get_template_part( 'template-parts/content', 'table');
                            }
                        } else {
                            get_template_part( 'template-parts/nocontent', 'table');
                        }
                        
                        wp_reset_postdata();
                    ?>
              </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mt-5">
            <a href="<?php echo get_permalink(8);?>" class="btn btn-primary">Добавить ставку</a>
        </div>
    </div>
</div>
<?php
get_footer();
