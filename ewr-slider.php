<?php
/**
 * Ewr Light-Weighted Slider Plugin is the simplest slider which is very light-weighted.
 * There is no any option except giving file names. Cos the main aim is being light-weighted.
 *
 * @package Ewr Light-Weighted Slider 
 * @author Evrim Oguz
 * @license GPL-2.0+
 * @link https://evrimoguz.com
 * @copyright 2021 evrimoguz.com All rights reserved.
 *
 *            @wordpress-plugin
 *            Plugin Name: Ewr Light-Weighted Slider Plugin
 *            Plugin URI: https://evrimoguz.com
 *            Description: Ewr Light-Weighted Slider Plugin is the simplest slider which is very light-weighted. There is no any option except giving file names. Cos the main aim is being light-weighted.
 *            Version: 1.0
 *            Author: Evrim Oguz
 *            Author URI: https://evrimoguz.com
 *            Text Domain: ewr-light-weighted-slider
 *            Contributors: Evrim Oguz
 *            License: GPL-2.0+
 *            License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 #create table
register_activation_hook( __FILE__, 'crudOperationsTable');
function crudOperationsTable() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'ewr_slider';
  $sql = "CREATE TABLE `$table_name` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(220) DEFAULT NULL,
  PRIMARY KEY(user_id)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
  ";
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}
#create page
add_action('admin_menu', 'addAdminPageContent');
function addAdminPageContent() {
  add_menu_page('Ewr Slider', 'Ewr Slider', 'manage_options' ,__FILE__, 'crudAdminPage', 'dashicons-wordpress');
}


function crudAdminPage() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'ewr_slider';
  if (isset($_POST['newsubmit'])) {
    $name = $_POST['newname'];
    $wpdb->query("INSERT INTO $table_name(name) VALUES('$name')");
    echo "<script>location.replace('admin.php?page=ewr-light-weighted-slider%2Fewr-slider.php');</script>";
  }
  if (isset($_POST['uptsubmit'])) {
    $id = $_POST['uptid'];
    $name = $_POST['uptname'];
    $wpdb->query("UPDATE $table_name SET name='$name' WHERE user_id='$id'");
    echo "<script>location.replace('admin.php?page=ewr-light-weighted-slider%2Fewr-slider.php');</script>";
  }
  if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $wpdb->query("DELETE FROM $table_name WHERE user_id='$del_id'");
    echo "<script>location.replace('admin.php?page=ewr-light-weighted-slider%2Fewr-slider.php');</script>";
  }
  ?>
  <div class="wrap">
    <h2>Ewr Light-Weighted Slider</h2>
    <table class="wp-list-table widefat striped">
      <thead>
        <tr>
          <th width="75%">File Name</th>
          <th width="25%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <form action="" method="post">
          <tr>
            <td><input type="text" id="newname" name="newname" size='100'></td>
            <td><button id="newsubmit" name="newsubmit" type="submit">INSERT</button></td>
          </tr>
        </form>
        <?php
          $result = $wpdb->get_results("SELECT * FROM $table_name");
          echo 'Total images:';
          echo count($result);
          foreach ($result as $print) {
            echo "
              <tr>
                <td width='75%'>$print->name</td>
                <td width='25%'><a href='admin.php?page=ewr-light-weighted-slider%2Fewr-slider.php&upt=$print->user_id'><button type='button'>UPDATE</button></a> <a href='admin.php?page=ewr-light-weighted-slider%2Fewr-slider.php&del=$print->user_id'><button type='button'>DELETE</button></a></td>
              </tr>
            ";
          }
        ?>
      </tbody>  
    </table>
    <br>
    <br>
    <?php
      if (isset($_GET['upt'])) {
        $upt_id = $_GET['upt'];
        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$upt_id'");
        foreach($result as $print) {
          $name = $print->name;
        }
        echo "
        <table class='wp-list-table widefat striped'>
          <thead>
            <tr>
              <th width='25%'>ID</th>
              <th width='50%'>Name</th>
              <th width='25%'>Actions</th>
            </tr>
          </thead>
          <tbody>
            <form action='' method='post'>
              <tr>
                <td width='25%'>$print->user_id <input type='hidden' id='uptid' name='uptid' value='$print->user_id'></td>
                <td width='50%'><input type='text' id='uptname' name='uptname' size='100' value='$print->name'></td>
                <td width='25%'><button id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=ewr-light-weighted-slider%2Fewr-slider.php'><button type='button'>CANCEL</button></a></td>
              </tr>
            </form>
          </tbody>
        </table>";
      }
    ?>
  </div>
  <?php
}
/*New slider for website*/
include('custom-shortcodes.php');
function ewr_slider() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'ewr_slider';
  ?>
   <style>

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Hide the images by default */
.mySlides {
  display: none;
}

/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  margin-top: -22px;
  padding: 16px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover, .next:hover {
  background-color: rgba(0,0,0,0.8);
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active, .dot:hover {
  background-color: #717171;
}

/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}

@-webkit-keyframes fade {
  from {opacity: .4}
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4}
  to {opacity: 1}
}
    </style>
    <script type="text/javascript">
        var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}

        
    </script>
    <script>
        var indexValue = 0;
        function slideShow(){
          setTimeout(slideShow, 5000);
          plusSlides(1);
        }
        slideShow();
		currentSlide(1);
      </script>
    <!-- Slideshow container -->
<div class="slideshow-container">

  <!-- Full-width images-->
<?php
$result = $wpdb->get_results("SELECT * FROM $table_name");
          foreach ($result as $print) {
            echo "
            <div class='mySlides fade'>
            <img src='$print->name' style='width:100%'></div>
            ";
          }
        ?>
  



  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
<?php
$result = $wpdb->get_results("SELECT * FROM $table_name");
          $i =0;
          foreach ($result as $print) {
            $i += 1;
            echo "
            <span class='dot' onclick='currentSlide($i)'></span>
            ";
          }
        ?>
 
</div>
<?php
}
add_shortcode('ewrslider', 'ewr_slider');
?>