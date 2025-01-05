<tr id="<?php echo esc_attr( sanitize_title( $addon_slug . '_transaction_key_row' ) ); ?>" class="active plugin-update-tr jslm-updater-licence-key-tr">
	<td class="plugin-update" colspan="3">
		<div class="jslm-updater-licence-key">
			<label for="<?php echo esc_attr( sanitize_title( $addon_slug ) ); ?>_transaction_key"><?php _e( 'transaction Key' ); ?>:</label>
			<input type="text" id="<?php echo esc_attr( sanitize_title( $addon_slug ) ); ?>_transaction_key" name="<?php echo esc_attr( esc_attr( $addon_slug ) ); ?>_transaction_key" placeholder="XXXXXXXXXXXXXXXX" />
			<input type="submit" id="<?php echo esc_attr( sanitize_title( $addon_slug ) ); ?>_submit_button" name="<?php echo esc_attr( esc_attr( $addon_slug ) ); ?>_submit_button" value="Authenticate" />
			<input type="hidden" name="jslm_addon_array_for_token[]" value="<?php echo esc_attr( esc_attr( $addon_slug ) ); ?>" />
			<div>
				<span class="description"><?php _e( 'Enter your license key and hit authenticate. A valid key is required for updates.' ); ?> <?php printf( 'Lost your key? <a href="%s">Retrieve it here</a>.', esc_url( 'https://wplearnmanager.com/' ) ); ?></span>
			</div>
		</div>
	</td>
</tr>
