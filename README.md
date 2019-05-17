# Gallery Types

A plugin that allows you to define a theme specific gallery type. Props to [Jetpack for their Gallery module](https://github.com/Automattic/jetpack/blob/4.6/functions.gallery.php).


## Add custom gallery types

By default the plugin supports only one type, WordPress default thumbnail grid. To add more types you can use the `gallery_types.types` filter.

```php
add_filter( 'gallery_types.types', function( $types ) {
	return array_merge( $types, [
		'slider' => [
			'label' => __( 'Slider', 'gallery-types' ),
			'supports_columns' => false,
		],
	] );
} );
```

Each type has a key (`slider`) with two values: `label` for the label displayed in the select menu and `supports_columns` to define whether the type supports columns. If `supports_columns` is set to false the columns option will be hidden.  
Once a user selects a type the shortcode will get a new `type` attribute with the key of a gallery type as value.

To change the default selected type you can use the `gallery_types.default-type` filter.

### Customize gallery output

This plugin does not change the ouput of the gallery shortcode. That needs to be part of your theme and could be something like this:

```php
/**
 * Customizes the gallery shortcode output if the type attribute is set to 'slider'.
 *
 * @see gallery_shortcode()
 *
 * @param string $output The gallery output. Default empty.
 * @param array  $attr   Attributes of the gallery shortcode.
 * @return string HTML content to display gallery.
 */
function required_gallery_shortcode( $output, $attr ) {
	$is_slider = false;
	
	// Check if the type attribute is set.
	if ( isset( $attr['type'] ) && 'slider' === $attr['type'] ) {
		$is_slider = true;
	}

	// Return the default gallery if the type attribute isn't set.
	if ( ! $is_slider ) {
		remove_filter( 'post_gallery', 'required_gallery_shortcode', 10 );
		$output = gallery_shortcode( $attr );
		add_filter( 'post_gallery', 'required_gallery_shortcode', 10, 2 );
		return $output;
	}

	// Override shortcode attributes.
	$attr['size']    = 'thumbnail';
	$attr['link']    = 'file';
	$attr['columns'] = 0;

	// Get default gallery output and wrap it in a custom container div.
	remove_filter( 'post_gallery', 'required_gallery_shortcode', 10 );
	$output = gallery_shortcode( $attr );
	add_filter( 'post_gallery', 'required_gallery_shortcode', 10, 2 );

	return "<div class='gallery-slider'>$output</div>";
}
add_filter( 'post_gallery', 'required_gallery_shortcode', 10, 2 );
```

## Contributing

### Build Task

To update the minfied JavaScript file you need Node.js installed.

```
# to install dependencies run:
npm install

# to update the file run:
npm run build
```
