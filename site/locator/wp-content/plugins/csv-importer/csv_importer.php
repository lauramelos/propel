<?php
/*
Plugin Name: CSV Importer
Description: Import data as posts from a CSV file. <em>You can reach the author at <a href="mailto:d.v.kobozev@gmail.com">d.v.kobozev@gmail.com</a></em>.
Version: 0.3.7
Author: Denis Kobozev
*/

/**
 * LICENSE: The MIT License {{{
 *
 * Copyright (c) <2009> <Denis Kobozev>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Denis Kobozev <d.v.kobozev@gmail.com>
 * @copyright 2009 Denis Kobozev
 * @license   The MIT License
 * }}}
 */
set_time_limit(0);
class CSVImporterPlugin {
    var $defaults = array(
        'csv_post_title'      => null,
        'csv_post_post'       => " ",
        'csv_post_type'       => null,
        'csv_post_excerpt'    => null,
        'csv_post_date'       => null,
        'csv_post_tags'       => null,
        'csv_post_categories' => null,
        'csv_post_author'     => null,
        'csv_post_slug'       => null,
        'csv_post_parent'     => 0,
    );

    var $log = array();

    /**
     * Determine value of option $name from database, $default value or $params,
     * save it to the db if needed and return it.
     *
     * @param string $name
     * @param mixed  $default
     * @param array  $params
     * @return string
     */
    function process_option($name, $default, $params) {
        if (array_key_exists($name, $params)) {
            $value = stripslashes($params[$name]);
        } elseif (array_key_exists('_'.$name, $params)) {
            // unchecked checkbox value
            $value = stripslashes($params['_'.$name]);
        } else {
            $value = null;
        }
        $stored_value = get_option($name);
        if ($value == null) {
            if ($stored_value === false) {
                if (is_callable($default) &&
                    method_exists($default[0], $default[1])) {
                    $value = call_user_func($default);
                } else {
                    $value = $default;
                }
                add_option($name, $value);
            } else {
                $value = $stored_value;
            }
        } else {
            if ($stored_value === false) {
                add_option($name, $value);
            } elseif ($stored_value != $value) {
                update_option($name, $value);
            }
        }
        return $value;
    }

    /**
     * Plugin's interface
     *
     * @return void
     */
    function form() {
        $opt_draft = $this->process_option('csv_importer_import_as_draft',
            'publish', $_POST);
        $opt_cat = $this->process_option('csv_importer_cat', 0, $_POST);

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $this->post(compact('opt_draft', 'opt_cat'));
        }

        // form HTML {{{
?>

<div class="wrap">
    <h2>Import CSV</h2>
    <form class="add:the-list: validate" method="post" enctype="multipart/form-data">
        <!-- Import as draft -->
        <p>
        <input name="_csv_importer_import_as_draft" type="hidden" value="publish" />
        <label><input name="csv_importer_import_as_draft" type="checkbox" <?php if ('draft' == $opt_draft) { echo 'checked="checked"'; } ?> value="draft" /> Import posts as drafts</label>
        </p>

        <!-- Parent category -->
        <p>Organize into category <?php wp_dropdown_categories(array('show_option_all' => 'Select one ...', 'hide_empty' => 0, 'hierarchical' => 1, 'show_count' => 0, 'name' => 'csv_importer_cat', 'orderby' => 'name', 'selected' => $opt_cat));?><br/>
            <small>This will create new categories inside the category parent you choose.</small></p>

        <!-- File input -->
        <p><label for="csv_import">Upload file:</label><br/>
            <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p>
        <p class="submit"><input type="submit" class="button" name="submit" value="Import" /></p>
    </form>
</div><!-- end wrap -->

<?php
        // end form HTML }}}

    }

    function print_messages() {
        if (!empty($this->log)) {

        // messages HTML {{{
?>

<div class="wrap">
    <?php if (!empty($this->log['error'])): ?>

    <div class="error">

        <?php foreach ($this->log['error'] as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>

    <?php if (!empty($this->log['notice'])): ?>

    <div class="updated fade">

        <?php foreach ($this->log['notice'] as $notice): ?>
            <p><?php echo $notice; ?></p>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>
</div><!-- end wrap -->

<?php
        // end messages HTML }}}

            $this->log = array();
        }
    }

  /**
   * Handle POST submission
   *
   * @param array $options
   * @return void
   */
  function post($options) {
    global $updated, $imported, $new_physician;
      if (empty($_FILES['csv_import']['tmp_name'])) {
          $this->log['error'][] = 'No file uploaded, aborting.';
          $this->print_messages();
          return;
      }

      require_once 'File_CSV_DataSource/DataSource.php';

      $time_start = microtime(true);
      $csv = new File_CSV_DataSource;
      $file = $_FILES['csv_import']['tmp_name'];
      $this->stripBOM($file);

      if (!$csv->load($file)) {
          $this->log['error'][] = 'Failed to load file, aborting.';
          $this->print_messages();
          return;
      }

      // pad shorter rows with empty values
      $csv->symmetrize();

      // WordPress sets the correct timezone for date functions somewhere
      // in the bowels of wp_insert_post(). We need strtotime() to return
      // correct time before the call to wp_insert_post().
      $tz = get_option('timezone_string');
      if ($tz && function_exists('date_default_timezone_set')) {
          date_default_timezone_set($tz);
      }

      $skipped = 0;
      $updated = 0;
      $imported = 0;
      $comments = 0;
      $new_physician = 0;
    
      /*Agregado Telematica*/
      //global $wpdb;
      
      //$wpdb->query("delete from wp_postmeta where post_id in (select ID from wp_posts where post_type='post'");
      //$wpdb->query("delete from wp_posts where post_type='post'");
      
      /*------*/
    
      foreach ($csv->connect() as $csv_data) {
          if ($post_id = $this->create_post($csv_data, $options)) {
              $comments += $this->add_comments($post_id, $csv_data);
              $this->create_custom_fields($post_id, $csv_data);
              if ($new_physician > 0 ) {
                //print_r('send new mail, id: '.$post_id.'<br />');
                send_email($post_id);
                add_post_meta($post_id, 'mystatus', 'sended', true);
                $new_physician = 0;
              }
          } else {
              $skipped++;
          }
      }

      if (file_exists($file)) {
          @unlink($file);
      }
      $total_proc = $imported + $updated;
      $exec_time = microtime(true) - $time_start;
      $this->log['notice'][] .= sprintf("<b>Processed {$total_proc} posts in %.2f seconds.</b>", $exec_time);
      if ($skipped) {
          $this->log['notice'][] = "<b>Skipped {$skipped} posts (most likely due to empty title, body and excerpt).</b>";
      }
      if ($updated) {
          $this->log['notice'][] = "<b>Updated {$updated} posts.</b>";
      }
      if ($imported) {
          $this->log['notice'][] = "<b>Imported {$imported} posts.</b>";
      }
      $this->print_messages();
  }

  function create_post($data, $options) {
      global $updated, $imported, $new_physician;
      extract($options);
      $data = array_merge($this->defaults, $data);
      $type = $data['csv_post_type'] ? $data['csv_post_type'] : 'post';
      $valid_type = (function_exists('post_type_exists') &&
          post_type_exists($type)) || in_array($type, array('post', 'page'));

      if (!$valid_type) {
          $this->log['error']["type-{$type}"] = sprintf(
              'Unknown post type "%s".', $type);
      }

      $new_post = array(
          'post_title'   => convert_chars($data['first_name_1'].' '.$data['last_name_1'].' '.$data['zip']),
          'post_content' => wpautop(convert_chars($data['csv_post_post'])),
          'post_status'  => $opt_draft,
          'post_type'    => $type,
          'post_date'    => $this->parse_date($data['csv_post_date']),
          'post_excerpt' => convert_chars($data['csv_post_excerpt']),
          'post_name'    => $data['csv_post_slug'],
          'post_author'  => $this->get_auth_id($data['csv_post_author']),
          'tax_input'    => $this->get_taxonomies($data),
          'post_parent'  => $data['csv_post_parent'],
          'territory_manager'  => $data['territory_manager'],
      );

      // pages don't have tags or categories
      if ('page' !== $type) {
          $new_post['tags_input'] = $data['csv_post_tags'];

          // Setup categories before inserting - this should make insertion
          // faster, but I don't exactly remember why :) Most likely because
          // we don't assign default cat to post when csv_post_categories
          // is not empty.
          $cats = $this->create_or_get_categories($data, $opt_cat);
          $new_post['post_category'] = $cats['post'];
      }
      // code added to check if post exists, so we'll update it
      $ifpost = get_page_by_title( convert_chars($data['first_name_1'].' '.$data['last_name_1'].' '.$data['zip']),'OBJECT', 'post' );
      if (isset($ifpost)) {
        $new_post['ID'] = $ifpost->ID;
        $id = wp_update_post( $new_post );
        $id = $id ? $id : $ifpost->ID;
        $updated++;
      } else {
      // create! (here the previous existing code to insert it if not exists)
        $id = wp_insert_post($new_post);
        $imported++;
        $new_physician = 1;
      }
      if ('page' !== $type && !$id) {
        // cleanup new categories on failure
        foreach ($cats['cleanup'] as $c) {
          wp_delete_term($c, 'category');
        }
      }
     return $id;
  }

    /**
     * Return an array of category ids for a post.
     *
     * @param string  $data csv_post_categories cell contents
     * @param integer $common_parent_id common parent id for all categories
     * @return array category ids
     */
    function create_or_get_categories($data, $common_parent_id) {
        $ids = array(
            'post' => array(),
            'cleanup' => array(),
        );
        $term_id=0;
        $items = array_map('trim', explode(',', $data['csv_post_categories']));
        foreach ($items as $item) {
            if (is_numeric($item)) {
                if (get_category($item) !== null) {
                    $ids['post'][] = $item;
                } else {
                    $this->log['error'][] = "Category ID {$item} does not exist, skipping.";
                }
            } else {
                $parent_id = $common_parent_id;
                // item can be a single category name or a string such as
                // Parent > Child > Grandchild
                $categories = array_map('trim', explode('>', $item));
                if (count($categories) > 1 && is_numeric($categories[0])) {
                    $parent_id = $categories[0];
                    if (get_category($parent_id) !== null) {
                        // valid id, everything's ok
                        $categories = array_slice($categories, 1);
                    } else {
                        $this->log['error'][] = "Category ID {$parent_id} does not exist, skipping.";
                        continue;
                    }
                }
                foreach ($categories as $category) {
                    if ($category) {
                        $term = $this->term_exists($category, 'category', $parent_id);
                        if ($term) {
                            $term_id = $term['term_id'];
                        } else {
                            $term_id = wp_insert_category(array(
                                'cat_name' => $category,
                                'category_parent' => $parent_id,
                            ));
                            $ids['cleanup'][] = $term_id;
                        }
                        $parent_id = $term_id;
                    }
                }
                $ids['post'][] = $term_id;
            }
        }
        return $ids;
    }

    /**
     * Parse taxonomy data from the file
     *
     * array(
     *      // hierarchical taxonomy name => ID array
     *      'my taxonomy 1' => array(1, 2, 3, ...),
     *      // non-hierarchical taxonomy name => term names string
     *      'my taxonomy 2' => array('term1', 'term2', ...),
     * )
     *
     * @param array $data
     * @return array
     */
    function get_taxonomies($data) {
        $taxonomies = array();
        foreach ($data as $k => $v) {
            if (preg_match('/^csv_ctax_(.*)$/', $k, $matches)) {
                $t_name = $matches[1];
                if ($this->taxonomy_exists($t_name)) {
                    $taxonomies[$t_name] = $this->create_terms($t_name,
                        $data[$k]);
                } else {
                    $this->log['error'][] = "Unknown taxonomy $t_name";
                }
            }
        }
        return $taxonomies;
    }

    /**
     * Return an array of term IDs for hierarchical taxonomies or the original
     * string from CSV for non-hierarchical taxonomies. The original string
     * should have the same format as csv_post_tags.
     *
     * @param string $taxonomy
     * @param string $field
     * @return mixed
     */
    function create_terms($taxonomy, $field) {
        if (is_taxonomy_hierarchical($taxonomy)) {
            $term_ids = array();
            foreach ($this->_parse_tax($field) as $row) {
                list($parent, $child) = $row;
                $parent_ok = true;
                if ($parent) {
                    $parent_info = $this->term_exists($parent, $taxonomy);
                    if (!$parent_info) {
                        // create parent
                        $parent_info = wp_insert_term($parent, $taxonomy);
                    }
                    if (!is_wp_error($parent_info)) {
                        $parent_id = $parent_info['term_id'];
                    } else {
                        // could not find or create parent
                        $parent_ok = false;
                    }
                } else {
                    $parent_id = 0;
                }

                if ($parent_ok) {
                    $child_info = $this->term_exists($child, $taxonomy, $parent_id);
                    if (!$child_info) {
                        // create child
                        $child_info = wp_insert_term($child, $taxonomy,
                            array('parent' => $parent_id));
                    }
                    if (!is_wp_error($child_info)) {
                        $term_ids[] = $child_info['term_id'];
                    }
                }
            }
            return $term_ids;
        } else {
            return $field;
        }
    }

    /**
     * Compatibility wrapper for WordPress term lookup.
     */
    function term_exists($term, $taxonomy = '', $parent = 0) {
        if (function_exists('term_exists')) { // 3.0 or later
            return term_exists($term, $taxonomy, $parent);
        } else {
            return is_term($term, $taxonomy, $parent);
        }
    }

    /**
     * Compatibility wrapper for WordPress taxonomy lookup.
     */
    function taxonomy_exists($taxonomy) {
        if (function_exists('taxonomy_exists')) { // 3.0 or later
            return taxonomy_exists($taxonomy);
        } else {
            return is_taxonomy($taxonomy);
        }
    }

    /**
     * Hierarchical taxonomy fields are tiny CSV files in their own right.
     *
     * @param string $field
     * @return array
     */
    function _parse_tax($field) {
        $data = array();
        if (function_exists('str_getcsv')) { // PHP 5 >= 5.3.0
            $lines = $this->split_lines($field);

            foreach ($lines as $line) {
                $data[] = str_getcsv($line, ',', '"');
            }
        } else {
            // Use temp files for older PHP versions. Reusing the tmp file for
            // the duration of the script might be faster, but not necessarily
            // significant.
            $handle = tmpfile();
            fwrite($handle, $field);
            fseek($handle, 0);

            while (($r = fgetcsv($handle, 999999, ',', '"')) !== false) {
                $data[] = $r;
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Try to split lines of text correctly regardless of the platform the text
     * is coming from.
     */
    function split_lines($text) {
        $lines = preg_split("/(\r\n|\n|\r)/", $text);
        return $lines;
    }

    function add_comments($post_id, $data) {
        // First get a list of the comments for this post
        $comments = array();
        foreach ($data as $k => $v) {
            // comments start with cvs_comment_
            if (    preg_match('/^csv_comment_([^_]+)_(.*)/', $k, $matches) &&
                    $v != '') {
                $comments[$matches[1]] = 1;
            }
        }
        // Sort this list which specifies the order they are inserted, in case
        // that matters somewhere
        ksort($comments);

        // Now go through each comment and insert it. More fields are possible
        // in principle (see docu of wp_insert_comment), but I didn't have data
        // for them so I didn't test them, so I didn't include them.
        $count = 0;
        foreach ($comments as $cid => $v) {
            $new_comment = array(
                'comment_post_ID' => $post_id,
                'comment_approved' => 1,
            );

            if (isset($data["csv_comment_{$cid}_author"])) {
                $new_comment['comment_author'] = convert_chars(
                    $data["csv_comment_{$cid}_author"]);
            }
            if (isset($data["csv_comment_{$cid}_author_email"])) {
                $new_comment['comment_author_email'] = convert_chars(
                    $data["csv_comment_{$cid}_author_email"]);
            }
            if (isset($data["csv_comment_{$cid}_url"])) {
                $new_comment['comment_author_url'] = convert_chars(
                    $data["csv_comment_{$cid}_url"]);
            }
            if (isset($data["csv_comment_{$cid}_content"])) {
                $new_comment['comment_content'] = convert_chars(
                    $data["csv_comment_{$cid}_content"]);
            }
            if (isset($data["csv_comment_{$cid}_date"])) {
                $new_comment['comment_date'] = $this->parse_date(
                    $data["csv_comment_{$cid}_date"]);
            }

            $id = wp_insert_comment($new_comment);
            if ($id) {
                $count++;
            } else {
                $this->log['error'][] = "Could not add comment $cid";
            }
        }
        return $count;
    }

    function create_custom_fields($post_id, $data) {
      $aline='';
      foreach ($data as $k => $v) {
        // anything that doesn't start with csv_ is a custom field
        if (!preg_match('/^csv_/', $k) && $v != '') {
          update_post_meta($post_id, $k, $v);
          if ($k=='address' || $k=='state' || $k=='city' || $k=='zip' ) {
            $aline.=$v.' ';
          }
        }
      }
      update_post_meta($post_id, 'address_line', $aline);
    }

    function get_auth_id($author) {
        if (is_numeric($author)) {
            return $author;
        }
        $author_data = get_userdatabylogin($author);
        return ($author_data) ? $author_data->ID : 0;
    }

    /**
     * Convert date in CSV file to 1999-12-31 23:52:00 format
     *
     * @param string $data
     * @return string
     */
    function parse_date($data) {
        $timestamp = strtotime($data);
        if (false === $timestamp) {
            return '';
        } else {
            return date('Y-m-d H:i:s', $timestamp);
        }
    }

    /**
     * Delete BOM from UTF-8 file.
     *
     * @param string $fname
     * @return void
     */
    function stripBOM($fname) {
        $res = fopen($fname, 'rb');
        if (false !== $res) {
            $bytes = fread($res, 3);
            if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
                $this->log['notice'][] = 'Getting rid of byte order mark...';
                fclose($res);

                $contents = file_get_contents($fname);
                if (false === $contents) {
                    trigger_error('Failed to get file contents.', E_USER_WARNING);
                }
                $contents = substr($contents, 3);
                $success = file_put_contents($fname, $contents);
                if (false === $success) {
                    trigger_error('Failed to put file contents.', E_USER_WARNING);
                }
            } else {
                fclose($res);
            }
        } else {
            $this->log['error'][] = 'Failed to open file, aborting.';
        }
    }
}


function csv_admin_menu() {
    require_once ABSPATH . '/wp-admin/admin.php';
    $plugin = new CSVImporterPlugin;
    add_menu_page('edit.php', 'Import CSV', 'manage_options', __FILE__, array($plugin, 'form'),'http://67.222.18.91/~propel/_img/content/csv.png');
	//add_management_page('edit.php', 'CSV Importer', 'manage_options', __FILE__, array($plugin, 'form'));
}

add_action('admin_menu', 'csv_admin_menu');
/*
function send_new_mail( $post ) {
  //echo $post;
  $post_id = $post;
  $post = get_post($post_id);
  $url= network_site_url();

  $nuevo= get_post_meta($post_id);
  $nzip=$nuevo['zip'][0];
  while (strlen($nzip)<5) $nzip='0'.$nzip;
  
  global $wpdb;
  $coordes  = $wpdb->get_results("select Lon, Lat from zipcodes where Zipcode='{$nzip}'");

      foreach($coordes as $coords)
        $zipcodes = $wpdb->get_results(
          "SELECT Zipcode, ( 3959 * acos( cos( radians( ".$coords->Lat." ) ) * 
          cos( radians( Lat ) ) * cos( radians( Lon ) - radians( ".$coords->Lon." ) ) 
          + sin( radians( ".$coords->Lat." ) ) * sin( radians( Lat ) ) ) ) AS distance 
          FROM zipcodes HAVING distance <= 500 ORDER BY Zipcode, distance");
      
      $emails_T = array();
      $names_T  = array();
      $distan_T = array();
      $distan_S = array();
      $zip_S    = array();
      
      if ($zipcodes) foreach ($zipcodes as $zips) {
        $emails=$wpdb->get_results("SELECT Name, Email, Distance, Zip FROM datauserword 
          WHERE Distance >= '".(round($zips->distance * 100) / 100)."' and enviado=0");
        foreach ($emails as $ema){
          if (!in_array($ema->Email, $emails_T)) {
            $emails_T[]=$ema->Email;
            $names_T[]=$ema->Name;
            $distan_T[]=round($zips->distance * 100) / 100;
            $distan_S[]=$ema->Distance;
            $zip_S[]=$ema->Zip;
          }
        }
      }
      if (count($emails_T)>0) {
        for ($i=1;$i<6;$i++) { 
          if ($nuevo['first_name_'.$i][0] <> '') $docs .="<span 
          style='margin:0 !Important;  padding:0 !Important;
          line-height:18px'>"
          .$nuevo['first_name_'.$i][0]." "
          .$nuevo['last_name_'.$i][0].", "
          .$nuevo['designation_'.$i][0]." "
          ."<br /></span>";
        }
      } 
      $banc=0;
      if (trim($nuevo['address_line'][0]) <>'' ) {
        $location = explode('|',$nuevo['address_line'][0]);
        if ($location[1]<>''){
          $temp = explode(','  , $location[1]);
          $lat = (float) $temp[0];
          $lng = (float) $temp[1];
          $banc=1;
        }
        if (strlen($location[0])>1)
          $direc=$location[0];
        else
          $direc=$nuevo['address_line'][0];
      } else $direc=$nuevo['address'][0].', '.$nuevo['city'][0].', '.$nuevo['state'][0].' + '.$nuevo['zip'][0];
      if ($banc==0){
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$direc."&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
        if ($response['status'] != 'OK') {
          $lat=0;
          $lng=0;
        } else {
          $geometry = $response['results'][0]['geometry'];
          $lat= $geometry['location']['lat'];
          $lng= $geometry['location']['lng'];
        }
      }
      $latlng = $lat . "," . $lng;
      $result = '<style type="text/css">
          a , .enlace{color:#feb61c; !Important; text-decoration:none;}
          a:hover {color:#feb61c; !Important; text-decoration:none}
        </style>
        <table width="576" align="center" ><tr>
        <td><a href="'.$url.'/../" alt="Propel site"><img src="'.$url.'/wp-content/themes/physician/_img/mailtop2.jpg" width="576"/>
        </a></td>
        </tr></table>
        <table width="516" align="center"><tr>
          <td colspan="2">
            <img src="'.$url.'/wp-content/themes/physician/_img/mailapropel.jpg"/>
            <p style="color:#9c9c9c; font-size:12px; font-family:Arial, Helvetica, sans-serif;line-height:22px">
            A new PROPEL physician has been added in your area. Please 
            <a href="'.$url.'/../" style="color:#feb61c; text-decoration:none;">visit our website</a> 
            or contact a physician near you to find out more about PROPEL.</p>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align:center"><img src="'.$url.'/wp-content/themes/physician/_img/mailine.jpg"/></td>
        </tr>
        <tr>
          <td width="222">
            <div style="color:#9c9c9c; font-size:12px; font-family:Helvetica, Arial; font-weight:600;line-height:18px ">'.$docs.'</div><br/>
            <div style="color:#9c9c9c; font-size:12px; font-family:Helvetica, Arial;line-height:18px">'.$nuevo['practice_name'][0].'
            <br />'.$nuevo['address'][0].' <br /> '.$nuevo['city'][0].', '.$nuevo['state'][0].' '.$nuevo['zip'][0].'</div><br/>
            <div style="color:#9c9c9c; font-size:12px; font-family:Helvetica, Arial;line-height:18px">';
            
      if ($nuevo['phone_number'][0]<>'')
        $result .='Tel: <a style="color:#9c9c9c; text-decoration: none;" 
        href="tel:'.$nuevo['phone_number'][0].'" value="+'.$nuevo['phone_number'][0].'" 
        target="_blank" style="color:#fcac00;text-decoration:none">'.$nuevo['phone_number'][0].'</a>';
      // next two lines commented since client asked to not use email any 
      // more.
      //if ($nuevo['email_address'][0]<>'')
      //$result .='<br/> Email: &nbsp;<span style="color:#feb61c !Important;text-decoration:none;" ><a href="mailto:'.$nuevo['email_address'][0].'" class="enlace" style="color:#fcac00;text-decoration:none">'.$nuevo['email_address'][0].'</a></span>';
      if ($nuevo['website'][0]<>'')
      $result .='<br/> Website:&nbsp;<span style="color:#feb61c !Important;text-decoration:none;" ><a href="http://'.$nuevo['website'][0].'" class="enlace"  style="color:#fcac00;text-decoration:none">'.$nuevo['website'][0].'</a></span>';
      
      $result .='</div><br/>
      </td>
      <td width="280" align="right" valign="top">';
      if ($lat<>0)  
        $result .='<a href="http://maps.google.com?ll='.$lat.','.$lng.'&z=16"  target=_blank style="text-decoration:none; border:none;">
        <img src="http://maps.google.com/maps/api/staticmap?center='.$latlng.'&zoom=16&size=260x83&sensor=false&markers=icon:'.$url.'/_img/content/map-marker2.png|'.$latlng.'" /></a>
        <br /> Geo-Loc:'.$latlng;
      else
        $result .='<a href="http://maps.google.com?q='.$direc.'&z=16&markers=icon:'.$url.'/_img/content/map-marker2.png|'.$direc.'" target=_blank  style="text-decoration:none; border:none;" >
        <img src="http://maps.google.com/maps/api/staticmap?center='.$direc.'&zoom=16&size=260x83&sensor=false&markers=icon:'.$url.'/_img/content/map-marker2.png|'.$direc.'" />
        </a>';
      
      $result3='</td></tr>
        </table><p></p>
        <table width="576" align="center" bgcolor="#dcdcdc" cellpadding="6">
          <tr>
          <td width="100" style="padding:0 30px;">
          <a href="'.$url.'/find-propel-physician/" style="color:#b4b4b1; font-size:12px; font-family:Arial, Helvetica, sans-serif; font-weight:400; text-decoration:none;">
          <img src="'.$url.'/wp-content/themes/physician/_img/mailfind.png" height="16" /> Find PROPEL</a></td>
          <td width="100"><a href="'.$url.'/../co-contact_us.html" style="color:#b4b4b1; font-size:12px; font-family:Helvetica, Arial; font-weight:400; text-decoration:none;"> 
          <img src="'.$url.'/wp-content/themes/physician/_img/mailcontact.png" /> Contact Us</a></td>
            <td style="text-align:right; padding-right:30px"><img src="'.$url.'/wp-content/themes/physician/_img/global/intersect_logo.png" /></td>
          </tr>
          <tr>
           <td colspan="3">
             <p style="color:#a8a8a8; font-size:11px; font-family:Helvetica, Arial;padding:0 30px;">
             You are receiving this email because you or someone using this email address signed up to 
             receive a notification when a PROPEL physician is available within "';
      for ($a=0; $a<count($emails_T);$a++){
        $result31 = $result3.$distan_S[$a].'&nbsp;miles" of your area ("'.$zip_S[$a].'").<br /></p>
          <p  style="color:#a8a8a8; font-size:11px; font-family:Helvetica, Arial;padding:0 30px;">Please <a href="'.$url.'/find-propel-physician/?unsuscribe='.$emails_T[$a].'" style="color:#fcac00;text-decoration:none" > click here</a> if you\'d like to stop receiving these notifications.</p>
          </td></tr></table>';
        $result2  = '<p style="color:#9c9c9c; font-size:12px; font-family:Helvetica, Arial ; text-align:right;line-height:18px;">Distance: '.$distan_T[$a].' miles <br /><span style="color:#fcac00;"><a href="http://maps.google.com?q='.$direc.'&hl=en&t=h&mra=ls&z=16&layer=t class="enlace" target="_blank" style="color:#fcac00;text-decoration:none">Directions</a></span></p></td>';

        //$subject  = $names_T[$a].", we found a PROPEL PHYSICIAN near you!";
        $subject = "New PROPEL Physician in your area";
        //die($result.$result2.$result31 );
        add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
        if (wp_mail( $emails_T[$a], $subject, $result.$result2.$result31 )) {
         //$yaenvio= $wpdb->query("update datauserword set enviado=1 where Email='".$emails_T[$a]."'");
         //aca hay que agregar uno al campo count
         $yaenvio= $wpdb->query("update datauserword set count = count + 1  where Email='".$emails_T[$a]."' AND Zip = ".$zip_S[$a]);
        }
      {
         
      }
}*/
?>
