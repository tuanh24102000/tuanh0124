<?php
//access.php file

//Declare class to access this php file
class access {

	//connection global variables
	var $host = null;
	var $user = null;
	var $pass = null;
	var $name = null;
    var $port = null;
	var $conn = null;
	var $result = null;
	
	//constructing our class
	function __construct($dbhost, $dbuser, $dbpass, $dbname, $dbport) {
	
	$this->host = $dbhost;
	$this->user = $dbuser;
	$this->pass = $dbpass;
	$this->name = $dbname;
    $this->port = $dbport;
	
	}
	
	//open connection function
	public function connect(){
	
	//establish connection and store it in conn variable
	$this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
	        
	//if error
	if(mysqli_connect_error()){
		echo "could not establish connection to DB";
		}

	//support all languages
	$this->conn->set_charset("utf8");
	
	}
	
	//disconnect function
	public function disconnect(){
	
	if($this->conn != null) {
	$this->conn->close();
	
	}
	
	}

	//insert into db
	public function registerUser($emailid, $password, $firstname, $lastname, $mobile, $salt) {
	//sql command to insert
	$sql = "INSERT INTO customer SET Email_ID=?, Password=?, First_Name=?, Last_Name=?, Mobile_Num=?, Salt=?, Cus_Merch_FLG='C'";

	//store query result in statement
	$statement = $this->conn->prepare($sql);
	
	//if error
	if (!$statement) {
	throw new Exception($statement->error);
	}
	
	//binding parameters of type string
	$statement->bind_param("ssssss", $emailid, $password, $firstname, $lastname, $mobile, $salt);
	
	$return_value = $statement->execute();
	
	return $return_value;
	
	} 
	
	
	public function createWallet($table, $id) {
	
	$sql = "INSERT INTO $table SET Customer_ID=?";
	
	$statement = $this->conn->prepare($sql);

		//if error occurred	
		if(!$statement) {
		throw new Exception($statement->error);
		}
	
	//binding the values
	$statement->bind_param("i", $id);
	
	//execute the statement
	$returnVal = $statement->execute();
	
	return $returnVal;
	
	}
	
	public function getWalletID($id) {
	
	$returnArray = array();
	
	$sql = "SELECT * FROM wallet WHERE Customer_ID='".$id."'";
	
	//assign output we got from sql to the result variable
	$result = $this->conn->query($sql);
	
	//if we have atleast one result
	if($result != null && (mysqli_num_rows($result)>=1) ) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
	
	if(!empty($row)){
		$returnArray = $row;
		}
	}
	
	return $returnArray;
	
	}
	
	public function walletTrans($table, $usrid, $walletid, $transtype, $transname, $amount, $merid, $mername) {
	
		$sql = "INSERT INTO $table SET Customer_ID=?, Wallet_ID=?, Transaction_Type=?, Transaction_Name=?, Amount=?, Merchant_ID=?, Merchant_Name=? ";

	$statement = $this->conn->prepare($sql);

		//if error occurred	
		if(!$statement) {
		throw new Exception($statement->error);
		}
	
	//binding the values
	$statement->bind_param("iissdis", $usrid, $walletid, $transtype, $transname, $amount, $merid, $mername);
	
	//execute the statement
	$returnVal = $statement->execute();
	
	return $returnVal;
	
	}	

	//to retrieve wallet data from DB
	function getWalletData($id) {
	
		$returnArray = array();
//		$sql = "SELECT Transaction_ID, Transaction_Type, Transaction_Name, Amount, Merchant_Name, DATE_FORMAT(CONVERT_TZ(Date, @@session.time_zone, '+05:30'),'%b %d %Y %h:%i %p') as Date  FROM wallet_transactions WHERE Customer_ID = '".$id."' order by Date desc";
//		$sql2 = "SELECT IFNULL((SUM(Amount)),0) as Balance FROM wallet_transactions WHERE Customer_ID = '".$id."'";
		
		$sql = "SELECT Transaction_ID, Transaction_Type, Transaction_Name, Amount, Merchant_Name, DATE_FORMAT(CONVERT_TZ(Date, @@session.time_zone, '+05:30'),'%b %d %Y %h:%i %p') as Date , bal.Balance  FROM wallet_transactions trans, (SELECT IFNULL((SUM(Amount)),0) as Balance, Customer_ID FROM wallet_transactions WHERE Customer_ID = '".$id."') bal WHERE trans.Customer_ID = '".$id."' and trans.Customer_ID = bal.Customer_ID order by trans.Date desc";



		$result = $this->conn->query($sql);
//		$result2 = $this->conn->query($sql2);
		$bal = mysqli_fetch_assoc($result2);

		//	$returnArray[] = $bal;
					
			while($row = mysqli_fetch_assoc($result)) {
			
				$returnArray[] = $row;
//				$returnArray[] = $bal;
			}	

		return $returnArray;
	}
	
		//Scan QR Code and send to database for payment
	public function payMerchant($customerid, $transtyp, $transname, $merchantid, $merchantname, $amount) {
		
		$returnArray = array();
		
		$sql = "INSERT INTO wallet_transactions SET Transaction_Type=?, Transaction_Name=?, Customer_ID=?, Merchant_ID=?, Merchant_Name=?, Amount=?, Wallet_ID=(select Wallet_ID from wallet where Customer_ID=?)";
		
		$statement = $this->conn->prepare($sql);

		//if error occurred	
		if(!$statement) {
		throw new Exception($statement->error);
		}
		
		//binding the values
		$statement->bind_param("ssiisdi", $transtyp, $transname, $customerid, $merchantid, $merchantname, $amount, $customerid);
		
		//execute the statement
		$returnVal = $statement->execute();
		
		return $returnVal;
	
	}
		
	
	//select user information
	public function getUser($username){
	
	$returnArray = array();
	
	$sql = "SELECT id,user,email,password FROM users where user='".$username."'";

	
	//assign output we got from sql to the result variable
	$result = $this->conn->query($sql);
	
	//if we have atleast one result
	if($result != null && (mysqli_num_rows($result)>=1) ) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
	
	if(!empty($row)){
		$returnArray = $row;
		}
	}
	
	return $returnArray;
	
	}
    
    	//Get Book Category information
	public function getBCat($id){
	
	$returnArray = array();
	
	$sql = "SELECT * FROM Book_Category WHERE Cat_Status='A' and Record_Status='A'";
	
	//assign output we got from sql to the result variable
	$result = $this->conn->query($sql);
	
	//if we have atleast one result
	if($result != null && (mysqli_num_rows($result)>=1) ) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
	
	if(!empty($row)){
		$returnArray = $row;
		}
	}
	
	return $returnArray;
	
	}
	
	//function to save our email confirmation tokens
	
	public function saveTokens($table, $id, $token) {
	
	$sql = "INSERT INTO $table SET id=?, token=?";
	
	$statement = $this->conn->prepare($sql);

		//if error occurred	
		if(!$statement) {
		throw new Exception($statement->error);
		}
	
	//binding the values
	$statement->bind_param("is", $id, $token);
	
	//execute the statement
	$returnVal = $statement->execute();
	
	return $returnVal;
	
	}
	
	//To get the token details
	function getuserID($table, $token) {
	
		$returnArray = array();
		$sql = "SELECT id FROM $table where token = '".$token."'";
		$result = $this->conn->query($sql);
		
		if ($result != null && (mysqli_num_rows($result)>=1)) {
		
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			if(!empty($row)) {
			
				$returnArray = $row;
			}
		
		return $returnArray;
		}
	}
	
	
	//Change Status of Email Confirmation Flag
	
	function emailConfirmationStatus($status, $id) {
	
		$sql = "UPDATE customer SET Email_Confirmed=? WHERE id=?";
		$statement = $this->conn->prepare($sql);
		
		if (!$statement) {		
			throw new Exception($statement->error);	
		}
			$statement->bind_param("ii", $status, $id);	
			$returnVal = $statement->execute();
			return $returnVal;
	}
	
	
	//Delete Token once email is confirmed
	
	function deleteToken($table, $token) {
	
		$sql = "DELETE FROM $table where token=?";
		
		$statement = $this->conn->prepare($sql);
		
		$statement->bind_param("s", $token);
		$returnVal = $statement->execute();
		return $returnVal;
	
	}
	

	//Scan QR Code and send to database
	
	public function scanqr($customerid, $qrid, $rewardid) {
		
		$returnArray = array();
		
		$sql = "INSERT INTO customer_rewards SET Customer_ID=?, QR_ID=?, Reward_ID=?";
		
		$statement = $this->conn->prepare($sql);

		//if error occurred	
		if(!$statement) {
		throw new Exception($statement->error);
		}
		
		//binding the values
		$statement->bind_param("iii", $customerid, $qrid, $rewardid);
		
		//execute the statement
		$returnVal = $statement->execute();
		
		return $returnVal;
	
	}
	
	
	//save ava path in DB
	function updateAvaPath($path, $id) {
	
		//sql statement to update ava path in DB
		$sql = "UPDATE customer SET Ava=? WHERE ID=?";
		$statement = $this->conn->prepare($sql);
		
		if(!$statement) {
			throw new Exception($statement->error);	
		}
		
		$statement->bind_param("si", $path, $id);
		
		$returnVal = $statement->execute();
		
		return $returnVal;
	}
	
	//to retrieve rewards from DB
	function getRewards() {
	
		$returnArray = array();
		$sql = "SELECT * FROM rewards R, merchant M where R.Merchant_ID = M.Merchant_ID ORDER BY Top_View";
		$result = $this->conn->query($sql);
		
			while($row = mysqli_fetch_assoc($result)) {
			
				$returnArray[] = $row;
			}	
		return $returnArray;
	}
	
	//to verify Valid QR code
	function checkQR($qrid, $rewardid) {
	
		$returnArray = array();
		$sql = "SELECT * FROM qrcodes where QR_ID = '".$qrid."' AND Reward_ID='".$rewardid."'";
		$result = $this->conn->query($sql);
		
		if ($result != null && (mysqli_num_rows($result)>=1)) {
		
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			if(!empty($row)) {
			
				$returnArray = $row;
			}
		
		return $returnArray;
		}
	}
	
	
	//to retrieve rewards from DB
	function getUserRewards($emailid) {
	
		$returnArray = array();
		$sql = "SELECT Count(CR.Customer_ID) as ChoppCount, R.Reward_Name, M.Merchant_Name, R.Ava 
			FROM customer_rewards CR, rewards R, merchant M, customer C
			WHERE CR.Reward_ID = R.Reward_ID and R.Merchant_ID = M.Merchant_ID and
			C.ID = CR.Customer_ID and C.Email_ID = '".$emailid."'
			GROUP BY R.Reward_Name, M.Merchant_Name, R.Ava;";
		$result = $this->conn->query($sql);
		
			while($row = mysqli_fetch_assoc($result)) {
			
				$returnArray[] = $row;
			}	
		return $returnArray;
	}
	
	public function searchRewards($word) {
	
		$returnArray = array();
		$sql = "SELECT R.*, M.* FROM rewards R, merchant M WHERE R.Merchant_ID = M.Merchant_ID and R.Reward_Name LIKE '%".$word."%' ORDER BY R.Top_View";
		$result = $this->conn->query($sql);
		
			while($row = mysqli_fetch_assoc($result)) {
			
				$returnArray[] = $row;
			}	
		return $returnArray;
	
	
	}
	
	public function getUserChopps($rewardid, $userid) {
	
		$returnArray = array();
		$sql = "SELECT count(*) as ChoppCount FROM customer_rewards WHERE Reward_ID = '".$rewardid."' and Customer_ID = '".$userid."' ";
		$result = $this->conn->query($sql);
		
			while($row = mysqli_fetch_assoc($result)) {
			
				$returnArray = $row;
			}	
		return $returnArray;
	}
	
	
	public function getTravelConnect() {
	
		$returnArray = array();
		$sql = "SELECT * FROM travel_connect";
		$result = $this->conn->query($sql);
		
			while($row = mysqli_fetch_assoc($result)) {
			
				$returnArray[] = $row;
			}	
		return $returnArray;
	}

	//save IR Ticket path in DB
	function uploadTicket($id, $path, $pnr) {
	
		//sql statement to update ava path in DB
		$sql = "INSERT INTO ir_ticket SET Customer_ID=?, Ava=?, PNR=?";
		$statement = $this->conn->prepare($sql);
		
		if(!$statement) {
			throw new Exception($statement->error);	
		}
		$statement->bind_param("iss", $id, $path, $pnr);
		$returnVal = $statement->execute();
		
		return $returnVal;
	}
	public function createUser($username, $email, $password)
	{
		$sql="INSERT INTO users SET id = NULL, user=?, email=?, password=?";
		
		$statement = $this->conn->prepare($sql);
		
		if (!$statement)
		{
			throw new Exception($statement->error);
		}
		
		$statement->bind_param("sss", $username, $email, $password);
		$return_value = $statement->execute();
		
		//$sql="INSERT INTO members SET Mem_ID='2', Mem_LoginID=?, Mem_Fname=?, Mem_Lname=?, Mem_Role=?, Mem_Contact=?, Mem_Email=?, Mem_Status=?, Record_Date='0000-00-00 00:00:00', Record_Status='A'";
		
	}
}


?>