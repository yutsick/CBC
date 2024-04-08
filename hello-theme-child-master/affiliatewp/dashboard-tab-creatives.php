<div id="affwp-affiliate-dashboard-creatives" class="affwp-tab-content">

	<h4>
		<?php _e( 'Creatives', 'affiliate-wp' ); ?>
		<span class="tooltip" title="Below are Images you can click to download, or copy the code in to your site to use for your own marketing materials, please let us know of any other creatives you would like to assist with your marketing initiatives.">
			<i class="fa fa-info-circle"></i>
		</span>
	</h4>
	
	<p>
		<hr>
	</p>

	<?php
	$per_page  = 30;
	$page      = affwp_get_current_page_number();
	$pages     = absint( ceil( affiliate_wp()->creatives->count( array( 'status' => 'active' ) ) / $per_page ) );
	$args      = array(
		'number' => $per_page,
		'offset' => $per_page * ( $page - 1 )
	);
	$creatives = affiliate_wp()->creative->affiliate_creatives( $args );
	?>

	<?php if ( $creatives ) : ?>

		<?php
		/**
		 * Fires immediately before creatives in the creatives tab of the affiliate area.
		 *
		 * @since 1.0
		 */
		do_action( 'affwp_before_creatives' );
		?>

		<?php echo $creatives; ?>

		<?php if ( $pages > 1 ) : ?>

			<p class="affwp-pagination">
				<?php
				echo paginate_links(
					array(
						'current'  => $page,
						'total'    => $pages,
						'add_args' => array(
							'tab' => 'creatives',
						),
					)
				);
				?>
			</p>

		<?php endif; ?>

		<?php
		/**
		 * Fires immediately after creatives in the creatives tab of the affiliate area.
		 *
		 * @since 1.0
		 */
		do_action( 'affwp_after_creatives' );
		?>

	<?php else : ?>

		<p class="affwp-no-results"><?php _e( 'Sorry, there are currently no creatives available.', 'affiliate-wp' ); ?></p>

	<?php endif; ?>

</div>
