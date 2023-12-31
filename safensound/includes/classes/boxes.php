<?php
/*
  $Id: boxes.php,v 1.33 2003/06/09 22:22:50 hpdl Exp $

  Modified by template-faq.com, Inc 9/2003
  Please visit www.template-faq.com for more designs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '0';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox($contents, $direct_output = false) {
      $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }

  class infoBox extends tableBox {
    function infoBox($contents, $css_suffix = '', $infoBoxcellpadding = '0', $contentcellpadding = '3') {

	#PR $css_suffix = right/left/etc. to identify differend CSS control for component
	#PR $infoBoxcellpadding and $contentcellpadding used to control cellpadding from outside

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents, $css_suffix, $contentcellpadding));
      $this->table_cellpadding = $infoBoxcellpadding;
      $this->table_parameters = 'class="infoBox' . $css_suffix . '"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents, $css_suffix = '', $contentcellpadding = '3') {
      $this->table_cellpadding = $contentcellpadding;
      $this->table_parameters = 'class="infoBoxContents' . $css_suffix . '"';
      $info_box_contents = array();
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }
  }


   class infoBox2 extends tableBox {
    function infoBox2($contents, $css_suffix = '', $infoBoxcellpadding = '0', $contentcellpadding = '3') {

	#PR $css_suffix = right/left/etc. to identify differend CSS control for component
	#PR $infoBoxcellpadding and $contentcellpadding used to control cellpadding from outside

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents, $css_suffix, $contentcellpadding));
      $this->table_cellpadding = $infoBoxcellpadding;
      $this->table_parameters = 'class="infoBox' . $css_suffix . '"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents, $css_suffix = '', $contentcellpadding = '3') {
      $this->table_cellpadding = $contentcellpadding;
      $this->table_parameters = 'class="infoBoxContents2' . $css_suffix . '"';
      $info_box_contents = array();
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }
  }



 class infoBoxW extends tableBox {
    function infoBox($contents, $css_suffix = '', $infoBoxcellpadding = '0', $contentcellpadding = '3') {

	#PR $css_suffix = right/left/etc. to identify differend CSS control for component
	#PR $infoBoxcellpadding and $contentcellpadding used to control cellpadding from outside

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents, $css_suffix, $contentcellpadding));
      $this->table_cellpadding = $infoBoxcellpadding;
      $this->table_parameters = 'class="infoBox' . $css_suffix . '"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents, $css_suffix = '', $contentcellpadding = '3') {
      $this->table_cellpadding = $contentcellpadding;
      $this->table_parameters = 'class="infoBoxContents' . $css_suffix . '"';
      $info_box_contents = array();
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }

    function infoBoxContents2($contents, $css_suffix = '', $contentcellpadding = '3') {
      $this->table_cellpadding = $contentcellpadding;
      $this->table_parameters = 'class="infoBoxContents2' . $css_suffix . '"';
      $info_box_contents = array();
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }
  }


  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false, $css_suffix = '') {
      $this->table_cellpadding = '0';


		if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . 'Specials' . '</a>';
              $info_box_contents = array();
		      $info_box_contents[] = array( array('params' => 'height="10" class="infoBoxHeadingLcorner' . $css_suffix .'"',
		                                         'text' => ' '),
								array('params' => 'height="10" class="infoBoxHeading' . $css_suffix .'"',
		                                         'text' => $right_arrow ),
		                                    array('params' => 'height="10" class="infoBoxHeadingRcorner' . $css_suffix .'"',
                                         'text' => ' '));

      }

      else {
        $right_arrow = '';
      $info_box_contents = array();
      $info_box_contents[] = array( array('params' => 'height="10" class="infoBoxHeadingLcorner' . $css_suffix .'"',
                                         'text' => ' '),
						array('params' => 'height="10" class="infoBoxHeading' . $css_suffix .'"',
                                         'text' => $contents[0]['text'] . '&nbsp;'. $right_arrow ),
                                    array('params' => 'height="10" class="infoBoxHeadingRcorner' . $css_suffix .'"',
                                         'text' => ' '));


     }


      $this->tableBox($info_box_contents, true);
    }
  }

  class contentBox extends tableBox {
    function contentBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="infoBoxContents"';
      return $this->tableBox($contents);
    }
  }

  class newProductsBox extends tableBox {
    function newProductsBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->productsBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="newProductsBox"';
      $this->tableBox($info_box_contents, true);
    }

    function productsBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="productsBoxContents"';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';

      $info_box_contents = array();
      $info_box_contents[] = array(  array('params' => 'height="14" class="infoBoxHeadingLcorner"',
                                         'text' => ' '),
						 array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => $contents[0]['text']),
                                    array('params' => 'height="14" class="infoBoxHeadingRcorner"',
                                         'text' => ' '));

      $this->tableBox($info_box_contents, true);
    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

  class productListingBox extends tableBox {
    function productListingBox($contents) {
      $this->table_parameters = 'class="productListing"';
      $this->tableBox($contents, true);
    }
  }
?>
