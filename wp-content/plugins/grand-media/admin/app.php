<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])){
    die('You are not allowed to call this page directly.');
}

/**
 * gmSettings()
 * @return mixed content
 */
function gmediaApp(){
    global $gmCore, $gmGallery;

    if(false !== ($force_app_status = $gmCore->_get('force_app_status'))){
        $gm_options               = get_option('gmediaOptions');
        $gm_options['mobile_app'] = (int)$force_app_status;
        update_option('gmediaOptions', $gm_options);
    }
    $alert     = '';
    $btn_state = '';
    if('127.0.0.1' == $_SERVER['REMOTE_ADDR'] || '::1' == $_SERVER['REMOTE_ADDR']){
        $alert     = $gmCore->alert('danger', __('Your server is not accessable by iOS application', 'grand-media'));
        $btn_state = ' disabled';
    }

    $site_email       = $gmGallery->options['site_email'];
    $site_title       = $gmGallery->options['site_title'];
    $site_description = $gmGallery->options['site_description'];
    $site_ID          = $gmGallery->options['site_ID'];
    $mobile_app       = (int)$gmGallery->options['mobile_app'];

    ?>
    <form class="panel panel-default" method="post" id="gm_application">
        <div class="panel-heading clearfix">
            <div class="btn-toolbar pull-left gm_service_actions">
                <div class="btn-group<?php echo $mobile_app? '' : ' hidden' ?>">
                    <button type="button" name="gmedia_application_deactivate" data-action="app_deactivate" class="btn btn-danger<?php echo $btn_state; ?>" data-confirm="<?php _e('Exclude your website from GmediaService?') ?>"><?php _e('Disable GmediaService', 'grand-media'); ?></button>
                    <button type="button" name="gmedia_application_updateinfo" data-action="app_updateinfo" class="btn btn-primary<?php echo $btn_state; ?>"><?php _e('Update Info', 'grand-media'); ?></button>
                </div>
                <button type="button" name="gmedia_application_activate" data-action="app_activate" class="gmapp_activate btn btn-primary<?php echo $btn_state . ($mobile_app? ' hidden' : ''); ?>"><?php _e('Enable GmediaService', 'grand-media'); ?></button>
            </div>
            <div class="spinner"></div>
            <?php
            wp_nonce_field('GmediaService');
            ?>
        </div>
        <div class="panel-body" id="gmedia-msg-panel"><?php echo $alert; ?></div>
        <div class="panel-body" id="gm_application_data">
            <?php if(current_user_can('manage_options')){ ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-6">
                            <?php /* ?>
                            <div class="gm_service_actions">
                                <p><button type="button" data-action="app_activate" class="btn btn-primary"><?php _e('Activate GmediaService', 'grand-media'); ?></button>
                                    <button type="button" data-action="app_deactivate" class="btn btn-primary"><?php _e('Deactivate GmediaService', 'grand-media'); ?></button></p>

                                <p><button type="button" data-action="app_updateinfo" class="btn btn-primary"><?php _e('Update GmediaService', 'grand-media'); ?></button>
                                    <button type="button" data-action="app_updatecron" class="btn btn-primary"><?php _e('Cron Job GmediaService', 'grand-media'); ?></button></p>

                                <p><button type="button" data-action="app_deactivateplugin" class="btn btn-primary"><?php _e('Deactivate Plugin GmediaService', 'grand-media'); ?></button>
                                    <button type="button" data-action="app_uninstallplugin" class="btn btn-primary"><?php _e('Uninstall Plugin GmediaService', 'grand-media'); ?></button></p>
                            </div>
                            <?php */ ?>
                            <!--<p><?php echo 'Server address: ' . $_SERVER['SERVER_ADDR'];
                            echo '<br>Remote address: ' . $_SERVER['REMOTE_ADDR'];
                            ?></p>-->
                            <div class="gmapp-description">
                                <div style="text-align:center; margin-bottom:30px;">
                                    <a target="_blank" href="https://itunes.apple.com/ua/app/gmedia/id947515626?mt=8"><img style="vertical-align:middle; max-width:100%; margin:0 30px; max-height:88px;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/icon-128x128.png" alt=""/></a>
                                    <a target="_blank" href="https://itunes.apple.com/ua/app/gmedia/id947515626?mt=8"><img style="vertical-align:middle; max-width:100%; margin:0 30px;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/appstore_button.png"/></a>
                                </div>

                                <p><?php _e('You are using one of the best plugins to create media library as well as your personal cloud storage on your WordPress website. You have chosen <strong><a href="https://wordpress.org/plugins/grand-media/" target="_blank">Gmedia Gallery Plugin</a></strong> and this choice gives you great opportunities to manage and organise your media library.', 'grand-media'); ?></p>
                                <p><?php _e('We are happy to offer you a simple way to access your photos and audios by means of your iOS devices: at a few taps and you will be able to create great photo gallery and share it with your friends, readers and subscribers.', 'grand-media'); ?></p>

                                <p class="text-center"><img style="max-width:90%;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/slide1.jpg" alt=""/></p>

                                <div class="text-left" style="padding-top:40%;">
                                    <div style="margin-right:20%">
                                        <h3><?php _e('DISCOVER and SHARE', 'grand-media'); ?></h3>
                                        <p><?php _e('Search, learn, open new horizons, share! It is just as easy as a piece of cake! Your photos will be seen by your friends, relatives and others.', 'grand-media'); ?></p>
                                    </div>
                                    <p><img style="max-width:90%;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/slide3.jpg" alt=""/></p>
                                </div>
                                <div class="text-left" style="padding-top:40%;">
                                    <div style="margin-right:20%">
                                        <h3><?php _e('PRIVATE CONTENT', 'grand-media'); ?></h3>
                                        <p><?php _e('If you are one of subscribers, contributors, authors, editors or administrators, use your login and password to get an access to the private content.', 'grand-media'); ?></p>
                                    </div>
                                    <p><img style="max-width:90%;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/slide5.jpg" alt=""/></p>
                                </div>

                                <div class="well well-lg text-center" style="margin-top:40%; padding-top:50px;">
                                <p><?php _e('Download Gmedia iOS application from the App Store to manage your Gmedia&nbsp;Library from iPhone.', 'grand-media'); ?></p>
                                <div>
                                    <a target="_blank" href="https://itunes.apple.com/ua/app/gmedia/id947515626?mt=8"><img style="vertical-align:middle; max-width:100%; margin:30px;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/appstore_button.png"/></a>
                                    <a target="_blank" href="https://itunes.apple.com/ua/app/gmedia/id947515626?mt=8"><img style="vertical-align:middle; max-width:100%; margin:30px; max-height:88px;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/icon-128x128.png" alt=""/></a>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="well-lg well">
                                <p><?php _e('Below you can see information about your website that will be used by GmediaService and iOS application, so you\'ll be able to manage your Gmedia Library with your smartphone and other people can find and view your public collections.', 'grand-media'); ?></p>
                                <div class="form-group">
                                    <label><?php _e('Email', 'grand-media') ?>:</label>
                                    <input type="text" name="site_email" class="form-control input-sm" value="<?php esc_attr_e($site_email); ?>" placeholder="<?php esc_attr_e(get_option('admin_email')); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Site URL', 'grand-media') ?>:</label>
                                    <input type="text" readonly="readonly" name="site_url" class="form-control input-sm" value="<?php echo home_url(); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Site Title', 'grand-media') ?>:</label>
                                    <input type="text" name="site_title" class="form-control input-sm" value="<?php esc_attr_e($site_title); ?>" placeholder="<?php esc_attr_e(get_bloginfo('name')); ?>"/>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Site Description', 'grand-media') ?>:</label>
                                    <textarea rows="2" cols="10" name="site_description" class="form-control input-sm" placeholder="<?php esc_attr_e(get_bloginfo('description')); ?>"><?php esc_html_e($site_description); ?></textarea>
                                </div>
                                <p><?php _e('Also the list of your Gmedia Tags will be shared with GmediaService.') ?></p>
                            </div>

                            <div class="gmapp-description">
                                <div class="text-right" style="padding-top:35%;">
                                    <div style="margin-left:20%">
                                        <h3><?php _e('FIND and ADD SITE it’s SIMPLY', 'grand-media'); ?></h3>
                                        <p><?php _e('Just a few touches and our smart search bar will let you find and add your website, your friend’s website or a famous blogger’s site to your favourites list.', 'grand-media'); ?></p>
                                    </div>
                                    <p><img style="max-width:90%;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/slide2.jpg" alt=""/></p>
                                </div>

                                <div class="text-right" style="padding-top:35%;">
                                    <div style="margin-left:20%">
                                        <h3><?php _e('MP3', 'grand-media'); ?></h3>
                                        <p><?php _e('Take your favourite music track with you on a trip or create a playlist to travel with it! It is so simple with Gmedia. Share your energy and positive mood with your friends!', 'grand-media'); ?></p>
                                    </div>
                                    <p><img style="max-width:90%;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/slide4.jpg" alt=""/></p>
                                </div>

                                <div class="text-right" style="padding-top:35%;">
                                    <div style="margin-left:20%">
                                        <h3><?php _e('GMEDIA LIBRARY', 'grand-media'); ?></h3>
                                        <p><?php _e('If you are one of subscribers, contributors,authors, editors or administrators, use your login and password to get an access to private content. If your type of users has an access to Gmedia Library, you will be able to create photo collections and download pictures just from iPhone, using wide functional opportunities of our app and plugin.', 'grand-media'); ?></p>
                                    </div>
                                    <p><img style="max-width:90%;" src="<?php echo $gmCore->gmedia_url; ?>/admin/assets/img/slide6.jpg" alt=""/></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    jQuery(function($) {

                        function gmedia_application(service) {
                            var post_data = {
                                action: 'gmedia_application',
                                service: service,
                                data: $('#gm_application_data :input').serialize(),
                                _wpnonce: $('#_wpnonce').val()
                            };
                            $.post(ajaxurl, post_data, function(data, textStatus, jqXHR) {
                                console.log(data);
                                if(data.error) {
                                    $('#gmedia-msg-panel').append(data.error);
                                } else if(data.message) {
                                    $('#gmedia-msg-panel').append(data.message);
                                }
                                //noinspection JSUnresolvedVariable
                                if(parseInt(data.mobile_app)) {
                                    $('.gm_service_actions > .btn-group').removeClass('hidden');
                                    $('.gmapp_activate').addClass('hidden');
                                } else {
                                    $('.gm_service_actions > .btn-group').addClass('hidden');
                                    $('.gmapp_activate').removeClass('hidden');
                                }
                            });
                        }

                        <?php if($mobile_app){ ?>
                        gmedia_application('app_checkstatus');
                        <?php } ?>

                        $('.gm_service_actions button').on('click', function() {
                            var service = $(this).attr('data-action');
                            gmedia_application(service);
                        });

                    });

                </script>
            <?php } ?>
        </div>
    </form>
    <?php
}