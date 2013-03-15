<?

/*

Plugin Name: CNP Callout
Plugin URI: http://clarknikdelpowell.com/wordpress/cnp-callout
Description: Gives you the ability to set page-specific callout box content
Author: Glenn Welser
Author URI: http://clarknikdelpowell.com
Version: 1.0

Copyright 2013 Glenn Welser  (email : glenn@clarknikdelpowell.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

//  CALLOUT  ///////////////////////////////////////////////////////

add_action( 'load-post.php', 'cnp_callout_setup' );
add_action( 'load-post-new.php', 'cnp_callout_setup' );

function cnp_callout_setup() {
	add_action( 'add_meta_boxes', 'cnp_callout_add_meta_boxes' );
	add_action( 'save_post', 'cnp_callout_save', 1, 2);
	
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
}

function cnp_callout_add_meta_boxes() {
	add_meta_box(
		'cnp_callout_meta'
	,	'Callout'
	,	'cnp_callout_meta_box'
	,	'post'
	);
	add_meta_box(
		'cnp_callout_meta'
	,	'Callout'
	,	'cnp_callout_meta_box'
	,	'page'
	);
}

function cnp_callout_meta_box() {
	global $post;
	
	$callouttitle   = get_post_meta( $post->ID, 'cnp_callout_title', true );
	$calloutimage   = get_post_meta( $post->ID, 'cnp_callout_image', true );
	$calloutcontent = get_post_meta( $post->ID, 'cnp_callout_content', true );
	$calloutlink 	 = get_post_meta( $post->ID, 'cnp_callout_link', true );

	echo '<p><label>Title</label><br /><input type="text" name="cnp_callout_title" id="cnp_callout_title" value="'.$callouttitle.'" style="width: 100%" /></p>';
	
	echo '<p><label>Image</label><br /></p>';
	echo '<p><a id ="callout_attach" href="#" title="Add Media">Upload/Insert Image</a></p>';
	echo '<input type="hidden" name="cnp_callout_image" id="cnp_callout_image" value="'.$calloutimage.'" />';
	echo '<div id="callout_attachment_wrap"';
	if ($calloutimage)
		echo ' style="display:block;"';
	else
		echo ' style="display:none;"';
	echo '><div id="callout_attachment">';
	if ($calloutimage) {
		echo '<img style="max-width:100%;" src="'.$calloutimage.'" />';
	}
	echo '</div><a id="callout_detach" href="#">Remove</a>';
	echo'</div>';
	
	echo '<p><label>Content</label><br /><textarea name="cnp_callout_content" id="cnp_callout_content" style="width:100%;" rows="5">'.$calloutcontent.'</textarea></p>';

	echo '<p><label>Link URL</label><br /><input type="text" name="cnp_callout_link" id="cnp_callout_link" value="'.$calloutlink.'" style="width: 100%" /></p>';

	
	echo '<script>
	window.restore_send_to_editor = window.send_to_editor;
	jQuery(document).ready(function($) {
		$(\'#callout_attach\').click(function() {
			tb_show(\'Upload/Insert Image\',\'media-upload.php?type=image&TB_iframe=true\');
			window.send_to_editor = function(html) {
				img = $(\'img\',html);
				imgurl = img.attr(\'src\');
				$(\'#cnp_callout_image\').val(imgurl);
				$(\'#callout_attachment_wrap\').show();
				$(\'#callout_attachment\').html(img);
				tb_remove();
				window.send_to_editor = window.restore_send_to_editor;
			};
			return false;
		});
		$(\'#callout_detach\').click(function() {
			$(\'#cnp_callout_image\').val(\'\');
			$(\'#callout_attachment_wrap\').hide();
			$(\'#callout_attachment\').html(\'\');
		});
	});
	</script>';
}

function cnp_callout_save($post_id, $post) {
	
	update_post_meta( $post_id, 'cnp_callout_title', $_POST['cnp_callout_title']);
	update_post_meta( $post_id, 'cnp_callout_image', $_POST['cnp_callout_image']);
	update_post_meta( $post_id, 'cnp_callout_content', $_POST['cnp_callout_content']);
	update_post_meta( $post_id, 'cnp_callout_link', $_POST['cnp_callout_link']);
	
}

?>