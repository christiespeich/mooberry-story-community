
	<div class="wrap cmb2-options-page option-<?php echo $option_key; ?>">
		<?php if ( $admin_title ) : ?>
			<h2><?php echo wp_kses_post( $admin_title ); ?></h2>
		<?php endif; ?>
		<h2 class="nav-tab-wrapper">
            <?php foreach ( $tabs as $tab_option_key => $tab_title ) : ?>
				<a class="nav-tab<?php if ( isset( $_GET['page'] ) && $tab_option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $tab_option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
			<?php endforeach; ?>

            <!--
			<?php /*foreach ( $tabs as $tab ) : */?>
				<a class="nav-tab<?php /*echo isset( $_GET['page'] ) && $tab->option_key === $_GET['page'] ? '  nav-tab-active' : '' */?>" href="<?php /* menu_page_url($tab->option_key); */?>"><?php /*echo wp_kses_post( $tab->tab_title ); */?></a>
			--><?php /*endforeach; */?>
		</h2>
		<form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo $cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo esc_attr( $option_key ); ?>">
			<?php $cmb_options->options_page_metabox(); ?>
			<?php submit_button( esc_attr( $save_button_text ), 'primary', 'submit-cmb' ); ?>
		</form>
	</div>
