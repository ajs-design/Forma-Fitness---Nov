<?php
/*--------------------------------

		CLIENT MEASUREMENT PHP SECTION
		
---------------------------------*/
class measurement{
	 
	public function __construct($conn)
    {
        $this->conn = $conn;
         return true;
    }

	public function insert_new_measurement($c_id, $weight, $height, $chest, $waist, $hips, $thighs, $biceps){
		$sql = "INSERT INTO client_measurements (c_id, c_weight, c_height, c_chest, c_waist, c_hips, c_thighs, c_biceps) VALUES (:c_id, :c_weight, :c_height, :c_chest, :c_waist, :c_hips, :c_thighs, :c_biceps)";
		$query = $this->conn->prepare($sql);
			$query->bindParam(":c_id", $c_id);
			$query->bindParam(":c_weight", $weight);
			$query->bindParam(":c_height", $height);
			$query->bindParam(":c_chest", $chest);
			$query->bindParam(":c_waist", $waist);
			$query->bindParam(":c_hips", $hips);
			$query->bindParam(":c_thighs", $thighs);
			$query->bindParam(":c_biceps", $biceps);
			
		$query->execute();
	}
}

//Instance object
$measurement = new measurement($conn);

//UPDATE CLIENT CHOICE FORM DROP DOWN
$client_measure_choice = '	<label for="c_choose">Choose Client:</label>
							<select name="cm_choose" class="cm_select" ONCHANGE="location = this.options[this.selectedIndex].value;">';
		if(isset($action)){
			foreach ($conn->query("SELECT c_last_name, c_first_name FROM clients WHERE c_id = '".$action_id."'") as $row){
				$client_measure_choice .=  '<option value="clients.php?action=measurement&id='.$action_id.'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
			}
		}	
		foreach ($conn->query("SELECT c_id, c_last_name, c_first_name FROM clients ORDER BY c_last_name ASC") as $row){
$client_measure_choice .=  '<option value="clients.php?action=measurement&id='.$row['c_id'].'">'.$row['c_last_name'].', '.$row['c_first_name'].'</option>';
		}
$client_measure_choice .= '	</select>
							<button type="button" class="s-button cm_choice">Select</button>
							<hr>';	

							
							
//Success variable
$client_measure_success = '';
//Error variables
$client_weight_error = '';
$client_height_error = '';
$client_chest_error = '';
$client_waist_error = '';
$client_hips_error = '';
$client_thighs_error = '';
$client_biceps_error = '';
$client_measurement_error = false;
//Empty variable
$client_measure = '';

if(isset($_POST['cm_submit'])){
	if(empty($_POST['cm_weight'])){
		$client_weight_error = 'A weight is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	if(empty($_POST['cm_height'])){
		$client_height_error = 'A height is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	if(empty($_POST['cm_chest'])){
		$client_chest_error = 'A chest measurement is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	if(empty($_POST['cm_waist'])){
		$client_waist_error = 'A waist measurement is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	if(empty($_POST['cm_hips'])){
		$client_hips_error = 'A hip measurement is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	if(empty($_POST['cm_thighs'])){
		$client_thighs_error = 'A thigh measurement is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	if(empty($_POST['cm_biceps'])){
		$client_biceps_error = 'A bicep measurement is required!';
		$client_measurement = true;
		$action_id = $_POST['cm_client'];
	}
	
	else{
		//Add to client_measurement table
		$measurement->insert_new_measurement($_POST['cm_client'], $_POST['cm_weight'], $_POST['cm_height'], $_POST['cm_chest'], $_POST['cm_waist'], $_POST['cm_hips'], $_POST['cm_thighs'], $_POST['cm_biceps']);
		$client_measure_success = '<h2 class="success">New measurement was taken!</br></h2>';
		$client_measurement = false;
	}
}
//If a client is selected
if($client_measurement == true){
	
$client_measure = '	<form action="clients.php" method="post" enctype="multipart/form-data">
						<label for="cm_weight">Client Weight:</label>
						<input style="width:10%;" type="text" name="cm_weight"/> <em style="color:#cccccc;">(KG)</em> ';
$client_measure .=	$client_weight_error;						
$client_measure .=	'</br>
						<label for="cm_height">Client Height:</label>
						<input style="width:10%;" type="text" name="cm_height"/> <em style="color:#cccccc;">(CM)</em> ';
$client_measure .=	$client_height_error;
$client_measure .=	'</br>
						<label for="cm_chest">Client Chest:</label>
						<input style="width:10%;" type="text" name="cm_chest"/> <em style="color:#cccccc;">(Inches)</em> ';
$client_measure .=	$client_chest_error;
$client_measure .=	'</br>
						<label for="cm_height">Client Waist:</label>
						<input style="width:10%;" type="text" name="cm_waist"/> <em style="color:#cccccc;">(Inches)</em> ';
$client_measure .=	$client_waist_error;
$client_measure .=	'</br>
						<label for="cm_height">Client Hips:</label>
						<input style="width:10%;" type="text" name="cm_hips"/> <em style="color:#cccccc;">(Inches)</em> ';
$client_measure .=	$client_hips_error;
$client_measure .=	'</br>
						<label for="cm_thighs">Client Thighs:</label>
						<input style="width:10%;" type="text" name="cm_thighs"/> <em style="color:#cccccc;">(Inches)</em> ';
$client_measure .=	$client_thighs_error;
$client_measure .=	'</br>
						<label for="cm_biceps">Client Biceps:</label>
						<input style="width:10%;" type="text" name="cm_biceps"/> <em style="color:#cccccc;">(Inches)</em> ';
$client_measure .=	$client_biceps_error;						
$client_measure .=	'</br>
						<input type="hidden" name="cm_client" value="'.$action_id.'"/>
						<input type="submit" name="cm_submit" class="s-button" value="Submit"/>
					</form>';
}