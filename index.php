  <?php
	define('DB_NAME', 'roofingdb_voh6');
	define('DB_USER', 'roofingdb_pxag');
	define('DB_PASSWORD', 'roofpass1');
	define('DB_HOST', 'mysql1.cs.clemson.edu');

	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

	if(!$link){
    	die('Could not connect: ' . mysql_error());
	}

	$db_selected = mysql_select_db(DB_NAME, $link);

	if(!$db_selected){
	   	die('Can\'t use ' . DB_NAME . ': ' . mysql_error());
	}

	if(array_key_exists('username', $_POST)){
		$cookie_user = $_POST['username'];
		$cookie_pass = $_POST['password'];

		$sql = "SELECT access_level from User where (username='$cookie_user' and password='$cookie_pass')";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$cookie_access = $row['access_level'];

		setcookie('usename', $cookie_user, time()+(86400*30), "/");
		setcookie('paword', $cookie_pass, time()+(86400*30), "/");
		setcookie('alevel', $cookie_access, time()+(86400*30), "/");
	}
	if(array_key_exists('action', $_POST)){
		$cookie_act = $_POST['action'];
		setcookie('act', $cookie_act, time()+(86400*30), "/");
	}
	else{
		setcookie('act', "Home", time()+(86400*30), "/");
	}
	if(array_key_exists('table', $_POST)){
		$cookie_tab = $_POST['table'];
		setcookie('tab', $cookie_tab, time()+(86400*30), "/");
	}
	else{
		setcookie('tab', "Home", time()+(86400*30), "/");
	}

?>

<html>
<head>
	<title>World of Roofing</title>
<style>
	body {font-family: Open Sans;}

	input.tab{
		overflow: hidden;
		font-family: Arial;
		border: 1px solid #ccc;
		background-color: #f1f1f1;
		width: 125px;
		cursor: pointer;
		font-weight: bold;
		padding: 10px;
		float: left;
		outline: none;
		transition: 0.2s;
		font-size: 12px;
	}

	input.tab:hover{background-color: #ddd;}

	.tabcontent{
		display: none;
		padding: 6px 12px;
		border 1px solid #ccc;
		border-top:none;
	}

	.tabcontent:target {
		display: block;
	}
</style>
</head>

<body>
<img src="Roof.jpg" style="height:125px;width:100%;">

<h1 style="text-align:center;">
	<b>Welcome to the World of Roofing</b>
</h1>

<!-- Main Menu -->
<form>
	<input type="button" class="tab" onclick="window.location.href=''" value="Home"></button>
</form>

<form action="/~jackied/4620/project/#Business" method="post">
	<input type="radio" name="table" value="Business" checked hidden>
	<input type="submit" class="tab" value="Business">
</form>

<form action="/~jackied/4620/project/#Projects" method="post">
	<input type="radio" name="table" value="Projects" checked hidden>
	<input type="submit" class="tab" value="Projects">
</form>

<form action="/~jackied/4620/project/#User" method="post">
	<input type="radio" name="table" value="User" checked hidden>
	<input type="submit" class="tab" value="User">
</form>
<?php
	if(array_key_exists('usename', $_COOKIE)){
		$user = $_COOKIE['usename'];
		$pass = $_COOKIE['paword'];
		$sql = "SELECT access_level from User where (username='$user' and password='$pass')";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$alevel = $row['access_level'];
		if($alevel > 5){
			echo"<form action='/~jackied/4620/project/#Admin' method='post'>";
			echo"<input type='radio' name='table' value='Admin' checked hidden>";
			echo"<input type='radio' name='action' value='Admin' checked hidden>";
			echo"<input type='submit' class='tab' value='Admin'></form>";
		}
	}
?>

<h1><br></h1>
<hr width="90%">

<!--Used as the Form to find Businesses-->
<div id="BusinessSearch" class="tabcontent">
	<h2 style="text-align:center;">Business</h2>

	<form action="/~jackied/4620/project/#BusinessInsert" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">

	</form>

	<h1><br></h1>
	<form action="/~jackied/4620/project/#BSearch" method="post">
		<fieldset>
			<legend><i>Search Criteria:</i></legend>

				Business Name: <input type="text" name="business_name" size="35" maxlength="30"> <br>
				Address: <input type="text" name="address" size="55" maxlength="50"><br>
				City: <input type="text" name="city" size="25" maxlength="20"><br>
				Zip Code: <input type="number" name="zip" max="99999"><br>
				<input type="radio" name="table" value="Business" checked hidden>
				<input type="radio" name="action" value="Search" checked hidden>
				<button type="submit"> Submit</button>

		</fieldset>
	</form>
</div>

<!-- PHP Functionality of Finding Businesses -->
<div id="BSearch" class="tabcontent">
	<h2 style="text-align:center;">Business</h2>

	<form action="/~jackied/4620/project/#BusinessSearch" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">

	</form>
	<form action="/~jackied/4620/project/#BusinessInsert" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">

	</form>

	<h1><br></h1>

	<?php
	$access = "0";
	//determining access level based on user
	if(array_key_exists('usename', $_COOKIE)){
		$user = $_COOKIE['usename'];
		$pass = $_COOKIE['paword'];

		$sql = "SELECT access_level from User where (username='$user' and password='$pass')";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$access = $row['access_level'];
	}


	if(($_COOKIE['tab'] == "Business") and ($_COOKIE['act'] == "Search")){
		$bname = $_POST['business_name'];
		$bname = str_replace('\'', '\\\'', $bname);

		$aname = $_POST['address'];
		$aname = str_replace('\'', '\\\'', $aname);

		$cname = $_POST['city'];
		$cname = str_replace('\'', '\\\'', $cname);

		$zipcode = $_POST['zip'];

		//generate the MySQL statement
		$sql = "SELECT * FROM Business where access_level <= '$access'";

		if($bname != "" ||$aname != "" || $cname != "" || $zipcode != ""){
			$sql .= " and ";
		}
		if($bname != ""){
			$sql .= "business_name = '$bname'";

			if($aname != "" || $cname != "" || $zipcode != ""){
				$sql .= " and ";
			}
		}
		if($aname != ""){
			$sql .= "address = '$aname'";

			if($cname != "" || $zipcode != ""){
				$sql .= " and ";
			}
		}
		if($cname != ""){
			$sql .= "city = '$cname'";

			if($zipcode != ""){
				$sql .= " and ";
			}
		}
		if($zipcode != ""){
			$sql .= "zip = '$zipcode'";
		}

		//Form to see if an entry should be edited or removed
		if ($result = mysql_query($sql)) {
			echo "<form action='/~jackied/4620/project/#BusinessEdit' method='post'>";
			echo "<fieldset>";
			echo "<legend><i>Selection:</i></legend>";
			echo "<select name='business' size='5' required>";
	        while($row = mysql_fetch_array($result)){
				echo "<option value='" . $row['business_name'] . "'>" . $row['business_name'] . "</option>";
			}
			echo "</select><br>";
			echo "<input type='radio' name='action' value='delete'>Remove Entry<br>";
			echo "<input type='radio' name='action' value='edit'>Edit Entry<br><br>";
			echo "<input type='radio' name='table' value='Business' checked hidden>";
			echo "<input type='submit' class='tab' value='Submit'>";
			echo "</fieldset>";
			echo "</form>";
		}

		//outputting the table
		if ($result = mysql_query($sql)) {
		    if(mysql_num_rows($result) > 0){
		        echo "<table style='border: solid 1px black;'>";
		            echo "<tr>";
		                echo "<th>Business Name</th>";
		                echo "<th>Address</th>";
		                echo "<th>City</th>";
		                echo "<th>Zip Code</th>";
		            echo "</tr>";
		        while($row = mysql_fetch_array($result)){
		            echo "<tr>";
		                echo "<td style='border: 1px solid black;'>  " . $row['business_name'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['address'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['city'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['zip'] . "  </td>";
		            echo "</tr>";
		        }
		    }
		    echo "</table>";
		    mysql_free_result($result);
		}else{
		    die('ERROR: ' . mysql_error());
		}
	}
	?>

</div>

<!-- page to edit or remove entry -->
<div id="BusinessEdit" class="tabcontent">
	<h2 style="text-align:center;">User</h2>

	<form action="/~jackied/4620/project/#BusinessSearch" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">

	</form>
	<form action="/~jackied/4620/project/#BusinessInsert" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">

	</form>

	<h1><br></h1>
	<?php
		//removing user defined entry
		if($_POST['action'] == "delete" && $_COOKIE['tab'] =="Business"){
			$bname = $_POST['business'];
			$sql = "DELETE FROM Business WHERE business_name = '$bname'";
			mysql_query($sql) or die('ERROR: ' . mysql_error());
			echo "'$bname' Has Been Removed from the database<br>";
		}
		//form for editing user defined entry
		if($_POST['action'] == "edit" && $_COOKIE['tab'] == "Business"){
			$bname = $_POST['business'];
			echo "<form action='/~jackied/4620/project/#BEdit' method='post'>";
			echo "<fieldset>";
			echo "<legend><i>Insert Information:</i></legend>";
			echo "Address: <input type='text' name='address' size='55' maxlength='50'><br>";
			echo "City: <input type='text' name='city' size='25' maxlength='20'><br>";
			echo "Zip Code: <input type='number' name='zip' max='99999'><br>";
			echo "<input type='radio' name='action' value='change' checked hidden><br>";
			echo "<input type='radio' name='table' value='Business' checked hidden>";
			echo "<input type='radio' name='business_name' value='$bname' checked hidden>";
			echo "<button type='submit' class='tab'> Submit</button>";
			echo "</fieldset></form>";
		}
	?>

</div>

<!-- Functionality to edit user defined entry  -->
<div id="BEdit" class="tabcontent">
	<h2 style="text-align:center;">User</h2>

	<form action="/~jackied/4620/project/#BusinessSearch" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">

	</form>
	<form action="/~jackied/4620/project/#BusinessInsert" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">

	</form>

	<h1><br></h1>
	<?php
		if(($_POST['action'] =="change") && $_COOKIE['tab']=="Business"){
			$bname = $_POST['business_name'];
			$aname = $_POST['address'];
			$aname = str_replace('\'', '\\\'', $aname);
			$cname = $_POST['city'];
			$cname = str_replace('\'', '\\\'', $cname);
			$zipcode = $_POST['zip'];

			//generate the MySQL statement
			$sql = "UPDATE Business SET ";
			if($aname != ""){
				$sql .= "address = '$aname'";
				if($cname != "" || $zipcode != ""){
					$sql .= ", ";
				}
			}
			if($cname != ""){
				$sql .= "city = '$cname'";
				if($zipcode != ""){
					$sql .= ", ";
				}
			}
			if($zipcode != ""){
				$sql .= "zip = '$zipcode'";
			}
			$sql .= " WHERE (business_name = '$bname')";
			mysql_query($sql) or die('ERROR: ' . mysql_error());

			//Generate the table to show result of editing
			$sql = "SELECT * FROM `Business` WHERE business_name = '$bname'";
			if ($result = mysql_query($sql)) {
			    echo "<table style='border: solid 1px black;'";
    			    echo "<tr>";
    			    	echo "<th>Business Name</th>";
    			    	echo "<th>Address</th>";
    			    	echo "<th>City</th>";
    			    	echo "<th>Zip Code</th>";
    			    echo "</tr>";
    			while($row = mysql_fetch_array($result)){
    	    		echo "<tr>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['business_name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['address'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['city'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['zip'] . " </td>";
    	    	    echo "</tr>";
    	    	}
		    	echo "</table>";
		    	mysql_free_result($result);
			}else{
   	 			die('ERROR: ' . mysql_error());
			}
		}
	?>
</div>

<!-- Form to Insert a Business to the Database -->
<div id="BusinessInsert" class="tabcontent">
	<h2 style="text-align:center;">Business</h2>
	<form action="/~jackied/4620/project/#BusinessSearch" method="post">
		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<h1><br></h1>
	<form action="/~jackied/4620/project/#BIn" method="post">
		<fieldset>
			<legend><i>Insert Information:</i></legend>
				Business Name: <input type="text" name="business_name" size="35" maxlength="30"> <br>
				Address: <input type="text" name="address" size="55" maxlength="50"><br>
				City: <input type="text" name="city" size="25" maxlength="20"><br>
				Zip Code: <input type="number" name="zip" max="99999"><br>
				<input type="radio" name="action" value="Insert" checked hidden><br>
				<button type="submit" class="tab"> Submit</button>
		</fieldset>
	</form>
</div>

<!-- Functionality to Insert a Business -->
<div id="BIn" class="tabcontent">
	<h2 style="text-align:center;">Business</h2>

	<form action="/~jackied/4620/project/#BusinessSearch" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search"></button>

	</form>
	<form action="/~jackied/4620/project/#BusinessInsert" method="post">

		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">

	</form>

	<h1><br></h1>

	<?php
	$access = "0";
	//Determine access level from User
	if(array_key_exists('usename', $_COOKIE)){
		$user = $_COOKIE['usename'];
		$pass = $_COOKIE['paword'];
		$sql = "SELECT access_level from User where (username='$user' and password='$pass')";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$access = $row['access_level'];
	}

	if($_COOKIE['tab'] == "Business" and ($_COOKIE['act'] == "Insert")){
		$bname = $_POST['business_name'];
		$bname = str_replace('\'', '\\\'', $bname);
		$aname = $_POST['address'];
		$aname = str_replace('\'', '\\\'', $aname);
		$cname = $_POST['city'];
		$cname = str_replace('\'', '\\\'', $cname);
		$zipcode = $_POST['zip'];

		//Insert the Business
		$sql = "INSERT INTO Business (business_name, address, city, zip, access_level) VALUES ('$bname', '$aname', '$cname', '$zipcode', '$access')";
		if(!mysql_query($sql)){
    		die('ERROR: ' . mysql_error());
		}

		//Generate the Table
		$sql = "SELECT * FROM Business where (business_name = '$bname')";
		if ($result = mysql_query($sql)) {
		    if(mysql_num_rows($result) > 0){
		        echo "<table style='border: solid 1px black;'>";
		            echo "<tr>";
		                echo "<th>Business Name</th>";
		                echo "<th>Address</th>";
		                echo "<th>City</th>";
		                echo "<th>Zip Code</th>";
		            echo "</tr>";
		        while($row = mysql_fetch_array($result)){
		            echo "<tr>";
		                echo "<td style='border: 1px solid black;'>  " . $row['business_name'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['address'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['city'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['zip'] . "  </td>";
		            echo "</tr>";
		        }
		    }
		    echo "</table>";
		    mysql_free_result($result);
		}else{
		    die('ERROR: ' . mysql_error());
		}

	}
	?>

</div>

<!-- Main Business Tab -->
<div id="Business" class="tabcontent">
	<h2 style="text-align:center;">Business</h2>
	<form action="/~jackied/4620/project/#BusinessSearch" method="post">
		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<form action="/~jackied/4620/project/#BusinessInsert" method="post">
		<input type="radio" name="table" value="Business" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>
</div>

<!-- Form to Search Project Table -->
<div id="ProjectsSearch" class="tabcontent">
	<h2 style="text-align:center;">Projects</h2>
	<form action="/~jackied/4620/project/#ProjectsInsert" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>

	<h1><br></h1>
	<form action="/~jackied/4620/project/#PSearch" method="post">
		<fieldset>
			<legend><i>Search Criteria:</i></legend>
				Minimum Project Number: <input type="number" name="min"><br>
				Maximum Project Number: <input type="number" name="max"><br>
				Exact Project Number: <input type="number" name="project_num"><br>
				Project Name: <input type="name" name="project_name"><br>
				Business Name: <input type="name" name="business_name"><br>
				Status: <input type="name" name="status"><br>
				<input type="radio" name="action" value="Search" checked hidden><br>
				<button type="submit" class="tab"> Submit</button>
		</fieldset>
	</form>
</div>

<!-- Functionality to Search Project Table -->
<div id="PSearch" class="tabcontent">
	<h2 style="text-align:center;">Projects</h2>
	<form action="/~jackied/4620/project/#ProjectsSearch" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<form action="/~jackied/4620/project/#ProjectsInsert" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>
	<h1><br></h1>
	<?php
		$access = "0";
		//Determine Access Level from User
		if(array_key_exists('usename', $_COOKIE)){
			$user = $_COOKIE['usename'];
			$pass = $_COOKIE['paword'];
			$sql = "SELECT access_level from User where (username='$user' and password='$pass')";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$access = $row['access_level'];
		}

		if(($_COOKIE['tab'] == "Projects") and ($_COOKIE['act'] == "Search")){
			$pnum = $_POST['project_num'];
			$pmax = $_POST['max'];
			$pmin = $_POST['min'];
			$pname = $_POST['project_name'];
			$pname = str_replace('\'', '\\\'', $pname);
			$bname = $_POST['business_name'];
			$bname = str_replace('\'', '\\\'', $bname);
			$stat = $_POST['status'];
			$stat = str_replace('\'', '\\\'', $stat);

			//Generate MySQL Select Statement
			$sql = "SELECT * FROM Project where access_level <= '$access'";
			if($pmin != "" || $pmax != "" || $pnum != "" || $pname != "" || $bname != "" || $stat != ""){
				$sql .= " and ";
			}
			if($pmin != ""){
				$sql .= "project_num >= '$pmin'";
				if($pmax != "" || $pnum != "" || $pname != "" || $bname != "" || $stat != ""){
					$sql .= " and ";
				}
			}
			if($pmax != ""){
				$sql .= "project_num <= '$pmax'";
				if($pnum != "" || $pname != "" || $bname != "" || $stat != ""){
					$sql .= " and ";
				}
			}
			if($pnum != ""){
				$sql .= "project_num = '$pnum'";
				if($pname != "" || $bname != "" || $stat != ""){
					$sql .= " and ";
				}
			}
			if($pname != ""){
				$sql .= "project_name = '$pname'";
				if($bname != "" || $stat != ""){
					$sql .= " and ";
				}
			}
			if($bname != ""){
				$sql .= "business_name = '$bname'";
				if($stat != ""){
					$sql .= " and ";
				}
			}
			if($stat != ""){
				$sql .= "status = '$stat'";
			}
		}

		//Allow user to choose to edit or remove table entry
		if ($result = mysql_query($sql)) {
			echo "<form action='/~jackied/4620/project/#ProjectsEdit' method='post'>";
			echo "<fieldset>";
			echo "<legend><i>Selection:</i></legend>";
			echo "<select name='project' size='5' required>";
	        while($row = mysql_fetch_array($result)){
				echo "<option value='" . $row['project_num'] . "'>" . $row['project_num'] . "</option>";
			}
			echo "</select><br>";
			echo "<input type='radio' name='action' value='delete'>Remove Entry<br>";
			echo "<input type='radio' name='action' value='edit'>Edit Entry<br><br>";
			echo "<input type='radio' name='table' value='Projects' checked hidden>";
			echo "<input type='submit' class='tab' value='Submit'>";
			echo "</fieldset>";
			echo "</form>";
		}

		//Generate Searched Table
		if ($result = mysql_query($sql)) {
		    if(mysql_num_rows($result) > 0){
		        echo "<table style='border: solid 1px black;'>";
		            echo "<tr>";
		                echo "<th>Project Number</th>";
		                echo "<th>Project Name</th>";
		                echo "<th>Business Name</th>";
		                echo "<th>Status</th>";
		            echo "</tr>";
		        while($row = mysql_fetch_array($result)){
		            echo "<tr>";
		                echo "<td style='border: 1px solid black;'>  " . $row['project_num'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['project_name'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['business_name'] . "  </td>";
	                	echo "<td style='border: 1px solid black;'>  " . $row['status'] . "  </td>";
	            	echo "</tr>";
		        }
		    }
		    echo "</table>";
		    mysql_free_result($result);
		}else{
		    die('ERROR: ' . mysql_error());
		}

	?>
</div>

<!-- Functionality to remove entry and Form for Editing entry -->
<div id="ProjectsEdit" class="tabcontent">
	<h2 style="text-align:center;">Projects</h2>
	<form action="/~jackied/4620/project/#ProjectsSearch" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<form action="/~jackied/4620/project/#ProjectsInsert" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>
	<h1><br></h1>
	<?php
		//Remove User Defined Entry
		if($_POST['action'] == "delete" && $_POST['table'] =="Projects"){
			$pnum = $_POST['project'];
			$sql = "DELETE FROM Project WHERE project_num = '$pnum'";
			mysql_query($sql) or die('ERROR: ' . mysql_error());
			echo "Project Number:'$pnum' Has Been Removed from the database<br>";
		}
		//Form to Edit User Defined Entry
		if($_POST['action'] == "edit" && $_POST['table'] == "Projects"){
			$pnum = $_POST['project'];
			echo "<form action='/~jackied/4620/project/#PEdit' method='post'>";
			echo "<fieldset>";
			echo "<legend><i>Insert Information:</i></legend>";
			echo "Project Name: <input type='name' name='project_name'><br>";
			echo "Business Name: <input type='name' name='business_name'><br>";
			echo "Status: <input type='name' name='status'><br>";
			echo "<input type='radio' name='action' value='change' checked hidden><br>";
			echo "<input type='radio' name='table' value='Projects' checked hidden>";
			echo "<input type='radio' name='project_num' value='$pnum' checked hidden>";
			echo "<button type='submit' class='tab'> Submit</button>";
			echo "</fieldset></form>";
		}
	?>
</div>

<!-- Functionality to Edit Project Entry -->
<div id="PEdit" class="tabcontent">
	<h2 style="text-align:center;">Projects</h2>
	<form action="/~jackied/4620/project/#ProjectsSearch" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<form action="/~jackied/4620/project/#ProjectsInsert" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>
	<h1><br></h1>
	<?php
		if(($_POST['action'] =="change") && $_COOKIE['tab']=="Projects"){
			$pnum = $_POST['project_num'];
			$pname = $_POST['project_name'];
			$pname = str_replace('\'', '\\\'', $pname);
			$bname = $_POST['business_name'];
			$bname = str_replace('\'', '\\\'', $bname);
			$stat = $_POST['status'];
			$stat = str_replace('\'', '\\\'', $stat);

			//Generate MySQL UPDATE statement
			$sql = "UPDATE Project SET ";
			if($pname != ""){
				$sql .= "project_name = '$pname'";
				if($bname != "" || $stat != ""){
					$sql .= ", ";
				}
			}
			if($bname != ""){
				$sql .= "business_name = '$bname'";
				if($stat != ""){
					$sql .= ", ";
				}
			}
			if($stat != ""){
				$sql .= "status = '$stat'";
			}
			$sql .= " WHERE (project_num = '$pnum')";
			mysql_query($sql) or die('ERROR: ' . mysql_error());

			//Display Updated table Entry
			$sql = "SELECT * FROM `Project` WHERE project_num = '$pnum'";
			if ($result = mysql_query($sql)) {
		        echo "<table style='border: solid 1px black;'>";
		            echo "<tr>";
		                echo "<th>Project Number</th>";
		                echo "<th>Project Name</th>";
		                echo "<th>Business Name</th>";
		                echo "<th>Status</th>";
		            echo "</tr>";
		        while($row = mysql_fetch_array($result)){
		            echo "<tr>";
		                echo "<td style='border: 1px solid black;'>  " . $row['project_num'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['project_name'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['business_name'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['status'] . "  </td>";
		            echo "</tr>";
		        }
		    	echo "</table>";
		    	mysql_free_result($result);
			}else{
   	 			die('ERROR: ' . mysql_error());
			}
		}
	?>
</div>

<!-- Form to Insert entry into Project Table -->
<div id="ProjectsInsert" class="tabcontent">
	<h2 style="text-align:center;">Projects</h2>
	<form action="/~jackied/4620/project/#ProjectsSearch" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<form action="/~jackied/4620/project/#ProjectsInsert" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>
	<h1><br></h1>
	<form action="/~jackied/4620/project/#PIn" method="post">
		<fieldset>
			<legend><i>Insert Information:</i></legend>
				Project Name: <input type="name" name="project_name"><br>
				Business Name: <input type="name" name="business_name"><br>
				Status: <input type="name" name="status"><br>
				<input type="radio" name="action" value="Insert" checked hidden><br>
				<button type="submit" class="tab"> Submit</button>
		</fieldset>
	</form>
</div>

<!-- Functionality to insert entry into Project Table -->
<div id="PIn" class="tabcontent">
	<h2 style="text-align:center;">Project</h2>
	<form action="/~jackied/4620/project/#ProjectsSearch" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<form action="/~jackied/4620/project/#ProjectsInsert" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>
	<h1><br></h1>

	<?php
	if($_POST['project_name'] && ($_COOKIE['act']=="Insert")){
		$pname = $_POST['project_name'];
		$pname = str_replace('\'', '\\\'', $pname);
		$bname = $_POST['business_name'];
		$bname = str_replace('\'', '\\\'', $bname);
		$stat = $_POST['status'];
		$stat = str_replace('\'', '\\\'', $stat);

		//Find Access Level From User
		if(array_key_exists('usename', $_COOKIE)){
			$user = $_COOKIE['usename'];
			$pass = $_COOKIE['paword'];
			$sql = "SELECT access_level from User where (username='$user' and password='$pass')";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$access = $row['access_level'];
		}

		//Insert Entry into Table
		$sql = "INSERT INTO Project (project_name, business_name, status, access_level) VALUES ('$pname', '$bname', '$stat', '$access')";
		if(!mysql_query($sql)){
	   	 	die('ERROR: ' . mysql_error());
		}

		//Display inserted entry
		$sql = "SELECT * FROM Project where (project_name='$pname' and business_name='$bname')";
		if ($result = mysql_query($sql)) {
		    if(mysql_num_rows($result) > 0){
		        echo "<table style='border: solid 1px black;'>";
		            echo "<tr>";
		                echo "<th>Project Number</th>";
		                echo "<th>Project Name</th>";
		                echo "<th>Business Name</th>";
		                echo "<th>Status</th>";
		            echo "</tr>";
		        while($row = mysql_fetch_array($result)){
		            echo "<tr>";
		                echo "<td style='border: 1px solid black;'>  " . $row['project_num'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['project_name'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['business_name'] . "  </td>";
		                echo "<td style='border: 1px solid black;'>  " . $row['status'] . "  </td>";
		            echo "</tr>";
		        }
		    }
		    echo "</table>";
		    mysql_free_result($result);
		}else{
		    die('ERROR: ' . mysql_error());
		}

	}
	?>
</div>

<!-- Main Projects Menu -->
<div id="Projects" class="tabcontent">
	<h2 style="text-align:center;">Projects</h2>
	<form action="/~jackied/4620/project/#ProjectsSearch" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Search">
	</form>
	<form action="/~jackied/4620/project/#ProjectsInsert" method="post">
		<input type="radio" name="table" value="Projects" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Insert">
	</form>
</div>

<!-- Form to Login to Account -->
<div id="LogIn" class="tabcontent">
	<h2 style="text-align:center;">User</h2>
	<form action="/~jackied/4620/project/#Register" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Register">
	</form>
	<form action="/~jackied/4620/project/#Account" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Account" checked hidden>
		<input type="submit" class="tab" value="Account">
	</form>
	<h1><br></h1>

	<form action="/~jackied/4620/project/#ULog" method="post">
		<fieldset>
			<legend><i>Search Criteria:</i></legend>
				Username: <input type="name" name="username"><br>
				Password: <input type="name" name="password"><br>
				<input type="radio" name="table" value="User" checked hidden>
				<input type="radio" name="action" value="Search" checked hidden><br>
				<button type="submit" class="tab"> Submit</button>
		</fieldset>
	</form>
</div>

<!-- Functionality to Login -->
<div id="ULog" class="tabcontent">
	<h2 style="text-align:center;">User</h2>
	<form action="/~jackied/4620/project/#Register" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Register">
	</form>
	<form action="/~jackied/4620/project/#Account" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Account" checked hidden>
		<input type="submit" class="tab" value="Account">
	</form>
	<h1><br></h1>

	<?php
		if(array_key_exists('username', $_POST) && array_key_exists('password', $_POST) && ($_COOKIE['act']=="Search")){
			$uname = $_POST['username'];
			$pname = $_POST['password'];
			$pname = str_replace('\'', '\\\'', $pname);

			//Find account in database and display
			$sql = "SELECT * FROM User WHERE username = '$uname' AND password = '$pname'";
			if ($result = mysql_query($sql)) {
			    if(mysql_num_rows($result) > 0){
			        echo "<table style='border: solid 1px black;'";
	    		        echo "<tr>";
	    		            echo "<th>Username</th>";
	    		            echo "<th>Password</th>";
	    		            echo "<th>Access Level</th>";
	    		        echo "</tr>";
	    		    while($row = mysql_fetch_array($result)){
	        	    echo "<tr>";
	        	        echo "<td style='border: 1px solid black;'>  " . $row['username'] . "  </td>";
	        	        echo "<td style='border: 1px solid black;'>  " . $row['password'] . "  </td>";
	        	        echo "<td style='border: 1px solid black;'>  " . $row['access_level'] . "  </td>";
	        	    echo "</tr>";
	        		}
	    		}
	    		echo "</table>";
	    		mysql_free_result($result);
			}else{
	    		die('ERROR: ' . mysql_error());
			}

		}
	?>
</div>

<!-- Form to Create an Account -->
<div id="Register" class="tabcontent">
	<h2 style="text-align:center;">User</h2>
	<form action="/~jackied/4620/project/#LogIn" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Log In">
	</form>
	<form action="/~jackied/4620/project/#Account" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Account" checked hidden>
		<input type="submit" class="tab" value="Account">
	</form>
	<h1><br></h1>

	<form action="/~jackied/4620/project/#UReg" method="post">
		<fieldset>
			<legend><i>Insert Information:</i></legend>
				Username: <input type="name" name="username"> <br>
				Password: <input type="name" name="password"><br>
				<input type="radio" name="table" value="User" checked hidden>
				<input type="radio" name="action" value="Insert" checked hidden><br>
				<button type="submit" class="tab"> Submit</button>
		</fieldset>
	</form>
</div>

<!-- Functionality to Create Account -->
<div id="UReg" class="tabcontent">
	<h2 style="text-align:center;">User</h2>
	<form action="/~jackied/4620/project/#LogIn" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Log In">
	</form>
	<form action="/~jackied/4620/project/#Account" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Account" checked hidden>
		<input type="submit" class="tab" value="Account">
	</form>
	<h1><br></h1>

	<?php
		if(array_key_exists('username', $_POST) && array_key_exists('password', $_POST) && ($_COOKIE['act']=="Insert")){
			$uname = $_POST['username'];
			$pname = $_POST['password'];
			$pname = str_replace('\'', '\\\'', $pname);

			//Ensure password has a capital letter
			if(strtolower($pname) != $pname){
				$access = rand(0,5);
				//Insert account into Database
				$sql = "INSERT INTO User (`username`, `password`, `access_level`) VALUES ('$uname', '$pname', '$access');";
				if(!mysql_query($sql)){
		    		die('ERROR: ' . mysql_error());
				}

				//Display Created Account
				$sql = "SELECT * FROM `User` WHERE `username` = '$uname' AND `password` = '$pname'";
				if ($result = mysql_query($sql)) {
				    if(mysql_num_rows($result) > 0){
				        echo "<table style='border: solid 1px black;'";
		    		        echo "<tr>";
		    		            echo "<th>Username</th>";
		    		            echo "<th>Password</th>";
		    		            echo "<th>Access Level</th>";
		    		        echo "</tr>";
		    		    while($row = mysql_fetch_array($result)){
		        	    echo "<tr>";
		        	        echo "<td style='border: 1px solid black;'>  " . $row['username'] . "  </td>";
			        	        echo "<td style='border: 1px solid black;'>  " . $row['password'] . "  </td>";
		        	        echo "<td style='border: 1px solid black;'>  " . $row['access_level'] . "  </td>";
		        	    echo "</tr>";
		        		}
		    		}
		    		echo "</table>";
			    		mysql_free_result($result);
				}else{
   		 			die('ERROR: ' . mysql_error());
				}
			}
			else {
				echo "<h2>Password Needs At Least 1 Capital Letter</h2>";
			}
		}
	?>
</div>

<!-- Form to Edit Account Information -->
<div id="Account" class="tabcontent">
	<h2 style="text-align:center;">User</h2>
	<form action="/~jackied/4620/project/#LogIn" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Log In">
	</form>
	<form action="/~jackied/4620/project/#Register" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Register">
	</form>
	<h1><br></h1>
	<form action="/~jackied/4620/project/#Info" method="post">
		<fieldset>
			<legend><i>Insert Information:</i></legend>
				First Name: <input type="name" name="first"> <br>
				Last Name: <input type="name" name="last"><br>
				Phone: <input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
				<span>Format: 123-456-7890</span><br>
				Email: <input type="name" name="email"><br>
				Mailing Address: <input type="name" name="mail"><br>
				<input type="radio" name="table" value="User" checked hidden>
				<input type="radio" name="action" value="Account" checked hidden><br>
				<button type="submit" class="tab"> Commit Changes</button>
		</fieldset>
	</form>
</div>

<!-- Functionality to Edit Account Information -->
<div id="Info" class="tabcontent">
	<h2 style="text-align:center;">User</h2>
	<form action="/~jackied/4620/project/#LogIn" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Log In">
	</form>
	<form action="/~jackied/4620/project/#Register" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Register">
	</form>
	<h1><br></h1>
	<?php
		if(($_COOKIE['act'] =="Account")){
			$user = $_COOKIE['usename'];
			$pass = $_COOKIE['paword'];
			$fname = $_POST['first'];
			$lname = $_POST['last'];
			$pnum = $_POST['phone'];
			$ename = $_POST['email'];
			$mname = $_POST['mail'];
			$mname = str_replace('\'', '\\\'', $mname);

			//Generate MySQL UPDATE statement
			$sql = "UPDATE User SET ";
			if($fname != ""){
				$sql .= "First_Name = '$fname'";
				if($lname != "" || $pnum != "" || $ename != "" || $mname != ""){
					$sql .= ", ";
				}
			}
			if($lname != ""){
				$sql .= "Last_Name = '$lname'";
				if($pnum != "" || $ename != "" || $mname != ""){
					$sql .= ", ";
				}
			}
			if($pnum != ""){
				$sql .= "phone = '$pnum'";
				if($ename != "" || $mname != ""){
					$sql .= ", ";
				}
			}
			if($ename != ""){
				$sql .= "Email = '$ename'";
				if($mname != ""){
					$sql .= ", ";
				}
			}
			if($mname != ""){
				$sql .= "Mail_Addr = '$mname'";
			}
			$sql .= " WHERE (username = '$user' and password = '$pass')";
			mysql_query($sql) or die('ERROR: ' . mysql_error());

			//Display updated account information
			$sql = "SELECT * FROM `User` WHERE `username` = '$user' AND `password` = '$pass'";
			if ($result = mysql_query($sql)) {
			    echo "<table style='border: solid 1px black;'";
    			    echo "<tr>";
    			    	echo "<th>Username</th>";
    			    	echo "<th>Password</th>";
    			    	echo "<th>First Name</th>";
    			    	echo "<th>Last Name</th>";
    			    	echo "<th>Email</th>";
    			    	echo "<th>Mail_Addr</th>";
    			    	echo "<th>Access Level</th>";
    			    echo "</tr>";
    			while($row = mysql_fetch_array($result)){
    	    		echo "<tr>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['username'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['password'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['First_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Last_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Email'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Mail_Addr'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['access_level'] . " </td>";
    	    	    echo "</tr>";
    	    	}
		    	echo "</table>";
		    	mysql_free_result($result);
			}else{
   	 			die('ERROR: ' . mysql_error());
			}
		}
	?>
</div>

<!-- Main User Menu -->
<div id="User" class="tabcontent">
	<h2 style="text-align:center;">User</h2>
	<form action="/~jackied/4620/project/#LogIn" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="Log In">
	</form>
	<form action="/~jackied/4620/project/#Register" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Insert" checked hidden>
		<input type="submit" class="tab" value="Register">
	</form>
	<form action="/~jackied/4620/project/#Account" method="post">
		<input type="radio" name="table" value="User" checked hidden>
		<input type="radio" name="action" value="Account" checked hidden>
		<input type="submit" class="tab" value="Account">
	</form>

	<h1><br></h1>

	<?php
		if(array_key_exists('usename', $_COOKIE)){
			$user = $_COOKIE['usename'];
			$pass = $_COOKIE['paword'];

			//Display current Account logged into
			$sql = "SELECT * FROM User WHERE username='$user' and password='$pass'";
			$result = mysql_query($sql);
			if(mysql_num_rows($result) > 0){
			    echo "<table style='border: solid 1px black;'";
    			    echo "<tr>";
    			    	echo "<th>Username</th>";
    			    	echo "<th>Password</th>";
    			    	echo "<th>First Name</th>";
    			    	echo "<th>Last Name</th>";
    			    	echo "<th>Email</th>";
    			    	echo "<th>Mail_Addr</th>";
    			    	echo "<th>Access Level</th>";
    			    echo "</tr>";
    			while($row = mysql_fetch_array($result)){
    	    		echo "<tr>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['username'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['password'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['First_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Last_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Email'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Mail_Addr'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['access_level'] . " </td>";
    	    	    echo "</tr>";
    	    		}
    			}
    			echo "</table>";
    			mysql_free_result($result);
			}
	?>
</div>

<!-- Form for Admin to Search Accounts -->
<div id="AdminSearch" class="tabcontent">
	<h2 style="text-align:center;">Admin</h2>
	<form action="/~jackied/4620/project/#Backup" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Backup" checked hidden>
		<input type="submit" class="tab" value="Backup">
	</form>
	<h1><br></h1>

	<form action="/~jackied/4620/project/#ASearch" method="post">
		<fieldset>
			<legend><i>Search Criteria:</i></legend>
				First Name: <input type="name" name="first"> <br>
				Last Name: <input type="name" name="last"><br>
				Mailing Address: <input type="name" name="mail"><br>
				Minimum Access Level: <input type="number" name="min_access" max="5"><br>
				Maximum Access Level: <input type="number" name="max_access" max="6"><br>
				Exact Access Level: <input type="number" name="access_level" max="6"><br>
				<input type="radio" name="table" value="Admin" checked hidden>
				<input type="radio" name="action" value="Edit" checked hidden><br>
				<button type="submit" class="tab"> Submit</button>
		</fieldset>
	</form>
</div>

<!-- Functionality to Search Project Table -->
<div id="ASearch" class="tabcontent">
	<h2 style="text-align:center;">Admin</h2>
	<form action="/~jackied/4620/project/#AdminSearch" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="User Search">
	</form>
	<form action="/~jackied/4620/project/#Backup" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Backup" checked hidden>
		<input type="submit" class="tab" value="Backup">
	</form>
	<h1><br></h1>
	<?php
		if(($_COOKIE['tab'] == "Admin") and ($_COOKIE['act'] == "Search")){
			$anum = $_POST['access_level'];
			$amax = $_POST['max_access'];
			$amin = $_POST['min_access'];
			$fname = $_POST['first'];
			$lname = $_POST['last'];
			$mail_addr = $_POST['mail'];
			$mail_addr = str_replace('\'', '\\\'', $mail_addr);

			//Generate MySQL Select Statement
			$sql = "SELECT * FROM User";

			if($anum != "" || $amin != "" || $amax != "" || $fname != "" || $lname != "" || $mail_addr != ""){
				$sql .= " WHERE ";
			}
			if($anum != ""){
				$sql .= "access_level = '$anum'";
				if($amin != "" || $amax != "" || $fname != "" || $lname != "" || $mail_addr != ""){
					$sql .= " and ";
				}
			}
			if($amin != ""){
				$sql .= "access_level >= '$amin'";
				if($amax != "" || $fname != "" || $lname != "" || $mail_addr != ""){
					$sql .= " and ";
				}
			}
			if($amax != ""){
				$sql .= "access_level <= '$amax'";
				if($fname != "" || $lname != "" || $mail_addr != ""){
					$sql .= " and ";
				}
			}
			if($fname != ""){
				$sql .= "First_Name = '$fname'";
				if($lname != "" || $mail_addr != ""){
					$sql .= " and ";
				}
			}
			if($lname != ""){
				$sql .= "Last_Name = '$lname'";
				if($mail_addr != ""){
					$sql .= " and ";
				}
			}
			if($mail_addr != ""){
				$sql .= "Mail_Addr = '$mail_addr'";
			}
		}

		//Allow admin to choose to edit or remove table entry
		if ($result = mysql_query($sql)) {
			echo "<form action='/~jackied/4620/project/#AdminEdit' method='post'>";
			echo "<fieldset>";
			echo "<legend><i>Selection:</i></legend>";
			echo "<select name='username' size='5' required>";
	        while($row = mysql_fetch_array($result)){
				echo "<option value='" . $row['username'] . "'>" . $row['username'] . "</option>";
			}
			echo "</select>";
			$result = mysql_query($sql);
			echo "<select name='password' size='5' required>";
	        while($row = mysql_fetch_array($result)){
				echo "<option value='" . $row['password'] . "'>" . $row['password'] . "</option>";
			}
			echo "</select><br>";
			echo "<input type='radio' name='action' value='delete'>Remove Entry<br>";
			echo "<input type='radio' name='action' value='edit'>Edit Entry<br><br>";
			echo "<input type='radio' name='table' value='Admin' checked hidden>";
			echo "<input type='submit' class='tab' value='Submit'>";
			echo "</fieldset>";
			echo "</form>";
		}

		//Generate Searched Table
		if ($result = mysql_query($sql)) {
		    if(mysql_num_rows($result) > 0){
			    echo "<table style='border: solid 1px black;'";
    			    echo "<tr>";
    			    	echo "<th>Username</th>";
    			    	echo "<th>Password</th>";
    			    	echo "<th>First Name</th>";
    			    	echo "<th>Last Name</th>";
    			    	echo "<th>Email</th>";
    			    	echo "<th>Mail_Addr</th>";
    			    	echo "<th>Access Level</th>";
    			    echo "</tr>";
    			while($row = mysql_fetch_array($result)){
    	    		echo "<tr>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['username'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['password'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['First_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Last_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Email'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Mail_Addr'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['access_level'] . " </td>";
    	    	    echo "</tr>";
    	    	}
    			echo "</table>";
    			mysql_free_result($result);
			}
		}else{
		    die('ERROR: ' . mysql_error());
		}

	?>
</div>

<!-- Functionality to remove entry and Form for Editing entry -->
<div id="AdminEdit" class="tabcontent">
	<h2 style="text-align:center;">Admin</h2>
	<form action="/~jackied/4620/project/#AdminSearch" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="User Search">
	</form>
	<form action="/~jackied/4620/project/#Backup" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Backup" checked hidden>
		<input type="submit" class="tab" value="Backup">
	</form>
	<h1><br></h1>
	<?php
		//Remove User Defined Entry
		if($_POST['action'] == "delete" && $_POST['table'] =="Admin"){
			$user = $_POST['username'];
			$pass = $_POST['password'];
			$sql = "DELETE FROM User WHERE (username = '$user' and password = '$pass')";
			mysql_query($sql) or die('ERROR: ' . mysql_error());
			echo "Account:'$user' Has Been Removed from the database<br>";
		}
		//Form to Edit User Defined Entry
		if($_POST['action'] == "edit" && $_POST['table'] == "Admin"){
			$user = $_POST['username'];
			$pass = $_POST['password'];
			echo "<form action='/~jackied/4620/project/#AEdit' method='post'>";
			echo "<fieldset>";
			echo "<legend><i>Insert Information:</i></legend>";
			echo "Access Level: <input type='number' name='access_level' required><br>";
			echo "<input type='radio' name='action' value='change' checked hidden><br>";
			echo "<input type='radio' name='table' value='Admin' checked hidden>";
			echo "<input type='radio' name='uname' value='$user' checked hidden>";
			echo "<input type='radio' name='pname' value='$pass' checked hidden>";
			echo "<button type='submit' class='tab'> Submit</button>";
			echo "</fieldset></form>";
		}
	?>
</div>

<!-- Functionality to Edit Account Information -->
<div id="AEdit" class="tabcontent">
	<h2 style="text-align:center;">Admin</h2>
	<form action="/~jackied/4620/project/#AdminSearch" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="User Search">
	</form>
	<form action="/~jackied/4620/project/#Backup" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Backup" checked hidden>
		<input type="submit" class="tab" value="Backup">
	</form>
	<h1><br></h1>
	<?php
		if(($_POST['action'] =="change")){
			$user = $_POST['uname'];
			$pass = $_POST['pname'];
			$alevel = $_POST['access_level'];

			//Generate MySQL UPDATE statement
			$sql = "UPDATE User SET access_level = '$alevel' WHERE (username = '$user' and password = '$pass')";
			mysql_query($sql) or die('ERROR: ' . mysql_error());

			//Display updated account information
			$sql = "SELECT * FROM `User` WHERE `username` = '$user' AND `password` = '$pass'";
			if ($result = mysql_query($sql)) {
			    echo "<table style='border: solid 1px black;'";
    			    echo "<tr>";
    			    	echo "<th>Username</th>";
    			    	echo "<th>Password</th>";
    			    	echo "<th>First Name</th>";
    			    	echo "<th>Last Name</th>";
    			    	echo "<th>Email</th>";
    			    	echo "<th>Mail_Addr</th>";
    			    	echo "<th>Access Level</th>";
    			    echo "</tr>";
    			while($row = mysql_fetch_array($result)){
    	    		echo "<tr>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['username'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['password'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['First_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Last_Name'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Email'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['Mail_Addr'] . "  </td>";
    	    	        echo "<td style='border: 1px solid black;'>  " . $row['access_level'] . " </td>";
    	    	    echo "</tr>";
    	    	}
		    	echo "</table>";
		    	mysql_free_result($result);
			}else{
   	 			die('ERROR: ' . mysql_error());
			}
		}
	?>
</div>

<!-- Backup Menu -->
<div id="Backup" class="tabcontent">
	<h2 style="text-align:center;">Admin</h2>
	<form action="/~jackied/4620/project/#AdminSearch" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="User Search">
	</form>
	<form action="/~jackied/4620/project/#Backup" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Backup" checked hidden>
		<input type="submit" class="tab" value="Backup">
	</form>
	<h1><br></h1>

	<form action="/~jackied/4620/project/#BTable" method="post">
		<fieldset>
			<legend><i>Choose Tables to Backup:</i></legend>
				<input type="checkbox" name="list[]" value="Business">Business<br>
				<input type="checkbox" name="list[]" value="Project">Projects<br>
				<input type="checkbox" name="list[]" value="User">User<br>
				<input type="radio" name="table" value="Admin" checked hidden>
				<input type="radio" name="action" value="Backup" checked hidden><br>
				<input type="submit" name="submit" value="Submit" class="tab">
		</fieldset>
	</form>
</div>

<!-- Functionality to Backup Tables -->
<div id="BTable" class="tabcontent">
	<h2 style="text-align:center;">Admin</h2>
	<form action="/~jackied/4620/project/#AdminSearch" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="User Search">
	</form>
	<form action="/~jackied/4620/project/#Backup" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Backup" checked hidden>
		<input type="submit" class="tab" value="Backup">
	</form>
	<h1><br></h1>
	<?php
		if(isset($_POST['submit'])){//to run PHP script on submit
			if(!empty($_POST['list'])){
				// Loop to make a copy of the table as a backup.
				foreach($_POST['list'] as $selected){
					$sql = "DROP TABLE IF EXISTS copy_".$selected;
					mysql_query($sql) or die('ERROR: ' . mysql_error());
					$sql = "CREATE TABLE copy_".$selected." LIKE ".$selected;
					mysql_query($sql) or die('ERROR: ' . mysql_error());
					$sql = "INSERT copy_".$selected." SELECT * FROM ".$selected;
					mysql_query($sql) or die('ERROR: ' . mysql_error());
					echo "<h3>Table: $selected has been copied</h3>";
				}
			}
		}
	?>
</div>

<!-- Main Admin Menu -->
<div id="Admin" class="tabcontent">
	<h2 style="text-align:center;">Admin</h2>
	<form action="/~jackied/4620/project/#AdminSearch" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Search" checked hidden>
		<input type="submit" class="tab" value="User Search">
	</form>
	<form action="/~jackied/4620/project/#Backup" method="post">
		<input type="radio" name="table" value="Admin" checked hidden>
		<input type="radio" name="action" value="Backup" checked hidden>
		<input type="submit" class="tab" value="Backup">
	</form>
	<h1><br></h1>
</div>

<h1><br></h1>
<hr width="90%">
<p align="center">
	<small>
		*Created by Jackie Doan(JackieD)*<br>
		Made in 2019
	</small>
</p>
<?php
	//close Database
	 mysql_close();
?>
</body>
</html>
