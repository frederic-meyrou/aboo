<?php
if (! class_exists ( 'GADASH_Frontend' )) {
	class GADASH_Frontend{
		function __construct(){
			add_filter ( 'the_content', array($this, 'ga_dash_front_content' ));
		}
		
		function ga_dash_front_content($content) {
			global $post;
			global $GADASH_Config;
			
			if (! current_user_can ( $GADASH_Config->options['ga_dash_access_front'] ) or !($GADASH_Config->options['ga_dash_frontend_stats'] OR $GADASH_Config->options['ga_dash_frontend_keywords'])) {
				return $content;
			}
		
			if (! is_feed () && ! is_home () && ! is_front_page ()) {

				/*
				 * Include GAPI
				*/
				if ($GADASH_Config->options ['ga_dash_token']) {
					include_once ($GADASH_Config->plugin_path . '/tools/gapi.php');
					global $GADASH_GAPI;
				} else {
					return $content;
				}	
				
				/*
				 * Include Tools
				*/
				include_once ($GADASH_Config->plugin_path . '/tools/tools.php');			
				$tools = new GADASH_Tools ();
				
				if (! $GADASH_GAPI->client->getAccessToken ()){
					return $content;
				}
				
				if (isset($GADASH_Config->options[ 'ga_dash_tableid_jail' ])) {
					$projectId = $GADASH_Config->options[ 'ga_dash_tableid_jail' ];
					$profile_info=$tools->get_selected_profile($GADASH_Config->options['ga_dash_profile_list'], $projectId);
					if (isset($profile_info[4])){
						$GADASH_GAPI->timeshift = $profile_info[4];
					} else{
						$GADASH_GAPI->timeshift = (int)current_time('timestamp') - time();
					}
				} else {
					return $content;
				}
				
				$page_url = str_replace(site_url(), "", get_permalink());

				$post_id = $post->ID;
				
				$content .= '<style>
				#ga_dash_sdata td{
					line-height:1.5em;
					padding:2px;
					font-size:1em;
				}
				#ga_dash_sdata{
					line-height:10px;
				}
				</style>';
							
				$content .= '<script type="text/javascript" src="https://www.google.com/jsapi"></script>
				<script type="text/javascript">
				  google.setOnLoadCallback(ga_dash_callback);
				
				  function ga_dash_callback(){
						
						if(typeof ga_dash_drawstats == "function"){
							ga_dash_drawstats();
						}
						if(typeof ga_dash_drawsd == "function"){
							ga_dash_drawsd();
						}
				}';
						
				if ($GADASH_Config->options ['ga_dash_frontend_stats']) {		
					$content .= $GADASH_GAPI->frontend_afterpost_visits($projectId, $page_url, $post_id );
				}	 
				
				if ($GADASH_Config->options ['ga_dash_frontend_keywords']) {
					$content .= $GADASH_GAPI->frontend_afterpost_searches($projectId, $page_url, $post_id );
				}
				
				$content .= "</script>";
				$content .= '<p><div id="ga_dash_statsdata"></div><div id="ga_dash_sdata" ></div></p>';
				
			}
			
			return $content;
		}		
	}
}
if(!is_admin()){
	$GLOBALS['GADASH_Frontend'] = new GADASH_Frontend();
}
