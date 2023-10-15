<?php
/*
  $Id: sitemap_seo.php,v 1.0 2009/01/02 
  written by Jack_mcs at www.osocmmerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/
 
function AddMissingBoxes($locn, $languages, $excludeList, $linksArray) //add all boxes to the database if none exists
{
  $cwd = getcwd();
  chdir ($locn);

  foreach (glob("*.php") as $filename) 
  {
    if (! in_array($filename, $excludeList))
    {
      for ($i = 0; $i < count($languages); ++$i)
      {
         $boxName = trim(substr($filename, 0, strpos($filename, ".")));   
         
         $boxes_query = tep_db_query("select count(*) as total from " . TABLE_SITEMAP_SEO_BOXES . " where box_file_name LIKE '" . $filename . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
         $boxes = tep_db_fetch_array($boxes_query);     
         
         if ($boxes['total'] == 0)  //then new entry 
           tep_db_query("insert into " . TABLE_SITEMAP_SEO_BOXES . " (box_file_name, box_page_name, language_id) values ('" . tep_db_input($filename) . "', '" . tep_db_input($boxName) . "', '" . (int)$languages[$i]['id'] . "')");
      }
      
      AddMissingLinks($filename, $languages, $linksArray);
    }
  }
 
  chdir ($cwd);  
}

function AddMissingLinks($filename, $languages)
{
//  $linksArray = GetBoxLinks($filename, $languages);
  $id = tep_db_insert_id();

  for ($l = 0; $l < count($linksArray); ++$l)
  {
    for ($i = 0; $i < count($languages); ++$i)
    {
      $boxes_query = tep_db_query("select count(*) as total from " . TABLE_SITEMAP_SEO_BOX_LINKS . " where box_file_name LIKE '" . $linksArray[$l]['box'] . "' and page_link_name LIKE '" . addslashes($linksArray[$l]['link']) . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
      $boxes = tep_db_fetch_array($boxes_query);  
      
      if ($boxes['total'] == 0)  //then new entry 
        tep_db_query("insert into " . TABLE_SITEMAP_SEO_BOX_LINKS . " (box_link_id, box_file_name, page_link_name, language_id) values ('" . $id . "', '" . tep_db_input($linksArray[$l]['box']) . "', '" . tep_db_input($linksArray[$l]['link']) . "', '" . (int)$languages[$i]['id'] . "')");
    }
  }
}
 
function getFileName($file, $define)        //retrieve the defined filename
{ 
 $fp = file($file);
  for ($idx = 0; $idx < count($fp); ++$idx)
  {
     if (strpos($fp[$idx], $define) !== FALSE)
     {
         $parts = explode("'", $fp[$idx]);   
         return $parts[3];     
     }
  }    
  return false;
}

function getBoxText($file, $define)          //retrieve the defined box name
{
  $fp = file($file);
  for ($idx = 0; $idx < count($fp); ++$idx)
  {
     if (strpos($fp[$idx], "define") === FALSE)
       continue;

     if (strpos($fp[$idx], $define) !== FALSE)
     {
         $parts = explode("'", $fp[$idx]);
         return $parts[3];     
     }
  }
  return NULL;
}
  
function GetBoxLinks($box, $languages)
{
  $boxLinks = array();

  if ($box != TEXT_MAKE_BOX_SELECTION)
  {
    $end =  (substr(DIR_FS_CATALOG, 0, -1) !== '/') ? '/' : '';
    $path = DIR_FS_CATALOG . $end . DIR_WS_BOXES . '/'. $box;
    $lines = array();

    if (GetFilesArray($path, $lines))
    {
      for ($i = 0, $idx = 0; $i < count($lines); ++$i)
      {
        if (eregi("<a(.*)</a", $lines[$i], $out))
        {
          if (eregi("tep_href_link((.*))", $out[1], $locn))
          {
            $boxLinks[$idx]['box'] = $box;

            $link = trim(substr($locn[1], strpos($locn[1], "(") + 1, strpos($locn[1], ")") - 1));
            $linkParts = explode(",", $link); //only use the first part of the link - removes SSL, etc.
            $link = $linkParts[0];            

            if ($link === strtoupper($link))
              $boxLinks[$idx]['link'] = $link;
            else                                 //link contains something like index.php, 'cPath=23'  
            {
              $parts = explode("'", $link);      //so break it up and try to put it back together so it can be used
              $newLink = '';
              for ($p = 0; $p < count($parts); ++$p)
              {
                if (empty($parts[$p]) || strpos($parts[$p], ",") !== FALSE)
                 continue;
                $newLink = (empty($newLink)) ? $parts[$p] : $newLink . '?'. $parts[$p];  
              }
              $boxLinks[$idx]['link'] = $newLink;
            } 

            $text = substr($locn[1], strpos($locn[1], ">") + 1);

            if (strpos($text, ".") !== FALSE)   
              $boxLinks[$idx]['text'] = trim(substr($text, strpos($text, ".") + 1, strrpos($text, ".")), " .'");
            else
              $boxLinks[$idx]['text'] = trim($text, " - '");
   
            $idx++;             
          }
        }
      }
 
      /************ Add additional links added by user - may not be valid ************/
      $boxes_query = tep_db_query("select box_file_name, page_link_name, pseudo_page_link_name, anchor_text, excluded_link, sort_order from " . TABLE_SITEMAP_SEO_BOX_LINKS . " where  box_file_name LIKE '" . $box . "'");
      while ($boxes = tep_db_fetch_array($boxes_query)) 
      { 
        if (in_multi_array($boxes['box_file_name'], $boxLinks))
        {
          if (tep_not_null($boxes['page_link_name']) && ! in_multi_array($boxes['page_link_name'], $boxLinks))
          {
            $boxLinks[$idx]['box'] = $boxes['box_file_name'];
            $boxLinks[$idx]['link'] = $boxes['page_link_name'];
            $boxLinks[$idx]['text'] = $boxes['pseudo_page_link_name'];
            $idx++;
          }  
        }
      }
    }  
  }
 
  return $boxLinks;
}
 
function GetBoxList($locn, $excludeList) //return a list for the individual section
{ 
  $boxFiles = array();
  $cwd = getcwd();
  chdir ($locn);

  $boxFiles[] = array('id' => TEXT_MAKE_BOX_SELECTION, 'text' => TEXT_MAKE_BOX_SELECTION);

  foreach (glob("*.php") as $filename) 
  {
    if (! in_array($filename, $excludeList))
      $boxFiles[] = array('id' => $filename, 'text' => $filename);
  }

  chdir ($cwd);
  return $boxFiles;  
}

function GetBoxListGroup($locn, $languages, $excludeList) //return a list of all of the boxes for main control
{ 
  $boxFiles = array();
  $cwd = getcwd();
  chdir ($locn);

  foreach (glob("*.php") as $filename) 
  {
    if (! in_array($filename, $excludeList))
    {
      for ($i = 0; $i < count($languages); ++$i)
      {
        $boxes_query = tep_db_query("select excluded_box, registered_box from " . TABLE_SITEMAP_SEO_BOXES . " where box_file_name LIKE '" . $filename . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        $boxes = tep_db_fetch_array($boxes_query);     
        $boxFileName = $filename . ' - '. $languages[$i]['name'];
        $boxFiles[] = array('id' => $boxFileName, 'text' => $boxFileName, 'excluded_box' => $boxes['excluded_box'], 'registered_box' => $boxes['registered_box']);
      }
    }
  }
 
  chdir ($cwd);
  return $boxFiles;  
}
 
function GetFilesArray($path, &$lines) //use curl if possible to read in site information
{
  global $messageStack;
  
  $lines = array();
  
  if (function_exists('curl_init')) 
  {
    $path = $admin_dir . '/' . $path;
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    curl_setopt ($ch, CURLOPT_URL, $path);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    $lines = explode("\n", $file_contents);
  }
  else
  {
    if ($fd = @fopen ($path, "r"))
    {
      while (!feof ($fd)) 
      {
        $buffer = fgets($fd, 4096);
        $lines[] = $buffer;
      }
      fclose ($fd);   
    } 
  }
  
  if (empty($lines))
  {
     $messageStack->add_session(sprintf(ERROR_FAILED_READ_FILE, $path), 'error');
     return false;
  }

  return true;  
}

function GetLinkSettings($filename, $linkArray, $languageID) //get the settings for this file and language
{
  $linkSettings = array();
 
  for ($b = 0; $b < count($linkArray); ++$b)
  {
    $boxes_query = tep_db_query("select pseudo_page_link_name, anchor_text, excluded_link, registered_link, sort_order from " . TABLE_SITEMAP_SEO_BOX_LINKS . " where box_file_name LIKE '" . $filename . "' and page_link_name LIKE '" . addslashes($linkArray[$b]['link']) . "' and language_id = '" . (int)$languageID . "'");
    while ($boxes = tep_db_fetch_array($boxes_query))
    {
      $linkSettings[] = array('pseudo_page_link_name' => (tep_not_null($boxes['pseudo_page_link_name']) ? $boxes['pseudo_page_link_name'] : ''),
                              'anchor_text' => $boxes['anchor_text'],
                              'excluded' => ($boxes['excluded_link'] ? 'checked' : ''),
                              'registered' => ($boxes['registered_link'] ? 'checked' : ''),
                              'sortorder' => $boxes['sort_order']);  
    }
  }

  return $linkSettings;  
}

function GetNameFromDefine($define, $languageName) //return the defined name
{
  if (tep_not_null($define) && $define === strtoupper($define))
  {
    $end =  (substr(DIR_FS_CATALOG, 0, -1) !== '/') ? '/' : '';
    $path = DIR_FS_CATALOG . $end . DIR_WS_LANGUAGES . $languageName . '.php';
    $lines = array();
  
    if (GetFilesArray($path, $lines))
    { 
      for ($i = 0; $i < count($lines); ++$i)
      {
        if (strpos($lines[$i], $define) !== FALSE)
        {
          $parts = explode(",", $lines[$i]);
          $parts[1] = str_replace("\'", "xyz", $parts[1]); //save any required apostrophes before stipping
          $name = explode("'", $parts[1]);                 //strip the apostrophes  
          $name[1] = str_replace("xyz", "'", $name[1]);    //add back the required ones
          return trim($name[1]);
        }
      }
    }
  }
  
  return $define;
}

function GetPagesArray($locn, $languages, $excludeList)
{
  $cwd = getcwd();
  $pagesArray = array();
  $end =  (substr(DIR_FS_CATALOG, 0, -1) !== '/') ? '/' : '';

  for ($i = 0; $i < count($languages); ++$i)
  {    
    $path = $locn . $languages[$i]['directory'];
    chdir ($path);

    foreach (glob("*.php") as $filename)   //don't recurse to aboive sub-directories from being read 
    {
      if (IsViewable(DIR_FS_CATALOG . $end . $filename))
      {
        $displayName = ucwords(str_replace("_", " ", substr($filename, 0, strpos($filename, ".")))); //remove the .php and underscores
        $pagesArray[$languages[$i]['id']][] = array('filename' => $filename,
                                                    'display_name' => $displayName);
      }                                                
    }    
  }    

  InitialDatabasePageFill($pagesArray, $languages, $excludeList);  //if the page table is empty, fill it
  chdir ($cwd);
  return $pagesArray;
}

function GetPageSettings($pagesArray, $languageID) //get the settings for this file and language
{
  $pageSettings = array();
  
  for ($b = 0; $b < count($pagesArray); ++$b)
  {
    $pages_query = tep_db_query("select alternate_page_name, anchor_text, excluded_page, registered_only, sort_order from " . TABLE_SITEMAP_SEO_PAGES . " where page_file_name LIKE '" . $pagesArray[$b]['filename'] . "' and language_id = '" . (int)$languageID . "'");
    while ($pages = tep_db_fetch_array($pages_query))
    {
      $pageSettings[] = array('alternate_page_name' => $pages['alternate_page_name'],
                              'anchor_text' => $pages['anchor_text'],
                              'excluded' => ($pages['excluded_page'] ? 'checked' : ''),
                              'registered_only' => ($pages['registered_only'] ? 'checked' : ''),
                              'sortorder' => $pages['sort_order']);  
    }
  }
 
  return $pageSettings;  
}

function InitialDatabasePageFill($pagesArray, $languages, $excludeList) //fill in the pages table if it is empty
{
  $pages_query = tep_db_query("select count(*) as total from " . TABLE_SITEMAP_SEO_PAGES);
  $pages = tep_db_fetch_array($pages_query);

  if ($pages['total'] == 0)
  {
    for ($i = 0, $n = 1; $i < count($languages) + 1; ++$i, ++$n)
    {
      for ($p = 0; $p < count($pagesArray[$n]); ++$p)
      {
        $exclude = in_array($pagesArray[$n][$p]['filename'], $excludeList);
        tep_db_query("insert into " . TABLE_SITEMAP_SEO_PAGES . " (page_file_name, excluded_page, language_id) values ('" . tep_db_input($pagesArray[$n][$p]['filename']) . "', '" . (int)$exclude . "', '" . (int)$languages[$i]['id'] . "')");
      }  
    }  
  }  
}

function in_multi_array($needle, $haystack) 
{
  $in_multi_array = false;
  if(in_array($needle, $haystack)) 
  {
    $in_multi_array = true;
  } 
  else 
  {
    foreach ($haystack as $key => $val) 
    {
      if(is_array($val)) 
      {
        if (in_multi_array($needle, $val)) 
        {
          $in_multi_array = true;
          break;
        }
      }
    }
  }
  return $in_multi_array;
}
 
function IsViewable($file)
{
  if (($fp = @file($file)))
  {
    for ($idx = 0; $idx < count($fp); ++$idx)
    {
       if (strpos($fp[$idx], "<head>") !== FALSE)
         return true;
    }
  }  
  return false;
}
 
// Output a form muliple select menu
if (! function_exists('SMMultiSelectMenu'))
{
  function SMMultiSelectMenu($name, $values, $selected_vals, $params = '', $required = false)
  {
    $field = '<select name="' . $name . '"';
    if ($params) $field .= ' ' . $params;
    $field .= ' multiple="multiple">';
    for ($i=0; $i<sizeof($values); ++$i)
    {
      if ($values[$i]['id'])
      {
        $field .= '<option value="' . $values[$i]['id'] . '"';
        if ( ((strlen($values[$i]['id']) > 0) && ($GLOBALS[$name] == $values[$i]['id'])) )
        {
          $field .= '  selected="selected"';
        }
        else if (tep_not_null($selected_vals))
        {
  	    for ($j=0; $j<sizeof($selected_vals); ++$j)
          {
  		   if ($selected_vals[$j]['id'] == $values[$i]['id'])
  		   {
  	         $field .= ' selected="selected"';
  		   }
  	    }
        }
      }
      if ($values[$i]['excluded_box'] == 1 && $values[$i]['registered_box'] == 1)
        $field .= ' style="background-color: 4CC417;" ';
      else if ($values[$i]['excluded_box'] == 1)
        $field .= ' style="background-color: #F778A1;" ';
      else if ($values[$i]['registered_box'] == 1)
        $field .= ' style="background-color: #66FFFF;" ';
       
      $field .= '>' . $values[$i]['text'] . '</option>';
    }
    $field .= '</select>';
  
    if ($required) $field .= TEXT_FIELD_REQUIRED;
  
    return $field;
  } 
}
?>