<div class="search_main">
    <form method="get" class="searchform" action="<?php echo home_url( '/' ); ?>" >
<!--         <input type="text" class="field s" name="s" value="<?php _e( 'ស្វែងរក ...', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'ស្វែងរក ...', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'ស្វែងរក ...', 'woothemes' ); ?>';}" /> -->
        <input type="text" class="field s" name="s"/>
        <input type="image" src="<?php echo get_template_directory_uri(); ?>/images/ico-search.png" alt="<?php _e( 'Search', 'woothemes' ); ?>" class="submit" name="submit" />
    </form>    
    <div class="fix"></div>
</div>
