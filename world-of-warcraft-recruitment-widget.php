<?php
/*
Plugin Name: World of Warcraft Recruitment Widget
Plugin URI:
Description: A simple to use WoW Recruitment Widget Plugin
Version: 1.1.5
Author: Yan Paiha
Author URI:
License: GPL2
*/
/*
Copyright 2015 Yan Paiha (email : y.paiha©gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Adds WoW Recruitment widget
 */
class World_of_Warcraft_recruitment_widget extends WP_Widget
{

	private $classes = array(
		'Warrior'     => array('Arms', 'Fury', 'Protection'),
		'Paladin'     => array('Holy', 'Protection', 'Retribution'),
		'Hunter'      => array('Beastmaster', 'Marksman', 'Survival'),
		'Rogue'       => array('Assassination', 'Combat', 'Subtlety'),
		'Priest'      => array('Discipline', 'Holy', 'Shadow'),
		'Deathknight' => array('Blood', 'Frost', 'Unholy'),
		'Shaman'      => array('Elemental', 'Enhancement', 'Restoration'),
		'Mage'        => array('Arcane', 'Fire', 'Frost'),
		'Warlock'     => array('Affliction', 'Demonology', 'Destruction'),
		'Monk'        => array('Brewmaster', 'Mistweaver', 'Battledancer'),
		'Druid'       => array('Balance', 'Cat', 'Bear', 'Restoration'),
	);

	public function __construct()
	{
		$widget_ops = array( 'description' => __( 'A simple to use WoW Recruitment Widget', 'world_of_warcraft_recruitment_widget_textdomain' ) );
		$control_ops = array( 'width' => 500 );
		
		parent::__construct('world_of_warcraft_recruitment_widget', __( 'World of Warcraft recruitment widget', 'world_of_warcraft_recruitment_widget_textdomain' ), $widget_ops, $control_ops);

	}

	public function widget($args, $instance)
	{
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			_e( $args['before_title'].apply_filters( 'widget_title', $instance['title'] ).$args['after_title'] );
		}

		?>
		<table class="recruitment_table">
		<?php foreach ( $this->classes as $key => $value ): ?>

			<?php if ( 'on' == $instance[ $key ] ): ?>
				<tr class="<?php echo $key ?>">
					<td><?php _e( $key, 'world_of_warcraft_recruitment_widget_textdomain' ) ?></td>
					<td>
						<?php foreach ( $value as $spec ): ?>
							<?php $style = ( $instance[ $key.'_'.$spec ] == 'on' ? 'open' : 'close' ); ?>
							<?php echo '<img class="'.$style.'" src="'.plugins_url( '/img/wow_icons/'.strtolower( $key.'_'.$spec ), __FILE__ ).'.jpg" alt="'.$spec.'" title="'.$spec.'" > '; ?>
						<?php endforeach ?>
					</td>
				</tr>
			<?php endif ?>

		<?php endforeach ?>
		</table>

		<?php

		echo $args['after_widget'];
	}

	public function form($instance)
	{
		$title = ! empty($instance['title']) ? $instance['title'] : __( 'New Title', 'world_of_warcraft_recruitment_widget_textdomain' );
		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'world_of_warcraft_recruitment_widget_textdomain' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<?php foreach ( $this->classes as $key => $value ): ?>
			<p>
				<label for="<?php echo $this->get_field_id( $key ) ?>"><?php _e( $key, 'world_of_warcraft_recruitment_widget_textdomain' ) ?></label>
				<input type="checkbox" name="<?php echo $this->get_field_name( $key ) ?>" id="<?php echo $this->get_field_id( $key ) ?>" <?php checked( $instance[ $key ], 'on' ); ?>> Show
				<?php foreach ( $value as $spec ): ?>
					<input type="checkbox" name="<?php echo $this->get_field_name( $key.'_'.$spec ) ?>" id="<?php echo $this->get_field_id( $key.'_'.$spec ) ?>" <?php checked( $instance[ $key.'_'.$spec ], 'on' ); ?>> <?php _e( $spec, 'world_of_warcraft_recruitment_widget_textdomain' ) ?>
				<?php endforeach ?>
			</p>
		<?php endforeach ?>

	<?php

	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		foreach ( $this->classes as $key => $value ) {
			$instance[ $key ] = $new_instance[ $key ];

			foreach ( $value as $spec ) {
				$instance[ $key.'_'.$spec ] = $new_instance[ $key.'_'.$spec ];
			}
		}

		return $instance;
	}
}

function add_style_files()
{
	wp_enqueue_style( 'widget_style', plugins_url( 'style.css', __FILE__ ), false, '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'add_style_files' );

function register_world_of_warcraft_recruitment_widget()
{
	register_widget( 'World_of_Warcraft_recruitment_widget' );
}

add_action( 'widgets_init', 'register_world_of_warcraft_recruitment_widget' );
load_plugin_textdomain( 'world_of_warcraft_recruitment_widget_textdomain', false, dirname( plugin_basename( __FILE__ ) ).'/languages' );
