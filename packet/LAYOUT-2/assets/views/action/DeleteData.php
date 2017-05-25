<?php 
$username = "mkmorgangling"; //mysql username
$password = "nepenthes"; //mysql password
$hostname = "localhost"; //hostname
$databasename = "qsbgcoll"; //databasename

$id = $_POST["id"];

$conn = pg_connect("host=$hostname dbname=$databasename user=$username password=$password") or die("Cannot connect to the database");


        for($i=0;$i<count($id);$i++)
	{
		if($id[$i] != "")
		{
			$strSQL = "UPDATE collection SET ";
                        $strSQL .="trash = 2 ";
			$strSQL .="WHERE idcollection = '".$id[$i]."' ";
			$objQuery = pg_query($strSQL);
		}
	}

	/*for($i=0;$i<count($id);$i++)
	{
		if($id[$i] != "")
		{
			$strSQL = "DELETE FROM collection ";
			$strSQL .="WHERE idcollection = '".$id[$i]."' ";
			$objQuery = pg_query($strSQL);
		}
	}*/







pg_close($conn);

?>