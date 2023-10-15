<?php
/*
  $Id: dbbackup.php,v 1.0 2009/08/20 16:06:03 Graith Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 Graith Internet

  NOT Released under the GNU General Public License
  
  This PHP code automatically backs up a MySQL database (OSC expected)
  and creates SQL files in the backups directory. Once going, it restarts 
  numerous times so that a max of 2M is written and a run time of 2s is max
*/
/******************************************************************************************
function 

*******************************************************************************************/
function restart_in_two_seconds()
{
}
/******************************************************************************************
*******************************************************************************************/

  define('MAX_TIME',2); // 2 seconds elapsed
  define('MAX_FILESIZE',2000000);

  define('PAGE_PARSE_START_TIME', microtime());
  $time_start = time();
  if (function_exists('getmicrotime')) $time_start = getmicrotime();


  
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');
  if (file_exists('includes/configure.php')) include('includes/configure.php');
  if (file_exists('includes/functions/general.php')) include('includes/functions/general.php'); // May need to handle non-OSC at some point	
  if (file_exists('includes/functions/database.php')) include('includes/functions/database.php'); // May need to handle non-OSC at some point	
  $action = (isset($_GET['action'])) ? $_GET['action'] : '';
  
  //
	$t = time();
	$d = date ("Ymd",$t);
  	$big_sql = "db$d".".sql";
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>DB Backup</title>
<?php
	if (!file_exists("backups/$big_sql"))
	{
?>
<meta http-equiv="refresh" content="6" />
<?php
	}
?>
</head>

<body>

<h1>Automated Database Backup</h1>

<?php
	// Check the stage by looking for a progress file in the backups directory
	if (!file_exists('backups') or (!is_dir('backups')) or (!is_writeable('backups')) )
	{
		die ("<h2>Error</h2> no writeable backups directory below this one");
	}
	
	if (!defined('DB_SERVER'))
	{
		die ("<h2>Error</h2> Expected database configuration in includes/configure.php");
	}
/*
** 	
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_DATABASE', 'db217747050');
  define('DB_SERVER_USERNAME', 'dbo217747050');
  define('DB_SERVER_PASSWORD', 'gnqSA585');
  define('HTTP_SERVER', 'http://www.charnwood-catalogue.co.uk'); // eg, http://localhost - should not be empty for productive servers

*/
	tep_db_connect() or die('Unable to connect to database server!');

	
	// See if there's a progress.txt file in the backups directory
	if ( (!file_exists('backups/progress.txt')) and ($action == '') )
	{
		// This must be the start.
		// Create a progress file of what to do
		$fh_progress = fopen('backups/progress.txt','w');
		if (!$fh_progress)
		{
			die ("<h2>Error</h2> Could not create backups/progress.txt for writing");
		}
?>
		<h2>Stage 1</h2>
		<b>Create action list for database</b><br>
<?php		
        $tables_query = tep_db_query('show tables');
        while ($tables = tep_db_fetch_array($tables_query)) 
		{
        	list(,$table) = each($tables);
			print " $table<br>";
			// delete any SQL file with this name
			
			$file = "backups/$table".".sql";
			$file2 = "backups/$table".".txt";
			
			if (file_exists($file))
			{
				unlink ($file);
			}
			if (file_exists($file2))
			{
				unlink ($file2);
			}
			fwrite($fh_progress,"\n$table");
		}
		fclose($fh_progress);
		
		// Wait 2 seconds and then restart
		restart_in_two_seconds();
		exit;
	}
	
	// Here the progress file exists. Read it into an array for spewing back later
	$fh_progress = fopen('backups/progress.txt','r');
	if (!$fh_progress)
	{
		die ("<h2>Error</h2> Could not read backups/progress.txt");
	}
	
	$progress = fread($fh_progress,1000000);
	$command_lines = array_reverse(split("\n",$progress));
	
	fclose($fh_progress);
	$command_idx = sizeof($command_lines)-1;

	while ($command_lines[$command_idx] == "")
	{
		$throw_away = array_pop($command_lines);
		$command_idx = sizeof($command_lines)-1;
	}
	
		
	do
	{
	
		list ($table,$status,$total_rows,$rows_completed) = split("\t",$command_lines[$command_idx]);
		
		if ($status == "done")
		{
//			print "already done $table<br>";
			$command_idx--;
			if ($command_id >= 0) continue;
			print "What to do here?".__LINE__; exit;
		}
		
		// todo - cope with done and partial done
		
		print "<h2>$table</h2>";
		
		print "<strong>$table,$status,$total_rows,$rows_completed</strong><br>";
		
		$file = "backups/$table".".sql";
		$table_list = array();
		// TODO - cope with partially done tables
	
		$new_file = (!file_exists($file));
		$fp = fopen ($file,"a");
		
		$filesz = 0;	// It's filesize for one file we care about
		$schema = ""; $primary_index = "";
			$schema = 'drop table if exists ' . $table . ';' . "\n" .
			'create table ' . $table . ' (' . "\n";
	          $fields_query = tep_db_query("show fields from " . $table);
	          while ($fields = tep_db_fetch_array($fields_query)) {
	            $table_list[] = $fields['Field'];
	
	            $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];
	
	            if (strlen($fields['Default']) > 0) {
	             if ($fields['Default'] == 'CURRENT_TIMESTAMP'){
	                $schema .= ' default ' . $fields['Default'] . '';
	               }else{
	                $schema .= ' default \'' . $fields['Default'] . '\'';
	               }
	             }
	            if ($fields['Null'] != 'YES') $schema .= ' not null';
	
	            if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];
	
	            $schema .= ',' . "\n";
	          }
	
	          $schema = ereg_replace(",\n$", '', $schema);
	// add the keys
	          $index = array();
	          $keys_query = tep_db_query("show keys from " . $table);
	          while ($keys = tep_db_fetch_array($keys_query)) {
	            $kname = $keys['Key_name'];
	
	            if (!isset($index[$kname])) {
	              $index[$kname] = array('unique' => !$keys['Non_unique'],
	                                     'columns' => array());
	            }
	
	            $index[$kname]['columns'][] = $keys['Column_name'];
	          }
	
	          while (list($kname, $info) = each($index)) {
	            $schema .= ',' . "\n";
	
	            $columns = implode($info['columns'], ', ');
	
	            if ($kname == 'PRIMARY') {
	              $schema .= '  PRIMARY KEY (' . $columns . ')';
				  $primary_index = $info['columns'][0];
	            } elseif ($info['unique']) {
	              $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
	            } else {
	              $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
	            }
	          }
	
	          $schema .= "\n" . ');' . "\n\n";
			  
			  
		if ($new_file)
		{	
			$table_query = "select count(*) as cnt from " . $table;
			$rows_query = tep_db_query($table_query);
			$rows = tep_db_fetch_array($rows_query);
			$table_size = $rows['cnt'];
			$status = "writing";
			$total_rows = $table_size;
			$rows_completed = 0;
			$schema .= "\n\n# $table_size rows to write\n\n";
			fputs($fp, $schema);
			$limit = " ";
		} else
		{
			$schema = "";
			print "Continue writing to $file<br>";
			$rows_remaining = $total_rows - $rows_completed;
			$rows_completed -= 0; // don't go back any
			$limit = " LIMIT $rows_completed, $rows_remaining ";
		}
		
	
		// TODO - need to do partial query for data if already started
		$filesz += strlen($schema);
		
		// Get data, maybe starting part way through
		$table_query = "select " . implode(", ", $table_list) . " from " . $table. $limit;
		
		$time_now = time();
		if (function_exists('getmicrotime')) $time_now = getmicrotime();
		$elapsed_time = $time_now - $time_start;
	
		print "$table_query<br>";
		
		$rows_query = tep_db_query($table_query);
	
		
			$rowdone = 0;
			$break_early = false;
			while ($rows = tep_db_fetch_array($rows_query)) {
	            $schema = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';
	
	            reset($table_list);
	            while (list(,$i) = each($table_list)) {
	              if (!isset($rows[$i])) {
	                $schema .= 'NULL, ';
	              } elseif (tep_not_null($rows[$i])) {
	                $row = addslashes($rows[$i]);
	                $row = ereg_replace("\n#", "\n".'\#', $row);
	
	                $schema .= '\'' . $row . '\', ';
	              } else {
	                $schema .= '\'\', ';
	              }
	            }
	
	            $schema = ereg_replace(', $', '', $schema) . ');' . "\n";
				$filesz += strlen($schema);
	            fputs($fp, $schema);
	
				$rowdone++;
				
				if (function_exists('getmicrotime')) $time_now = getmicrotime();
				$elapsed_time = $time_now - $time_start;
				if (($elapsed_time > MAX_TIME) or ($filesz > MAX_FILESIZE)) 
				{
					$break_early = true;
					break;			
				}
			}
	
	
		if ($break_early)
		{
			$rows_completed += $rowdone;
	        fputs($fp, "\n\n# Break early ($rows_completed rows done)\n");
		} else
		{
			$rows_completed += $rowdone;
	        fputs($fp, "\n\n# Completed ($rows_completed completed out of $total_rows)\n");
		}
		$status = ($rows_completed == $total_rows) ? "done" : "continue";
			
		fclose($fp);
		print "$table $status $total_rows $rows_completed<br>";
	//	list ($table,$status,$total_rows,$rows_completed) = split("\t",$command_lines[$command_idx]);
		$command_lines[$command_idx] = "$table\t$status\t$total_rows\t$rows_completed";
		
		$command_idx--;
		if ($break_early) break;
	} while ($command_idx >= 0);

	$fh_progress = fopen('backups/progress.txt','w');
	if (!$fh_progress)
	{
		die ("<h2>Error</h2> Could not create backups/progress.txt for writing");
	}
	$progress = implode("\n",array_reverse($command_lines));
	fwrite($fh_progress,$progress);
	fclose($fh_progress);

	
	if ($break_early)
	{
		print "Need to tidy up and break early<br>";
	
		$time_now = time();
		if (function_exists('getmicrotime')) $time_now = getmicrotime();
		$elapsed_time = $time_now - $time_start;
//		print "<br>elapsed_time = ".$elapsed_time."<br>";
		// Reform progress.txt file from the commands
		
		
		// Wait 2 seconds and then restart
		restart_in_two_seconds();
		exit;
	} else
	{
		print "<strong>Finished</strong><br><table>";
		
		// See what the total SQL filesize will be.
		$total_filesize = 0;
		$tables = array_reverse($command_lines);
		foreach ($tables as $line)
		{
			list ($table,$status,$total_rows,$rows_completed) = split("\t",$line);
			$file = "backups/$table".".sql";
			$fs = filesize ($file);
			print "<tr><td>$table</td><td align=right>".number_format($fs)."</td></tr>";
			$total_filesize += $fs;
			
		}
		print "<tr><th>Total</th><td align=right>".number_format($total_filesize)."</td></tr>";
		
		print "</table>";
		if (!file_exists("backups/$big_sql"))
		{
			print "Creating $big_sql<br>";
			$fh_big = fopen("backups/$big_sql",'w');
			if (!$fh_big)
			{
				die ("<h2>Error</h2> Could not create backups/$big_sql for writing");
			}
			
			foreach ($tables as $line)
			{
				list ($table,$status,$total_rows,$rows_completed) = split("\t",$line);
				$file = "backups/$table".".sql";
				$fh_read = fopen ($file,'r');
				do
				{
					$contents = fread($fh_read,1000000);
					fwrite($fh_big,$contents);
				} while (!feof($fh_read));
				fwrite($fh_big,"\n");
				fclose($fh_read);
			}
			
			fclose($fh_big);
		}
		
	}	
?>

<p></p>

</body>
</html>
