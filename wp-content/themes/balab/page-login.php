<?php
/*
Template Name: page-login
*/
get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-12 mt-2">
            <h1 class="text-center mb-5"><?php the_title(); ?></h1>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col col-lg-6">
			<?php wp_login_form(); ?>
        </div>
    </div>
</div>
<?php
get_footer();