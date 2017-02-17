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

## Contributing

### Build Task

To update the minfied JavaScript file you need Node.js installed.

```
# to install dependencies run:
npm install

# to update the file run:
npm run build:js
```
