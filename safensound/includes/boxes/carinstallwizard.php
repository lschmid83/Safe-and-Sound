<?php
/*
  $Id: visitoremail.php,v 1.16 2003/06/10 18:26:33 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<!-- car install wizard //-->
          <tr>
            <td width="182px" ><form name="carinstallwizard" method="post">

<script language="JavaScript" type="text/javascript">
function setCookie(c_name,value,expiredays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+
	((expiredays==null) ? "" : ";expires="+exdate.toUTCString());
}
</script>

<?php
	//create the info box contents array
  	$info_box_contents = array();
  	$info_box_contents[] = array('text' => 'Car Install Wizard');

  	//create the html form with combo1(vehicle manufacturer)
  	$form = '<tr><td>' .
			'<select name="combo1" onchange="combo1_selection_changed(this);" style="width:164">';

  	//get the vehicle manufacturer categories for the car install wizard
	$sql = 'SELECT categories.categories_id, categories_description.categories_name ' .
           'FROM categories, categories_description ' .
           'WHERE categories.parent_id = 60545 AND categories.categories_id = categories_description.categories_id ' .
           'ORDER BY categories_name';
  	$result = mysql_query($sql);

  	$count = 0;
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$categories_id[$count] = $row['categories_id'];
		if($_COOKIE["Manufacturer"] == $categories_id[$count]) //get the cookie set on the form submit
			$form .= '<option value="' . $categories_id[$count] . '" selected>' . $row['categories_name'] . '</option>'; //set the selected value
		else
			$form .= '<option value="' . $categories_id[$count] . '">' . $row['categories_name'] . '</option>';
			$count++;
	}
  	mysql_free_result($result);
	$form .= '</select></td></tr>';

  	//js function to set the value of combo2 based on the combo1 selection
	$js = '<script language="javascript" type="text/javascript">' .
				'function combo1_selection_changed(combo1){' .
		    'var combo2_values = new Array();';

  	//get vehicle models for each of the manufacturer categories
	for($c = 0; $c < count($categories_id); $c++)
	{
		$sql = 'SELECT categories.categories_id, categories_description.categories_name ' .
           'FROM categories, categories_description ' .
           'WHERE categories.parent_id = ' . $categories_id[$c] . ' AND categories.categories_id = categories_description.categories_id ' .
           'ORDER BY categories_name';
  	$result = mysql_query($sql);

  	$js .= 'combo2_values[' . $categories_id[$c] . '] = new Array('; //create a js array to store the vehicle models
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
				$js .= '"' . $row['categories_id'] . '|' . $row['categories_name'] . '",'; //store the categories id and name separated by |
		}
		$js = rtrim($js, ","); //remove the comma after the last element in the array
		$js .= ');';
		mysql_free_result($result);
	}

  	$js .= 'var combo1_value = combo1.value;' . //get the selected value from combo1
				 'document.forms["carinstallwizard"].elements["combo2"].options.length=0;' . 	//make sure combo2 is empty
				 'for (var i=0;i<combo2_values[combo1_value].length;i++){' . //loop throught the hard-coded values
				 'var opt = document.createElement("option");' . //dynamically create a new <option> element
				 "opt.setAttribute('value', combo2_values[combo1_value][i].split('|')[0]);" . //set the value to the categories id
				 "opt.innerHTML =combo2_values[combo1_value][i].split('|')[1];" . //set the displayed value to the categories name
  			 'document.forms["carinstallwizard"].elements["combo2"].appendChild(opt);}}</script>'; //append this option to combo2
	print $js;

  	//js function to set the car install wizard form action to the combo box values
	$js = '<script language="javascript" type="text/javascript">function submitform(){' .
	      'setCookie("Manufacturer",document.forms["carinstallwizard"].elements["combo1"].value,"1");' . //store the manufacturer and model selection in a cookie
	      'setCookie("Model",document.forms["carinstallwizard"].elements["combo2"].value,"1");' .
	      'document.forms["carinstallwizard"].action="index.php?cPath=60545_" + document.forms["carinstallwizard"].elements["combo1"].value + "_" + document.forms["carinstallwizard"].elements["combo2"].value;' .
          'document.forms["carinstallwizard"].submit();' .
        '}</script>';
 	print $js; //add the js function to the html

  	//create combo2 (vehicle model) with the default values for the manufacture category
	$form .= '<tr><td><select name="combo2" style="width:164">';

	if(isset($_COOKIE["Manufacturer"])) //if manufacturer has already been selected and stored in cookie
		$categories_id[0] = $_COOKIE["Manufacturer"]; //set selected manufacturer id

	$sql = 'SELECT categories.categories_id, categories_description.categories_name ' .
         'FROM categories, categories_description ' .
         'WHERE categories.parent_id = ' . $categories_id[0] . ' AND categories.categories_id = categories_description.categories_id ' .
         'ORDER BY categories_name';
  	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		if($_COOKIE["Model"] == $row['categories_id'])
			$form .= '<option value="' . $row['categories_id'] . '" selected>' . $row['categories_name'] . '</option>';
		else
			$form .= '<option value="' . $row['categories_id'] . '">' . $row['categories_name'] . '</option>';
	}
	mysql_free_result($result);

	$form .= '</select></td></tr>' .
			     '<tr><td align="right"><a href="javascript: submitform()"><img src="images/form_submit.png" width="76" height="17" border="0" alt=""></a></td></tr>';

  //construct the info box with the html form contents
  new infoBoxHeading($info_box_contents, false, false, false, $column_location);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text' => '<table style="margin-top:-4px; margin-bottom:-7px"><tr><td class="main">Select your vehicle make and model below.</td></tr>' . $form . '</table>');
  new infoBox($info_box_contents);

?>
            </form></td>
          </tr>
<!-- car install wizard eof //-->
