<?php
/**
 * Register widget
 */
function pure_portfolio_register_widget() {
	register_widget( 'Pure_Portfolio_Social_Icons_Widget' );
}
add_action( 'widgets_init', 'pure_portfolio_register_widget' );

/**
 * Social Icons Widget
 *
 * @since 1.0.0
 */
class Pure_Portfolio_Social_Icons_Widget extends WP_Widget {

	/**
	 * Sets up a new Social Icons widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			// widget ID.
			'pure_portfolio_social_icons',
			// widget name.
			__( 'Pure Portfolio Social Icons', 'pure-portfolio' ),
			// widget description.
			array( 'description' => __( 'Adds a social icon menu.', 'pure-portfolio' ) )
		);
	}

	/**
	 * Outputs the content for the current Social Icons widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Social Icons widget instance.
	 */
	public function widget( $args, $instance ) {
		// Get menu.
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( ! $nav_menu ) {
			return;
		}

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$nav_menu_args = array(
			'menu'        => $nav_menu,
			'menu_class'  => 'social-links',
			'link_before' => '<span class="screen-reader-text">',
			'link_after'  => '</span>',
		);

		/**
		 * Filter the arguments for the Social Icons widget.
		 *
		 * @since 4.2.0
		 *
		 * @param array    $nav_menu_args {
		 *     An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
		 *
		 *     @type callback|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
		 *     @type mixed         $menu        Menu ID, slug, or name.
		 * }
		 * @param stdClass $nav_menu      Nav menu object for the current menu.
		 * @param array    $args          Display arguments for the current widget.
		 */
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args ) );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating settings for the current Navigation Menu widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	/**
	 * Outputs the settings form for the Social Icons widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 * @global WP_Customize_Manager $wp_customize
	 */
	public function form( $instance ) {
		$title    = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus.
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		if ( ! $menus ) {
			/* translators: %s: URL to create a new menu. */
			echo '<p>' . sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.', 'pure-portfolio' ), esc_url( admin_url( 'nav-menus.php' ) ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'pure-portfolio' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php esc_html_e( 'Select Menu:', 'pure-portfolio' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
				<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'pure-portfolio' ); ?></option>
				<?php
				foreach ( $menus as $menu ) {
					echo '<option value="' . esc_attr( $menu->term_id ) . '"' . selected( $nav_menu, $menu->term_id, false ) . '>' . esc_html( $menu->name ) . '</option>';
				}
				?>
			</select>
		</p>
		<?php
	}
}
