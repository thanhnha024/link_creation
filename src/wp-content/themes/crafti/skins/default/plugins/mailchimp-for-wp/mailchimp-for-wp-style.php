<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'crafti_mailchimp_get_css' ) ) {
	add_filter( 'crafti_filter_get_css', 'crafti_mailchimp_get_css', 10, 2 );
	function crafti_mailchimp_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS
form.mc4wp-form .mc4wp-form-fields input[type="email"] {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
form.mc4wp-form .mc4wp-form-fields input[type="submit"] {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

#style-5.mc4wp-form .mc4wp-form-fields input[type="email"] {
	{$fonts['h5_font-family']}
}

CSS;
		}

		return $css;
	}
}

