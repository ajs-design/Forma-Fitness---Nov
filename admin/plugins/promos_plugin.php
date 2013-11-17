<?php
/*--------------------------------

		TOPICS PHP SECTION
		
---------------------------------*/
class promo{
	 
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    } 
	public function insert_promo($promo_title, $promo_id, $promo_name, $promo_blurb, $promo_text, $promo_expiry){
		$sql = "INSERT INTO promos (promo_title, promo_id, promo_name, promo_blurb, promo_text, promo_expiry) VALUES (:promo_title, :promo_id, :promo_name, :promo_blurb, :promo_text, :promo_expiry)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":promo_title", $promo_title);
			$query->bindParam(":promo_id", $promo_id);
			$query->bindParam(":promo_name", $promo_name);
			$query->bindParam(":promo_blurb", $promo_blurb);
			$query->bindParam(":promo_text", $promo_text);
			$query->bindParam(":promo_text", $promo_expiry);
			$query->execute();
	}
	
		
	public function display_topics(){
		foreach ($this->conn->query("SELECT * FROM topics ORDER BY t_date DESC") as $row){
		echo '<div class="item_row">
				<div class="desc">
					<h2>'.$row['t_title'].'</h2>
				</div>
				<div class="controls">
					<button type="button" class="x-button t_remove">Delete</button>
					<a href="manage.php?action=edit&type=topic&id='.$row['t_id'].'"><button class="s-button" type="button">Edit</button></a>
					<div class="t_remove_div">
						<h3>Are you sure you want to delete this item?</h3></br>
						<button type="button" class="s-button t_remove_cnl">No</button>
						<a href="manage.php?action=delete&type=topic&id='.$row['t_id'].'"><button class="x-button" type="button">Yes</button></a>
					</div>
				</div>';
			
		echo '</div>';
		}
	}
	
}

//Instance objects
$promo = new promo($conn);

//Success variable
$promo_success = '';
//Error variables
$promo_title_error = '';
$promo_blurb_error = '';
$promo_text_error = '';
$promo_expiry_error = '';
$promo_image_error = '';

//Instance objects
$promo = new promo($conn);

if (isset ($_POST['promo_submit'])){
	//Check to see if topic is entered
	if (empty($_POST['promo_title'])){
		$promo_title_error = '<em class="error">A Promotion Title is required!</em>';
	}	
	
	if (empty($_POST['promo_blurb'])){
		$promo_blurb_error = '<em class="error">A Promotion Blurb is required!</em>';
	}
	
	if (empty($_POST['promo_text'])){
		$promo_text_error = '<em class="error">Promotion Text is required!</em>';
	}
	
	if (empty($_POST['promo_expiry'])){
		$promo_expiry_error = '<em class="error">Promotion Expiry Date is required!</em>';
	}
			else{
				
					$promo->insert_promo($_POST['promo_title'], $_POST['promo_id'], $_POST['promo_name'], $_POST['promo_blurb'], $_POST['promo_text'], 											$_POST['promo_expiry']);
				
				$promo_success = '<h2 class="success">Promotion Created!</h2>';
			}	
}
/*----------END OF TOPICS PHP SECTION----------*/


/*--------------------------------

		TOPICS HTML SECTION
		
---------------------------------*/

$promo_html = $promo_success;
$promo_html .= <<<EOD
		<form action="index.php?return=promo" method="post">
			<label for="promo_title">Promotion Title:</label>
			<input name="promo_title" placeholder="Promotion Title" type="text" />
			</br>
EOD;
$promo_html .= $promo_title_error;

$promo_html .= <<<EOD
		
			<label for="promo_blurb">Promotion Blurb:</label>
			<textarea class="wys" name="promo_blurb"></textarea>
			<br>
EOD;
$promo_html .= $promo_blurb_error;

$promo_html .= <<<EOD
		
			<label for="promo_text">Promotion Text:</label>
			<textarea class="wys" name="promo_text"></textarea>
			<br>
EOD;
$promo_html .= $promo_text_error;

$promo_html .= <<<EOD
		
			<label for="promo_expiry">Promo Expiry Date:</label>
			<p style="display:inline;"><input name="promo_expiry" type="text" class="datepicker" /></p>
			<br>
EOD;
$promo_html .= $promo_expiry_error;

$promo_html .= <<<EOD
<br>
			<input class="s-button" type="submit" value="Submit" name="t_submit" />
		</form>
EOD;



/*----------END OF TOPICS HTML SECTION----------*/
?>