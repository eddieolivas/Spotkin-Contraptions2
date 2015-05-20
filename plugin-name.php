<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://contraptionmaker.com
 * @since             1.0.0
 * @package           spotkin_contraptions
 *
 * @wordpress-plugin
 * Plugin Name:       Spotkin Contraptions
 * Plugin URI:        http://contraptionmaker.com/
 * Description:       This plugin allows use of the contraptions shortcode which allows you to list contraptions, puzzles, and mods in the Contraption Maker database.
 * Version:           1.0.0
 * Author:            Eddie Olivas
 * Author URI:        http://chrysaliswebdevelopment.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spotkin-contraptions
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
 
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();
	
	//[contraption]
		function contraption_func( $atts ){
			$num = rand ( 0 , 100 );
			
			$a = shortcode_atts( array(
				'type' => 'contraption',
				'curation' => 'none',
				'size' => 'small',
				'name' => '',
				'user' => 'any',
				'sort_by' => 'date',
				'date_range' => '',
				'paging' => true,
				'limit' => '50',
				'page' => 1,
				'id' => $num,
			), $atts );
			
			$curl = curl_init();
			
			//Setup variables based on shortcode arguments.
    	$type = $a['type'];
    	
    	$user = $a['user'];
    	
    	$size = $a['size'];
    	
    	$sort_by = $a['sort_by'];
    	
    	$limit = $a['limit'];
    	
    	//Make sure no one sets the limit above 1000
    	if ($limit > 1000) { $limit = 1000; }
    	
    	$curation = $a['curation'];
    	
    	$date_range = $a['date_range'];
    	
    	$name = $a['name'];
    	
    	$paging = $a['paging'];
    	
    	$page = $a['page'];
    	
    	
    	//Setup the query array and add items to it based on shortcode arguments.
    	$query = [];
    	
    	if ($type != 'all') {
    	  $query['itemType'] = $type;
    	}
        
      //Handle curation type
      if ($curation == 'editorschoice') {
        $query['editorschoice'] = true;
      }
  	  else if ($curation == 'recent') {
  			
  			$thirtyDaysAgo = strtotime('-30 days') * 1000;
  			
  			$query['creationDate']['$gt'] = $thirtyDaysAgo;
  			
  		}
  		else if ($curation == 'specific') {
  			
  			if ($name != '') {
  			  $query['name'] = $name;
  			}
  			else {
  				echo 'A name needs to be added to the Contraptions shortcode when using "specific" as the curation type';
  			}
  			
  		}
  		
  		//Handle user
  		if ($user != 'any') {
  			$query['ownerName'] = $user;
  		}
  		
  		//Handle start/end dates
  		if ($date_range != '' && $page !== 1) {
  		  
  		  echo "\$date_range = ".$date_range;
  		  
  		  $date_range = explode(',', $a['date_range']);
  		  
  		  $start_date = strtotime($date_range[0]) * 1000;
    	
    	  $end_date = strtotime($date_range[1]) * 1000;
  			
  			$query['creationDate']['$gt'] = $start_date;
  			
  			$query['creationDate']['$lt'] = $end_date;
  			
  		}
  		
  		//Handle sorting
  		if ($sort_by == 'date') {
  		  $sort['creationDate'] = -1;
  		  //$sort['direction'] = 1;
  		  
  		}
  		else if ($sort_by == 'rating') {
  		  $sort['rating'] = -1;
  		}
  		else if ($sort_by == 'user') {
  		  $sort['ownernName'] = -1;
  		}
  		else if ($sort_by == 'name') {
  		  $sort['name'] = -1;
  		}
  		
  		//Json encode the query array
  		$query = json_encode($query);
  		$sort = json_encode($sort);
						    
      //URL encode the json query						    
			$query = urlencode($query);
			$sort = urlencode($sort);
			
			//Set the http request url	
			if($page != 1 ) {
			  
			  $skip = $limit * $page - $limit;
			  //echo "\$skip = ".$skip."<br>";
			  $url = 'http://54.214.110.58:8080/workshop/items?query='.$query.'&sort='.$sort.'&limit='.$limit.'&skip='.$skip;
			  $page++;
			}
			else {
			  $url = 'http://54.214.110.58:8080/workshop/items?query='.$query.'&sort='.$sort.'&limit='.$limit; 
			}
			
						    
			//echo 'Request URL: '.$url.'<br><br>';
						 
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => 1
			));
			
			//Run the query and store results			    
			$result = curl_exec($curl);
				
			//Turn json encoded response into PHP array.			    
			$result = json_decode($result);
						    
						    
			if(!curl_exec($curl)){
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
      }
      else {
          if($page == 1) {
            echo "<ul class='cmworkshops ".$size."'>";
          }
          
          for($i = 0; $i < count($result); $i++) {
            $item = $result[$i];
            $name = $item->name;
            $name = $item->name = (strlen($item->name) > 20) ? substr($item->name,0,10).'...' : $item->name;
            $rating = $item->rating;
            $ownerName = $item->ownerName;
            $type = $item->itemType;
            $date = gmdate('m-d-Y', $item->creationDate/1000);
            
            if ($size == "small") {$imageUrl = $item->imageUrl."=s163";}
            else if ($size == "medium") {$imageUrl = $item->imageUrl."=s278";}
            else if ($size == "large") {$imageUrl = $item->imageUrl;}
            
            echo '<li class=blog-thumbs-view-entry><div class=blog-thumb><img width=185 height=117 src='.$imageUrl.' class=attachment-my-thumbnail wp-post-image alt=collage-resized /></a></div><div class=group-box-bottom><div class=blog-thumb-title>'.$name.'</div>'.$ownerName.'<br> Rating: '.$rating.'<div class=group-box-details><ul class=post-categories><li><a href=# rel=category tag>'.$type.'</a></li></ul> <br>'.$date.'</div></div></li>';
          }
          
          if($page == 1) {
            echo "</ul><br>"; 
            $page++;
          }        
          
          
          if ($paging != 0) {
            echo "</ul><br>";
            echo "<div class='more_button'><button class='more_contraptions' name='more' data-page='".$page."' data-type='".$type."' data-curation='".$curation."' data-size='".$size."' data-user='".$user."' data-sort_by='".$sort_by."' data-date_range='".$date_range."' data-limit='".$limit."'>More</button></div>";  
          }
		  
		  else {
		  	echo "paging == 0";
		  }
          
      }

      //Close the connection.						    
			curl_close($curl);
		}
		add_shortcode( 'contraption', 'contraption_func' );
		
		add_action( 'wp_ajax_nopriv_get_next_page', 'get_next_page');
		//add_action( 'wp_ajax_get_next_page', 'get_next_page');	
				
				
		function get_next_page($page_number, $type, $curation, $size, $user, $sort_by, $date_range, $limit) {
			echo do_shortcode("[contraption page='".$page_number."' type='".$type."' curation='".$curation."' size='".$size."' user='".$user."' sort_by='".$sort_by."' date_range='".$date_range."' limit='".$limit."']");
			//echo "test";
			
			
		}
		
		if (isset($_POST['contraption_page'])) {
				get_next_page($_POST['contraption_page'], $_POST['type'], $_POST['curation'], $_POST['size'], $_POST['user'], $_POST['sort_by'], $_POST['date_range'], $_POST['limit']);
				
			}	

}
run_plugin_name();
