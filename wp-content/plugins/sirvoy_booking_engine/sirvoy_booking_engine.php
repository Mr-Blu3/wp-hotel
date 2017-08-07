<?php
/*
Plugin Name: Sirvoy Booking Engine
Version: 2.6
Plugin URI: https://sirvoy.com/topic/booking-engine/installing-on-your-website/installing-the-booking-engine-on-wordpress/
Author: Sirvoy Ltd.
Author URI: http://www.sirvoy.com
Description: With this plugin you can easily add a booking engine to your Wordpress website and accept online bookings. The bookings will be registered in your Sirvoy account. This plugin is free to use, but you need to have a Sirvoy account. Sirvoy is an online booking system for guest houses, B&Bs and hotels.

Copyright (c) 2011-2017, Sirvoy Ltd.
Released under the GPL license
All rights reserved.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT
NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

register_activation_hook(__FILE__,'sirvoy_booking_install'); 
register_deactivation_hook( __FILE__, 'sirvoy_booking_remove' );

function sirvoy_booking_install()
{
    add_option("sirvoy_engine_id", '', '', 'yes');
}

function sirvoy_booking_remove()
{
    delete_option('sirvoy_engine_id');
}

add_shortcode("sirvoy", "sirvoy_booking_engine");
add_shortcode("sirvoy-booking-engine", "sirvoy_booking_engine");

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'sirvoy_booking_action_links' );

function sirvoy_booking_action_links( $links )
{
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=sirvoy-admin-menu') ) .'">Settings</a>';
   return $links;
}

function sirvoy_booking_engine($atts)
{
    if(! isset($atts['id']) && get_option('sirvoy_engine_id') == "")
        return "<div style='background: #eee; padding: 15px 20px; margin: 20px 0; color: darkred;'>No booking form ID has been specified. Please go to 'Settings -> Sirvoy Booking Engine' in WP.</div>";
    $str = '<script async type="text/javascript" src="https://secured.sirvoy.com/widget/sirvoy.js" data-form-id="';
    $str .= ((isset($atts['id']) && $atts['id'] != "") ? urlencode($atts['id']) : urlencode(get_option('sirvoy_engine_id'))) . '"';
    if (isset($atts['language']) && $atts['language'] != "")
        $str .= ' data-lang="' . urlencode($atts['language']) . '"';
    $str .= '></script>';
    return $str;
}

// admin page for entering widget ID
if (is_admin()) {
    add_action('admin_menu', 'sirvoy_menu');
    function sirvoy_menu() {
        add_options_page('Sirvoy Booking Engine', 'Sirvoy Booking Engine', 'administrator', 'sirvoy-admin-menu', 'sirvoy_admin_html_page');
    }
}

function sirvoy_admin_html_page()
{
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2>Sirvoy Booking Engine</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<div style="padding: 5px 0;">Booking module ID:</div>
<input name="sirvoy_engine_id" type="text" id="sirvoy_engine_id" size=40 maxlength=100 value="<?php echo get_option('sirvoy_engine_id'); ?>" />
<div style="font-style: italic; padding: 5px 0; color: gray;">Enter the booking module id as found in your Sirvoy account.</div>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="sirvoy_engine_id" />
<input type="submit" value="<?php _e('Save') ?>" />

<p>&nbsp;</p>
<p>&nbsp;</p>

<h3>How to use this plugin:</h3>
<p>Go to <i>Appearance -> Widgets</i> and add the booking widget to your sidebar.</p>
<p>Put this shortcode in pages and posts where you want to display the booking engine: <b>[sirvoy]</b></p>
<p>If you have multiple booking modules in your Sirvoy account, you can specify the booking module ID like this: <b>[sirvoy id="your-id-goes-here"]</b></p>
<p>The browser's language will be auto-detected and used (if your sirvoy account supports that language). But you may also specify a language like this: <b>[sirvoy language="fr"]</b></p>
<p>You can modify the color, font, and style of the booking form, in <i>Settings</i> in your Sirvoy account.</p>

</form>
</div>
<?php
}

// Widget for sidebar
class SirvoyWidget extends WP_Widget
{
    function __construct() {
        parent::__construct(
            'SirvoyWidget',
            'Sirvoy Booking Widget',
            array('classname' => 'SirvoyWidget', 'description' => 'Displays a small widget version of the Sirvoy Booking Engine.' )
        );
    }

    function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance)
    {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'];
            echo esc_html( $instance['title'] );
            echo $args['after_title'];
        }

        echo '<script async type="text/javascript" src="https://secured.sirvoy.com/widget/sirvoy.js" data-form-id="'
            . urlencode(get_option('sirvoy_engine_id')) . '" data-widget="small"></script>';

        echo $args['after_widget'];
    }
}

function sirvoy_booking_register_widgets() {
    register_widget('SirvoyWidget');
}

add_action('widgets_init', 'sirvoy_booking_register_widgets');
