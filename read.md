#Поставленая задача
Настойки, которые можно задавать из админпанели вордпреса могут быть общие(на все товары и категории), а так же индивидуально для каждого товара:

  1. элемент <delivery-options>
  2. элемент <outlets>
  3. элементы bid, cbid, fee
  4. элемент <currency>
  5. элемент <categories> 
  6. элемент <param> использует данные товара - Атрибуты
  7. элемент <rec> использует данные товара - Принадлежности
  8. элементы id, type (произвольный), available
  9. Название предложения: vendor, name, model
  10. элемент <picture> выводить изображение товара + галерея товара
  11. элемент <description> из краткого описания товара с учетом html
  12. элемент <cpa>
  13. элемент <sales_notes> общие + индивидуально для каждого товара
  14. элементы delivery, pickup и store общие + индивидуально для каждого товара
  15. элемент <oldprice> Цена распродажи (р.)
  
##Увеличиваем количество вводимых переменных из админ панеле

  В файле `admin/class-market-exporter-admin.php` добавить новые поля для ввода
  переменных:
  `
   
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
  `
  
Далее добавленна новый тип поля `mis_multiselect` в функцию `input_fields_cb( $args )`:

`
                
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
`

Регистрируем соответстующие переменные в функции `validate_shop_settings_array( $input )`:

`
        
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

		$output['mis_delivery_option']	= ( isset( $input['mis_delivery_option'] ) ) ? true : false;
		$output['mis_pickup']	= ( isset( $input['mis_pickup'] ) ) ? true : false;
		$output['mis_store']	= ( isset( $input['mis_store'] ) ) ? true : false;

		$output['include_param']	= $input['include_param'];
`

##Редактируем генерируемый файл

В файле `admin/class-market-exporter-wc.php` резервируем новую функцию для
получения значения атрибута товара:

`

    private function get_attr_offer($product, $attribute_product) {
        $attributes = $product->get_attributes();
        $attr_value = '';
        $attr_value .= isset($attributes['pa_'.$attribute_product]) ? wp_get_post_terms( $product->id , 'pa_'.$attribute_product, array("fields" => "all"))[0]->name : '';
        return $attr_value;
    }
`

Добавляем поля в шапку файла `yml_header( $currency )`

`

        if ( $this->settings['mis_delivery_option'] )
            $yml .= '    <delivery>true</delivery>'.PHP_EOL;
        else
            $yml .= '    <delivery>false</delivery>'.PHP_EOL;
        if ( $this->settings['mis_pickup'] )
            $yml .= '    <pickup>true</pickup>'.PHP_EOL;
        else
            $yml .= '    <pickup>true</pickup>'.PHP_EOL;
        if ( $this->settings['mis_store'] )
            $yml .= '    <store>true</store>'.PHP_EOL;
        else
            $yml .= '    <store>false</store>'.PHP_EOL;

        $yml .= '    <currencies>'.PHP_EOL;
        if ( ( $currency == 'USD' ) || ( $currency == 'EUR' ) ):
            $yml .= '      <currency id="RUR" rate="1"/>'.PHP_EOL;
            $yml .= '      <currency id="' . $currency . '" rate="СВ" />'.PHP_EOL;
        else:
            $yml .= '      <currency id="' . $currency . '" rate="1" />'.PHP_EOL;
        endif;
        
        if($this->settings['mis_ua'] != 'not_set') {
            $currency_ua = $this->settings['mis_ua'];
        }
        if($this->settings['mis_bel'] != 'not_set') {
            $currency_bel = $this->settings['mis_bel'];
        }
        if($this->settings['mis_usa'] != 'not_set') {
            $currency_usa = $this->settings['mis_usa'];
        }
        if($this->settings['mis_euro'] != 'not_set') {
            $currency_euro = $this->settings['mis_euro'];
        }
        if($this->settings['mis_tenge'] != 'not_set') {
            $currency_tenge = $this->settings['mis_tenge'];
        }
        
            if($this->settings['mis_ua'] != 'not_set'):
                $yml .= '      <currency id="UAH" rate="'.$this->settings['mis_ua'].'" />'.PHP_EOL;
            endif;
            if($this->settings['mis_bel'] != 'not_set'):
                $yml .= '      <currency id="BYN" rate="'.$this->settings['mis_bel'].'" />'.PHP_EOL;
            endif;
            if($this->settings['mis_usa'] != 'not_set'):
                $yml .= '      <currency id="USD" rate="'.$this->settings['mis_usa'].'" />'.PHP_EOL;
            endif;
            if($this->settings['mis_euro'] != 'not_set'):
                $yml .= '      <currency id="EUR" rate="'.$this->settings['mis_euro'].'" />'.PHP_EOL;
            endif;
            if($this->settings['mis_tenge'] != 'not_set'):
                $yml .= '      <currency id="KZT" rate="'.$this->settings['mis_tenge'].'" />'.PHP_EOL;
            endif;
        $yml .= '    </currencies>'.PHP_EOL;
        $yml .= '    <delivery-options>'.PHP_EOL;
        $yml .= '        <option';
            if($this->settings['count_delivery_all']):
                $yml .= ' cost="'.esc_html($this->settings['count_delivery_all']).'"';
            endif;
            if($this->settings['days_delivery_all']):
                $yml .= ' days="'.esc_html($this->settings['days_delivery_all']).'"';
            endif;
            if($this->settings['order_before_delivery_all']){
                $yml .= ' order_before="'.esc_html($this->settings['order_before_delivery_all']).'"/>'.PHP_EOL;
            }else {
                $yml .= ' />'.PHP_EOL;
            }
        $yml .= '    </delivery-options>'.PHP_EOL;
`

Добавляем поля в тело файла `yml_offers( $currency, $query )`:

`

                $bid = $this->get_attr_offer($offer, $this->settings['mis_bid']);
                $cbid = $this->get_attr_offer($offer, $this->settings['mis_cbid']);
                $fee = $this->get_attr_offer($offer, $this->settings['mis_fee']);
                $yml .= '      <offer id="' . $offerID . '" type="vendor.model"';

                    if($bid):
                    $yml .= ' bid="'.wp_strip_all_tags( $bid ).'"';
                    endif;
                    if($cbid):
                        $yml .= ' cbid="'.wp_strip_all_tags( $cbid ).'"';
                    endif;
                    if($fee):
                        $yml .= ' fee="'.wp_strip_all_tags( $fee ).'"';
                    endif;
                    $yml .= ' available="'.( $offer->stock != "outofstock" ? "true" : "false" ).'">'.PHP_EOL;
                
                // Link.
                $yml .= '        <url>' . get_permalink( $offer->id ) . $var_link . '</url>'.PHP_EOL;
                // Price.
                if ( $offer->sale_price && ( $offer->sale_price < $offer->regular_price ) ):
                    $yml .= '        <price>' . $offer->sale_price . '</price>'.PHP_EOL;
                    $yml .= '        <oldprice>' . $offer->regular_price . '</oldprice>'.PHP_EOL;
                else:
                    $yml .= '        <price>' . $offer->regular_price . '</price>'.PHP_EOL;
                endif;
				if($cur_id = $this->get_attr_offer($offer, $this->settings['curensy_delivery_product'])){
					$yml .= '        <currencyId>'.$cur_id.'</currencyId>'.PHP_EOL;
				}
				else{
				    $yml .= '        <currencyId>'.$currency.'</currencyId>'.PHP_EOL;
                }
`

`
                
                $image = get_the_post_thumbnail_url( null, 'full' );
                //foreach ( $images as $image ):
                    if ( strlen( utf8_decode( $image ) ) <= 512 )
                        $yml .= '        <picture>' . esc_url( $image ) . '</picture>'.PHP_EOL;
                //endforeach;
					$mis_picture_gallery = $offer->get_gallery_attachment_ids();
					foreach ($mis_picture_gallery as $picture_id):
						$picture_url = wp_get_attachment_url($picture_id);
						if ( strlen( utf8_decode( $picture_url ) ) <= 512 )
							$yml .= '        <picture>' . esc_url( $picture_url ) . '</picture>'.PHP_EOL;
					endforeach;
                
                // Доставка товара
                $cost = $this->get_attr_offer($offer, $this->settings['count_delivery_product']);
                $days = $this->get_attr_offer($offer, $this->settings['days_delivery_product']);
                $order_before = $this->get_attr_offer($offer, $this->settings['order_before_delivery_product']);
                if ($cost || $days || $order_before):
                $yml .= '        <delivery-options>'.PHP_EOL;
                $yml .= '            <option';
                    if($cost):
                        $yml .= ' cost="'.wp_strip_all_tags( $cost ).'"';
                    endif;
                    if($days):
                        $yml .= ' days="'.wp_strip_all_tags( $days ).'"';
                    endif;
                    if($order_before){
                        $yml .= ' order_before="'.wp_strip_all_tags( $order_before ).'"/>'.PHP_EOL;
                    }else {
                        $yml .= ' />'.PHP_EOL;
                    }
                $yml .= '        </delivery-options>'.PHP_EOL;
                endif;
                
                
                $yml .= '        <name>' . $this->clean( $offer->get_title() ) . '</name>'.PHP_EOL;

                // Vendor.
                $vendor = $this->get_attr_offer($offer, $this->settings['vendor']);
                    if ( $vendor )
                        $yml .= '        <vendor>' . wp_strip_all_tags( $vendor ) . '</vendor>'.PHP_EOL;
                // Delivery.
                $delivery = $this->get_attr_offer($offer, $this->settings['mis_delivery_product']);
                    if ( $delivery )
                        $yml .= '        <delivery>' . wp_strip_all_tags( $delivery ) . '</delivery>'.PHP_EOL;
                // Pickup.
                $pickup = $this->get_attr_offer($offer, $this->settings['mis_pickup_product']);
                    if ( $pickup )
                        $yml .= '        <pickup>' . wp_strip_all_tags( $pickup ) . '</pickup>'.PHP_EOL;
                // Store.
                $store = $this->get_attr_offer($offer, $this->settings['mis_store_product']);
                    if ( $store )
                        $yml .= '        <store>' . wp_strip_all_tags( $store ) . '</store>'.PHP_EOL;
                // Model.
                $model = $this->get_attr_offer($offer, $this->settings['model']);
                    if ( $model )
                        $yml .= '        <model>' . wp_strip_all_tags( $model ) . '</model>'.PHP_EOL;
`


    `
                $cpa = $this->get_attr_offer($offer, $this->settings['mis_cpa']);
                if ( $cpa )
                    $yml .= '        <cpa>'. wp_strip_all_tags( $cpa ) .'</cpa>'.PHP_EOL;
                
                global $woocommerce_loop;
				if($mis_recomend = Electro_WC_Helper::get_accessories( $offer )):
                $yml .= '        <rec>'. implode(',', $mis_recomend) .'</rec>'.PHP_EOL;
				endif;
                //Param
                $mis_param_array = $this->settings['include_param'];
                if($mis_param_array):
                foreach($mis_param_array as $param):
                    $param_arr = explode( " | ", $param);
                    $attributes = $offer->get_attributes();
                    $attr_value = wp_get_post_terms( $product->id , 'pa_'.$param_arr[0], array("fields" => "all"));
                    if( $attr_value[0]->slug )
                        $yml .= '        <param name="'.$param_arr[1].'">'. $attr_value[0]->name .'</param>'.PHP_EOL;
                endforeach;
                endif;
    `