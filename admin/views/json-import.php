<script id="acf-sync-template" type="x-tmpl-mustache">
    <div class="acf-box">
        <div class="title">
            <h3><?php _e('Import field groups from local JSON', 'acf-sync'); ?></h3>
        </div>
        <div class="inner">
            <p><?php _e('Import manually the field groups from your local JSON folder.', 'acf-sync'); ?></p>
            
            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <div class="acf-hidden">
                    <input type="hidden" name="action" value="acf-manual-sync">
                    <input type="hidden" name="_acfnonce" value="<?php echo wp_create_nonce( 'acf-sync' ); ?>" />
                </div>
                <input type="submit" class="acf-button blue" value="<?php _e('Sync field groups', 'acf-sync'); ?>" />
            </form>
            
        </div>
        
    </div>
</script>

<script type="text/javascript">
    (function($) {

        $ACFWrapper = $('.acf-settings-wrap');

        if ( $ACFWrapper.length <= 0 ) return false;

        var html = $('#acf-sync-template').html();
        $(html).appendTo( $ACFWrapper );

    })(jQuery); 
</script>
