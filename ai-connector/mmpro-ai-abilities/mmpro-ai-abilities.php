<?php
/**
 * Plugin Name:       MMPro AI Abilities
 * Description:       Lets AI agents read and edit Mega Menu Pro headers in Bricks through the WordPress Abilities API and MCP.
 * Version:           0.3.0
 * Update URI:        https://github.com/udoro/MMPro-Bricks-Docs
 * Requires at least: 6.9
 * Requires PHP:      7.4
 * Author:            Design with Cracka
 * License:           GPL-2.0-or-later
 * Text Domain:       mmpro-ai-abilities
 */

defined( 'ABSPATH' ) || exit;

/**
 * Bricks' own element-write abilities reject Mega Menu Pro headers: their navigation check wants
 * the `brx-nav-nested-items` block as a direct child of the Nav (Nestable), and Mega Menu Pro keeps
 * it inside "nav wrapper". The builder's save path does not run that check, so these abilities save
 * the way the builder does: revision first, Bricks' security check, then the slashed element list.
 *
 * Every write is limited to a header template whose root element is labelled "Header Pro".
 */
final class MMPro_AI_Abilities {

	const VERSION          = '0.3.0';

	/** Updates: the "Update URI" header sends WordPress's update check for this plugin here. */
	const SLUG             = 'mmpro-ai-abilities';
	const REPO_URL         = 'https://github.com/udoro/MMPro-Bricks-Docs';
	const UPDATE_JSON      = 'https://raw.githubusercontent.com/udoro/MMPro-Bricks-Docs/main/ai-connector/mmpro-ai-abilities/update.json';
	const PACKAGE_PREFIX   = 'https://raw.githubusercontent.com/udoro/MMPro-Bricks-Docs/';
	const UPDATE_TRANSIENT = 'mmpro_ai_abilities_update';
	const CATEGORY         = 'mmpro';
	const MAX_NEW_ELEMENTS = 200;

	/** Settings that make an element run code. Never accepted from an agent. */
	const CODE_KEYS = [ 'code', 'signature', 'executeCode', 'queryEditor', 'useQueryEditor', 'javascriptCode', 'cssCode' ];

	/** Settings set-element-settings leaves alone, with what to do instead. */
	const MANAGED_SETTINGS = [
		'_attributes' => 'Use mmpro/set-attributes.',
		'_hidden'     => 'Mega Menu Pro and Bricks manage it.',
		'megaMenu'    => 'Switching between a multilevel dropdown and a mega menu changes its structure. Duplicate a dropdown of the kind you need instead.',
	];

	/** Rules in MENU Styles / Options that hold variables an agent may set. */
	const CSS_RULES = [ ':root', 'html.dwc-mobile', '.brx-sticky.scrolling' ];

	/** Config objects in MENU Styles / Options JS. */
	const JS_OBJECTS = [ 'MegaMenuCONFIG', 'CenteredLogoCONFIG' ];

	public static function boot() {
		add_action( 'wp_abilities_api_categories_init', [ __CLASS__, 'register_category' ] );
		add_action( 'wp_abilities_api_init', [ __CLASS__, 'register_abilities' ] );
		add_filter( 'update_plugins_github.com', [ __CLASS__, 'check_update' ], 10, 3 );
		add_filter( 'plugins_api', [ __CLASS__, 'plugin_info' ], 10, 3 );
	}

	/* ---------------------------------------------------------------------------------------- */
	/* Updates                                                                                  */
	/* ---------------------------------------------------------------------------------------- */

	/** WordPress (5.8+) asks the Update URI host for this plugin's latest version; it compares versions itself. */
	public static function check_update( $update, $plugin_data, $plugin_file ) {
		if ( plugin_basename( __FILE__ ) !== $plugin_file ) {
			return $update;
		}
		$info = self::update_info();
		if ( ! $info ) {
			return $update;
		}
		return [
			'slug'         => self::SLUG,
			'version'      => $info['version'],
			'package'      => $info['package'],
			'url'          => self::REPO_URL,
			'tested'       => (string) ( $info['tested'] ?? '' ),
			'requires_php' => (string) ( $info['requires_php'] ?? '' ),
		];
	}

	/** Fills the "View details" window on the Plugins screen. */
	public static function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || ! is_object( $args ) || self::SLUG !== ( $args->slug ?? '' ) ) {
			return $result;
		}
		$info = self::update_info();
		if ( ! $info ) {
			return $result;
		}
		return (object) [
			'name'          => 'MMPro AI Abilities',
			'slug'          => self::SLUG,
			'version'       => $info['version'],
			'author'        => 'Design with Cracka',
			'homepage'      => self::REPO_URL,
			'requires'      => (string) ( $info['requires'] ?? '6.9' ),
			'requires_php'  => (string) ( $info['requires_php'] ?? '7.4' ),
			'tested'        => (string) ( $info['tested'] ?? '' ),
			'download_link' => $info['package'],
			'sections'      => [ 'changelog' => wp_kses_post( (string) ( $info['changelog'] ?? '' ) ) ],
		];
	}

	/** update.json from the plugin's repo, cached. Only a package inside the repo is accepted. */
	private static function update_info() {
		$cached = get_transient( self::UPDATE_TRANSIENT );
		if ( is_array( $cached ) ) {
			return $cached ? $cached : null;
		}
		$response = wp_remote_get( self::UPDATE_JSON, [ 'timeout' => 10 ] );
		$data     = null;
		if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
			$data = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		}
		$valid = is_array( $data )
			&& ! empty( $data['version'] ) && is_string( $data['version'] ) && preg_match( '/^\d+(\.\d+){1,3}$/', $data['version'] )
			&& ! empty( $data['package'] ) && is_string( $data['package'] ) && 0 === strpos( $data['package'], self::PACKAGE_PREFIX );
		// A failed check is cached for an hour, so a GitHub outage does not slow every admin page.
		set_transient( self::UPDATE_TRANSIENT, $valid ? $data : [], $valid ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
		return $valid ? $data : null;
	}

	/* ---------------------------------------------------------------------------------------- */
	/* Registration                                                                             */
	/* ---------------------------------------------------------------------------------------- */

	public static function register_category() {
		wp_register_ability_category(
			self::CATEGORY,
			[
				'label'       => 'Mega Menu Pro',
				'description' => 'Read and edit Mega Menu Pro headers in Bricks.',
			]
		);
	}

	public static function register_abilities() {
		$post_id = [
			'type'        => 'integer',
			'description' => 'Header template ID, from mmpro/get-header.',
		];
		$dry_run = [
			'type'        => 'boolean',
			'description' => 'Validate and return the result without saving.',
		];
		$digest  = [
			'type'        => 'string',
			'description' => 'Digest from mmpro/get-header or the previous write. The write is refused if the header changed since then.',
		];

		self::register(
			'get-header',
			'Get Mega Menu Pro header',
			'Find the Mega Menu Pro header template and summarise it: element IDs, Header Pro and Nav (Nestable) attributes, the menu items, and the JS options. Pass include ["cssVariables"] to add the CSS variables. Call this first; every write needs its postId and element IDs.',
			[
				'postId'  => $post_id,
				'include' => [
					'type'  => 'array',
					'items' => [
						'type' => 'string',
						'enum' => [ 'cssVariables' ],
					],
				],
			],
			[],
			'get_header',
			'can_read',
			[ true, false, true ]
		);

		self::register(
			'set-attributes',
			'Set element attributes',
			'Set attributes on one element of the Mega Menu Pro header, such as Header Pro, the Nav (Nestable), a Dropdown or a mega menu Content element. attributes maps a name to a value: a string sets it, "" turns it off (the attribute stays, empty), null removes it. Use null for presence-only attributes such as data-breakout-link or data-is-button.',
			[
				'postId'         => $post_id,
				'elementId'      => [ 'type' => 'string' ],
				'attributes'     => [
					'type'                 => 'object',
					'additionalProperties' => [ 'type' => [ 'string', 'null' ] ],
				],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'elementId', 'attributes' ],
			'set_attributes',
			'can_write',
			[ false, false, true ]
		);

		self::register(
			'set-css-variables',
			'Set CSS variables',
			'Set CSS variables in the MENU Styles / Options code block. rule is the selector holding them: ":root" (default, every screen), "html.dwc-mobile" (mobile menu only) or ".brx-sticky.scrolling" (sticky header only). In ":root" only existing variables can be changed; in the other two rules a missing variable is added. Values are written exactly; give zero a unit (0px).',
			[
				'postId'         => $post_id,
				'rule'           => [
					'type' => 'string',
					'enum' => self::CSS_RULES,
				],
				'variables'      => [
					'type'                 => 'object',
					'additionalProperties' => [ 'type' => 'string' ],
				],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'variables' ],
			'set_css_variables',
			'can_write_code',
			[ false, false, true ]
		);

		self::register(
			'set-js-options',
			'Set JS options',
			'Set existing options in the MENU Styles / Options JS: MegaMenuCONFIG (minWidth, adaptiveHeight, stripeStyle, closeNavOnClick ...) or CenteredLogoCONFIG (enable, centerNudge ...). A boolean written to an option that holds 0 or 1 is stored as 1 or 0. Changing minWidth alone does not move the breakpoint: the CSS code blocks must match it.',
			[
				'postId'         => $post_id,
				'object'         => [
					'type' => 'string',
					'enum' => self::JS_OBJECTS,
				],
				'options'        => [
					'type'                 => 'object',
					'additionalProperties' => [ 'type' => [ 'number', 'boolean', 'string' ] ],
				],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'object', 'options' ],
			'set_js_options',
			'can_write_code',
			[ false, false, true ]
		);

		self::register(
			'set-menu-item',
			'Set menu item text or link',
			'Change the text or link of a menu item or a link inside a dropdown or mega menu. Works on text-link and text-basic elements, and on the text of a Dropdown.',
			[
				'postId'         => $post_id,
				'elementId'      => [ 'type' => 'string' ],
				'text'           => [ 'type' => 'string' ],
				'url'            => [ 'type' => 'string' ],
				'newTab'         => [ 'type' => 'boolean' ],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'elementId' ],
			'set_menu_item',
			'can_write',
			[ false, false, true ]
		);

		self::register(
			'duplicate-element',
			'Duplicate a menu element',
			'Copy an element and everything inside it, with new IDs, next to the original. Use it to add a menu item, a dropdown or a mega menu by copying one of the same kind. Optional text and url apply to the copy. Only elements inside Nav items can be copied.',
			[
				'postId'         => $post_id,
				'elementId'      => [ 'type' => 'string' ],
				'position'       => [
					'type' => 'string',
					'enum' => [ 'after', 'before' ],
				],
				'text'           => [ 'type' => 'string' ],
				'url'            => [ 'type' => 'string' ],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'elementId' ],
			'duplicate_element',
			'can_write_structure',
			[ false, false, false ]
		);

		self::register(
			'remove-element',
			'Remove a menu element',
			'Remove an element and everything inside it. Only elements inside Nav items can be removed; the header structure itself is protected. A revision is saved first.',
			[
				'postId'         => $post_id,
				'elementId'      => [ 'type' => 'string' ],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'elementId' ],
			'remove_element',
			'can_write_structure',
			[ false, true, true ]
		);

		self::register(
			'add-elements',
			'Add elements',
			'Add a tree of Bricks elements inside Nav items, for example columns and links inside a mega menu\'s Content Inner. elements is an array of {name, label?, settings?, children?}; IDs are generated. Code-running settings are refused. position is the index among the parent\'s children (default: end).',
			[
				'postId'         => $post_id,
				'parentId'       => [ 'type' => 'string' ],
				'position'       => [
					'type'    => 'integer',
					'minimum' => 0,
				],
				'elements'       => [
					'type'  => 'array',
					'items' => [ 'type' => 'object' ],
				],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'parentId', 'elements' ],
			'add_elements',
			'can_write_structure',
			[ false, false, false ]
		);

		self::register(
			'set-element-settings',
			'Set element settings',
			'Change Bricks settings of one existing element inside Nav items, the way the builder panel does: settings maps a key to its new value, null removes the key, keys not named stay as they are. Code-running keys, _attributes (use mmpro/set-attributes), _hidden and megaMenu are refused. A Content Inner keeps tag "li"; a dropdown Content keeps its tag.',
			[
				'postId'         => $post_id,
				'elementId'      => [ 'type' => 'string' ],
				'settings'       => [
					'type'                 => 'object',
					'additionalProperties' => true,
				],
				'dryRun'         => $dry_run,
				'expectedDigest' => $digest,
			],
			[ 'postId', 'elementId', 'settings' ],
			'set_element_settings',
			'can_write',
			[ false, false, true ]
		);

		self::register(
			'set-breakpoint',
			'Set the menu breakpoint',
			'Move the desktop/mobile breakpoint, the documented way: sets minWidth in MENU Styles / Options and replaces the old breakpoint widths inside the @media rules of the MEDIA QUERY and MEGA MENU Codes blocks. desktopMinWidth is the first desktop width; mobile is one pixel less. Nothing else in those blocks changes.',
			[
				'postId'          => $post_id,
				'desktopMinWidth' => [
					'type'    => 'integer',
					'minimum' => 480,
					'maximum' => 3840,
				],
				'dryRun'          => $dry_run,
				'expectedDigest'  => $digest,
			],
			[ 'postId', 'desktopMinWidth' ],
			'set_breakpoint',
			'can_write_code',
			[ false, false, true ]
		);
	}

	private static function register( $name, $label, $description, $properties, $required, $execute, $permission, $annotations ) {
		$schema = [
			'type'                 => 'object',
			'properties'           => $properties,
			'additionalProperties' => false,
		];
		if ( $required ) {
			$schema['required'] = $required;
		} else {
			$schema['default'] = [];
		}

		wp_register_ability(
			'mmpro/' . $name,
			[
				'label'               => $label,
				'description'         => $description,
				'category'            => self::CATEGORY,
				'input_schema'        => $schema,
				'output_schema'       => [ 'type' => 'object' ],
				'execute_callback'    => [ __CLASS__, $execute ],
				'permission_callback' => [ __CLASS__, $permission ],
				'meta'                => [
					'annotations'  => [
						'readonly'    => $annotations[0],
						'destructive' => $annotations[1],
						'idempotent'  => $annotations[2],
					],
					'show_in_rest' => true,
					'mcp'          => [
						'public' => true,
						'type'   => 'tool',
					],
				],
			]
		);
	}

	/* ---------------------------------------------------------------------------------------- */
	/* Permissions                                                                              */
	/* ---------------------------------------------------------------------------------------- */

	private static function input_post_id( $input ) {
		return is_array( $input ) ? absint( $input['postId'] ?? 0 ) : 0;
	}

	public static function can_read( $input = null ) {
		$post_id = self::input_post_id( $input );
		return $post_id ? current_user_can( 'edit_post', $post_id ) : current_user_can( 'edit_posts' );
	}

	public static function can_write( $input = null ) {
		$post_id = self::input_post_id( $input );
		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}
		if ( class_exists( '\Bricks\Capabilities' ) && ! \Bricks\Capabilities::current_user_can_use_builder( $post_id ) ) {
			return false;
		}
		return true;
	}

	/** Code blocks are replaced with their stored copy for users who cannot execute code. */
	public static function can_write_code( $input = null ) {
		return self::can_write( $input )
			&& class_exists( '\Bricks\Capabilities' )
			&& \Bricks\Capabilities::current_user_can_execute_code();
	}

	/** Bricks drops new elements for users who may only edit existing ones. */
	public static function can_write_structure( $input = null ) {
		if ( ! self::can_write( $input ) ) {
			return false;
		}
		if ( class_exists( '\Bricks\Builder_Permissions' ) && ! \Bricks\Builder_Permissions::user_can_modify_element_count() ) {
			return false;
		}
		return true;
	}

	/* ---------------------------------------------------------------------------------------- */
	/* Abilities                                                                                */
	/* ---------------------------------------------------------------------------------------- */

	public static function get_header( $input = null ) {
		$input   = is_array( $input ) ? $input : [];
		$post_id = absint( $input['postId'] ?? 0 );

		if ( ! $post_id ) {
			$headers = self::find_headers();
			if ( is_wp_error( $headers ) ) {
				return $headers;
			}
			if ( ! $headers ) {
				return self::error( 'no_header', 'No Mega Menu Pro header template found. Import the header template first.' );
			}
			if ( count( $headers ) > 1 ) {
				return [
					'headers' => $headers,
					'message' => 'More than one Mega Menu Pro header. Call again with postId.',
				];
			}
			$post_id = $headers[0]['postId'];
		}

		$header = self::load( $post_id );
		if ( is_wp_error( $header ) ) {
			return $header;
		}

		$elements = $header['elements'];
		$map      = $header['map'];
		$idx      = self::index( $elements );
		$root     = $elements[ $idx[ $map['headerPro'] ] ];

		$result = [
			'postId'     => $post_id,
			'title'      => get_the_title( $post_id ),
			'label'      => $root['label'] ?? '',
			'variant'    => false !== stripos( (string) ( $root['label'] ?? '' ), 'lite' ) ? 'lite' : 'full',
			'digest'     => self::digest( $elements ),
			'ids'        => [
				'headerPro'  => $map['headerPro'],
				'nav'        => $map['nav'],
				'navItems'   => $map['navItems'],
				'options'    => $map['options'],
				'codeBlocks' => $map['codeBlocks'],
			],
			'attributes' => [
				'headerPro' => self::attribute_map( $root ),
				'nav'       => $map['nav'] ? self::attribute_map( $elements[ $idx[ $map['nav'] ] ] ) : null,
			],
			'menu'       => $map['navItems'] ? self::menu_tree( $elements, $idx, $map['navItems'], 0 ) : [],
			'cssLoading' => class_exists( '\Bricks\Database' ) ? \Bricks\Database::get_setting( 'cssLoading' ) : null,
		];

		if ( $map['options'] ) {
			$settings            = $elements[ $idx[ $map['options'] ] ]['settings'] ?? [];
			$js                  = (string) ( $settings['javascriptCode'] ?? '' );
			$result['jsOptions'] = [];
			foreach ( self::JS_OBJECTS as $object ) {
				$parsed = self::js_object_values( $js, $object );
				if ( ! is_wp_error( $parsed ) ) {
					$result['jsOptions'][ $object ] = $parsed;
				}
			}
			$min_width = (int) ( $result['jsOptions']['MegaMenuCONFIG']['minWidth'] ?? 0 );
			if ( $min_width ) {
				$result['breakpoint'] = [
					'desktopMinWidth' => $min_width,
					'mobileMaxWidth'  => $min_width - 1,
				];
			}

			if ( in_array( 'cssVariables', (array) ( $input['include'] ?? [] ), true ) ) {
				$css                    = (string) ( $settings['cssCode'] ?? '' );
				$result['cssVariables'] = [];
				foreach ( self::CSS_RULES as $rule ) {
					$values = self::css_rule_values( $css, $rule );
					if ( ! is_wp_error( $values ) ) {
						$result['cssVariables'][ $rule ] = $values;
					}
				}
			}
		}

		return $result;
	}

	public static function set_attributes( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx ) = $header;

		$element_id = (string) ( $input['elementId'] ?? '' );
		if ( ! isset( $idx[ $element_id ] ) ) {
			return self::error( 'no_element', "No element {$element_id} in template {$post_id}." );
		}
		$k = $idx[ $element_id ];
		if ( 'code' === ( $elements[ $k ]['name'] ?? '' ) ) {
			return self::error( 'code_block', 'Code block attributes belong to Mega Menu Pro. Leave them as they are.' );
		}

		$attributes = $elements[ $k ]['settings']['_attributes'] ?? [];
		$attributes = is_array( $attributes ) ? array_values( $attributes ) : [];
		$changes    = [];

		foreach ( (array) ( $input['attributes'] ?? [] ) as $name => $value ) {
			$name = (string) $name;
			if ( ! preg_match( '/^[a-zA-Z_][a-zA-Z0-9_.:-]*$/', $name ) ) {
				return self::error( 'bad_name', "Invalid attribute name: {$name}" );
			}
			if ( null !== $value && ! is_scalar( $value ) ) {
				return self::error( 'bad_value', "Attribute {$name} needs a string or null." );
			}

			$pos = null;
			foreach ( $attributes as $i => $attribute ) {
				if ( ( $attribute['name'] ?? '' ) === $name ) {
					$pos = $i;
					break;
				}
			}
			$before = null === $pos ? null : (string) ( $attributes[ $pos ]['value'] ?? '' );

			if ( null === $value ) {
				if ( null !== $pos ) {
					array_splice( $attributes, $pos, 1 );
				}
				$after = null;
			} else {
				$value = (string) $value;
				if ( null === $pos ) {
					$attributes[] = [
						'id'   => self::new_id( self::attribute_ids( $attributes ) ),
						'name' => $name,
					];
					$pos          = count( $attributes ) - 1;
				}
				if ( '' === $value ) {
					unset( $attributes[ $pos ]['value'] );
				} else {
					$attributes[ $pos ]['value'] = $value;
				}
				$after = $value;
			}

			$changes[] = [
				'name'   => $name,
				'before' => $before,
				'after'  => $after,
			];
		}

		if ( ! $changes ) {
			return self::error( 'nothing_to_do', 'Pass at least one attribute.' );
		}

		if ( $attributes ) {
			$elements[ $k ]['settings']['_attributes'] = array_values( $attributes );
		} else {
			unset( $elements[ $k ]['settings']['_attributes'] );
		}

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'elementId' => $element_id,
				'changes'   => $changes,
			]
		);
	}

	public static function set_css_variables( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		if ( ! $map['options'] ) {
			return self::error( 'no_options_block', 'The MENU Styles / Options code block was not found.' );
		}
		$k    = $idx[ $map['options'] ];
		$css  = (string) ( $elements[ $k ]['settings']['cssCode'] ?? '' );
		$rule = (string) ( $input['rule'] ?? ':root' );
		if ( ! in_array( $rule, self::CSS_RULES, true ) ) {
			return self::error( 'bad_rule', 'rule must be one of: ' . implode( ', ', self::CSS_RULES ) );
		}

		$length_before = strlen( $css );
		$changes       = [];

		foreach ( (array) ( $input['variables'] ?? [] ) as $variable => $value ) {
			$variable = (string) $variable;
			$value    = trim( (string) $value );
			if ( ! preg_match( '/^--[a-zA-Z0-9_-]+$/', $variable ) ) {
				return self::error( 'bad_name', "Invalid CSS variable name: {$variable}" );
			}
			if ( '' === $value || preg_match( '/[;{}<>\r\n]|\/\*|\*\//', $value ) ) {
				return self::error( 'bad_value', "Invalid value for {$variable}. Use a single CSS value with no ; { } < > or comments." );
			}

			$span = self::css_rule_span( $css, $rule );
			if ( is_wp_error( $span ) ) {
				return $span;
			}
			$declaration = self::css_declaration( $css, $span, $variable );
			if ( is_wp_error( $declaration ) ) {
				return $declaration;
			}

			if ( $declaration ) {
				list( $start, $end ) = $declaration;
				$before              = substr( $css, $start, $end - $start );
				$css                 = substr_replace( $css, $value, $start, $end - $start );
				$added               = false;
			} else {
				if ( ':root' === $rule ) {
					return self::error( 'unknown_variable', "{$variable} is not in :root. Check the name in the reference; only existing variables can be changed there." );
				}
				list( , $close ) = $span;
				$prefix          = "\n" === substr( rtrim( substr( $css, 0, $close ), " \t" ), -1 ) ? '' : "\n";
				$css             = substr_replace( $css, "{$prefix}  {$variable}: {$value};\n", $close, 0 );
				$before          = null;
				$added           = true;
			}

			$changes[] = [
				'variable' => $variable,
				'before'   => $before,
				'after'    => $value,
				'added'    => $added,
			];
		}

		if ( ! $changes ) {
			return self::error( 'nothing_to_do', 'Pass at least one variable.' );
		}

		$elements[ $k ]['settings']['cssCode'] = $css;

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'rule'      => $rule,
				'changes'   => $changes,
				'cssLength' => [
					'before' => $length_before,
					'after'  => strlen( $css ),
				],
			]
		);
	}

	public static function set_js_options( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		if ( ! $map['options'] ) {
			return self::error( 'no_options_block', 'The MENU Styles / Options code block was not found.' );
		}
		$object = (string) ( $input['object'] ?? '' );
		if ( ! in_array( $object, self::JS_OBJECTS, true ) ) {
			return self::error( 'bad_object', 'object must be one of: ' . implode( ', ', self::JS_OBJECTS ) );
		}

		$k       = $idx[ $map['options'] ];
		$js      = (string) ( $elements[ $k ]['settings']['javascriptCode'] ?? '' );
		$changes = [];

		foreach ( (array) ( $input['options'] ?? [] ) as $option => $value ) {
			$option = (string) $option;
			if ( ! preg_match( '/^[A-Za-z_$][A-Za-z0-9_$]*$/', $option ) ) {
				return self::error( 'bad_name', "Invalid option name: {$option}" );
			}

			$span = self::js_object_span( $js, $object );
			if ( is_wp_error( $span ) ) {
				return $span;
			}
			$found = self::js_option( $js, $span, $option );
			if ( is_wp_error( $found ) ) {
				return $found;
			}
			if ( ! $found ) {
				return self::error( 'unknown_option', "{$object} has no option {$option}. Only existing options can be changed." );
			}

			list( $start, $end ) = $found;
			$before              = substr( $js, $start, $end - $start );
			$literal             = self::js_literal( $value, $before );
			if ( is_wp_error( $literal ) ) {
				return $literal;
			}
			$js        = substr_replace( $js, $literal, $start, $end - $start );
			$changes[] = [
				'option' => $option,
				'before' => $before,
				'after'  => $literal,
			];
		}

		if ( ! $changes ) {
			return self::error( 'nothing_to_do', 'Pass at least one option.' );
		}

		$elements[ $k ]['settings']['javascriptCode'] = $js;

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'object'  => $object,
				'changes' => $changes,
			]
		);
	}

	public static function set_menu_item( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		$element_id = (string) ( $input['elementId'] ?? '' );
		$check      = self::require_inside_nav_items( $elements, $idx, $map, $element_id, false );
		if ( is_wp_error( $check ) ) {
			return $check;
		}
		if ( ! isset( $input['text'] ) && ! isset( $input['url'] ) && ! isset( $input['newTab'] ) ) {
			return self::error( 'nothing_to_do', 'Pass text, url or newTab.' );
		}

		$k      = $idx[ $element_id ];
		$before = self::link_summary( $elements[ $k ] );
		$result = self::apply_link_fields( $elements[ $k ], $input );
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		$elements[ $k ] = $result;

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'elementId' => $element_id,
				'before'    => $before,
				'after'     => self::link_summary( $elements[ $k ] ),
			]
		);
	}

	public static function duplicate_element( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		$source_id = (string) ( $input['elementId'] ?? '' );
		$check     = self::require_inside_nav_items( $elements, $idx, $map, $source_id, false );
		if ( is_wp_error( $check ) ) {
			return $check;
		}

		$subtree = self::subtree_ids( $elements, $idx, $source_id );
		$taken   = array_fill_keys( array_keys( $idx ), true );
		$new_ids = [];
		foreach ( $subtree as $old_id ) {
			$new_ids[ $old_id ]           = self::new_id( $taken );
			$taken[ $new_ids[ $old_id ] ] = true;
		}

		$copies = [];
		foreach ( $subtree as $old_id ) {
			$copy       = $elements[ $idx[ $old_id ] ];
			$copy['id'] = $new_ids[ $old_id ];
			if ( $old_id !== $source_id ) {
				$copy['parent'] = $new_ids[ (string) $copy['parent'] ];
			}
			$copy['children'] = array_values(
				array_map(
					function ( $child ) use ( $new_ids ) {
						return $new_ids[ (string) $child ] ?? $child;
					},
					(array) ( $copy['children'] ?? [] )
				)
			);
			if ( ! empty( $copy['settings']['_attributes'] ) && is_array( $copy['settings']['_attributes'] ) ) {
				$used = [];
				foreach ( $copy['settings']['_attributes'] as $i => $attribute ) {
					$copy['settings']['_attributes'][ $i ]['id']          = self::new_id( $used );
					$used[ $copy['settings']['_attributes'][ $i ]['id'] ] = true;
				}
			}
			$copies[] = $copy;
		}

		$new_root = $new_ids[ $source_id ];
		if ( isset( $input['text'] ) || isset( $input['url'] ) ) {
			$applied = self::apply_link_fields( $copies[0], $input );
			if ( is_wp_error( $applied ) ) {
				return $applied;
			}
			$copies[0] = $applied;
		}

		// Insert the copy into the parent's child order, next to the source.
		$parent_id = (string) $elements[ $idx[ $source_id ] ]['parent'];
		$pk        = $idx[ $parent_id ];
		$children  = array_values( (array) ( $elements[ $pk ]['children'] ?? [] ) );
		$at        = array_search( $source_id, array_map( 'strval', $children ), true );
		$at        = false === $at ? count( $children ) : $at + ( 'before' === ( $input['position'] ?? 'after' ) ? 0 : 1 );
		array_splice( $children, $at, 0, [ $new_root ] );
		$elements[ $pk ]['children'] = $children;

		// Keep the flat list ordered: the copy follows the source subtree.
		$last = max(
			array_map(
				function ( $id ) use ( $idx ) {
					return $idx[ $id ];
				},
				$subtree
			)
		);
		array_splice( $elements, $last + 1, 0, $copies );

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'sourceId' => $source_id,
				'newId'    => $new_root,
				'newIds'   => $new_ids,
			]
		);
	}

	public static function remove_element( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		$element_id = (string) ( $input['elementId'] ?? '' );
		$check      = self::require_inside_nav_items( $elements, $idx, $map, $element_id, false );
		if ( is_wp_error( $check ) ) {
			return $check;
		}
		if ( self::has_hidden_class( $elements[ $idx[ $element_id ] ], 'brx-dropdown-content' ) ) {
			return self::error( 'protected', "Element {$element_id} is a dropdown's Content. Remove the whole Dropdown, or remove what is inside its Content." );
		}

		$remove    = array_fill_keys( self::subtree_ids( $elements, $idx, $element_id ), true );
		$parent_id = (string) $elements[ $idx[ $element_id ] ]['parent'];
		$pk        = $idx[ $parent_id ];

		$elements[ $pk ]['children'] = array_values(
			array_filter(
				(array) ( $elements[ $pk ]['children'] ?? [] ),
				function ( $child ) use ( $element_id ) {
					return (string) $child !== $element_id;
				}
			)
		);
		$elements                    = array_values(
			array_filter(
				$elements,
				function ( $element ) use ( $remove ) {
					return ! isset( $remove[ (string) $element['id'] ] );
				}
			)
		);

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'removedIds' => array_keys( $remove ),
			]
		);
	}

	public static function add_elements( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		$parent_id = (string) ( $input['parentId'] ?? '' );
		$check     = self::require_inside_nav_items( $elements, $idx, $map, $parent_id, true );
		if ( is_wp_error( $check ) ) {
			return $check;
		}
		if ( ! self::is_nestable( $elements[ $idx[ $parent_id ] ]['name'] ?? '' ) ) {
			return self::error( 'not_nestable', "Element {$parent_id} cannot have children." );
		}
		if ( self::is_mega_content( $elements, $idx, $parent_id ) ) {
			foreach ( (array) ( $input['elements'] ?? [] ) as $node ) {
				$is_inner = is_array( $node ) && 'li' === ( $node['settings']['tag'] ?? '' ) && self::is_nestable( (string) ( $node['name'] ?? '' ) );
				if ( ! $is_inner ) {
					return self::error( 'content_inner_required', 'Mega menu content goes inside a Content Inner. Add one nestable element with settings.tag "li" here (a div or block), then add your content inside it.' );
				}
			}
		}

		$taken = array_fill_keys( array_keys( $idx ), true );
		$flat  = [];
		$roots = [];
		foreach ( (array) ( $input['elements'] ?? [] ) as $node ) {
			$id = self::flatten_node( $node, $parent_id, $flat, $taken, 0 );
			if ( is_wp_error( $id ) ) {
				return $id;
			}
			$roots[] = $id;
		}
		if ( ! $roots ) {
			return self::error( 'nothing_to_do', 'Pass at least one element.' );
		}
		if ( count( $flat ) > self::MAX_NEW_ELEMENTS ) {
			return self::error( 'too_many', 'At most ' . self::MAX_NEW_ELEMENTS . ' elements per call.' );
		}

		$pk       = $idx[ $parent_id ];
		$children = array_values( (array) ( $elements[ $pk ]['children'] ?? [] ) );
		$at       = isset( $input['position'] ) ? min( absint( $input['position'] ), count( $children ) ) : count( $children );
		array_splice( $children, $at, 0, $roots );
		$elements[ $pk ]['children'] = $children;

		$last = max(
			array_map(
				function ( $id ) use ( $idx ) {
					return $idx[ $id ];
				},
				self::subtree_ids( $elements, $idx, $parent_id )
			)
		);
		array_splice( $elements, $last + 1, 0, $flat );

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'parentId' => $parent_id,
				'newIds'   => array_column( $flat, 'id' ),
				'rootIds'  => $roots,
			]
		);
	}

	public static function set_element_settings( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		$element_id = (string) ( $input['elementId'] ?? '' );
		$check      = self::require_inside_nav_items( $elements, $idx, $map, $element_id, false );
		if ( is_wp_error( $check ) ) {
			return $check;
		}

		$new = $input['settings'] ?? null;
		if ( ! is_array( $new ) || ! $new ) {
			return self::error( 'nothing_to_do', 'Pass at least one setting.' );
		}
		if ( self::has_code_keys( $new ) ) {
			return self::error( 'code_setting', 'Settings that run code are not accepted.' );
		}
		foreach ( self::MANAGED_SETTINGS as $key => $instead ) {
			if ( array_key_exists( $key, $new ) ) {
				return self::error( 'managed_setting', "{$key} is not changed here. {$instead}" );
			}
		}

		$k       = $idx[ $element_id ];
		$element = $elements[ $k ];
		if ( array_key_exists( 'tag', $new ) ) {
			$parent_id = (string) ( $element['parent'] ?? '' );
			if ( self::is_mega_content( $elements, $idx, $parent_id ) && 'li' !== $new['tag'] ) {
				return self::error( 'content_inner_tag', 'A Content Inner keeps tag "li".' );
			}
			if ( self::has_hidden_class( $element, 'brx-dropdown-content' ) ) {
				return self::error( 'content_tag', "A dropdown's Content keeps its tag." );
			}
		}

		$settings = is_array( $element['settings'] ?? null ) ? $element['settings'] : [];
		$changes  = [];
		foreach ( $new as $key => $value ) {
			$key       = (string) $key;
			$before    = $settings[ $key ] ?? null;
			if ( null === $value ) {
				unset( $settings[ $key ] );
			} else {
				$settings[ $key ] = $value;
			}
			$changes[] = [
				'key'    => $key,
				'before' => $before,
				'after'  => $value,
			];
		}
		$elements[ $k ]['settings'] = $settings;

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'elementId' => $element_id,
				'changes'   => $changes,
			]
		);
	}

	public static function set_breakpoint( $input ) {
		$header = self::load_for_write( $input );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		list( $post_id, $elements, $idx, $map ) = $header;

		$new_desktop = (int) ( $input['desktopMinWidth'] ?? 0 );
		if ( $new_desktop < 480 || $new_desktop > 3840 ) {
			return self::error( 'bad_value', 'desktopMinWidth must be between 480 and 3840.' );
		}
		if ( ! $map['options'] ) {
			return self::error( 'no_options_block', 'The MENU Styles / Options code block was not found.' );
		}

		$ok = $idx[ $map['options'] ];
		$js = (string) ( $elements[ $ok ]['settings']['javascriptCode'] ?? '' );
		$span = self::js_object_span( $js, 'MegaMenuCONFIG' );
		if ( is_wp_error( $span ) ) {
			return $span;
		}
		$found = self::js_option( $js, $span, 'minWidth' );
		if ( ! is_array( $found ) ) {
			return self::error( 'no_option', 'MegaMenuCONFIG has no minWidth.' );
		}
		$old_desktop = (int) trim( substr( $js, $found[0], $found[1] - $found[0] ) );
		if ( $old_desktop < 1 ) {
			return self::error( 'bad_option', 'minWidth is not a plain number, so the old breakpoint is unknown.' );
		}
		if ( $old_desktop === $new_desktop ) {
			return self::error( 'nothing_to_do', "The breakpoint is already {$new_desktop}px." );
		}

		$blocks = [
			'mediaQuery'    => self::find_code_block( $elements, '/media\s*query/i' ),
			'megaMenuCodes' => self::find_code_block( $elements, '/mega\s*menu\s*codes/i' ),
		];
		foreach ( $blocks as $name => $block_id ) {
			if ( null === $block_id ) {
				return self::error( 'no_block', "The {$name} code block was not found by its label." );
			}
		}

		// One pass per @media prelude, so a new value can never be replaced a second time.
		$map_px  = [
			$old_desktop . 'px'       => $new_desktop . 'px',
			( $old_desktop - 1 ) . 'px' => ( $new_desktop - 1 ) . 'px',
		];
		$pattern = '/(?<![\d.])(' . $old_desktop . 'px|' . ( $old_desktop - 1 ) . 'px)/';
		$counts  = [];
		foreach ( $blocks as $name => $block_id ) {
			$bk    = $idx[ $block_id ];
			$css   = (string) ( $elements[ $bk ]['settings']['cssCode'] ?? '' );
			$count = 0;
			$css   = preg_replace_callback(
				'/@media[^{]*\{/',
				function ( $prelude ) use ( $pattern, $map_px, &$count ) {
					return preg_replace_callback(
						$pattern,
						function ( $m ) use ( $map_px, &$count ) {
							$count++;
							return $map_px[ $m[1] ];
						},
						$prelude[0]
					);
				},
				$css
			);
			$counts[ $name ]                        = $count;
			$elements[ $bk ]['settings']['cssCode'] = $css;
		}
		if ( 0 === array_sum( $counts ) ) {
			return self::error( 'no_media_rules', "No @media rule uses {$old_desktop}px or " . ( $old_desktop - 1 ) . 'px. The breakpoint was changed by hand; fix it in the builder.' );
		}

		$js                                            = substr_replace( $js, (string) $new_desktop, $found[0], $found[1] - $found[0] );
		$elements[ $ok ]['settings']['javascriptCode'] = $js;

		return self::finish(
			$post_id,
			$elements,
			$input,
			[
				'before'       => [
					'desktopMinWidth' => $old_desktop,
					'mobileMaxWidth'  => $old_desktop - 1,
				],
				'after'        => [
					'desktopMinWidth' => $new_desktop,
					'mobileMaxWidth'  => $new_desktop - 1,
				],
				'replacements' => $counts,
			]
		);
	}

	/* ---------------------------------------------------------------------------------------- */
	/* Loading and saving                                                                       */
	/* ---------------------------------------------------------------------------------------- */

	private static function error( $code, $message ) {
		return new WP_Error( 'mmpro_' . $code, $message, [ 'status' => 400 ] );
	}

	private static function bricks_ready() {
		if ( ! defined( 'BRICKS_DB_PAGE_HEADER' ) || ! class_exists( '\Bricks\Helpers' ) ) {
			return self::error( 'bricks_missing', 'Bricks is not active on this site.' );
		}
		return true;
	}

	private static function template_type_key() {
		return defined( 'BRICKS_DB_TEMPLATE_TYPE' ) ? BRICKS_DB_TEMPLATE_TYPE : '_bricks_template_type';
	}

	private static function find_headers() {
		$ready = self::bricks_ready();
		if ( is_wp_error( $ready ) ) {
			return $ready;
		}
		$ids     = get_posts(
			[
				'post_type'      => 'bricks_template',
				'post_status'    => [ 'publish', 'draft', 'private' ],
				'posts_per_page' => 50,
				'fields'         => 'ids',
				'meta_key'       => self::template_type_key(), // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => 'header', // phpcs:ignore WordPress.DB.SlowDBQuery
			]
		);
		$headers = [];
		foreach ( $ids as $id ) {
			if ( ! current_user_can( 'edit_post', $id ) ) {
				continue;
			}
			$elements = get_post_meta( $id, BRICKS_DB_PAGE_HEADER, true );
			if ( ! is_array( $elements ) ) {
				continue;
			}
			$elements = array_values( $elements );
			$map      = self::map( $elements );
			if ( $map['headerPro'] ) {
				$root      = $elements[ self::index( $elements )[ $map['headerPro'] ] ];
				$headers[] = [
					'postId' => (int) $id,
					'title'  => get_the_title( $id ),
					'label'  => $root['label'] ?? '',
					'status' => get_post_status( $id ),
				];
			}
		}
		return $headers;
	}

	private static function load( $post_id ) {
		$ready = self::bricks_ready();
		if ( is_wp_error( $ready ) ) {
			return $ready;
		}
		$post = get_post( $post_id );
		if ( ! $post || 'bricks_template' !== $post->post_type ) {
			return self::error( 'not_template', "Post {$post_id} is not a Bricks template." );
		}
		if ( 'header' !== get_post_meta( $post_id, self::template_type_key(), true ) ) {
			return self::error( 'not_header', "Template {$post_id} is not a header template." );
		}
		$elements = get_post_meta( $post_id, BRICKS_DB_PAGE_HEADER, true );
		if ( ! is_array( $elements ) || ! $elements ) {
			return self::error( 'empty', "Header template {$post_id} has no elements." );
		}
		$elements = array_values( $elements );
		$map      = self::map( $elements );
		if ( ! $map['headerPro'] ) {
			return self::error( 'not_mmpro', "Template {$post_id} has no root element labelled \"Header Pro\"." );
		}
		return [
			'elements' => $elements,
			'map'      => $map,
		];
	}

	/** @return array|WP_Error [ post_id, elements, index, map ] */
	private static function load_for_write( $input ) {
		$post_id = self::input_post_id( $input );
		$header  = self::load( $post_id );
		if ( is_wp_error( $header ) ) {
			return $header;
		}
		$expected = (string) ( $input['expectedDigest'] ?? '' );
		if ( '' !== $expected && self::digest( $header['elements'] ) !== $expected ) {
			return self::error( 'stale', 'The header changed since your digest. Call mmpro/get-header again.' );
		}
		return [ $post_id, $header['elements'], self::index( $header['elements'] ), $header['map'] ];
	}

	/** Same order as the Bricks builder save: revision, security check, slashed element list. */
	private static function finish( $post_id, array $elements, $input, array $result ) {
		$elements = array_values( $elements );

		if ( ! empty( $input['dryRun'] ) ) {
			$result['dryRun'] = true;
			return $result;
		}

		add_filter( 'wp_save_post_revision_check_for_changes', '__return_false' );
		$revision_id = wp_save_post_revision( $post_id );
		remove_filter( 'wp_save_post_revision_check_for_changes', '__return_false' );

		// update_post_meta() unslashes; without wp_slash() backslashes in the code blocks are lost.
		$checked = \Bricks\Helpers::security_check_elements_before_save( wp_slash( $elements ), $post_id, 'header' );
		update_post_meta( $post_id, BRICKS_DB_PAGE_HEADER, $checked );
		wp_cache_delete( $post_id, 'post_meta' );

		$stored               = get_post_meta( $post_id, BRICKS_DB_PAGE_HEADER, true );
		$stored               = is_array( $stored ) ? array_values( $stored ) : [];
		$result['dryRun']     = false;
		$result['revisionId'] = is_int( $revision_id ) ? $revision_id : 0;
		$result['digest']     = self::digest( $stored );
		$result['persisted']  = self::digest( $stored ) === self::digest( $elements );
		if ( ! $result['persisted'] ) {
			$result['warning'] = 'The stored header differs from what was sent. Bricks may have removed content this user is not allowed to save.';
		}
		return $result;
	}

	private static function digest( array $elements ) {
		return md5( (string) wp_json_encode( array_values( $elements ) ) );
	}

	/* ---------------------------------------------------------------------------------------- */
	/* Element helpers                                                                          */
	/* ---------------------------------------------------------------------------------------- */

	private static function map( array $elements ) {
		$map = [
			'headerPro'  => null,
			'nav'        => null,
			'navItems'   => null,
			'options'    => null,
			'codeBlocks' => [],
		];
		foreach ( $elements as $element ) {
			$id       = (string) ( $element['id'] ?? '' );
			$name     = $element['name'] ?? '';
			$label    = (string) ( $element['label'] ?? '' );
			$settings = $element['settings'] ?? [];

			if ( ! $map['headerPro'] && empty( $element['parent'] ) && 'section' === $name && 0 === strpos( $label, 'Header Pro' ) ) {
				$map['headerPro'] = $id;
			}
			if ( ! $map['nav'] && 'nav-nested' === $name ) {
				$map['nav'] = $id;
			}
			if ( ! $map['navItems'] && self::has_hidden_class( $element, 'brx-nav-nested-items' ) ) {
				$map['navItems'] = $id;
			}
			if ( 'code' === $name ) {
				$map['codeBlocks'][] = [
					'id'    => $id,
					'label' => $label,
				];
				if ( ! $map['options'] && false !== strpos( (string) ( $settings['javascriptCode'] ?? '' ), 'const MegaMenuCONFIG' ) ) {
					$map['options'] = $id;
				}
			}
		}
		return $map;
	}

	/** A dropdown's Content element whose dropdown is a mega menu. */
	private static function is_mega_content( array $elements, array $idx, $id ) {
		if ( ! isset( $idx[ $id ] ) || ! self::has_hidden_class( $elements[ $idx[ $id ] ], 'brx-dropdown-content' ) ) {
			return false;
		}
		$parent_id = (string) ( $elements[ $idx[ $id ] ]['parent'] ?? '' );
		if ( ! isset( $idx[ $parent_id ] ) ) {
			return false;
		}
		$parent = $elements[ $idx[ $parent_id ] ];
		return 'dropdown' === ( $parent['name'] ?? '' ) && ! empty( $parent['settings']['megaMenu'] );
	}

	private static function find_code_block( array $elements, $label_pattern ) {
		foreach ( $elements as $element ) {
			if ( 'code' === ( $element['name'] ?? '' ) && preg_match( $label_pattern, (string) ( $element['label'] ?? '' ) ) ) {
				return (string) $element['id'];
			}
		}
		return null;
	}

	private static function has_hidden_class( array $element, $class ) {
		$classes = $element['settings']['_hidden']['_cssClasses'] ?? '';
		return is_string( $classes ) && in_array( $class, preg_split( '/\s+/', trim( $classes ) ), true );
	}

	private static function index( array $elements ) {
		$index = [];
		foreach ( $elements as $k => $element ) {
			$index[ (string) $element['id'] ] = $k;
		}
		return $index;
	}

	private static function is_descendant( array $elements, array $idx, $id, $ancestor ) {
		$guard = 0;
		while ( isset( $idx[ $id ] ) && $guard++ < 1000 ) {
			$parent = (string) ( $elements[ $idx[ $id ] ]['parent'] ?? '' );
			if ( $parent === (string) $ancestor ) {
				return true;
			}
			if ( '' === $parent || '0' === $parent ) {
				return false;
			}
			$id = $parent;
		}
		return false;
	}

	private static function require_inside_nav_items( array $elements, array $idx, array $map, $id, $allow_nav_items ) {
		if ( ! isset( $idx[ $id ] ) ) {
			return self::error( 'no_element', "No element {$id} in this header." );
		}
		if ( ! $map['navItems'] ) {
			return self::error( 'no_nav_items', 'The Nav items block (brx-nav-nested-items) was not found.' );
		}
		if ( $allow_nav_items && $id === $map['navItems'] ) {
			return true;
		}
		if ( ! self::is_descendant( $elements, $idx, $id, $map['navItems'] ) ) {
			return self::error( 'protected', "Element {$id} is part of the header structure, not the menu. Only elements inside Nav items can be changed this way." );
		}
		return true;
	}

	/** The element and all its descendants, element first. */
	private static function subtree_ids( array $elements, array $idx, $root_id ) {
		$by_parent = [];
		foreach ( $elements as $element ) {
			$by_parent[ (string) ( $element['parent'] ?? '' ) ][] = (string) $element['id'];
		}
		$ids   = [];
		$queue = [ (string) $root_id ];
		while ( $queue ) {
			$id    = array_shift( $queue );
			$ids[] = $id;
			foreach ( $by_parent[ $id ] ?? [] as $child ) {
				$queue[] = $child;
			}
		}
		return array_values(
			array_filter(
				$ids,
				function ( $id ) use ( $idx ) {
					return isset( $idx[ $id ] );
				}
			)
		);
	}

	private static function new_id( array $taken ) {
		do {
			$id = class_exists( '\Bricks\Helpers' ) ? \Bricks\Helpers::generate_random_id( false ) : substr( md5( (string) wp_rand() ), 0, 6 );
		} while ( isset( $taken[ $id ] ) );
		return $id;
	}

	private static function attribute_ids( array $attributes ) {
		$ids = [];
		foreach ( $attributes as $attribute ) {
			if ( isset( $attribute['id'] ) ) {
				$ids[ $attribute['id'] ] = true;
			}
		}
		return $ids;
	}

	private static function attribute_map( array $element ) {
		$out = [];
		foreach ( (array) ( $element['settings']['_attributes'] ?? [] ) as $attribute ) {
			if ( isset( $attribute['name'] ) ) {
				$out[ $attribute['name'] ] = (string) ( $attribute['value'] ?? '' );
			}
		}
		return $out;
	}

	private static function is_nestable( $name ) {
		if ( ! class_exists( '\Bricks\Elements' ) ) {
			return false;
		}
		$element = \Bricks\Elements::get_element( [ 'name' => $name ] );
		return is_array( $element ) && ! empty( $element['nestable'] );
	}

	private static function element_exists( $name ) {
		if ( ! class_exists( '\Bricks\Elements' ) ) {
			return false;
		}
		$element = \Bricks\Elements::get_element( [ 'name' => $name ] );
		return is_array( $element ) && ! empty( $element );
	}

	private static function link_summary( array $element ) {
		return [
			'name'   => $element['name'] ?? '',
			'text'   => $element['settings']['text'] ?? null,
			'url'    => $element['settings']['link']['url'] ?? null,
			'newTab' => ! empty( $element['settings']['link']['newTab'] ),
		];
	}

	private static function apply_link_fields( array $element, array $input ) {
		$name = $element['name'] ?? '';
		if ( ! in_array( $name, [ 'text-link', 'text-basic', 'dropdown' ], true ) ) {
			return self::error( 'not_a_link', "Element {$element['id']} is a {$name}, not a link or dropdown." );
		}
		if ( isset( $input['text'] ) ) {
			$element['settings']['text'] = wp_kses_post( (string) $input['text'] );
		}
		if ( isset( $input['url'] ) || isset( $input['newTab'] ) ) {
			if ( 'dropdown' === $name ) {
				return self::error( 'dropdown_link', 'A Dropdown has no link to set. Change its text only.' );
			}
			$link = is_array( $element['settings']['link'] ?? null ) ? $element['settings']['link'] : [];
			if ( isset( $input['url'] ) ) {
				$link['type'] = 'external';
				$link['url']  = esc_url_raw( (string) $input['url'] );
			}
			if ( isset( $input['newTab'] ) ) {
				if ( $input['newTab'] ) {
					$link['newTab'] = true;
				} else {
					unset( $link['newTab'] );
				}
			}
			$element['settings']['link'] = $link;
		}
		return $element;
	}

	private static function menu_tree( array $elements, array $idx, $parent_id, $depth ) {
		$items = [];
		foreach ( (array) ( $elements[ $idx[ $parent_id ] ]['children'] ?? [] ) as $child_id ) {
			$child_id = (string) $child_id;
			if ( ! isset( $idx[ $child_id ] ) ) {
				continue;
			}
			$element = $elements[ $idx[ $child_id ] ];
			$item    = [
				'id'    => $child_id,
				'name'  => $element['name'] ?? '',
				'label' => $element['label'] ?? '',
				'text'  => isset( $element['settings']['text'] ) ? wp_strip_all_tags( (string) $element['settings']['text'] ) : null,
			];
			if ( isset( $element['settings']['link']['url'] ) ) {
				$item['url'] = $element['settings']['link']['url'];
			}
			$attributes = array_filter( self::attribute_map( $element ), 'strlen' );
			if ( $attributes ) {
				$item['attributes'] = $attributes;
			}

			if ( 'dropdown' === $item['name'] ) {
				$item['megaMenu'] = ! empty( $element['settings']['megaMenu'] );
				foreach ( (array) ( $element['children'] ?? [] ) as $content_id ) {
					$content_id = (string) $content_id;
					if ( ! isset( $idx[ $content_id ] ) || ! self::has_hidden_class( $elements[ $idx[ $content_id ] ], 'brx-dropdown-content' ) ) {
						continue;
					}
					$item['contentId'] = $content_id;
					if ( $item['megaMenu'] ) {
						$item['contentAttributes'] = self::attribute_map( $elements[ $idx[ $content_id ] ] );
						$item['content']           = [];
						foreach ( (array) ( $elements[ $idx[ $content_id ] ]['children'] ?? [] ) as $inner_id ) {
							$inner_id = (string) $inner_id;
							if ( isset( $idx[ $inner_id ] ) ) {
								$item['content'][] = [
									'id'    => $inner_id,
									'name'  => $elements[ $idx[ $inner_id ] ]['name'] ?? '',
									'label' => $elements[ $idx[ $inner_id ] ]['label'] ?? '',
								];
							}
						}
					} elseif ( $depth < 4 ) {
						$item['items'] = self::menu_tree( $elements, $idx, $content_id, $depth + 1 );
					}
					break;
				}
			}
			$items[] = $item;
		}
		return $items;
	}

	/** @return string|WP_Error ID of the node's root element. */
	private static function flatten_node( $node, $parent_id, array &$flat, array &$taken, $depth ) {
		if ( $depth > 20 ) {
			return self::error( 'too_deep', 'Element tree is nested too deeply.' );
		}
		if ( ! is_array( $node ) || empty( $node['name'] ) || ! is_string( $node['name'] ) ) {
			return self::error( 'bad_element', 'Every element needs a name.' );
		}
		$name = $node['name'];
		if ( 'code' === $name || ! self::element_exists( $name ) ) {
			return self::error( 'bad_element', "Element type {$name} is not allowed or does not exist." );
		}
		$settings = isset( $node['settings'] ) && is_array( $node['settings'] ) ? $node['settings'] : [];
		if ( self::has_code_keys( $settings ) ) {
			return self::error( 'code_setting', "Settings for {$name} contain a code-running key. These are not accepted." );
		}
		$children = isset( $node['children'] ) && is_array( $node['children'] ) ? $node['children'] : [];
		if ( $children && ! self::is_nestable( $name ) ) {
			return self::error( 'not_nestable', "Element type {$name} cannot have children." );
		}

		$id           = self::new_id( $taken );
		$taken[ $id ] = true;
		$element      = [
			'id'       => $id,
			'name'     => $name,
			'parent'   => $parent_id,
			'children' => [],
			'settings' => $settings,
		];
		if ( ! empty( $node['label'] ) && is_string( $node['label'] ) ) {
			$element['label'] = sanitize_text_field( $node['label'] );
		}
		$position = count( $flat );
		$flat[]   = $element;

		foreach ( $children as $child ) {
			$child_id = self::flatten_node( $child, $id, $flat, $taken, $depth + 1 );
			if ( is_wp_error( $child_id ) ) {
				return $child_id;
			}
			$flat[ $position ]['children'][] = $child_id;
		}
		return $id;
	}

	private static function has_code_keys( array $settings ) {
		foreach ( $settings as $key => $value ) {
			if ( in_array( (string) $key, self::CODE_KEYS, true ) ) {
				return true;
			}
			if ( is_array( $value ) && self::has_code_keys( $value ) ) {
				return true;
			}
		}
		return false;
	}

	/* ---------------------------------------------------------------------------------------- */
	/* Code block parsing                                                                       */
	/*                                                                                          */
	/* Edits are made on the stored string at exact offsets, so every other character of the   */
	/* code block stays as it was. Structure is found on a copy with comments (and, in JS,      */
	/* strings) blanked out to spaces, which keeps the offsets identical.                       */
	/* ---------------------------------------------------------------------------------------- */

	private static function blank( $src, $js ) {
		$out = $src;
		$len = strlen( $src );
		$i   = 0;
		while ( $i < $len ) {
			$c   = $src[ $i ];
			$n   = $i + 1 < $len ? $src[ $i + 1 ] : '';
			$end = null;
			if ( '/' === $c && '*' === $n ) {
				$close = strpos( $src, '*/', $i + 2 );
				$end   = false === $close ? $len : $close + 2;
			} elseif ( $js && '/' === $c && '/' === $n ) {
				$close = strpos( $src, "\n", $i );
				$end   = false === $close ? $len : $close;
			} elseif ( $js && ( "'" === $c || '"' === $c || '`' === $c ) ) {
				$j = $i + 1;
				while ( $j < $len && $src[ $j ] !== $c ) {
					$j += '\\' === $src[ $j ] ? 2 : 1;
				}
				// Keep the quotes, blank the inside.
				for ( $p = $i + 1; $p < min( $j, $len ); $p++ ) {
					if ( "\n" !== $out[ $p ] ) {
						$out[ $p ] = ' ';
					}
				}
				$i = $j + 1;
				continue;
			}
			if ( null !== $end ) {
				for ( $p = $i; $p < $end; $p++ ) {
					if ( "\n" !== $out[ $p ] ) {
						$out[ $p ] = ' ';
					}
				}
				$i = $end;
				continue;
			}
			$i++;
		}
		return $out;
	}

	/** @return array|WP_Error [ offset of '{', offset of matching '}' ] */
	private static function css_rule_span( $css, $selector ) {
		$clean   = self::blank( $css, false );
		$parts   = preg_split( '/\s+/', trim( $selector ) );
		$escaped = array_map(
			function ( $part ) {
				return preg_quote( $part, '/' );
			},
			$parts
		);
		$regex   = '/(^|[};])(\s*)(' . implode( '\s+', $escaped ) . ')\s*\{/';
		if ( ! preg_match_all( $regex, $clean, $matches, PREG_OFFSET_CAPTURE ) ) {
			return self::error( 'no_rule', "Rule {$selector} was not found in MENU Styles / Options." );
		}
		if ( count( $matches[0] ) > 1 ) {
			return self::error( 'ambiguous_rule', "Rule {$selector} appears more than once in MENU Styles / Options." );
		}
		$open  = $matches[0][0][1] + strlen( $matches[0][0][0] ) - 1;
		$close = self::matching_brace( $clean, $open );
		if ( null === $close ) {
			return self::error( 'broken_rule', "Rule {$selector} has no closing brace." );
		}
		return [ $open, $close ];
	}

	/** @return array|null|WP_Error [ value start, value end ] or null if absent. */
	private static function css_declaration( $css, array $span, $variable ) {
		list( $open, $close ) = $span;
		$clean                = self::blank( $css, false );
		$body                 = substr( $clean, $open + 1, $close - $open - 1 );
		$regex                = '/(^|[\s;{])' . preg_quote( $variable, '/' ) . '\s*:\s*/';
		if ( ! preg_match_all( $regex, $body, $matches, PREG_OFFSET_CAPTURE ) ) {
			return null;
		}
		if ( count( $matches[0] ) > 1 ) {
			return self::error( 'ambiguous_variable', "{$variable} is declared more than once in this rule." );
		}
		$start = $open + 1 + $matches[0][0][1] + strlen( $matches[0][0][0] );
		$stop  = strpos( $clean, ';', $start );
		if ( false === $stop || $stop > $close ) {
			$stop = $close;
		}
		$end = $stop;
		while ( $end > $start && ctype_space( $clean[ $end - 1 ] ) ) {
			$end--;
		}
		return [ $start, $end ];
	}

	private static function css_rule_values( $css, $selector ) {
		$span = self::css_rule_span( $css, $selector );
		if ( is_wp_error( $span ) ) {
			return $span;
		}
		list( $open, $close ) = $span;
		$clean                = self::blank( $css, false );
		$body                 = substr( $clean, $open + 1, $close - $open - 1 );
		$out                  = [];
		if ( preg_match_all( '/(^|[\s;{])(--[a-zA-Z0-9_-]+)\s*:/', $body, $matches ) ) {
			foreach ( $matches[2] as $variable ) {
				$found = self::css_declaration( $css, $span, $variable );
				if ( is_array( $found ) ) {
					$out[ $variable ] = substr( $css, $found[0], $found[1] - $found[0] );
				}
			}
		}
		return $out;
	}

	private static function matching_brace( $clean, $open ) {
		$depth = 0;
		$len   = strlen( $clean );
		for ( $i = $open; $i < $len; $i++ ) {
			if ( '{' === $clean[ $i ] ) {
				$depth++;
			} elseif ( '}' === $clean[ $i ] ) {
				$depth--;
				if ( 0 === $depth ) {
					return $i;
				}
			}
		}
		return null;
	}

	/** @return array|WP_Error [ offset of '{', offset of matching '}' ] */
	private static function js_object_span( $js, $object ) {
		$clean = self::blank( $js, true );
		if ( ! preg_match_all( '/\b(?:const|let|var)\s+' . preg_quote( $object, '/' ) . '\s*=\s*\{/', $clean, $matches, PREG_OFFSET_CAPTURE ) ) {
			return self::error( 'no_object', "{$object} was not found in MENU Styles / Options." );
		}
		if ( count( $matches[0] ) > 1 ) {
			return self::error( 'ambiguous_object', "{$object} is declared more than once." );
		}
		$open  = $matches[0][0][1] + strlen( $matches[0][0][0] ) - 1;
		$close = self::matching_brace( $clean, $open );
		if ( null === $close ) {
			return self::error( 'broken_object', "{$object} has no closing brace." );
		}
		return [ $open, $close ];
	}

	/** @return array|null|WP_Error [ value start, value end ] of a top-level property, or null. */
	private static function js_option( $js, array $span, $option ) {
		list( $open, $close ) = $span;
		$clean                = self::blank( $js, true );
		$body                 = substr( $clean, $open + 1, $close - $open - 1 );
		if ( ! preg_match_all( '/(^|[\s,{])' . preg_quote( $option, '/' ) . '\s*:\s*/', $body, $matches, PREG_OFFSET_CAPTURE ) ) {
			return null;
		}
		if ( count( $matches[0] ) > 1 ) {
			return self::error( 'ambiguous_option', "{$option} appears more than once." );
		}
		$start = $open + 1 + $matches[0][0][1] + strlen( $matches[0][0][0] );
		$end   = $start;
		while ( $end < $close && ',' !== $clean[ $end ] && "\n" !== $clean[ $end ] ) {
			$end++;
		}
		while ( $end > $start && ctype_space( $clean[ $end - 1 ] ) ) {
			$end--;
		}
		return [ $start, $end ];
	}

	private static function js_object_values( $js, $object ) {
		$span = self::js_object_span( $js, $object );
		if ( is_wp_error( $span ) ) {
			return $span;
		}
		list( $open, $close ) = $span;
		$clean                = self::blank( $js, true );
		$body                 = substr( $clean, $open + 1, $close - $open - 1 );
		$out                  = [];
		if ( preg_match_all( '/(^|[\s,{])([A-Za-z_$][A-Za-z0-9_$]*)\s*:/', $body, $matches ) ) {
			foreach ( $matches[2] as $option ) {
				$found = self::js_option( $js, $span, $option );
				if ( is_array( $found ) ) {
					$out[ $option ] = substr( $js, $found[0], $found[1] - $found[0] );
				}
			}
		}
		return $out;
	}

	/** Format a value the way the existing token is written. */
	private static function js_literal( $value, $before ) {
		$before = trim( (string) $before );
		if ( is_bool( $value ) ) {
			if ( '0' === $before || '1' === $before ) {
				return $value ? '1' : '0';
			}
			return $value ? 'true' : 'false';
		}
		if ( is_int( $value ) || is_float( $value ) ) {
			return (string) $value;
		}
		$value = (string) $value;
		if ( preg_match( '/[\r\n]/', $value ) ) {
			return self::error( 'bad_value', 'String options must be on one line.' );
		}
		$quote = ( '' !== $before && ( '"' === $before[0] || "'" === $before[0] ) ) ? $before[0] : "'";
		return $quote . str_replace( [ '\\', $quote ], [ '\\\\', '\\' . $quote ], $value ) . $quote;
	}
}

MMPro_AI_Abilities::boot();
