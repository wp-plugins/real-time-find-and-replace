function addFormField() {
	var id = jQuery('#id').val();
jQuery("#far_itemlist").append("<li id ='row" + id + "'><textarea class='left' name='farfind["+ id +"]' id='farfind" + id + "'></textarea>"
	+ "<textarea class='left' name='farreplace["+ id +"]' id='farreplace" + id + "'></textarea>"
	+ "<label class='left' for='farregex" + id + "'>RegEx?:&nbsp;</label><input class='left'  type='checkbox' name='farregex[" + id + "]' id='farregex" + id +"'/><br />"
	+ "<br /><input type='button' class='button left remove' value='Remove' onClick='removeFormField(\"#row"+ id +"\"); return false;' />\n</li>");
	id = (id - 1) + 2;
	document.getElementById("id").value = id;

   jQuery('html, body').animate({
        scrollTop: jQuery("#row"+(id-1)).offset().top
    }, 1000);

}
function removeFormField(id) {
	jQuery(id).remove();
}


jQuery(function() {
jQuery( "#far_itemlist" ).sortable();

});
