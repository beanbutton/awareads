<?php
/**
 * Meta Keywords Editor Module
 * 
 * @since 4.0
 */

if (class_exists('SU_Module')) {

class SU_MetaKeywords extends SU_Module {
	
	function get_module_title() { return __('Meta Keywords Editor', 'seo-ultimate'); }
	function get_menu_title()   { return __('Meta Keywords', 'seo-ultimate'); }
	function get_settings_key() { return 'meta'; }
	function get_default_status() { return SU_MODULE_DISABLED; }
	
	function init() {
		add_action('su_head', array(&$this, 'head_tag_output'));
		add_filter('su_postmeta_help', array(&$this, 'postmeta_help'), 20);
	}
	
	function get_default_settings() {
		return array(
			  'auto_keywords_posttype_post_words_value' => 3
			, 'auto_keywords_posttype_page_words_value' => 3
			, 'auto_keywords_posttype_attachment_words_value' => 3
		);
	}
	
	function get_admin_page_tabs() {
		return array_merge(
			  array(
				  array('title' => __('Default Values', 'seo-ultimate'), 'id' => 'su-default-values', 'callback' => 'defaults_tab')
				, array('title' => __('Blog Homepage', 'seo-ultimate'), 'id' => 'su-blog-homepage', 'callback' => 'home_tab')
				)
			, $this->get_meta_edit_tabs(array(
				  'type' => 'textbox'
				, 'name' => 'keywords'
				, 'term_settings_key' => 'taxonomy_keywords'
				, 'label' => __('Meta Keywords', 'seo-ultimate')
			))
		);
	}
	
	function defaults_tab() {
		$this->admin_form_table_start();
		
		$posttypenames = suwp::get_post_type_names();
		foreach ($posttypenames as $posttypename) {
			$posttype = get_post_type_object($posttypename);
			$posttypelabel = $posttype->labels->name;
			
			$checkboxes = array();
			
			if (post_type_supports($posttypename, 'editor'))
				$checkboxes["auto_keywords_posttype_{$posttypename}_words"] = __('The %d most commonly-used words', 'seo-ultimate');
			
			$taxnames = get_object_taxonomies($posttypename);
			
			foreach ($taxnames as $taxname) {
				$taxonomy = get_taxonomy($taxname);
				$checkboxes["auto_keywords_posttype_{$posttypename}_tax_{$taxname}"] = $taxonomy->labels->name;
			}
			
			if ($checkboxes)
				$this->checkboxes($checkboxes, $posttypelabel);
		}
		
		$this->textarea('global_keywords', __('Sitewide Keywords', 'seo-ultimate') . '<br /><small><em>' . __('(Separate with commas)', 'seo-ultimate') . '</em></small>');
		
		$this->admin_form_table_end();
	}
	
	function home_tab() {
		$this->admin_form_table_start();
		$this->textarea('home_keywords', __('Blog Homepage Meta Keywords', 'seo-ultimate'), 3);
		$this->admin_form_table_end();
	}
	
	function head_tag_output() {
		global $post;
		
		$kw = false;
		
		//If we're viewing the homepage, look for homepage meta data.
		if (is_home()) {
			$kw = $this->get_setting('home_keywords');
		
		//If we're viewing a post or page...
		} elseif (is_singular()) {
			
			//...look for its meta data
			$kw = $this->get_postmeta('keywords');	
			
			//...and add default values
			if ($posttypename = get_post_type()) {
				$taxnames = get_object_taxonomies($posttypename);
				
				foreach ($taxnames as $taxname) {
					if ($this->get_setting("auto_keywords_posttype_{$posttypename}_tax_{$taxname}", false)) {
						$terms = get_the_terms(0, $taxname);
						$terms = suarr::flatten_values($terms, 'name');
						$terms = implode(',', $terms);
						$kw .= ',' . $terms;
					}
				}
				
				if ($this->get_setting("auto_keywords_posttype_{$posttypename}_words", false)) {
					$words = preg_split("/[\W+]/", strip_tags($post->post_content), null, PREG_SPLIT_NO_EMPTY);
					$words = array_count_values($words);
					arsort($words);
					$words = array_filter($words, array(&$this, 'filter_word_counts'));
					$words = array_keys($words);
					$stopwords = suarr::explode_lines($this->get_setting('words_to_remove', array(), 'slugs'));
					$words = array_diff($words, $stopwords);
					$words = array_slice($words, 0, $this->get_setting("auto_keywords_posttype_{$posttypename}_words_value"));
					$words = implode(',', $words);
					$kw .= ',' . $words;
				}
			}
			
		//If we're viewing a term, look for its meta data.
		} elseif (suwp::is_tax()) {
			global $wp_query;
			$tax_keywords = $this->get_setting('taxonomy_keywords');
			$kw = $tax_keywords[$wp_query->get_queried_object_id()];
		}
		
		if ($globals = $this->get_setting('global_keywords')) {
			if (strlen($kw)) $kw .= ',';
			$kw .= $globals;
		}
		
		$kw = str_replace(array("\r\n", "\n"), ',', $kw);
		$kw = explode(',', $kw);
		$kw = array_map('trim', $kw); //Remove extra spaces from beginning/end of keywords
		$kw = array_filter($kw); //Remove blank keywords
		$kw = suarr::array_unique_i($kw); //Remove duplicate keywords
		$kw = implode(',', $kw);
		
		//Do we have keywords? If so, output them.
		if ($kw) {
			$kw = su_esc_attr($kw);
			echo "\t<meta name=\"keywords\" content=\"$kw\" />\n";
		}
	}
	
	function filter_word_counts($count) {
		return $count > 1;
	}
	
	function postmeta_fields($fields) {	
		$fields['25|keywords'] = $this->get_postmeta_textbox('keywords', __('Meta Keywords:<br /><em>(separate with commas)</em>', 'seo-ultimate'));
		return $fields;
	}
	
	function postmeta_help($help) {
		$help[] = __('<strong>Keywords</strong> &mdash; The value of the meta keywords tag. The keywords list gives search engines a hint as to what this post/page is about. Be sure to separate keywords with commas, like so: <samp>one,two,three</samp>.', 'seo-ultimate');
		return $help;
	}
	
	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-meta-keywords-overview'
			, 'title' => __('Overview', 'seo-ultimate')
			, 'content' => __("
<p>Meta Keywords Editor lets you tell search engines what keywords are associated with the various pages on your site. Modern search engines don&#8217;t give meta keywords much weight, but the option is there if you want to use it. You can customize the meta keywords of an individual post or page by using the textboxes that Meta Editor adds to the post/page editors.</p>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-meta-keywords-settings'
			, 'title' => __('Settings Help', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>Sitewide Keywords</strong> &mdash; Here you can enter keywords that describe the overall subject matter of your entire blog. Use ommas to separate keywords. These keywords will be put in the <code>&gt;meta name=&quot;keywords&quot; /&gt;</code> tags of all webpages on the site (homepage, posts, pages, archives, etc.).</li>
	<li><strong>Blog Homepage Meta Keywords</strong> &mdash; These keywords will be applied only to the <em>blog</em> homepage. Note that if you&#8217;ve specified a &#8220;front page&#8221; under <a href='options-reading.php'>Settings &rArr; Reading</a>, you&#8217;ll need to edit your frontpage and set your frontpage keywords there.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-meta-keywords-faq'
			, 'title' => __('FAQ', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li>
		<p><strong>How do I edit the meta keywords of my homepage?</strong><br />If you are using a &#8220;blog homepage&#8221; (the default option of showing your blog posts on your homepage), just use the Blog Homepage field.</p>
		<p>If you have configured your <a href='options-reading.php'>Settings &rArr; Reading</a> section to use a &#8220;frontpage&#8221; (i.e. a Page as your homepage), just edit that Page and use the &#8220;Meta Keywords&#8221; field in the &#8220;SEO Settings&#8221; box.</p>
	</li>
	<li><strong>What happens if I add a global keyword that I previously assigned to individual posts or pages?</strong><br />Don&#8217;t worry; Meta Keywords Editor will remove duplicate keywords automatically.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-meta-keywords-troubleshooting'
			, 'title' => __('Troubleshooting', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li>
		<p><strong>What do I do if my site has multiple meta tags?</strong><br />First, try removing your theme&#8217;s built-in meta tags if it has them. Go to <a href='theme-editor.php' target='_blank'>Appearance &rArr; Editor</a> and edit <code>header.php</code>. Delete or comment-out any <code>&lt;meta&gt;</code> tags.</p>
		<p>If the problem persists, try disabling other SEO plugins that may be generating meta tags.</p>
		<p>Troubleshooting tip: Go to <a href='options-general.php?page=seo-ultimate'>Settings &rArr; SEO Ultimate</a> and enable the &#8220;Insert comments around HTML code insertions&#8221; option. This will mark SEO Ultimate&#8217;s meta tags with comments, allowing you to see which meta tags are generated by SEO Ultimate and which aren&#8217;t.</p>
	</li>
</ul>
", 'seo-ultimate')));
	}
}

}
?>