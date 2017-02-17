<?php
/**
 * Plugin class.
 *
 * @since 1.0.0
 * @package GalleryTypes
 */

namespace Required\GalleryTypes;

/**
 * Class used to register main actions and filters.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Initializes the plugin.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		$this->register_hooks();
	}

	/**
	 * Registers actions and filters.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_hooks() {
		add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	/**
	 * Registers the script actions if the theme supports more than one
	 * gallery types.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_init() {
		if ( count( $this->get_gallery_types() ) < 2 ) {
			return;
		}

		add_action( 'wp_enqueue_media', [ $this, 'enqueue_script' ] );
		add_action( 'print_media_templates', [ $this, 'print_js_template' ] );
		add_filter( 'media_view_settings', [ $this, 'gallery_default_shortcode_atts' ] );
	}

	/**
	 * Gets a list of supported gallery types.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array List of supported gallery types.
	 */
	public function get_gallery_types() {
		return apply_filters( 'gallery_types.types', [
			'default' => __( 'Thumbnail Grid', 'gallery-types' ),
		] );
	}

	/**
	 * Gets the default gallery type.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Slug of the default gallery type.
	 */
	public function get_default_gallery_type() {
		return apply_filters( 'gallery_types.default-type', 'default' );
	}

	/**
	 * Enqueues the admin script for the gallery type setting.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_script() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'gallery-types',
			plugins_url( "assets/js/gallery-types-setting$suffix.js", PLUGIN_FILE ),
			[ 'media-views' ],
			'20170217'
		);
	}

	/**
	 * Sets default gallery type.
	 *
	 * @param array $settings List of media view settings.
	 * @return array List of media view settings with 'galleryDefaults' key.
	 */
	function gallery_default_shortcode_atts( $settings ) {
		$settings['galleryDefaults'] = array_merge( (array) $settings['galleryDefaults'], [
			'type' => $this->get_default_gallery_type(),
		] );

		return $settings;
	}

	/**
	 * Prints the JavaScript template for the gallery type setting.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_js_template() {
		$default_gallery_type = $this->get_default_gallery_type();

		?>
		<script type="text/html" id="tmpl-gallery-types-setting">
			<label class="setting">
				<span><?php esc_html_e( 'Type', 'gallery-types' ); ?></span>
				<select class="type" name="type" data-setting="type">
					<?php foreach ( $this->get_gallery_types() as $value => $data ) :
						$attrs  = 'value="' . esc_attr( $value ) . '"';
						if ( isset( $data['supports_columns'] ) && ! $data['supports_columns'] ) {
							$attrs .= ' data-supports-columns="0"';
						} else {
							$attrs .= ' data-supports-columns="1"';
						}
						$attrs .= selected( $value, $default_gallery_type, false );
						?>
						<option <?php echo $attrs; ?>>
							<?php echo esc_html( $data['label'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
		</script>
		<?php
	}
}
