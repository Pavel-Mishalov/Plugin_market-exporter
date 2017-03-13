<?php
/**
 * Class ME_WC
 *
 * A class that utilizes WooCommerce builtin functions to generate the YML instead of querying the database.
 *
 * @since     0.3.0
 */

class ME_WC {

    /**
     * Constructor method.
     *
     * @since     0.3.0
     */
    public function __construct() {
        // Get plugin settings.
        $this->settings = get_option( 'market_exporter_shop_settings' );
        $this->settings_api = get_option( 'market_exporter_api' );

        // Init default values if not set in config.
        if ( ! isset( $this->settings['file_date'] ) )
            $this->settings['file_date'] = 'yes';

        if ( ! isset( $this->settings['image_count'] ) )
            $this->settings['image_count'] = 10;
    }
    
    /*
     *Функция получает объект товара и строку атрибута, возвращает значение атрибута
     */
    
    private function get_attr_offer($product, $attribute_product) {
        $attributes = $product->get_attributes();
        $attr_value = '';
        $attr_value .= isset($attributes['pa_'.$attribute_product]) ? wp_get_post_terms( $product->id , 'pa_'.$attribute_product, array("fields" => "all"))[0]->name : '';
        return $attr_value;
    }

    /**
     * Generate YML file.
     *
     * Available error codes:
     *      false - everything is ok
     *      100   - wrong currency
     *      200   - no shipping method available
     *      300   - no available products
     *
     * @since     0.3.0
     * @return    int|string
     */
    public function generate_YML() {
        // Check currency.
        if ( ! $currency = $this->check_currecny() )
            return 100;

        // Get products.
        if ( ! $query = $this->check_products() )
            return 300;

        // Generate XML data.
        $yml  = '';
        $yml .= $this->yml_header( $currency );
        $yml .= $this->yml_offers( $currency, $query );
        $yml .= $this->yml_footer();
        $yml  = str_replace("	", "   ", $yml) ;

        // Create file.
        $market_exporter_fs = new Market_Exporter_FS( 'market-exporter' );
        $file_path = $market_exporter_fs->write_file( $yml, $this->settings['file_date'] );
        return $file_path;
    }

    /**
     * Check currency.
     *
     * Checks if the selected currency in WooCommerce is supported by Yandex Market.
     * As of today it is allowed to list products in six currencies: RUB, UAH, BYR, KZT, USD and EUR.
     * But! WooCommerce doesn't support BYR and KZT. And USD and EUR can be used only to export products.
     * They will still be listed in RUB or UAH.
     *
     * @since     0.3.0
     * @return    string      Returns currency if it is supported, else false.
     */
    private function check_currecny() {

        $currency = 'RUB';

        switch ( $currency ) {
            case 'RUB':
                return 'RUR';
            case 'UAH':
            case 'USD';
            case 'EUR':
                return $currency;
            default:
                return false;
        }
    }

    /**
     * Check if any products ara available for export.
     *
     * @since     0.3.0
     * @return    bool|WP_Query     Return products.
     */
    private function check_products() {

        $args = array(
            'posts_per_page' => -1,
            //'post_type' => array('product', 'product_variation'),
            'post_type'     => array('product'),
            'post_status'   => 'publish',
            'meta_query'    => array(
                array(
                    'key'   => '_price',
                    'value' => 0,
                    'compare' => '>',
                    'type'  => 'NUMERIC'
                ),
                array(
                    'key'   => '_stock_status',
                    //'value' => 'instock'
                )
            ),
            'orderby'   => 'ID',
            'order'     => 'DESC'
        );

        // If in options some specific categories are defined for export only.
        if ( isset( $this->settings[ 'include_cat' ] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy'  => 'product_cat',
                    'field'     => 'term_id',
                    'terms'     => $this->settings[ 'include_cat' ]
                ]
            ];
        }

        $query = new WP_Query( $args );

        if ( $query->found_posts != 0 )
            return $query;

        return false;
    }

    /**
     * Replace characters that are not allowed in the YML file.
     *
     * @since     0.3.0
     * @param     $string
     * @return    mixed
     */
    private function clean( $string ) {
        $string = str_replace( '"', '&quot;', $string);
        $string = str_replace( '&', '&amp;', $string);
        $string = str_replace( '>', '&gt;', $string);
        $string = str_replace( '<', '&lt;', $string);
        $string = str_replace( '\'', '&apos;', $string);
        $string = str_replace( '&nbsp;', '', $string );
        $string = str_replace( ':', '&#58;', $string );
        return $string;
    }

    /**
     * Generate YML header.
     *
     * @since     0.3.0
     * @param     $currency
     *
     * @return    string
     */
    private function yml_header( $currency ) {

        $yml  = '<?xml version="1.0" encoding="' . get_bloginfo( "charset" ) . '"?>'.PHP_EOL;
        $yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.PHP_EOL;
        $yml .= '<yml_catalog date="' . date( "Y-m-d H:i" ) . '">'.PHP_EOL;
        $yml .= '  <shop>'.PHP_EOL;
        $yml .= '    <name>' . esc_html( $this->settings['website_name'] ) . '</name>'.PHP_EOL;
        $yml .= '    <company>' . esc_html( $this->settings['company_name'] ) . '</company>'.PHP_EOL;
        $yml .= '    <url>' . get_site_url() . '</url>'.PHP_EOL;
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
            if( is_numeric ($this->settings['count_delivery_all'] ) ):
                $yml .= ' cost="'.esc_html($this->settings['count_delivery_all']).'"';
            endif;
            if( is_numeric ($this->settings['days_delivery_all'] ) ):
                $yml .= ' days="'.esc_html($this->settings['days_delivery_all']).'"';
            endif;
            if( is_numeric ( $this->settings['order_before_delivery_all'] ) ){
                $yml .= ' order-before="'.esc_html($this->settings['order_before_delivery_all']).'"/>'.PHP_EOL;
            }else {
                $yml .= ' />'.PHP_EOL;
            }
        $yml .= '    </delivery-options>'.PHP_EOL;

        $yml .= '    <categories>'.PHP_EOL;
        
		foreach ( get_categories( array( 'taxonomy' => 'product_cat', 'orderby' => 'term_id' ) ) as $category ):
            if ( $category->parent == 0 ) {
                $yml .= '      <category id="' . $category->cat_ID . '">' . wp_strip_all_tags( $category->name ) . '</category>'.PHP_EOL;
            }
        endforeach;
		foreach ( get_categories( array( 'taxonomy' => 'product_cat', 'orderby' => 'term_id' ) ) as $category ):
            if ( $category->parent == 0 ) {
            } else {
               	 $yml .= '      <category id="' . $category->cat_ID . '" parentId="' . $category->parent . '">' . wp_strip_all_tags( $category->name) . '</category>'.PHP_EOL;
            }

		
        endforeach;
        $yml .= '    </categories>'.PHP_EOL;

        $yml .= '    <offers>'.PHP_EOL;

        return $yml;
    }

    /**
     * Generate YML body with offers.
     *
     * @since     0.3.0
     * @param     $currency
     * @param     $query
     *
     * @return    string
     */
    private function yml_offers( $currency, $query ) {

        $yml = '';

        while ( $query->have_posts() ):

            $query->the_post();

            $product = wc_get_product( $query->post->ID );
            // We use a seperate variable for offer because we will be rewriting it for variable products.
            $offer = $product;

            /*
             * By default we set $variation_count to 1.
             * That means that there is at least one product available.
             * Variation products will have more than 1 count.
             */
            $variation_count = 1;
            if ( $product->is_type( 'variable' ) ):
                $variation_count = count( $offer->get_children() );
                $variations = $product->get_available_variations();
            endif;

            while ( $variation_count > 0 ):
                $variation_count--;

                // If variable product, get product id from $variations array.
                $offerID = ( ( $product->is_type( 'variable' ) ) ? $variations[ $variation_count ][ 'variation_id' ] : $product->id );

                // Prepare variation link.
                $var_link = '';
                if ( $product->is_type( 'variable' ) ):
                    $variable_attribute = wc_get_product_variation_attributes( $offerID );
                    $var_link = '?' . key( $variable_attribute ) . '=' . current( $variable_attribute );

                    // This has to work but we need to think of a way to save the initial offer variable.
                    $offer = new WC_Product_Variation( $offerID );
                endif;
		
		$display_yml = $this->get_attr_offer($offer, $this->settings['mis_yml_product_display']);
		if($display_yml !== 'false'):
                // NOTE: Below this point we start using $offer instead of $product.
                $bid = $this->get_attr_offer($offer, $this->settings['mis_bid']);
                $cbid = $this->get_attr_offer($offer, $this->settings['mis_cbid']);
                $fee = $this->get_attr_offer($offer, $this->settings['mis_fee']);
                $yml .= '      <offer id="' . $offerID . '" type="vendor.model"';

                    if($bid && is_numeric($bid)):
                    $yml .= ' bid="'.wp_strip_all_tags( $bid ).'"';
                    endif;
                    if($cbid && is_numeric($cbid)):
                        $yml .= ' cbid="'.wp_strip_all_tags( $cbid ).'"';
                    endif;
                    if($fee && is_numeric($fee) ):
                        $yml .= ' fee="'.wp_strip_all_tags( $fee ).'"';
                    endif;
			$available_status = ($offer->stock_status == "instock" && !$offer->managing_stock() ) ? "true" : "false";
                    $yml .= ' available="'. $available_status . '">'.PHP_EOL;

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
                // Category.
                // Not using $offerID, because variable products inherit category from parent.
                $categories = get_the_terms( $product->id, 'product_cat' );
                $category = array_pop( $categories );
                $yml .= '        <categoryId>' . $category->term_id . '</categoryId>'.PHP_EOL;

                // Market category.
                if ( isset( $this->settings['market_category'] ) && $this->settings['market_category'] != 'not_set' ):
                    $market_category = wc_get_product_terms( $offerID, 'pa_'.$this->settings['market_category'], array( 'fields' => 'names' ) );
                    if ( $market_category )
                        $yml .= '        <market_category>' . wp_strip_all_tags( array_shift( $market_category ) ) . '</market_category>'.PHP_EOL;
                endif;

                // TODO: get all the images
                $image = get_the_post_thumbnail_url( null, 'full' );
                //foreach ( $images as $image ):
                    if ( strlen( utf8_decode( $image ) ) <= 512 )
                        $yml .= '        <picture>' . esc_url( $image ) . '</picture>'.PHP_EOL;
                //endforeach;
					$mis_picture_gallery = $offer->get_gallery_attachment_ids();
					$image_count = $this->settings['image_count'];
					foreach ($mis_picture_gallery as $picture_id):
					   if($image_count > 1):
						$picture_url = wp_get_attachment_url($picture_id);
						if ( strlen( utf8_decode( $picture_url ) ) <= 512 )
							$yml .= '        <picture>' . esc_url( $picture_url ) . '</picture>'.PHP_EOL;
					   endif;
					   $image_count--;
					endforeach;
		// Delivery.
                $delivery = $this->get_attr_offer($offer, $this->settings['mis_delivery_product']);
                    if ( $delivery )
                        $yml .= '        <delivery>' . wp_strip_all_tags( $delivery ) . '</delivery>'.PHP_EOL;
                
                // Доставка товара
                $cost = $this->get_attr_offer($offer, $this->settings['count_delivery_product']);
                $days = $this->get_attr_offer($offer, $this->settings['days_delivery_product']);
                $order_before = $this->get_attr_offer($offer, $this->settings['order_before_delivery_product']);
                if ($cost && $delivery !== 'false' || $days && $delivery !== 'false' || $order_before && $delivery !== 'false') {
                $yml .= '        <delivery-options>'.PHP_EOL;
                $yml .= '            <option';
                    if(is_numeric ($cost))
			$yml .= ' cost="'.wp_strip_all_tags( $cost ).'"';
                    if(is_numeric ($days))
			$yml .= ' days="'.wp_strip_all_tags( $days ).'"';
                    if(is_numeric ($order_before)){
                        $yml .= ' order-before="'.wp_strip_all_tags( $order_before ).'"/>'.PHP_EOL;
                    }else {
                        $yml .= ' />'.PHP_EOL;
                    }
                $yml .= '        </delivery-options>'.PHP_EOL;
                }elseif($delivery !== 'false'){
      			  $yml .= '    <delivery-options>'.PHP_EOL;
     			   $yml .= '        <option';
     		       if( is_numeric ($this->settings['count_delivery_all'] ) ):
        		        $yml .= ' cost="'.esc_html($this->settings['count_delivery_all']).'"';
       		     endif;
       		     if( is_numeric ($this->settings['days_delivery_all'] ) ):
        		        $yml .= ' days="'.esc_html($this->settings['days_delivery_all']).'"';
		     endif;
       		     if( is_numeric ( $this->settings['order_before_delivery_all'] ) ){
        		        $yml .= ' order-before="'.esc_html($this->settings['order_before_delivery_all']).'"/>'.PHP_EOL;
        	    }else {
        	        $yml .= ' />'.PHP_EOL;
            		}
        		$yml .= '    </delivery-options>'.PHP_EOL;
		}
                
                
                //$yml .= '        <name>' . str_replace( '&', '&amp;', $this->clean( $offer->get_title() ) ) . '</name>'.PHP_EOL;

                // Vendor.
                $vendor = $this->get_attr_offer($offer, $this->settings['vendor']);
                    if ( $vendor ){
			$yml .= '        <vendor>' . wp_strip_all_tags( $vendor ) . '</vendor>'.PHP_EOL;
		    }
		    else {
                        $yml .= '        <vendor>' . str_replace( '&', '&amp;', $this->clean( $offer->get_title() ) ) . '</vendor>'.PHP_EOL;
		    }
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
                    if ( $model ){
                        $yml .= '        <model>' . wp_strip_all_tags( $model ) . '</model>'.PHP_EOL;
		    }
		    else{
			$yml .= '        <model>' . str_replace( '&', '&amp;', $this->clean( $offer->get_title() ) ) . '</model>'.PHP_EOL;
		    }
		//Outlets
                $shop_id = $this->get_attr_offer($offer, $this->settings['mis_shop_id']);
		if( is_numeric($shop_id) ){
		$yml .= '        <outlets>'.PHP_EOL;
		$yml .= '           <outlet id="'. $shop_id .'" instock="1" />'.PHP_EOL;
		$yml .= '        </outlets>'.PHP_EOL;
		}else{
		$yml .= '        <outlets>'.PHP_EOL;
		$yml .= '           <outlet id="1" instock="1" />'.PHP_EOL;
		$yml .= '        </outlets>'.PHP_EOL;
		}

                // Vendor code.
                if ( $offer->sku )
                    $yml .= '        <vendorCode>' . $offer->sku . '</vendorCode>'.PHP_EOL;
				
				//WPBMap::addAllMappedShortcodes(); // This does all the work
                //$yml .= '        <description><![CDATA[' . html_entity_decode( apply_filters( 'the_content', $offer->post->post_content ), ENT_COMPAT, "UTF-8" ) . ']]></description>'.PHP_EOL;

                if ( $offer->post->post_excerpt )
                    $yml .= '        <description> <![CDATA[' . html_entity_decode( str_replace(': ', ':', $offer->post->post_excerpt), ENT_COMPAT, "UTF-8" ) . ']]> </description>'.PHP_EOL;
				
                // Sales notes.
		$sales_notes = $this->get_attr_offer($offer, $this->settings['mis_sales_notes']);
                    if ( $sales_notes )
                    $yml .= '        <sales_notes>' . wp_strip_all_tags( $sales_notes ) . '</sales_notes>'.PHP_EOL;
                
                $cpa = $this->get_attr_offer($offer, $this->settings['mis_cpa']);
                if ( is_numeric ( $cpa ) )
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
                    if( $attr_value[0]->slug ):
                        $yml .= '        <param name="'.$param_arr[1].'">';
			foreach( $attr_value as $key=>$values ):
				if($key < 1){
					$yml .= $values->name;
				}else{
					$yml .= ' | ' . $values->name;
				}					
			endforeach;
		   	$yml .= '</param>' . PHP_EOL;
		    endif;
                endforeach;
                endif;

                $yml .= '      </offer>'.PHP_EOL;
	    endif;
            endwhile;
	
        endwhile;

        return $yml;
    }

    /**
     * Generate YML footer.
     *
     * @since     0.3.0
     * @return    string
     */
    private function yml_footer() {

        $yml  = '    </offers>'.PHP_EOL;
        $yml .= '  </shop>'.PHP_EOL;
        $yml .= '</yml_catalog>'.PHP_EOL;

        return $yml;
    }
}
