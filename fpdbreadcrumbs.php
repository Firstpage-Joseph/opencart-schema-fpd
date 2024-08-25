<?php

       private function fpdBreadCrumbs() {


           $data[] = array();


           if (isset($this->request->get['route'])) {


               if (isset($this->request->get['information_id'])) {


                   $information_id = 0;
  
                   if (isset($this->request->get['information_id'])) {
                       $information_id = (int)$this->request->get['information_id'];
                   }


                   $information_info = $this->model_catalog_information->getInformation($information_id);


                   $fpdbreadcrumbs = array();
                   $fpdbreadcrumbs[] = array(
                       'text' => $this->language->get('text_home'),
                       'href' => $this->url->link('common/home')
                   );
                   $fpdbreadcrumbs[] = array(
                       'text' => $information_info['title'],
                       'href' => $this->url->link('information/information', 'information_id=' .  $information_id)
                   );
                   if (!empty($fpdbreadcrumbs)) {
                       $getpositionbreadcrumbs = [];
                       foreach($fpdbreadcrumbs as $key => $b) {
                           $getpositionbreadcrumbs[] = [
                               "@type" => "ListItem",
                               "position" => $key+1,
                               "name" => $b['text'],
                               "item" => $b['href']
                           ];
                       }
                       if (!empty($getpositionbreadcrumbs)) {
                           $breadcrumbs = array(
                               "@context" => "https://schema.org/",
                               "@type" => "BreadcrumbList",
                               "itemListElement" => $getpositionbreadcrumbs
                           );
                           $jsonBreadcrumbs = json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                           $fpd_custom_json_script = $jsonBreadcrumbs;


                           $data['fpd_custom_json_script'] = $fpd_custom_json_script;


                       }
                   }


               } elseif (isset($this->request->get['path'])) {
                  
                   $path = $this->request->get['path'];


                   $fpdbreadcrumbs = array();
                   $fpdbreadcrumbs[] = array(
                       'text' => $this->language->get('text_home'),
                       'href' => $this->url->link('common/home')
                   );


                   $this->load->model('catalog/category');


                   $category_path = array();
                   $categories = explode('_', $path);


                   foreach($categories as $category_id){
                       $category_info = $this->model_catalog_category->getCategory($category_id);
                       if($category_info){
                           $category_path[] = $category_id;
                           $fpdbreadcrumbs[] = array(
                               'text' => $category_info['name'],
                               'href' => $this->url->link('product/category', 'path=' . implode('_', $category_path))
                           );
                       }
                   }


                   if (!empty($fpdbreadcrumbs)) {


                       $getpositionbreadcrumbs = [];


                       foreach($fpdbreadcrumbs as $key => $b) {
                           $getpositionbreadcrumbs[] = [
                               "@type" => "ListItem",
                               "position" => $key+1,
                               "name" => $b['text'],
                               "item" => $b['href']
                           ];
                       }


                       if (!empty($getpositionbreadcrumbs)) {


                           if (isset($this->request->get['product_id'])) {
                              
                               $product_id = $this->request->get['product_id'];
                               $this->load->model('catalog/product');
                               $product_info = $this->model_catalog_product->getProduct($product_id);
                               $breadcrumbs = $this->productSchemaInfo($product_info);
                              
                           } else {


                               $breadcrumbs = array(
                                   "@context" => "https://schema.org/",
                                   "@type" => "BreadcrumbList",
                                   "itemListElement" => $getpositionbreadcrumbs
                               );


                           }


                           $jsonBreadcrumbs = json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                           $fpd_custom_json_script = $jsonBreadcrumbs;


                           $data['fpd_custom_json_script'] = $fpd_custom_json_script;


                       }
                   }


               } elseif (isset($this->request->get['product_id'])) {
                  
                   $product_id = $this->request->get['product_id'];
                   $this->load->model('catalog/product');
                   $product_info = $this->model_catalog_product->getProduct($product_id);
                   $breadcrumbs = $this->productSchemaInfo($product_info);
                   $jsonBreadcrumbs = json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                   $fpd_custom_json_script = $jsonBreadcrumbs;
                   $data['fpd_custom_json_script'] = $fpd_custom_json_script;


               } elseif (isset($this->request->get['news_id'])) {


                   $newid = $this->request->get['news_id'];
                   $breadcrumbs = $this->articleSchema($newid);
                   $jsonBreadcrumbs = json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                   $fpd_custom_json_script = $jsonBreadcrumbs;
                   $data['fpd_custom_json_script'] = $fpd_custom_json_script;


               }


           }
          
           return $data;
       }


