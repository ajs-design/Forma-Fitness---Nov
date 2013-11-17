<?php
/*---------------------------
|							|
|							|
|	  	NUTRITION CLASS		|
|							|
|							|
----------------------------*/

/*
CLASS JOBS
	@ Order and echo each year 
	@ Order and echo each months nutrition
	@ Return article of the month image, id, title and author
	@ Pick the latest 6 topics by date and return their title and an image from an article within that topic
*/

class nutrition{
	public function __construct($conn)
	{
	        $this->conn = $conn;
	         return true;
	}

	public function nutrition_years(){
		$date = '';
		foreach ($this->conn->query("SELECT r_date FROM recipes ORDER BY r_date ASC") as $row){
			$date .= date_to_year($row['r_date']) . ',';
		}
		$year = rtrim($date,',');
		$year = explode(',', $year);
		$year = array_unique($year);
		return $year;
	}
	
	public function nutrition_months($year){
		$date = '';
		foreach ($this->conn->query("SELECT r_date FROM recipes WHERE r_date BETWEEN '".$year."-01-01 00:00:01' AND '".$year."-12-31 23:59:59' ORDER BY r_date ASC") as $row){
			$date .= date_to_month($row['r_date']) . ',';
		}
		$month = rtrim($date,',');
		$month = explode(',', $month);
		$month = array_unique($month);
		return $month;
	}
	
	public function r_of_month(){
		foreach ($this->conn->query("SELECT * FROM recipes WHERE r_of_month = '1'") as $row){
			$month = date_to_month($row['r_date']);
			$year = date_to_year($row['r_date']);
			echo '	<a href="index.php?p=nutrition&filter=month&year='.$year.'&filter_desc='.$month.'&filter_result='.$row['r_title'].'&filter_result_id='.$row['r_id'].'">
						<div class="otm">
							<img src="./admin/database_images/'.$row['r_img'].'"/>
							<span><h2>Recipe of the Month</h2>
								<h2>'.$row['r_title'].'</h2>
							</span>
						</div>
					</a>';
		}	
	}
	
	public function r_of_month_link(){
		foreach ($this->conn->query("SELECT * FROM recipes WHERE r_of_month = '1'") as $row){
			$month = date_to_month($row['r_date']);
			$year = date_to_year($row['r_date']);
			echo 'index.php?p=nutrition&filter=month&year='.$year.'&filter_desc='.$month.'&filter_result='.$row['r_title'].'&filter_result_id='.$row['r_id'];
		}	
	}
	
	public function r_of_month_id(){
		foreach ($this->conn->query("SELECT r_id FROM recipes WHERE r_of_month = '1'") as $row){
			echo $row['r_id'];
		}	
	}
	
	public function nutrition_breadcrumb($get){
		$bread = '<a href="index.php">Home</a> > ';
		if(isset($get['p'])){
			$bread .= '<a href="index.php?p='.$get['p'].'">'.upper_case_word($get['p']).'</a> > ';
		}
		if(isset($get['filter'])){
			if($get['filter'] == 'month'){
				$bread .= '<a href="index.php?p=nutrition">'.$get['year'].'</a> > <a href="index.php?p='.$get['p'].'&filter='.$get['filter'].'&year='.$get['year'].'&filter_desc='.$get['filter_desc'].'">'.$get['filter_desc'].'</a> > ';
			}
			if($get['filter'] == 'topic'){
				$bread .= '<a href="index.php?p='.$get['p'].'&filter='.$get['filter'].'&filter_desc='.$get['filter_desc'].'">'.$get['filter_desc'].'</a> > ';
			}
			if(($get['filter'] == 'topic') && (isset($get['filter_result']))){
				$bread .= '<a href="'.current_page().'">'.$get['filter_result'].'</a>';
			}
			if(($get['filter'] == 'month') && (isset($get['filter_result']))){
				$bread .= '<a href="'.current_page().'">'.$get['filter_result'].'</a>';
			}
		}
		echo $bread;
	}
	
	public function display_recipe($id){
		foreach ($this->conn->query("SELECT * FROM recipes WHERE r_id = '".$id."'") as $row){
			$month = date_to_month($row['r_date']);
			$year = date_to_year($row['r_date']);
			echo '	<div class="col_3 alpha">
						<img src="./admin/database_images/'.$row['r_img'].'"/>
					</div>
						<h1>'.$row['r_title'].'</h1>		
		<hr style="margin-bottom:5px;">
		<div class="social">
			<!-- TWITTER BUTTON -->		
			<a href="https://twitter.com/share" class="twitter-share-button" data-text="'.current_page().'Latest Fitness Recipe by Forma Fitness" data-hashtags="formafitness">Tweet</a>
			<!-- FACEBOOK BUTTON -->		
			<div class="fb-like" data-href="'.current_page().'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
			<!-- GOOGLE + BUTTON -->		
			<div class="g-plusone" data-size="medium"></div>
			<!-- PINTEREST BUTTON -->
			<a class="pinterst" href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img style="width:auto !important;" src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
			<a class="pinterst" href="#" onClick=" window.print(); return false"><img style="height:18px;width:18px;" src="./images/icons/printer.png"/> Print this page</a>
			<hr style="margin-top:0px;margin-bottom:10px;">
		</div>
		<!-- RECIPE CONTENT -->
		<em>'.$row['r_ing'].'</em></br>
		<em style="color:#448ccb;">by - '.$month.' / '.$year.'</em>
		<hr style="margin-top:10px;">
		
		<!-- ARTICLE CONTENT -->
		'. $this->display_recipe_steps($id) . '
		
		<hr style="margin-bottom:5px;">
		<div class="social">
			<!-- TWITTER BUTTON -->		
			<a href="https://twitter.com/share" class="twitter-share-button" data-text="'.current_page().'Latest Fitness Recipe by Forma Fitness" data-hashtags="formafitness">Tweet</a>
			<!-- FACEBOOK BUTTON -->		
			<div class="fb-like" data-href="'.current_page().'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
			<!-- GOOGLE + BUTTON -->		
			<div class="g-plusone" data-size="medium"></div>
			<!-- PINTEREST BUTTON -->
			<a class="pinterst" href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img style="width:auto !important;" src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
			<a class="pinterst" href="#" onClick=" window.print(); return false"><img style="height:18px;width:18px;" src="./images/icons/printer.png"/> Print this page</a>
			<hr style="margin-top:0px;margin-bottom:10px;">
		</div>
		
					';
		}
	}
	
	public function display_recipe_steps($id)
	{
		$steps = '';
		
		foreach ($this->conn->query("SELECT * FROM recipe_steps WHERE r_id = '".$id."'") as $row){
			$steps .= ' <div class="col_8 alpha omega step">
							<div class="col_1 alpha">
								<h1>'.$row['r_step_num'].':</h1>
							</div>
							<div class="col_10 omega">
								<p>'.$row['r_step_desc'].'</p>
							</div>
						</div><div class="clear"></div>';
		}
		return $steps;
	}
	
	public function latest_recipes(){
		
		foreach ($this->conn->query("SELECT r_title, r_img, r_id FROM recipes ORDER BY r_date DESC LIMIT 6") as $row){
			
		
				echo '	<div class="col_4">
							<a href="index.php?p=nutrition&filter=topic&filter_desc='.$row['r_title'].'&filter_desc_id='.$row['r_id'].'">
								<img src="./admin/database_images/'.$row['r_img'].'"/>
								<span>
									<h3>'.$row['r_title'].'</h3>
									<h3>Click to view</h3>
								</span>
							</a>
						</div>';
				
			}
		
	}
	
	public function top_nutrition(){
		foreach ($this->conn->query("SELECT r_img, r_id, r_title FROM recipes ORDER BY r_date DESC LIMIT 4") as $row){
			echo '	<div class="col_4">
						<a href="index.php?p=nutrition&filter=topic&filter_result='.$row['r_title'].'&filter_result_id='.$row['r_id'].'">
							<img src="./admin/database_images/'.$row['r_img'].'"/>
							<span>
								<h3>'.$row['r_title'].'</h3>
								<h3>Click to view</h3>
							</span>
						</a>
					</div>';
		}
	}

	public function filter_results($filter, $filter_desc, $year){
		switch ($filter)
		{
		case 'month':
			$month = month_to_date($filter_desc);
			$date_from = $year.'-'.$month.'-01 00:00:00';
			$date_to = $year.'-'.$month.'-31 23:59:59';
			foreach ($this->conn->query("SELECT * FROM recipes WHERE r_date BETWEEN '".$date_from."' AND '".$date_to."' ORDER BY r_date DESC") as $row){
				$month = date_to_month($row['r_date']);
				$year = date_to_year($row['r_date']);
				
				if(empty($row['r_img'])){
					echo '<a href="index.php?p=nutrition&filter=month&filter_desc='.$month.'&year='.$year.'&filter_result='.$row['r_title'].'&filter_result_id='.$row['r_id'].'">
							<div class="search_list_item">
								<div class="col_8 alpha omega">
									<h2>'.$row['r_title'].'</h2>
									<p>'.$row['r_blurb'].'</p>
									<em>'.$row['r_author'].' - '.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';
						
				}
				if(!empty($row['r_img'])){
					echo '<a href="index.php?p=nutrition&filter=month&filter_desc='.$month.'&year='.$year.'&filter_result='.$row['r_title'].'&filter_result_id='.$row['r_id'].'">
							<div class="search_list_item">
								<div class="col_3 alpha">
									<img src="./admin/database_images/'.$row['r_img'].'"/>
								</div>
								<div class="col_5 omega">
									<h2>'.$row['r_title'].'</h2>
									<em>'.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';
				}
			}
		  break;
		case 'topic':
			foreach ($this->conn->query("SELECT * FROM recipes WHERE r_title = '".$filter_desc."' ORDER BY r_date DESC") as $row){	
				$month = date_to_month($row['r_date']);
				$year = date_to_year($row['r_date']);
				if(empty($row['r_img'])){
					echo '<a href="index.php?p=nutrition&filter=topic&filter_desc='.$filter_desc.'&filter_result='.$row['r_title'].'&filter_result_id='.$row['r_id'].'">
							<div class="search_list_item">
								<div class="col_8 alpha omega">
									<h2>'.$row['r_title'].'</h2>
									<em>'.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';	
				}
				if(!empty($row['r_img'])){
					echo '<a href="index.php?p=nutrition&filter=topic&filter_desc='.$filter_desc.'&filter_result='.$row['r_title'].'&filter_result_id='.$row['r_id'].'">
							<div class="search_list_item">
								<div class="col_3 alpha">
									<img src="./admin/database_images/'.$row['r_img'].'"/>
								</div>
								<div class="col_5 omega">
									<h2>'.$row['r_title'].'</h2>
									<em>'.$month.' '.$year.'</em>
								</div>
							</div>
						</a>';
				}
			}
		  break;
		default:
		  return false;
		}
	}
}



?>