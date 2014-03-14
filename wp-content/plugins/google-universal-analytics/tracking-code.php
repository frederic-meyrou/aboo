<?php
$web_property_id = 	get_option( 'web_property_id' );
$track_links 	 =	get_option('track_links');
$homeurl		 =	get_option('home');
$find = array( 'http://', 'https://', 'www.' );
$replace = '';
$homeurl = str_replace( $find, $replace, $homeurl );
?>
<!-- Google Universal Analytics for WordPress -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo $web_property_id; ?>', '<?php echo $homeurl; ?>');
ga('send', 'pageview');
</script>
<?php if($track_links=='on'): ?>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
    jQuery('a').click(function(e) {
		var $this = jQuery(this);
      	var href = $this.prop('href').split('?')[0];
		var ext = href.split('.').pop();
		if ('xls,xlsx,doc,docx,ppt,pptx,pdf,txt,zip,rar,7z,exe,wma,mov,avi,wmv,mp3,csv,tsv'.split(',').indexOf(ext) !== -1) {		
        ga('send', 'event', 'Download', ext, href);
      }
	  if (href.toLowerCase().indexOf('mailto:') === 0) {
        ga('send', 'event', 'Mailto', href.substr(7));
		
      }
      if ((this.protocol === 'http:' || this.protocol === 'https:') && this.hostname.indexOf(document.location.hostname) === -1) {
        ga('send', 'event', 'Outbound', this.hostname, this.pathname);
      }
	  	
	});
});
</script>
<?php endif; 