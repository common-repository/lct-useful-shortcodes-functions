<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/** @noinspection PhpUndefinedVariableInspection */
$ereminder_array = $data['list'];
$debug           = false;
?>
<table class="widefat">
	<thead>
	<tr>
		<th class="id">ID</th>
		<th class="content" style="width: 25%;"><?php _e( 'Reminder', 'TD_LCT' ); ?></th>
		<th class="date"><?php _e( 'Send Reminder on', 'TD_LCT' ); ?></th>
		<th class="email"><?php _e( 'Send To', 'TD_LCT' ); ?></th>
		<th class="action"><?php _e( 'Action', 'TD_LCT' ); ?></th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<th class="id"><?php _e( 'ID', 'TD_LCT' ); ?></th>
		<th class="content" style="width: 25%;"><?php _e( 'Reminder', 'TD_LCT' ); ?></th>
		<th class="date"><?php _e( 'Send Reminder on', 'TD_LCT' ); ?></th>
		<th class="email"><?php _e( 'Send To', 'TD_LCT' ); ?></th>
		<th class="action"><?php _e( 'Action', 'TD_LCT' ); ?></th>
	</tr>
	</tfoot>
	<tbody>
	<?php if ( empty( $ereminder_array ) ) : ?>
		<tr>
			<td colspan="5"><?php _e( 'No reminders found.', 'TD_LCT' ); ?></td>
		</tr>
	<?php else : ?>
		<?php foreach ( $ereminder_array as $ereminder ) {
			$meta         = [];
			$other_emails = [];


			/**
			 * Already Sent
			 */
			if ( $ereminder->post_status === 'publish' ) {
				if ( $debug ) {
					continue;
				}


				if (
					function_exists( 'x_zxzacf' )
					&& ( $job_link = get_post_meta( $ereminder->ID, x_zxzacf( 'job_link' ), true ) )
				) {
					$meta[] = '<br />';
					$meta[] = sprintf( 'Post ID: %s (%s)', $job_link, get_post_type( $job_link ) );
				} elseif (
					function_exists( 'x_zxzacf' )
					&& ( $job_link = get_post_meta( $ereminder->ID, x_zxzacf( 'post_id' ), true ) )
				) {
					$meta[] = '<br />';
					$meta[] = sprintf( 'Post ID: %s (%s)', $job_link, get_post_type( $job_link ) );
				} elseif ( $reminder_post_id = get_post_meta( $ereminder->ID, zxzacf( 'post_id' ), true ) ) {
					$meta[] = '';
					$meta[] = sprintf( 'Post ID: %s (%s)', $reminder_post_id, get_post_type( $reminder_post_id ) );
				}


				$content = '<strong>' . $ereminder->post_title . '</strong>' . lct_return( $meta );


				/**
				 * Cc email address(es)
				 */
				if ( $cc = get_field( zxzacf( 'cc' ), $ereminder->ID ) ) {
					if ( ! is_array( $cc ) ) {
						$cc = [ $cc ];
					}

					$other_emails[] = '<br />Cc: ' . implode( '<br />&nbsp;&nbsp;&mdash; ', $cc );
				}


				/**
				 * Bcc email address(es)
				 */
				if ( $bcc = get_field( zxzacf( 'bcc' ), $ereminder->ID ) ) {
					if ( ! is_array( $bcc ) ) {
						$bcc = [ $bcc ];
					}

					$other_emails[] = '<br />Bcc: ' . implode( '<br />&nbsp;&nbsp;&mdash; ', $bcc );
				}


				/**
				 * Not sent or content processed
				 */
			} elseif ( $ereminder->post_content === '...' ) {
				if (
					function_exists( 'x_zxzacf' )
					&& ( $template = get_post_meta( $ereminder->ID, x_zxzacf( 'template' ), true ) )
				) {
					$title = $template;
				} else {
					$title = $ereminder->post_title;
				}


				if (
					function_exists( 'x_zxzacf' )
					&& ( $job_link = get_post_meta( $ereminder->ID, x_zxzacf( 'job_link' ), true ) )
				) {
					if (
						$debug
						&& $job_link != 405115
					) {
						continue;
					}


					$meta[] = '';
					$meta[] = sprintf( 'Post ID: %s (%s)', $job_link, get_post_type( $job_link ) );


					if ( $debug ) {
						$misc      = get_post_meta( $ereminder->ID, x_zxzacf( 'misc' ), true );
						$milestone = get_post( $misc['milestone_id'] );
						$meta[]    = sprintf( 'Milestone: %s', $milestone->post_title );
					}
				} elseif ( $reminder_post_id = get_post_meta( $ereminder->ID, zxzacf( 'post_id' ), true ) ) {
					if ( $debug ) {
						continue;
					}


					$meta[] = '';
					$meta[] = sprintf( 'Post ID: %s (%s)', $reminder_post_id, get_post_type( $reminder_post_id ) );
				}


				$content = '<strong>' . $title . '</strong>' . lct_return( $meta, '<br />' );


				/**
				 * Content processed
				 */
			} else {
				if ( $debug ) {
					continue;
				}


				$content = '<strong>' . $ereminder->post_title . '</strong><br /><div class="lct_processed_content">' . $ereminder->post_content . '</div>';

				$content .= '<style>
				.lct_processed_content{
					font-size: 10px !important;
					line-height: 1em !important;
				}
				
				
				.lct_processed_content h1,
				.lct_processed_content h2,
				.lct_processed_content h3,
				.lct_processed_content h4,
				.lct_processed_content h5,
				.lct_processed_content h6,
				.lct_processed_content p{
					font-size: 10px !important;
					line-height: 1em !important;
					padding: 0 !important;
					margin: 0 !important;
				}
				</style>';
			}
			?>


			<tr data-id="<?php echo $ereminder->ID; ?>">
				<td class="id"><?php echo $ereminder->ID; ?></td>
				<td class="content" style="width: 25%;"><?php echo $content; ?></td>
				<td class="date"><?php echo date( 'l, F j, Y @ g:iA', strtotime( $ereminder->post_date ) ); ?></td>
				<td class="email"><?php echo $ereminder->post_excerpt . lct_return( $other_emails ); ?></td>


				<?php
				$links = [];


				$delete_link = add_query_arg( [
					'page'        => 'lct_ereminder',
					'pder-action' => 'delete',
					'pder-submit' => 'true',
					'wpapi_nonce' => wp_create_nonce( 'wp_rest' ),
					'postid'      => $ereminder->ID
				], admin_url( 'admin.php' ) );

				$links[] = sprintf( '<a class="pder-delete-link" href="%s">Delete</a>', esc_url( $delete_link ) );


				if ( $ereminder->post_status !== 'publish' ) {
					$send_link = add_query_arg( [
						'page'        => 'lct_ereminder',
						'pder-action' => 'send',
						'pder-submit' => 'true',
						'wpapi_nonce' => wp_create_nonce( 'wp_rest' ),
						'postid'      => $ereminder->ID
					], admin_url( 'admin.php' ) );

					$links[] = sprintf( '<a class="pder-send-link" href="%s">Send Now</a>', esc_url( $send_link ) );
				}


				if ( ! empty( $job_link ) ) {
					$links[] = sprintf( '<a href="%s" target="_blank">View Post</a>', get_the_permalink( $job_link ) );
				}
				?>
				<td class="action"><?php echo implode( ' | ', $links ); ?></td>


			</tr>
		<?php } ?>
	<?php endif; ?>
	</tbody>
</table>
