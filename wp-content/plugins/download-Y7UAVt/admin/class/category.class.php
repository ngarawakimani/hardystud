<?php

class wp_booking_calendar_category {
	private static $category_id;
	private static $categoryQry;
	
	public function setCategory($id) {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$categoryQry = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->base_prefix.$blog_prefix."booking_categories WHERE category_id = %d",$id));
		
		
		wp_booking_calendar_category::$categoryQry = $categoryQry;
		wp_booking_calendar_category::$category_id=$categoryQry[0]->category_id;
	}
	
	public function getCategoryId() {
		global $wpdb;
		global $blog_id;
		return wp_booking_calendar_category::$category_id;
	}
	
	public function getCategoryName() {
		global $wpdb;
		global $blog_id;
		return stripslashes(wp_booking_calendar_category::$categoryQry[0]->category_name);
	}
	
	public function getCategoryActive() {
		global $wpdb;
		global $blog_id;
		return wp_booking_calendar_category::$categoryQry[0]->category_active;
	}
	
	public function getCategoryOrder() {
		global $wpdb;
		global $blog_id;
		return wp_booking_calendar_category::$categoryQry[0]->category_order;
	}
	
	public function publishCategories($listIds) {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_categories SET category_active = %d WHERE category_id IN (".$listIds.")",1));
	}
	
	public function unpublishCategories($listIds) {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_categories SET category_active = %d WHERE category_id IN (".$listIds.")",0));
	}
	
	public function delCategories($listIds) {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		
		$arrIds = explode(',',$listIds);
        if(function_exists('icl_unregister_string')) {
            for ($i = 0; $i < count($arrIds); $i++) {
                icl_unregister_string('wp-booking-calendar-categories', 'category_' . $arrIds[$i]);
            }
        }
		$wpdb->query("DELETE FROM ".$wpdb->base_prefix.$blog_prefix."booking_categories WHERE category_id IN (".$listIds.")");
		$calendarsQry = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix.$blog_prefix."booking_calendars WHERE category_id IN (".$listIds.")");
		for($i=0;$i<count($calendarsQry);$i++) {
            if(function_exists('icl_unregister_string')) {
                icl_unregister_string('wp-booking-calendar-calendars', 'calendar_' . $calendarsQry[$i]->calendar_id);
            }
			$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->base_prefix.$blog_prefix."booking_calendars WHERE calendar_id = %d",$calendarsQry[$i]->calendar_id));
			/*delete holidays*/
			$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->base_prefix.$blog_prefix."booking_holidays WHERE calendar_id =%d",$calendarsQry[$i]->calendar_id));
			/*check for reservations, if any disable slots, otherwise del slots*/
			$slotsQry=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->base_prefix.$blog_prefix."booking_slots WHERE calendar_id = %d",$calendarsQry[$i]->calendar_id));
			for($j=0;$j<count($slotsQry);$j++) {
				$query = $wpdb->prepare("SELECT * FROM ".$wpdb->base_prefix.$blog_prefix."booking_reservation WHERE slot_id = %d",$slotsQry[$j]->slot_id);
				$numRes = $wpdb->query($query);
				if($numRes>0) {
					$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_slots SET slot_active = %d WHERE slot_id  = %d",0,$slotsQry[$j]->slot_id));
				} else {
					$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->base_prefix.$blog_prefix."booking_slots  WHERE slot_id = %d",$slotsQry[$j]->slot_id));
				}
			}
			
		}
		
		
	}
	
	public function addCategory($name) {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$newOrder = 0;
		/*check order of last calendar*/
		$catQuery = "SELECT category_order as max FROM ".$wpdb->base_prefix.$blog_prefix."booking_categories ORDER BY category_order DESC LIMIT 1";
		$numrows = $wpdb->query($catQuery);
		if($numrows>0) {
			$newOrder = $wpdb->get_var($catQuery)+1;
		}
		$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->base_prefix.$blog_prefix."booking_categories (category_name,category_order,category_active) VALUES(%s,%d,%d)",$name,$newOrder,0));
		$category_id=$wpdb->insert_id;
		$package = array(
		 'kind' => 'WP Booking Calendar',
		 'name' => 'categories',
		 'title' => 'WP Booking Calendar - Categories'
		 );
		
		do_action( 'wpml_register_string', $name, "category_".$category_id, $package, "Category '".$name."' title", 'LINE' );
		
		return $category_id;
	}
	
	
	public function getCategoryRecordcount() {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		return $wpdb->query("SELECT * FROM ".$wpdb->base_prefix.$blog_prefix."booking_categories");

	}
	
	public function setDefaultCategory($category_id) {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_categories SET category_order = %d, category_active = %d WHERE category_id= %d",0,1,$category_id));
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_categories SET category_order = category_order +1 WHERE category_id <> %d",$category_id));
	}
	
	public function updateCategory() {
		global $wpdb;
		global $blog_id;
		$blog_prefix=$blog_id."_";
		if($blog_id==1) {
			$blog_prefix="";
		}
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix.$blog_prefix."booking_categories SET category_name= %s WHERE category_id = %d",$_REQUEST["name"],$_REQUEST["item_id"]));
		
		$package = array(
		 'kind' => 'WP Booking Calendar',
		 'name' => 'categories',
		 'title' => 'WP Booking Calendar - Categories'
		 );
		
		do_action( 'wpml_register_string', $_REQUEST["name"], "category_".$category_id, $package, "Category '".$_REQUEST["name"]."' title", 'LINE' );
		
	}

}

?>