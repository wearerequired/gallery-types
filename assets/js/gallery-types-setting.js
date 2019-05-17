/**
 * Gallery Settings
 *
 * @link https://github.com/Automattic/jetpack/blob/4.6/_inc/gallery-settings.js
 */
(function( $ ) {
	var media = wp.media;

	// Wrap the render() function to append controls.
	var originalGallery = media.view.Settings.Gallery;
	media.view.Settings.Gallery = originalGallery.extend( {
		render: function() {
			var $el = this.$el, model = this.model;

			originalGallery.prototype.render.apply( this, arguments );

			// Append the type template and update the settings.
			$el.append( media.template( 'gallery-types-setting' ) );

			// Lil hack that lets media know there's a type attribute.
			if ( 'default' !== model.get( 'type' ) ) {
				media.gallery.defaults.type = model.get( 'type' );
			} else {
				media.gallery.defaults.type = 'default';
			}
			this.update.apply( this, ['type'] );

			// Hide the Columns setting for all types except Default.
			$el.find( 'select[name=type]' ).on( 'change', function() {
				var $columnSetting = $el.find( 'select[name=columns]' ).closest( 'label.setting' ),
					$selectedOption = $( this ).find( 'option:selected' );

				if ( 1 === $selectedOption.data( 'supports-columns' ) ) {
					$columnSetting.show();
				} else {
					$columnSetting.hide();
				}
			} ).change();

			return this;
		}
	} );
} )( jQuery );
