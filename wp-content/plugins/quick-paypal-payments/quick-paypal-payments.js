function qppclear(thisfield, defaulttext) {if (thisfield.value == defaulttext) {thisfield.value = "";}}
function qpprecall(thisfield, defaulttext) {if (thisfield.value == "") {thisfield.value = defaulttext;}}
function replaceContentInContainer(target,source) {document.getElementById(target).innerHTML  = document.getElementById(source).innerHTML; }