<?php
/*
Plugin Name: Share my Tweet
Plugin URI: #
Description: A twitter widget which shows your articles across wordpress blogging platform
Version: 1.0
Author: Gadgets Choose
Author URI: http://HowToTweetss.gadgets-code.com
*/


/* Copyright 2010 Gadgets Choose (email : morning131@hotmail.com) 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, please visit <http://www.gnu.org/licenses/>.
*/

function sharetweets_init() {

  register_widget('share_mytweet');

 } 

  add_action('widgets_init', 'sharetweets_init');


 class share_mytweet extends WP_Widget {

  function share_mytweet() {
   $widget_ops = array('classname'=>'share_mytweet', 'description'=>'Share my Tweets');
   parent::WP_Widget('share-my-tweet', __('Share my Tweets'), $widget_ops); 
  }

  function widget($args, $instance) {
    
    $tweetname = $instance['tweetname'];
    $post_title= wp_title( '',false, '' );
    $articleUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
   
    //Do the la la part...

    $url = "http://search.twitter.com/search.json?q=%40WebGadgets+filter:links&rpp=5";
    $curlHandler = curl_init();
    curl_setopt($curlHandler,CURLOPT_URL,"$url");
    curl_setopt($curlHandler,CURLOPT_RETURNTRANSFER,1);
    $apiResponse = curl_exec($curlHandler);

    curl_close($curlHandler);

    $json = json_decode($apiResponse);

    extract($args);
    echo $before_widget;
   
    echo "<div style=\"width:200px;float:left;font-family: fantasy, verdana, sans-serif; font-size:1em; font-weight:bold;color:grey;background-color:white;\">
          $tweetname<br/>
          <script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script>
          <a href=\"http://twitter.com/share\" class=\"twitter-share-button\" 
          data-text=\"@WebGadgets $post_title\"
          data-url=$articleUrl
          data-count=\"none\"
          data-related=\"WebGadgets\" >Tweet</a><br/>";


    if($json->results) {

    
      foreach($json->results as $result){
 
      $create = $result->created_at;
      $resultText = $result->text;
      $resultUser = $result->from_user;
      $pic = $result->profile_image_url;
 
      $pattern = preg_match_all("/\s+(http\:\/\/)\w+\.{0,1}\w+\.{0,1}\/{0,1}\w+\/{0,1}\w*/",$resultText,$match1);
 
      $val = $match1[0][0];

      $val=trim($val);
      $newUrl = " <a href=$val style=\"text-decoration:underline;\" target=\"_blank\">$val</a> ";
 
      $oldUrl = "/\s+(http\:\/\/)\w+\.{0,1}\w+\.{0,1}\/{0,1}\w+\/{0,1}\w*/";
 
      $resultText = preg_replace($oldUrl,$newUrl,$resultText);
 

      $pattern = preg_match_all("/\s*\@\w+/",$resultText,$match);
      $value = $match[0][0];
      $trim = str_replace("@","",$value);
      $trim = trim($trim);
      $newScreenname = "&nbsp;@<a style=\"text-decoration:underline;\" href= \"http://twitter.com/$trim\" target=\"_blank\">$trim</a>&nbsp;";

      $oldScreenname = "/\s*\@\w+/";
 
      $resultText = preg_replace($oldScreenname,$newScreenname,$resultText);

 
      //Serve the meal to the customers

      echo "<img style=\"width:39px;height:39px;\" src=$pic style=\"float:left; margin:13px 5px 0 0; \"/>&nbsp;
            <a href=\"http://twitter.com/$resultUser\" style=\"font-size:1.2em;color:orange;text-decoration:none;\" target=\"_blank\" >$resultUser</a><br/>
            <p style=\"text-align:justify;float:left;padding:3px;font-size:0.8em\">$resultText Tweet Created at :  $create
            </p> 
            ";
      }}
      echo "</div>";
      echo $after_widget;
    
   }  
 

   function form($instance) {
     $instance = wp_parse_args( (array) $instance, array(
                   'tweetname' => 'Share Tweet'
                   ));
?>
           <p>
           <label for="<?php echo $this->get_field_id('tweetname');?>">Widget Title :</label>
           <input class="widefat" id="<?php echo $this->get_field_id('tweetname');?>" name="<?php echo $this->get_field_name('tweetname');?>"
            type="text" value="<?php echo $instance['tweetname'];?>"/></p> 
     
<?php 
   }
  

   function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['tweetname'] = strip_tags($new_instance['tweetname']);
    
    return $instance;
  }
 }    
?>