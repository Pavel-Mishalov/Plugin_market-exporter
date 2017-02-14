<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Market_Exporter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since     0.0.1
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     0.0.1
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      0.0.1
	 *
	 * @param      string $plugin_name   The name of this plugin.
	 * @param      string $version       The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since     0.0.1
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Market_Exporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Market_Exporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/market-exporter-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Market_Exporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Market_Exporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/market-exporter-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add sub menu page to the WooCommerce menu.
	 *
	 * @since   0.0.1
	 */
	public function add_admin_page() {
        add_submenu_page(
            'woocommerce',
            __( 'Market Exporter', $this->plugin_name ),
            __( 'Market Exporter', $this->plugin_name ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_admin_page' )
        );
	}

	/**
	 * Display plugin page.
	 *
	 * @since   0.0.1
	 */
	public function display_admin_page() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/market-exporter-admin-display.php';
	}

	/**
	 * Add settings fields.
	 *
	 * @since   0.0.4
	 */
	public function register_settings() {
        register_setting(
            $this->plugin_name,
            'market_exporter_shop_settings',
            array( &$this, 'validate_shop_settings_array' )
        );

        add_settings_section(
            'market_exporter_section_general',
            __('Global settings', $this->plugin_name),
            array( &$this, 'section_general_cb' ),
            $this->plugin_name
        );

		// Add website name text field option.
        add_settings_field(
            'market_exporter_website_name',
            __( 'Website Name', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'website_name',
                'placeholder'       => __( 'Website Name', $this->plugin_name ),
                'description'       => __( 'Not longer than 20 characters. Has to be the name of the shop, that is configured in Yandex Market.', $this->plugin_name ),
                'type'              => 'text'
            ]
        );

		// Add company name text field option.
        add_settings_field(
            'market_exporter_company_name',
            __( 'Company Name', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'company_name',
                'placeholder'       => __( 'Company Name', $this->plugin_name ),
                'description'       => __( 'Full company name. Not published in Yandex Market.', $this->plugin_name ),
                'type'              => 'text'
            ]
        );

		// Цена доставки
        add_settings_field(
            'market_exporter_count_delivery_all',
            __( 'Цена доставки', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'count_delivery_all',
                'placeholder'       => __( 'Цена доставки', $this->plugin_name ),
                'description'       => __( 'Введите цену курьерской доставки товара.', $this->plugin_name ),
                'type'              => 'text'
            ]
        );

		// Время доставки
        add_settings_field(
            'market_exporter_days_delivery_all',
            __( 'Время доставки', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'days_delivery_all',
                'placeholder'       => __( 'Срок доставки', $this->plugin_name ),
                'description'       => __( 'Введите необходимое количество дней для доставки товара.', $this->plugin_name ),
                'type'              => 'text'
            ]
        );

		// Цена доставки
        add_settings_field(
            'market_exporter_order_before_delivery_all',
            __( 'Время приема заказов', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'order_before_delivery_all',
                'placeholder'       => __( 'Введите число', $this->plugin_name ),
                'description'       => __( 'Введите число, до которого часа принимаете заказы курьерской доставки.', $this->plugin_name ),
                'type'              => 'text'
            ]
        );
		
		$attributes_array['not_set'] = __( 'Disabled', $this->plugin_name );
		foreach ( $this->get_attributes() as $attribute ) {
			$attributes_array[ $attribute[0] ] = $attribute[1];
		}

		// Цена доставки товара
        add_settings_field(
            'market_exporter_count_delivery_product',
            __( 'Цена доставки товара', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'count_delivery_product',
                'description'       => __( 'Выберите атрибут цены курьерской доставки товара.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
            ]
        );

		// Время доставки товара
        add_settings_field(
            'market_exporter_days_delivery_product',
            __( 'Время доставки товара', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'days_delivery_product',
                'description'       => __( 'Выберите атрибут количества дней доставки товара.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
            ]
        );
		
		// Время приема заказов доставки
        add_settings_field(
            'market_exporter_order_before_delivery_product',
            __( 'Время приема заказов доставки товара', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'order_before_delivery_product',
                'description'       => __( 'Выберите атрибут, который отвечает за время до которого часа принимаете заказы на доставки товара.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
            ]
        );
		
		//  Значения ставки в прайс-листе (oсновная ставка)
        add_settings_field(
            'market_exporter_mis_bid',
            __( 'Значения ставки в прайс-листе (oсновная ставка)', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_bid',
                'description'       => __( 'Выберите атрибут значения ставки в прайс-листе (oсновная ставка).', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
            ]
        );

		// Значения ставки в прайс-листе (cтавка для карточки модели)
        add_settings_field(
            'market_exporter_mis_cbid',
            __( 'Значения ставки в прайс-листе (cтавка для карточки модели)', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_cbid',
                'description'       => __( 'Выберите атрибут значения ставки в прайс-листе (cтавка для карточки модели).', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
            ]
        );
		
		// Размер комиссии товара в прайс-листе
        add_settings_field(
            'market_exporter_mis_fee',
            __( 'Размер комиссии товара в прайс-листе', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_fee',
                'description'       => __( 'Выберите атрибут, который отвечает за размер комиссии товара в прайс-листе.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
            ]
        );
		
		$rate_banks['not_set'] = __( 'Disabled', $this->plugin_name );
		$rate_banks['CBRF'] = 'Курс по Центральному банку РФ';
		$rate_banks['NBU'] = 'Курс по Национальному банку Украины';
		$rate_banks['NBK'] = 'Курс по Национальному банку Казахстана';
		$rate_banks['CB'] = 'Курс по банку той страны, к которой относится магазин по своему региону, указанному в личном кабинете';
		// Курс рубля к гривне
        add_settings_field(
            'market_exporter_ua',
            __( 'Курс рубля к гривне', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_ua',
                'description'       => __( 'Выберите НБ, который отвечает курс рубля к гривне.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $rate_banks
			]
        );

		// Курс рубля к беларускому рублю
        add_settings_field(
            'market_exporter_bel',
            __( 'Курс рубля к беларускому рублю', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_bel',
                'description'       => __( 'Выберите НБ, который отвечает курс рубля к беларускому рублю.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $rate_banks
			]
        );

		// Курс рубля к тенге
        add_settings_field(
            'market_exporter_tenge',
            __( 'Курс рубля к тенге', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_tenge',
                'description'       => __( 'Выберите НБ, который отвечает курс рубля к тенге.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $rate_banks
			]
        );

		// Курс рубля к доллару
        add_settings_field(
            'market_exporter_usa',
            __( 'Курс рубля к доллару', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_usa',
                'description'       => __( 'Выберите НБ, который отвечает курс рубля к доллару.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $rate_banks
			]
        );

		// Курс рубля к евро
        add_settings_field(
            'market_exporter_euro',
            __( 'Курс рубля к евро', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_euro',
                'description'       => __( 'Выберите НБ, который отвечает курс рубля к евро.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $rate_banks
			]
        );
		
		// Валюта продажи товара
        add_settings_field(
            'market_exporter_curency_delivery_product',
            __( 'Валюта продажи товара', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'curensy_delivery_product',
                'description'       => __( 'Выберите атрибут, который отвечает за выбор валюты товара.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
            ]
        );

		// Участие в программе «Заказ на Маркете»
        add_settings_field(
            'market_exporter_mis_cpa',
            __( 'Участие в программе «Заказ на Маркете»', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_cpa',
                'description'       => __( 'Выберите атрибут, который отвечает за участие в программе «Заказ на Маркете».', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
			]
        );

		// Add backorders field option.
        add_settings_field(
            'market_exporter_file_date',
            __( 'Add date to YML file name', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'file_date',
                'description'       => __( 'If enabled YML file will have current date at the end: ym-export-yyyy-mm-dd.yml.', $this->plugin_name ),
                'type'              => 'checkbox'
            ]
        );

		// Add image count text field option.
		add_settings_field(
			'market_exporter_image_count',
			__( 'Images per product', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'image_count',
				'placeholder'       => __( 'Images per product', $this->plugin_name ),
				'description'       => __( 'Max number of images to export for product. Max 10 images.', $this->plugin_name ),
				'type'              => 'text'
			]
		);

		// Add selection of 'vendor' property.
		add_settings_field(
			'market_exporter_vendor',
			__( 'Vendor property', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'vendor',
				'description'       => __( 'Custom property used to specify vendor.', $this->plugin_name ),
				'type'              => 'select',
				'options'			=> $attributes_array
			]
		);

		// Add selection of 'model' property
		add_settings_field(
			'market_exporter_model',
			__( 'Модель товара', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'model',
				'description'       => __( 'Выберите атрибут, который отвечает за данное поле.', $this->plugin_name ),
				'type'              => 'select',
				'options'			=> $attributes_array
			]
		);

		// Add market_category text field option.
		add_settings_field(
			'market_exporter_market_category',
			__( 'Market category property', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'market_category',
				'description'       => sprintf( __( 'Category of product on Yandex Market. Can be set to a value from <a href="%s" target="_blank">this list</a> only.', $this->plugin_name ), 'http://download.cdn.yandex.net/market/market_categories.xls' ),
				'type'              => 'select',
				'options'			=> $attributes_array
			]
		);

		// Add sales_notes field option.
		add_settings_field(
			'market_exporter_sales_notes',
			__( 'Enable sales_notes', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'sales_notes',
				'description'       => __( 'If enabled will use product field "short description" as value for property "sales_notes". Not longer than 50 characters.', $this->plugin_name ),
				'type'              => 'checkbox'
			]
		);

		// Add backorders field option.
		add_settings_field(
			'market_exporter_backorders',
			__( 'Export products with backorders', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'backorders',
				'description'       => __( 'If enabled products that are available for backorder will be exported to YML.', $this->plugin_name ),
				'type'              => 'checkbox'
			]
		);

		// Возможность доставки товара
		add_settings_field(
			'market_exporter_mis_delivery_option',
			__( 'Возможность доставки товаров', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'mis_delivery_option',
				'description'       => __( 'Если активна, то доставка товара осуществляется.', $this->plugin_name ),
				'type'              => 'checkbox'
			]
		);

		// Возможность самовывоза
		add_settings_field(
			'market_exporter_mis_pickup',
			__( 'Возможность самовывоза товаров', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'mis_pickup',
				'description'       => __( 'Если активна, то самовывоз товара возможен.', $this->plugin_name ),
				'type'              => 'checkbox'
			]
		);

		// Наличие точки продажи
		add_settings_field(
			'market_exporter_mis_store',
			__( 'Наличие точки продаж', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'mis_store',
				'description'       => __( 'Если активна, то иммеются точки продажи.', $this->plugin_name ),
				'type'              => 'checkbox'
			]
		);

		// Возможность доставки товара
        add_settings_field(
            'market_exporter_mis_delivery_product',
            __( 'Возможность доставки товара', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_delivery_product',
                'description'       => __( 'Выберите атрибут, который отвечает за возможность доставки товара.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
			]
        );

		// Возможность самовывоза товара
        add_settings_field(
            'market_exporter_mis_pickup_product',
            __( 'Возможность самовывоза товара', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_pickup_product',
                'description'       => __( 'Выберите атрибут, который отвечает за самовывоз товара.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
			]
        );

		// Наличие точки продажи товара
        add_settings_field(
            'market_exporter_mis_store_product',
            __( 'Наличие точки продажи товара', $this->plugin_name ),
            array( &$this, 'input_fields_cb' ),
            $this->plugin_name,
            'market_exporter_section_general',
            [
                'label_for'         => 'mis_store_product',
                'description'       => __( 'Выберите атрибут, который отвечает за наличие точки продажи товара.', $this->plugin_name ),
                'type'              => 'select',
				'options'			=> $attributes_array
			]
        );
		
		// Список свойств
		add_settings_field(
			'market_exporter_include_param',
			__( 'Список параметров товара', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'include_param',
				'description'       => __( 'Только выбранные категории атрибуты товаров будут отображать параметры товара. Чтобы выбрать несколько, зажмите клавишу ctrl (на Windows) или cmd (на Mac).', $this->plugin_name ),
				'type'              => 'mis_multiselect'
			]
		);

		// Add categories multiselect option.
		add_settings_field(
			'market_exporter_include_cat',
			__( 'Include selected categories', $this->plugin_name ),
			array( &$this, 'input_fields_cb' ),
			$this->plugin_name,
			'market_exporter_section_general',
			[
				'label_for'         => 'include_cat',
				'description'       => __( 'Only selected categories will be included in the export file. Hold down the control (ctrl) button on Windows or command (cmd) on Mac to select multiple options. If nothing is selected - all the categories will be exported.', $this->plugin_name ),
				'type'              => 'multiselect'
			]
		);
	}

    /**
     * Callback function for add_settings_section().
     * $args have the following keys defined: title, id, callback.
     * The values are defined at the add_settings_section() function.
     *
     * @since 0.3.0
     * @param $args
     */
    public function section_general_cb( $args ) {
        ?>
        <p id="<?= esc_attr( $args[ 'id' ] ); ?>">
            <?= esc_html__( 'Settings that are used in the export process.', $this->plugin_name ); ?>
        </p>
        <?php
    }

    /**
     * Callback function for add_settings_field().
     * The values for $args are defined at the add_settings_field() function.
     *
     * @since 0.3.0
     * @param $args
     */
    public function input_fields_cb( $args ) {
        $options = get_option('market_exporter_shop_settings');

        if ( esc_attr( $args[ 'type' ] ) == 'text' || esc_attr( $args[ 'type' ] ) == 'checkbox' ) : ?>

            <input id="<?= esc_attr( $args[ 'label_for' ] ); ?>"
				   type="<?= esc_attr( $args[ 'type' ] ); ?>"
                   name="market_exporter_shop_settings[<?= esc_attr( $args[ 'label_for' ] ); ?>]"
                   value="<?= $options[ $args[ 'label_for' ] ]; ?>"
                   <?php if ( esc_attr( $args[ 'type' ] ) == 'text' ) :?>placeholder="<?= esc_attr( $args[ 'placeholder' ] ); endif; ?>"
				   <?php if ( esc_attr( $args[ 'type' ] ) == 'checkbox' && $options[ $args[ 'label_for' ] ] == 'yes' ) echo "checked"; ?>>

        <?php elseif ( esc_attr( $args[ 'type' ] ) == 'select' ) : ?>

			<select id="<?= esc_attr( $args[ 'label_for' ] ); ?>"
					name="market_exporter_shop_settings[<?= esc_attr( $args[ 'label_for' ] ); ?>]">
				<?php foreach( $args[ 'options' ] as $key => $value ) : ?>
					<option value="<?= $key; ?>" <?php if ( $options[ $args[ 'label_for' ] ] == $key ) echo 'selected'; ?>>
						<?= $value; ?>
					</option>
				<?php endforeach; ?>
			</select>

		<?php elseif ( esc_attr( $args[ 'type' ] ) == 'mis_multiselect' ) :

			$select_param_array = [];
			if ( isset( $options[ $args[ 'label_for' ] ] ) )
				$select_param_array = $options[ $args[ 'label_for' ] ];
			?>
			<select size="10" id="<?= esc_attr( $args[ 'label_for' ] ); ?>"
					name="market_exporter_shop_settings[<?= esc_attr( $args[ 'label_for' ] ); ?>][]"
					multiple>
				<?php foreach ( $this->get_attributes() as $attribute ) : ?>
					<option value="<?= $attribute[0].' | '.$attribute[1]; ?>"
							<?php if ( in_array( $attribute[0], $select_param_array ) ) echo "selected"; ?>><?= $attribute[1]; ?></option>
				<?php endforeach; ?>
			</select>

		<?php elseif ( esc_attr( $args[ 'type' ] ) == 'multiselect' ) :

			$select_array = [];
			if ( isset( $options[ $args[ 'label_for' ] ] ) )
				$select_array = $options[ $args[ 'label_for' ] ];
			?>
			<select size="10" id="<?= esc_attr( $args[ 'label_for' ] ); ?>"
					name="market_exporter_shop_settings[<?= esc_attr( $args[ 'label_for' ] ); ?>][]"
					multiple>
				<?php foreach ( get_categories( [ 'taxonomy' => 'product_cat', 'parent' => 0 ] ) as $category ) : ?>
					<option value="<?= $category->cat_ID; ?>"
							<?php if ( in_array( $category->cat_ID, $select_array ) ) echo "selected"; ?>><?= $category->name; ?></option>
					<?php foreach ( get_categories( [ 'taxonomy' => 'product_cat', 'parent' => $category->cat_ID ] ) as $subcategory ) : ?>
						<option value="<?= $subcategory->cat_ID; ?>"
								<?php if ( in_array( $subcategory->cat_ID, $select_array ) ) echo "selected"; ?>><?= "&mdash;&nbsp;" . $subcategory->name; ?></option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</select>

		<?php endif; ?>

		<p class="description">
			<?= $args[ 'description' ]; ?>
		</p>

		<?php
    }

	/**
	 * Sanitize shop settings array.
	 *
	 * @since   0.0.5
	 *
	 * @param   array $input Current settings.
	 *
	 * @return  array             $output     Sanitized settings.
	 */
	public function validate_shop_settings_array( $input ) {
		$output = get_option( 'market_exporter_shop_settings' );

		$output['website_name'] = sanitize_text_field( $input['website_name'] );
		$output['company_name']	= sanitize_text_field( $input['company_name'] );

		// According to Yandex up to 10 images per product.
		$images = intval( $input['image_count'] );
		if ( $images > 10 ) {
			$output['image_count'] = 10;
		} else {
			$output['image_count'] = $images;
		}
		
		$output['count_delivery_all']   = intval( $input['count_delivery_all'] );
		$output['days_delivery_all']    = intval( $input['days_delivery_all'] );
		$output['order_before_delivery_all'] = intval( $input['order_before_delivery_all'] );
		$output['count_delivery_product'] = sanitize_text_field( $input['count_delivery_product'] );
		$output['days_delivery_product'] = sanitize_text_field( $input['days_delivery_product'] );
		$output['order_before_delivery_product'] = sanitize_text_field( $input['order_before_delivery_product'] );
		$output['mis_bid']              = sanitize_text_field( $input['mis_bid'] );
		$output['mis_cbid']             = sanitize_text_field( $input['mis_cbid'] );
		$output['mis_fee']              = sanitize_text_field( $input['mis_fee'] );
		$output['mis_bel']              = sanitize_text_field( $input['mis_bel'] );
		$output['mis_tenge']            = sanitize_text_field( $input['mis_tenge'] );
		$output['mis_usa']              = sanitize_text_field( $input['mis_usa'] );
		$output['mis_euro']             = sanitize_text_field( $input['mis_euro'] );
		$output['mis_ua']               = sanitize_text_field( $input['mis_ua'] );
		$output['curensy_delivery_product'] = $input['curensy_delivery_product'];
		$output['vendor']               = sanitize_text_field( $input['vendor'] );
		$output['model']                = sanitize_text_field( $input['model'] );
		$output['market_category']      = sanitize_text_field( $input['market_category'] );
		$output['mis_delivery_product'] = sanitize_text_field( $input['mis_delivery_product'] );
		$output['mis_pickup_product']   = sanitize_text_field( $input['mis_pickup_product'] );
		$output['mis_store_product']    = sanitize_text_field( $input['mis_store_product'] );
		$output['mis_cpa']              = sanitize_text_field( $input['mis_cpa'] );

		$output['sales_notes']	= ( isset( $input['sales_notes'] ) ) ? true : false;
		$output['backorders']	= ( isset( $input['backorders'] ) ) ? true : false;
		$output['file_date']	= ( isset( $input['file_date'] ) ) ? true : false;
		$output['mis_delivery_option']	= ( isset( $input['mis_delivery_option'] ) ) ? true : false;
		$output['mis_pickup']	= ( isset( $input['mis_pickup'] ) ) ? true : false;
		$output['mis_store']	= ( isset( $input['mis_store'] ) ) ? true : false;

		$output['include_param']	= $input['include_param'];
		// Convert to int array.
		$output['include_cat']	= array_map( 'intval', $input['include_cat'] );

		return $output;
	}

	/**
	 * Add Setings link to plugin in plugins list.
	 *
	 * @since   0.0.5
	 *
	 * @param   array $links Links for the current plugin.
	 *
	 * @return  array                          New links array for the current plugin.
	 */
	public function plugin_add_settings_link( $links ) {
		$settings_link = "<a href=" . admin_url( 'admin.php?page=' . $this->plugin_name . '&tab=settings' ) . ">" . __( 'Settings', $this->plugin_name ) . "</a>";
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Get custom attributes.
	 *
	 * Used on WooCommerce settings page. It lets the user choose which of the custom attributes to use for vendor value.
	 *
	 * @since      0.0.7
	 * @return      array                                Return the array of custom attributes.
	 */
	private function get_attributes() {
		global $wpdb;

		return $wpdb->get_results(
			"SELECT attribute_name AS attr_key, attribute_label AS attr_value
								 FROM $wpdb->prefix" . "woocommerce_attribute_taxonomies", ARRAY_N );
	}

	/**
	 * Register crontab.
	 *
	 * @since   0.2.0
	 */
	public function crontab_activate() {
		// Schedule task
		if ( ! wp_next_scheduled( 'market_exporter_daily' ) ) {
			wp_schedule_event( time(), 'five_seconds', 'market_exporter_daily' );
		}
	}

}
