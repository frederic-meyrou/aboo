<?php
$profile = $tools->get_selected_profile ( $GADASH_Config->options ['ga_dash_profile_list'], $GADASH_Config->options ['ga_dash_tableid_jail'] );
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  <?php	if ($GADASH_Config->options ['ga_enhanced_links']) {?>
  var pluginUrl = 
	  '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
	 _gaq.push(['_require', 'inpage_linkid', pluginUrl]);
  <?php }?>
  _gaq.push(['_setAccount', '<?php echo $profile[2]; ?>']);
  _gaq.push(['_trackPageview']<?php if ($GADASH_Config->options ['ga_dash_anonim']) {?>, ['_gat._anonymizeIp']<?php }?>);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>