function addFormField() {
	var id = document.getElementById("id").value;
	jQuery("#divTxt").append("<p id='row" + id + "'><label for='farfind" + id + "'>Find:&nbsp;</label><textarea rows='2' cols='30' name='farfind[" + id + "]' id='farfind" + id + "' /></textarea>&nbsp;&nbsp;<label for='farregex" + id + "'>RegEx?:&nbsp;</label><input type='checkbox' name='farregex[" + id + "]' id='farregex" + id + "' />&nbsp;&nbsp;<label for='farreplace" + id + "'>Replace:&nbsp;</label><textarea rows='2' cols='30' name='farreplace[" + id + "]' id='farreplace" + id + "' /></textarea>&nbsp;&nbsp&nbsp;<a href='#' onClick='removeFormField(\"#row" + id + "\"); return false;'>Remove</a></p>");
	jQuery('#row' + id).highlightFade({
		speed:1000
	});
	id = (id - 1) + 2;
	document.getElementById("id").value = id;
}
function removeFormField(id) {
	jQuery(id).highlightFade({color:'rgb(255,0,0)',complete:function() { jQuery(id).remove() },iterator:'sinusoidal'});}
 