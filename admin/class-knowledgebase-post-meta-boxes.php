<?php
/**
 * Handles custom post meta boxes for the 'knowledgebase_item' post type.
 *
 */

final class KBP_Knowledgebase_Post_Meta_Boxes {

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 */
	public static function setup() {

		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );

		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
	}

	/**
	 * Adds the meta box.
	 *
	 */
	public static function add_meta_boxes() {

		add_meta_box(
			'knowledgebase-item-details',
			__( 'Menu Item Details', 'knowledgebase' ),
			array( __CLASS__, 'details_meta_box' ),
			'knowledgebase_item',
			'side',
			'core'
		);
	}

	/**
	 * Displays the "menu item details" meta box.  Currently, this only holds a single text field for
	 * entering the menu item price.
	 *
	 */
	public static function details_meta_box( $object, $box ) { ?>

		<input type="hidden" name="knowledgebase_item_details_meta_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<p>
			<label for="menu-item-price"><?php _e( 'Price', 'knowledgebase' ); ?></label>
			<br />
			<input type="text" class="widefat" name="menu-item-price" id="menu-item-price" value="<?php echo esc_attr( kbp_get_menu_item_price( $object->ID ) ); ?>" />
		</p>

		<?php do_action( 'kbp_item_details_meta_box', $object, $box );
	}

	/**
	 * Saves the custom post meta for the menu item.
	 *
	 */
	public static function save_post( $post_id, $post ) {

		/* Verify the nonce. */
		if ( !isset( $_POST['knowledgebase_item_details_meta_nonce'] ) || !wp_verify_nonce( $_POST['knowledgebase_item_details_meta_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		/* Don't save if the post is only a revision. */
		if ( 'revision' == $post->post_type ) {
			return;
		}

		$meta = array(
			'_knowledgebase_item_price' => floatval( strip_tags( $_POST['menu-item-price'] ) )
		);

		foreach ( $meta as $meta_key => $new_meta_value ) {

			/* Get the meta value of the custom field key. */
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			/* If a new meta value was added and there was no previous value, add it. */
			if ( $new_meta_value && '' == $meta_value )
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );

			/* If the new meta value does not match the old value, update it. */
			elseif ( $new_meta_value && $new_meta_value != $meta_value )
				update_post_meta( $post_id, $meta_key, $new_meta_value );

			/* If there is no new meta value but an old value exists, delete it. */
			elseif ( '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, $meta_key, $meta_value );
		}
	}
}

KBP_Knowledgebase_Post_Meta_Boxes::setup();
