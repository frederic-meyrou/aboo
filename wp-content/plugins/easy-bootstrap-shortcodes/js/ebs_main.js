/**
 * Created by vijay on 19/12/13.
 */
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
