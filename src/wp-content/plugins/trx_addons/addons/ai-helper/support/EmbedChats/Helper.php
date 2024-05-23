<?php
namespace TrxAddons\AiHelper\EmbedChats;

if ( ! class_exists( 'Helper' ) ) {

	/**
	 * Main class for AI Helper EmbedChat support
	 */
	class Helper {

		/**
		 * Constructor
		 */
		function __construct() {
			add_action( 'wp_footer', array( $this, 'embed_chats' ) );
			add_action( 'admin_footer', array( $this, 'embed_chats' ) );
		}

		/**
		 * Embed chats to the admin footer
		 * 
		 * @hooked 'admin_footer'
		 */
		function embed_chats() {
			$chats = trx_addons_get_option( 'ai_helper_embed_chats' );
			if ( is_array( $chats ) && count( $chats ) > 0 ) {
				foreach( $chats as $chat ) {
					$enable = ! empty( $chat['code'] )
								&& (
									( in_array( $chat['scope'], array( 'admin', 'site' ) ) && is_admin() )
									||
									( in_array( $chat['scope'], array( 'frontend', 'site' ) ) && ! is_admin() )
									);
					if ( $enable && ! empty( $chat['url_contain'] ) ) {
						$enable = false;
						$url = trx_addons_get_current_url();
						$parts = array_map( 'trim', explode( "\n", str_replace( ',', "\n", $chat['url_contain'] ) ) );
						foreach( $parts as $part ) {
							if ( strpos( $url, $part ) !== false ) {
								$enable = true;
								break;
							}
						}
					}
					if ( $enable ) {
						?>
						<!-- EmbedChat <?php echo esc_attr( $chat['title'] ); ?> -->
						<?php
						echo str_replace(
							array( '{images}' ),
							array( trx_addons_get_folder_url( TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/images' ) ),
							trim( $chat['code'] )
						);
						?>
						<!-- /EmbedChat <?php echo esc_attr( $chat['title'] ); ?> -->
						<?php
					}
				}
			}
		}
	}
}
