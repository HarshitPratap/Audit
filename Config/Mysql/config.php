<?php
class Define
{
	private $host = NULL;
	private $user = NULL;
	private $psw = NULL;
	private $db = NULL;
	function __construct()
    {
	 $this -> host ='localhost';
	 $this -> user = 'root';
	 $this -> psw = '';
	 $this -> db = 'audit_data';
	}
	protected function getconfig()
	{
	 return array($this->host,$this->user,$this->psw,$this->db);
	}
}
?>
