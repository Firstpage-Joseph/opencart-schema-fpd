<?php
private function productSchemaInfo($product_info) {

			$url = '';
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}
			
			$product_url = $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']);
			$stock = [];

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                if ($this->config->get('config_product_decimal_places')) {
					$dataprice = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
					$dataprice = $this->currency->format2($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                }
			} 

			if ($product_info['quantity'] <= 0) {
				$stock = [
					'@type' => 'Offer',
					'url' => $product_url,
					'priceCurrency' => 'SGD',
					'price' =>  str_replace('$', '', $dataprice),
					'itemCondition' => 'https://schema.org/NewCondition',
					'availability' => 'https://schema.org/OutofStock'
				];
			} else {
				$stock = [
					'@type' => 'Offer',
					'url' => $product_url,
					'priceCurrency' => 'SGD',
					'price' => str_replace('$', '', $dataprice),
					'itemCondition' => 'https://schema.org/NewCondition',
					'availability' => 'https://schema.org/InStock'
				];
			}
			

			$ratingcount = (int)$product_info['reviews'];
			$ratingvalue = (int)$product_info['rating'];

			$product_info_desc = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$schema = [
				'@context' => 'https://schema.org/',
				'@type' => 'Product',
				'quantity' => $product_info['quantity'],
				'name' => $product_info['name'],
				'image' => "https://www.falconfire.com.sg/image/cache/".$product_info['image'],
				'description' => $product_info_desc,
				'brand' => [
					'@type' => 'Brand',
					'name' => 'Falcon'
				],
				'offers' => $stock,
				'aggregateRating' => [
					'@type' => 'AggregateRating',
					'ratingValue' => "4.5",
					'reviewCount' => "24"
				]
			];
			if ( $product_info['sku'] ) {
				$schema['sku'] = $product_info['sku'];
			}
			if ( $product_info['mpn'] ) {
				$schema['mpn'] = $product_info['mpn'];
			}

			return $schema;

		}
