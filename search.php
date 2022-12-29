<?php
/*
Plugin Name: Search
Description: Search
Author:  Belous Alex
Version: 1.0
*/

class Search
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'AddScript'));
        add_action('wp_ajax_affordance_search_data', array($this, 'AffordanceSearchData'));
        add_action('wp_ajax_noprev_affordance_search_data', array($this, 'AffordanceSearchData'));

        add_shortcode('Search', array($this, 'CreateShortcode'));
    }

    public function AddScript()
    {
        wp_enqueue_script('saf-app', plugin_dir_url(__FILE__) . '/js/app.js', array('jquery'));
        wp_localize_script('saf-app', 'ajax_search', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_style('stylesheet', plugins_url() . '/' . basename(dirname(__FILE__)) . '/css/front.css');
    }

    // ShortCode [Search]
    function CreateShortcode($atts)
    {
        ob_start();
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = [
            'post_type' => 'car',
            'posts_per_page' => 1,
            'paged' => $paged
        ];
        $carQuery = new WP_Query($args);
        $keyword = $_REQUEST['s'];
        ?>
        <form action="<?php echo home_url('/'); ?>" method="get">
            <input type="text" name="s" id="search_keyword" value="<?php echo $keyword; ?>" class="form-control search-input" placeholder="Search..."/>
            <button id="button-search" class="button-search">Search</button>
        </form>
        <div class="search-suggestion"></div>
        <?php
        if ($carQuery->have_posts()) :
            while ($carQuery->have_posts()) : $carQuery->the_post(); ?>
                <div class="inner-content-wrap">
                    <div class="cq-posts-list">
                        <h3 class="cq-h3"><?php the_title(); ?></h3>
                        <div>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                            <p><?php echo the_content(); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile;
        else:
            echo '<p><strong>' . _e('not found', 'wl-test-theme') . '</strong></p>';
        endif;
        $this->pagination($carQuery->max_num_pages);
        wp_reset_query();
        $output = ob_get_clean();
        return $output;
    }

    // search autocomplete
    function AffordanceSearchData()
    {
        $args = [
            'post_type' => 'car',
            'status' => 'publish',
            'relevanssi' => true,
        ];
        $output = '';
        $output = '<ul class="grid effect-3" id="grid">';

        if (isset($_POST['keyword']) && !empty($_POST['keyword'])) {
            $args['s'] = $_POST['keyword'];
        }

        $wpPosts = new WP_Query($args);

        if ($wpPosts->have_posts()) :
            while ($wpPosts->have_posts()) :
                $wpPosts->the_post();
                $output .= '<li class="col-md-4 col-sm-6 col-xs-12">
                        <h3 class="resources-content-heading"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>
                        <p class="resources-content-description">' . get_the_excerpt() . '</p>
                        <div class="resources-action-area">
                            <a href="' . get_permalink() . '" class="more-link">Read More <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                        </div>
                    </li>';
            endwhile;
        else:
            $output .= '<p><strong>' . _e('not found', 'wl-test-theme') . '</strong></p>';
        endif;

        $output .= '</ul>';
        wp_reset_query();
        echo $output;
        die;
    }

    // pagination from Post Car
    function pagination($pages = '', $range = 4)
    {
        $showItems = ( $range * 2 ) + 1;
        global $paged;
        if ( empty( $paged ) ) $paged = 1;{
            if ( 1 != $pages ) {
                echo "<nav aria-label='Page navigation example'>  <ul class='pagination'>";
                for ( $i = 1; $i <= $pages; $i++ ) {
                    if ( 1 != $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showItems ) ) {
                        echo ($paged == $i) ? "<li class='page-item active'><a class='page-link'>" . $i . "</a></li>" : "<li class='page-item'> <a href='" . get_pagenum_link($i) . "' class='page-link'>" . $i . "</a></li>";
                    }
                }
                echo "</ul></nav>\n";
            }
        }
    }
}

new Search();
?>