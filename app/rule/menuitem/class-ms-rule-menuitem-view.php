<?php

class MS_Rule_MenuItem_View extends MS_View {

	public function to_html() {
		$membership = MS_Model_Membership::get_base();
		$menus 		= $membership->get_rule( MS_Rule_MenuItem::RULE_ID )->get_menu_array();

		$menu_ids 	= array_keys( $menus );
		$menu_id 	= reset( $menu_ids );
		if ( isset( $_REQUEST['menu_id'] ) ) {
			$menu_id = $_REQUEST['menu_id'];
		}

		// This fixes the list-title generated by MS_Helper_ListTable_Rule.
		unset( $_GET['status'] );

		$rule_menu = $membership->get_rule( MS_Rule_MenuItem::RULE_ID );
		$rule_listtable = new MS_Rule_MenuItem_ListTable(
			$rule_menu,
			$menus,
			$menu_id
		);

		$fields['rule_menu'] = array(
			'id' 	=> 'rule_menu',
			'name' 	=> 'rule',
			'value' => 'menu',
			'type' 	=> MS_Helper_Html::INPUT_TYPE_HIDDEN,
		);

		$menu_url = esc_url_raw(
			add_query_arg( array( 'menu_id' => $menu_id ) )
		);
		$rule_listtable->prepare_items();

		$header_data = apply_filters(
			'ms_view_membership_protectedcontent_header',
			array(
				'title' => __( 'Menüpunkte', 'membership2' ),
				'desc' 	=> __( 'Schütze einzelne Menüpunkte.', 'membership2' ),
			),
			MS_Rule_MenuItem::RULE_ID,
			$this
		);

		ob_start();
		?>
		<div class="ms-settings">
			<?php MS_Helper_Html::settings_tab_header( $header_data ); ?>

			<form id="ms-menu-form" method="post" action="<?php echo '' . $menu_url; ?>">
				<?php
				MS_Helper_Html::html_element( $fields['rule_menu'] );
				$rule_listtable->views();
				$rule_listtable->display();

				do_action(
					'ms_view_membership_protectedcontent_footer',
					MS_Rule_MenuItem::RULE_ID,
					$this
				);
				?>
			</form>
		</div>
		<?php

		MS_Helper_Html::settings_footer();

		return ob_get_clean();
	}

}