<?php

private function articleSchema($newid) {

			$this->language->load('news/article');
			$this->load->model('catalog/news');
			$this->load->model('catalog/ncategory');	

			$news_info = $this->model_catalog_news->getNewsStory($newid);
			$art_url = $this->url->link('news/article', 'news_id=' . $newid);

			$newsdesc = html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8');
			$date_format = $this->config->get('ncategory_bnews_date_format') ? $this->config->get('ncategory_bnews_date_format') : 'd.m.Y';
			$date_added = date($date_format, strtotime($news_info['date_added']));
			$featured_image = 'https://www.falconfire.com.sg/image/'.$news_info['image2'];

			$schema = array(
				"@context" => "https://schema.org",
				"@type" => "BlogPosting",
				"mainEntityOfPage" => array(
					"@type" => "WebPage",
					"@id" => $art_url 
				),
				"headline" => $news_info['title'],
				"description" => $newsdesc,
				"image" => $featured_image,
				"author" => array(
					"@type" => "Organization",
					"name" => "Falcon"
				),
				"publisher" => array(
					"@type" => "Organization",
					"name" => "Falcon",
					"logo" => array(
						"@type" => "ImageObject",
						"url" => "https://www.falconfire.com.sg/image/catalog/homepage/logo_falcon.png"
					)
				),
				"datePublished" => $date_added
			);
			return $schema;
		}
