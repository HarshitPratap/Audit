<?php
	include("config.php");
	class Connection extends Define
	{
		private $conn = NULL;
		private $con = NULL;
		private $config = NULL;
		function __construct()
    	{
			parent::__construct();
			$con = parent::getconfig();
			$this->conn = mysqli_connect($con[0], $con[1], $con[2], $con[3]);   
            if (mysqli_connect_errno($this->conn))
			{
                echo "Unable to connect to MySQL Database: " . mysqli_connect_error();
            }
			
		}
		public function getquerystr($qry)
		{
			return mysqli_query($this->conn,$qry);
		}	
	}
?>