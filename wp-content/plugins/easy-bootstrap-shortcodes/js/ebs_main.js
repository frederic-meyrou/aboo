/**
 * Created by vijay on 19/12/13.
 */

var gBtnVar={};
function open_dialogue(dialogueid,width,height){
    if(typeof(width)==='undefined') width = 'auto';
    if(typeof(height)==='undefined') height = 'auto';
    jQuery( dialogueid ).dialog({
        dialogClass : 'wp-dialog osc-dialog',
        autoOpen: true,
        height: height,
        width: width,
        modal: true
    });

}

function close_dialogue(dialogueid){
    jQuery( dialogueid ).dialog('close');
}

var plugininfo={
    longname : 'shortcodename',
    author : 'Oscitas Themes',
    authorurl : 'http://www.oscitasthemes.com/',
    infourl : 'http://www.oscitasthemes.com/',
    version : "1.0.0"
}

function _create_tinyMCE_options(pluginObj, width) {
    if(typeof(width)==='undefined') width = 'auto';
    var pluginName = 'oscitas'+pluginObj.pluginName.substr(0, 1).toUpperCase() + pluginObj.pluginName.substr(1);
    pluginObj.hashId = '#'+pluginObj.id;
    var options = {
        init : function(ed, url) {
            ed.addButton('oscitas'+pluginObj.pluginName, {
                title : pluginObj.title,
                image : url+'/icon.png',
                onclick : function() {
                    eval('create_oscitas_'+pluginObj.pluginName+'(pluginObj);open_dialogue("'+pluginObj.hashId+'","'+width+'")');
                    if (pluginObj.setRowColors) {
                        jQuery(pluginObj.hashId+' table tr:visible:even').css('background', '#F0F0F0');
                        jQuery(pluginObj.hashId+' table tr:visible:odd').css('background', '#DADADD');
                    }
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            plugininfo.longname=pluginObj.title;
            return plugininfo;
        }
    };
    tinymce.create('tinymce.plugins.'+pluginName, options);
    options = eval('tinymce.plugins.'+pluginName);
    //return options;
    tinymce.PluginManager.add('oscitas'+pluginObj.pluginName, tinymce.plugins[pluginName]);
}

function _create_tinyMCE_dropdown(pluginObj,width,height) {
    if(typeof(width)==='undefined') width = 'auto';
    if(typeof(height)==='undefined') height = 'auto';
    pluginObj.hashId = '#'+pluginObj.id;
    eval('create_oscitas_'+pluginObj.pluginName+'(pluginObj);open_dialogue("'+pluginObj.hashId+'","'+width+'","'+height+'")');
    if (pluginObj.setRowColors) {
        jQuery(pluginObj.hashId+' table tr:visible:even').css('background', '#F0F0F0');
        jQuery(pluginObj.hashId+' table tr:visible:odd').css('background', '#DADADD');
    }
}

var iconsval= jQuery('<li type="glyphicon" data-value="glyphicon-asterisk"  class="glyphicon glyphicon-asterisk"> </li>\
<li type="glyphicon" data-value="glyphicon-plus"  class="glyphicon glyphicon-plus"> </li>\
<li type="glyphicon" data-value="glyphicon-euro"  class="glyphicon glyphicon-euro"> </li>\
<li type="glyphicon" data-value="glyphicon-minus"  class="glyphicon glyphicon-minus"> </li>\
<li type="glyphicon" data-value="glyphicon-cloud"  class="glyphicon glyphicon-cloud"> </li>\
<li type="glyphicon" data-value="glyphicon-envelope"  class="glyphicon glyphicon-envelope"> </li>\
<li type="glyphicon" data-value="glyphicon-pencil"  class="glyphicon glyphicon-pencil"> </li>\
<li type="glyphicon" data-value="glyphicon-glass"  class="glyphicon glyphicon-glass"> </li>\
<li type="glyphicon" data-value="glyphicon-music"  class="glyphicon glyphicon-music"> </li>\
<li type="glyphicon" data-value="glyphicon-search"  class="glyphicon glyphicon-search"> </li>\
<li type="glyphicon" data-value="glyphicon-heart"  class="glyphicon glyphicon-heart"> </li>\
<li type="glyphicon" data-value="glyphicon-star"  class="glyphicon glyphicon-star"> </li>\
<li type="glyphicon" data-value="glyphicon-star-empty"  class="glyphicon glyphicon-star-empty"> </li>\
<li type="glyphicon" data-value="glyphicon-user"  class="glyphicon glyphicon-user"> </li>\
<li type="glyphicon" data-value="glyphicon-film"  class="glyphicon glyphicon-film"> </li>\
<li type="glyphicon" data-value="glyphicon-th-large"  class="glyphicon glyphicon-th-large"> </li>\
<li type="glyphicon" data-value="glyphicon-th"  class="glyphicon glyphicon-th"> </li>\
<li type="glyphicon" data-value="glyphicon-th-list"  class="glyphicon glyphicon-th-list"> </li>\
<li type="glyphicon" data-value="glyphicon-ok"  class="glyphicon glyphicon-ok"> </li>\
<li type="glyphicon" data-value="glyphicon-remove"  class="glyphicon glyphicon-remove"> </li>\
<li type="glyphicon" data-value="glyphicon-zoom-in"  class="glyphicon glyphicon-zoom-in"> </li>\
<li type="glyphicon" data-value="glyphicon-zoom-out"  class="glyphicon glyphicon-zoom-out"> </li>\
<li type="glyphicon" data-value="glyphicon-off"  class="glyphicon glyphicon-off"> </li>\
<li type="glyphicon" data-value="glyphicon-signal"  class="glyphicon glyphicon-signal"> </li>\
<li type="glyphicon" data-value="glyphicon-cog"  class="glyphicon glyphicon-cog"> </li>\
<li type="glyphicon" data-value="glyphicon-trash"  class="glyphicon glyphicon-trash"> </li>\
<li type="glyphicon" data-value="glyphicon-home"  class="glyphicon glyphicon-home"> </li>\
<li type="glyphicon" data-value="glyphicon-file"  class="glyphicon glyphicon-file"> </li>\
<li type="glyphicon" data-value="glyphicon-time"  class="glyphicon glyphicon-time"> </li>\
<li type="glyphicon" data-value="glyphicon-road"  class="glyphicon glyphicon-road"> </li>\
<li type="glyphicon" data-value="glyphicon-download-alt"  class="glyphicon glyphicon-download-alt"> </li>\
<li type="glyphicon" data-value="glyphicon-download"  class="glyphicon glyphicon-download"> </li>\
<li type="glyphicon" data-value="glyphicon-upload"  class="glyphicon glyphicon-upload"> </li>\
<li type="glyphicon" data-value="glyphicon-inbox"  class="glyphicon glyphicon-inbox"> </li>\
<li type="glyphicon" data-value="glyphicon-play-circle"  class="glyphicon glyphicon-play-circle"> </li>\
<li type="glyphicon" data-value="glyphicon-repeat"  class="glyphicon glyphicon-repeat"> </li>\
<li type="glyphicon" data-value="glyphicon-refresh"  class="glyphicon glyphicon-refresh"> </li>\
<li type="glyphicon" data-value="glyphicon-list-alt"  class="glyphicon glyphicon-list-alt"> </li>\
<li type="glyphicon" data-value="glyphicon-flag"  class="glyphicon glyphicon-flag"> </li>\
<li type="glyphicon" data-value="glyphicon-headphones"  class="glyphicon glyphicon-headphones"> </li>\
<li type="glyphicon" data-value="glyphicon-volume-off"  class="glyphicon glyphicon-volume-off"> </li>\
<li type="glyphicon" data-value="glyphicon-volume-down"  class="glyphicon glyphicon-volume-down"> </li>\
<li type="glyphicon" data-value="glyphicon-volume-up"  class="glyphicon glyphicon-volume-up"> </li>\
<li type="glyphicon" data-value="glyphicon-qrcode"  class="glyphicon glyphicon-qrcode"> </li>\
<li type="glyphicon" data-value="glyphicon-barcode"  class="glyphicon glyphicon-barcode"> </li>\
<li type="glyphicon" data-value="glyphicon-tag"  class="glyphicon glyphicon-tag"> </li>\
<li type="glyphicon" data-value="glyphicon-tags"  class="glyphicon glyphicon-tags"> </li>\
<li type="glyphicon" data-value="glyphicon-book"  class="glyphicon glyphicon-book"> </li>\
<li type="glyphicon" data-value="glyphicon-print"  class="glyphicon glyphicon-print"> </li>\
<li type="glyphicon" data-value="glyphicon-font"  class="glyphicon glyphicon-font"> </li>\
<li type="glyphicon" data-value="glyphicon-bold"  class="glyphicon glyphicon-bold"> </li>\
<li type="glyphicon" data-value="glyphicon-italic"  class="glyphicon glyphicon-italic"> </li>\
<li type="glyphicon" data-value="glyphicon-text-height"  class="glyphicon glyphicon-text-height"> </li>\
<li type="glyphicon" data-value="glyphicon-text-width"  class="glyphicon glyphicon-text-width"> </li>\
<li type="glyphicon" data-value="glyphicon-align-left"  class="glyphicon glyphicon-align-left"> </li>\
<li type="glyphicon" data-value="glyphicon-align-center"  class="glyphicon glyphicon-align-center"> </li>\
<li type="glyphicon" data-value="glyphicon-align-right"  class="glyphicon glyphicon-align-right"> </li>\
<li type="glyphicon" data-value="glyphicon-align-justify"  class="glyphicon glyphicon-align-justify"> </li>\
<li type="glyphicon" data-value="glyphicon-list"  class="glyphicon glyphicon-list"> </li>\
<li type="glyphicon" data-value="glyphicon-indent-left"  class="glyphicon glyphicon-indent-left"> </li>\
<li type="glyphicon" data-value="glyphicon-indent-right"  class="glyphicon glyphicon-indent-right"> </li>\
<li type="glyphicon" data-value="glyphicon-facetime-video"  class="glyphicon glyphicon-facetime-video"> </li>\
<li type="glyphicon" data-value="glyphicon-picture"  class="glyphicon glyphicon-picture"> </li>\
<li type="glyphicon" data-value="glyphicon-map-marker"  class="glyphicon glyphicon-map-marker"> </li>\
<li type="glyphicon" data-value="glyphicon-adjust"  class="glyphicon glyphicon-adjust"> </li>\
<li type="glyphicon" data-value="glyphicon-tint"  class="glyphicon glyphicon-tint"> </li>\
<li type="glyphicon" data-value="glyphicon-edit"  class="glyphicon glyphicon-edit"> </li>\
<li type="glyphicon" data-value="glyphicon-share"  class="glyphicon glyphicon-share"> </li>\
<li type="glyphicon" data-value="glyphicon-check"  class="glyphicon glyphicon-check"> </li>\
<li type="glyphicon" data-value="glyphicon-move"  class="glyphicon glyphicon-move"> </li>\
<li type="glyphicon" data-value="glyphicon-step-backward"  class="glyphicon glyphicon-step-backward"> </li>\
<li type="glyphicon" data-value="glyphicon-fast-backward"  class="glyphicon glyphicon-fast-backward"> </li>\
<li type="glyphicon" data-value="glyphicon-backward"  class="glyphicon glyphicon-backward"> </li>\
<li type="glyphicon" data-value="glyphicon-play"  class="glyphicon glyphicon-play"> </li>\
<li type="glyphicon" data-value="glyphicon-pause"  class="glyphicon glyphicon-pause"> </li>\
<li type="glyphicon" data-value="glyphicon-stop"  class="glyphicon glyphicon-stop"> </li>\
<li type="glyphicon" data-value="glyphicon-forward"  class="glyphicon glyphicon-forward"> </li>\
<li type="glyphicon" data-value="glyphicon-fast-forward"  class="glyphicon glyphicon-fast-forward"> </li>\
<li type="glyphicon" data-value="glyphicon-step-forward"  class="glyphicon glyphicon-step-forward"> </li>\
<li type="glyphicon" data-value="glyphicon-eject"  class="glyphicon glyphicon-eject"> </li>\
<li type="glyphicon" data-value="glyphicon-chevron-left"  class="glyphicon glyphicon-chevron-left"> </li>\
<li type="glyphicon" data-value="glyphicon-chevron-right"  class="glyphicon glyphicon-chevron-right"> </li>\
<li type="glyphicon" data-value="glyphicon-plus-sign"  class="glyphicon glyphicon-plus-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-minus-sign"  class="glyphicon glyphicon-minus-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-remove-sign"  class="glyphicon glyphicon-remove-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-ok-sign"  class="glyphicon glyphicon-ok-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-question-sign"  class="glyphicon glyphicon-question-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-info-sign"  class="glyphicon glyphicon-info-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-screenshot"  class="glyphicon glyphicon-screenshot"> </li>\
<li type="glyphicon" data-value="glyphicon-remove-circle"  class="glyphicon glyphicon-remove-circle"> </li>\
<li type="glyphicon" data-value="glyphicon-ok-circle"  class="glyphicon glyphicon-ok-circle"> </li>\
<li type="glyphicon" data-value="glyphicon-ban-circle"  class="glyphicon glyphicon-ban-circle"> </li>\
<li type="glyphicon" data-value="glyphicon-arrow-left"  class="glyphicon glyphicon-arrow-left"> </li>\
<li type="glyphicon" data-value="glyphicon-arrow-right"  class="glyphicon glyphicon-arrow-right"> </li>\
<li type="glyphicon" data-value="glyphicon-arrow-up"  class="glyphicon glyphicon-arrow-up"> </li>\
<li type="glyphicon" data-value="glyphicon-arrow-down"  class="glyphicon glyphicon-arrow-down"> </li>\
<li type="glyphicon" data-value="glyphicon-share-alt"  class="glyphicon glyphicon-share-alt"> </li>\
<li type="glyphicon" data-value="glyphicon-resize-full"  class="glyphicon glyphicon-resize-full"> </li>\
<li type="glyphicon" data-value="glyphicon-resize-small"  class="glyphicon glyphicon-resize-small"> </li>\
<li type="glyphicon" data-value="glyphicon-exclamation-sign"  class="glyphicon glyphicon-exclamation-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-gift"  class="glyphicon glyphicon-gift"> </li>\
<li type="glyphicon" data-value="glyphicon-leaf"  class="glyphicon glyphicon-leaf"> </li>\
<li type="glyphicon" data-value="glyphicon-eye-open"  class="glyphicon glyphicon-eye-open"> </li>\
<li type="glyphicon" data-value="glyphicon-eye-close"  class="glyphicon glyphicon-eye-close"> </li>\
<li type="glyphicon" data-value="glyphicon-warning-sign"  class="glyphicon glyphicon-warning-sign"> </li>\
<li type="glyphicon" data-value="glyphicon-plane"  class="glyphicon glyphicon-plane"> </li>\
<li type="glyphicon" data-value="glyphicon-random"  class="glyphicon glyphicon-random"> </li>\
<li type="glyphicon" data-value="glyphicon-comment"  class="glyphicon glyphicon-comment"> </li>\
<li type="glyphicon" data-value="glyphicon-magnet"  class="glyphicon glyphicon-magnet"> </li>\
<li type="glyphicon" data-value="glyphicon-chevron-up"  class="glyphicon glyphicon-chevron-up"> </li>\
<li type="glyphicon" data-value="glyphicon-chevron-down"  class="glyphicon glyphicon-chevron-down"> </li>\
<li type="glyphicon" data-value="glyphicon-retweet"  class="glyphicon glyphicon-retweet"> </li>\
<li type="glyphicon" data-value="glyphicon-shopping-cart"  class="glyphicon glyphicon-shopping-cart"> </li>\
<li type="glyphicon" data-value="glyphicon-folder-close"  class="glyphicon glyphicon-folder-close"> </li>\
<li type="glyphicon" data-value="glyphicon-folder-open"  class="glyphicon glyphicon-folder-open"> </li>\
<li type="glyphicon" data-value="glyphicon-resize-vertical"  class="glyphicon glyphicon-resize-vertical"> </li>\
<li type="glyphicon" data-value="glyphicon-resize-horizontal"  class="glyphicon glyphicon-resize-horizontal"> </li>\
<li type="glyphicon" data-value="glyphicon-hdd"  class="glyphicon glyphicon-hdd"> </li>\
<li type="glyphicon" data-value="glyphicon-bullhorn"  class="glyphicon glyphicon-bullhorn"> </li>\
<li type="glyphicon" data-value="glyphicon-certificate"  class="glyphicon glyphicon-certificate"> </li>\
<li type="glyphicon" data-value="glyphicon-thumbs-up"  class="glyphicon glyphicon-thumbs-up"> </li>\
<li type="glyphicon" data-value="glyphicon-thumbs-down"  class="glyphicon glyphicon-thumbs-down"> </li>\
<li type="glyphicon" data-value="glyphicon-hand-right"  class="glyphicon glyphicon-hand-right"> </li>\
<li type="glyphicon" data-value="glyphicon-hand-left"  class="glyphicon glyphicon-hand-left"> </li>\
<li type="glyphicon" data-value="glyphicon-hand-up"  class="glyphicon glyphicon-hand-up"> </li>\
<li type="glyphicon" data-value="glyphicon-hand-down"  class="glyphicon glyphicon-hand-down"> </li>\
<li type="glyphicon" data-value="glyphicon-circle-arrow-right"  class="glyphicon glyphicon-circle-arrow-right"> </li>\
<li type="glyphicon" data-value="glyphicon-circle-arrow-left"  class="glyphicon glyphicon-circle-arrow-left"> </li>\
<li type="glyphicon" data-value="glyphicon-circle-arrow-up"  class="glyphicon glyphicon-circle-arrow-up"> </li>\
<li type="glyphicon" data-value="glyphicon-circle-arrow-down"  class="glyphicon glyphicon-circle-arrow-down"> </li>\
<li type="glyphicon" data-value="glyphicon-globe"  class="glyphicon glyphicon-globe"> </li>\
<li type="glyphicon" data-value="glyphicon-tasks"  class="glyphicon glyphicon-tasks"> </li>\
<li type="glyphicon" data-value="glyphicon-filter"  class="glyphicon glyphicon-filter"> </li>\
<li type="glyphicon" data-value="glyphicon-fullscreen"  class="glyphicon glyphicon-fullscreen"> </li>\
<li type="glyphicon" data-value="glyphicon-dashboard"  class="glyphicon glyphicon-dashboard"> </li>\
<li type="glyphicon" data-value="glyphicon-heart-empty"  class="glyphicon glyphicon-heart-empty"> </li>\
<li type="glyphicon" data-value="glyphicon-link"  class="glyphicon glyphicon-link"> </li>\
<li type="glyphicon" data-value="glyphicon-phone"  class="glyphicon glyphicon-phone"> </li>\
<li type="glyphicon" data-value="glyphicon-usd"  class="glyphicon glyphicon-usd"> </li>\
<li type="glyphicon" data-value="glyphicon-gbp"  class="glyphicon glyphicon-gbp"> </li>\
<li type="glyphicon" data-value="glyphicon-sort"  class="glyphicon glyphicon-sort"> </li>\
<li type="glyphicon" data-value="glyphicon-sort-by-alphabet"  class="glyphicon glyphicon-sort-by-alphabet"> </li>\
<li type="glyphicon" data-value="glyphicon-sort-by-alphabet-alt"  class="glyphicon glyphicon-sort-by-alphabet-alt"> </li>\
<li type="glyphicon" data-value="glyphicon-sort-by-order"  class="glyphicon glyphicon-sort-by-order"> </li>\
<li type="glyphicon" data-value="glyphicon-sort-by-order-alt"  class="glyphicon glyphicon-sort-by-order-alt"> </li>\
<li type="glyphicon" data-value="glyphicon-sort-by-attributes"  class="glyphicon glyphicon-sort-by-attributes"> </li>\
<li type="glyphicon" data-value="glyphicon-sort-by-attributes-alt"  class="glyphicon glyphicon-sort-by-attributes-alt"> </li>\
<li type="glyphicon" data-value="glyphicon-unchecked"  class="glyphicon glyphicon-unchecked"> </li>\
<li type="glyphicon" data-value="glyphicon-expand"  class="glyphicon glyphicon-expand"> </li>\
<li type="glyphicon" data-value="glyphicon-collapse-down"  class="glyphicon glyphicon-collapse-down"> </li>\
<li type="glyphicon" data-value="glyphicon-collapse-up"  class="glyphicon glyphicon-collapse-up"> </li>\
<li type="glyphicon" data-value="glyphicon-log-in"  class="glyphicon glyphicon-log-in"> </li>\
<li type="glyphicon" data-value="glyphicon-flash"  class="glyphicon glyphicon-flash"> </li>\
<li type="glyphicon" data-value="glyphicon-log-out"  class="glyphicon glyphicon-log-out"> </li>\
<li type="glyphicon" data-value="glyphicon-new-window"  class="glyphicon glyphicon-new-window"> </li>\
<li type="glyphicon" data-value="glyphicon-record"  class="glyphicon glyphicon-record"> </li>\
<li type="glyphicon" data-value="glyphicon-save"  class="glyphicon glyphicon-save"> </li>\
<li type="glyphicon" data-value="glyphicon-open"  class="glyphicon glyphicon-open"> </li>\
<li type="glyphicon" data-value="glyphicon-saved"  class="glyphicon glyphicon-saved"> </li>\
<li type="glyphicon" data-value="glyphicon-import"  class="glyphicon glyphicon-import"> </li>\
<li type="glyphicon" data-value="glyphicon-export"  class="glyphicon glyphicon-export"> </li>\
<li type="glyphicon" data-value="glyphicon-send"  class="glyphicon glyphicon-send"> </li>\
<li type="glyphicon" data-value="glyphicon-floppy-disk"  class="glyphicon glyphicon-floppy-disk"> </li>\
<li type="glyphicon" data-value="glyphicon-floppy-saved"  class="glyphicon glyphicon-floppy-saved"> </li>\
<li type="glyphicon" data-value="glyphicon-floppy-remove"  class="glyphicon glyphicon-floppy-remove"> </li>\
<li type="glyphicon" data-value="glyphicon-floppy-save"  class="glyphicon glyphicon-floppy-save"> </li>\
<li type="glyphicon" data-value="glyphicon-floppy-open"  class="glyphicon glyphicon-floppy-open"> </li>\
<li type="glyphicon" data-value="glyphicon-credit-card"  class="glyphicon glyphicon-credit-card"> </li>\
<li type="glyphicon" data-value="glyphicon-transfer"  class="glyphicon glyphicon-transfer"> </li>\
<li type="glyphicon" data-value="glyphicon-cutlery"  class="glyphicon glyphicon-cutlery"> </li>\
<li type="glyphicon" data-value="glyphicon-header"  class="glyphicon glyphicon-header"> </li>\
<li type="glyphicon" data-value="glyphicon-compressed"  class="glyphicon glyphicon-compressed"> </li>\
<li type="glyphicon" data-value="glyphicon-earphone"  class="glyphicon glyphicon-earphone"> </li>\
<li type="glyphicon" data-value="glyphicon-phone-alt"  class="glyphicon glyphicon-phone-alt"> </li>\
<li type="glyphicon" data-value="glyphicon-tower"  class="glyphicon glyphicon-tower"> </li>\
<li type="glyphicon" data-value="glyphicon-stats"  class="glyphicon glyphicon-stats"> </li>\
<li type="glyphicon" data-value="glyphicon-sd-video"  class="glyphicon glyphicon-sd-video"> </li>\
<li type="glyphicon" data-value="glyphicon-hd-video"  class="glyphicon glyphicon-hd-video"> </li>\
<li type="glyphicon" data-value="glyphicon-subtitles"  class="glyphicon glyphicon-subtitles"> </li>\
<li type="glyphicon" data-value="glyphicon-sound-stereo"  class="glyphicon glyphicon-sound-stereo"> </li>\
<li type="glyphicon" data-value="glyphicon-sound-dolby"  class="glyphicon glyphicon-sound-dolby"> </li>\
<li type="glyphicon" data-value="glyphicon-copyright-mark"  class="glyphicon glyphicon-copyright-mark"> </li>\
<li type="glyphicon" data-value="glyphicon-registration-mark"  class="glyphicon glyphicon-registration-mark"> </li>\
<li type="glyphicon" data-value="glyphicon-cloud-download"  class="glyphicon glyphicon-cloud-download"> </li>\
<li type="glyphicon" data-value="glyphicon-cloud-upload"  class="glyphicon glyphicon-cloud-upload"> </li>\
<li type="glyphicon" data-value="glyphicon-tree-conifer"  class="glyphicon glyphicon-tree-conifer"> </li>\
<li type="glyphicon" data-value="glyphicon-tree-deciduous"  class="glyphicon glyphicon-tree-deciduous"> </li>\
<li type="glyphicon" data-value="glyphicon-briefcase"  class="glyphicon glyphicon-briefcase"> </li>\
<li type="glyphicon" data-value="glyphicon-calendar"  class="glyphicon glyphicon-calendar"> </li>\
<li type="glyphicon" data-value="glyphicon-pushpin"  class="glyphicon glyphicon-pushpin"> </li>\
<li type="glyphicon" data-value="glyphicon-paperclip"  class="glyphicon glyphicon-paperclip"> </li>\
<li type="glyphicon" data-value="glyphicon-camera"  class="glyphicon glyphicon-camera"> </li>\
<li type="glyphicon" data-value="glyphicon-lock"  class="glyphicon glyphicon-lock"> </li>\
<li type="glyphicon" data-value="glyphicon-bell"  class="glyphicon glyphicon-bell"> </li>\
<li type="glyphicon" data-value="glyphicon-bookmark"  class="glyphicon glyphicon-bookmark"> </li>\
<li type="glyphicon" data-value="glyphicon-fire"  class="glyphicon glyphicon-fire"> </li>\
<li type="glyphicon" data-value="glyphicon-wrench"  class="glyphicon glyphicon-wrench"> </li>');
var ebsicons='';
jQuery(iconsval).each(function(ind,val){
    ebsicons+=val.outerHTML;
});


