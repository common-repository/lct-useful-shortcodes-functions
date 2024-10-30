<?php
//Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$fields = [];
if ( isset( $data['fields'] ) ) {
	$fields = $data['fields'];
}

$messages = [];
if ( isset( $data['messages'] ) ) {
	$messages = $data['messages'];
}
?>
<div class="wrap ereminder">
	<?php if ( ! empty( $messages ) ) : ?>
		<?php if ( ! empty( $messages['error'] ) ): ?>
			<div class="error message">
				<?php foreach ( $messages['error'] as $message ): ?>
					<?php echo $message; ?><br/>
				<?php endforeach; ?>
			</div>
		<?php elseif ( ! empty( $messages['success'] ) ): ?>
			<div class="updated message">
				<?php foreach ( $messages['success'] as $message ): ?>
					<?php echo $message; ?><br/>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="reminder-list pder-scheduled">
		<h3>Scheduled Reminders</h3>
		<?php
		global $wpdb;

		$ereminder_array = $wpdb->get_results(
			$wpdb->prepare( "SELECT *
				FROM {$wpdb->posts}
				WHERE post_type = %s
					AND post_status = 'draft'
					AND 1 = %d
				ORDER BY post_date ASC",
				'ereminder',
				1
			)
		);
		$scheduled_data  = [
			'list' => $ereminder_array,
			'type' => 'scheduled'
		];
		echo PDER_Utils::get_view( 'ereminder-list.php', $scheduled_data );
		?>
	</div>

	<div class="reminder-list pder-sent">
		<?php
		$delete_all_link = add_query_arg( [
			'page'        => 'lct_ereminder',
			'pder-action' => 'delete-all',
			'pder-submit' => 'true',
			'wpapi_nonce' => wp_create_nonce( 'wp_rest' ),
		], admin_url( 'admin.php' ) );
		?>
		<h3>Sent Reminders <a href="<?php echo esc_url( $delete_all_link ); ?>" class="button-secondary">Delete all sent reminders</a></h3>
		<?php
		global $wpdb;

		$ereminder_array = $wpdb->get_results(
			$wpdb->prepare( "SELECT *
				FROM {$wpdb->posts}
				WHERE post_type = %s
					AND post_status = 'publish'
					AND 1 = %d
				ORDER BY post_date DESC",
				'ereminder',
				1
			)
		);
		$scheduled_data  = [
			'list' => $ereminder_array,
			'type' => 'sent'
		];
		echo PDER_Utils::get_view( 'ereminder-list.php', $scheduled_data );
		?>
	</div>

</div>
