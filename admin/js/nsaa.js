  jQuery(document).ready(function($) {


   let shoulddatatableit = $('body').find('.thereis_nothinghere_now').html();
   if(shoulddatatableit ==null){
      $('#listospamcommentaction').DataTable();

   }
   $('#nsaa_clear_wp_comment').on('click',function(e){ 
    e.preventDefault();
    $('.display_result_ajax_call').html('<img id="pend-load" src="../wp-content/plugins/no-spam-at-all/assets/img/loading_options.gif"/>');
var commentxed = {
action:   'nsaaDeleteQueque',
delete_queue:'1'
}

$.post(ajaxurl, commentxed, function(ppp){
    $('.display_result_ajax_call').html(ppp);
});
    

});


$('body').on('click', '.click_each_action_nsaa_btn', function(ev){
	 ev.preventDefault();
	 let currentrow = $(this).closest('tr');
   let cfirmit = confirm('Are you sure about this');
   if(cfirmit){

      cdata = { 
      	  actiontype:$(this).data('actiontype'),
      	   comment_id:$(this).data('current_comment_id'),
      	   action:'repostDeleteThisComment'
       }

       $.post(ajaxurl, cdata, function(report){
            
            if(report =='OK'){
            	 currentrow.remove();
            }
            else{
            	alert(report);
            }

       });
   }

})



$('#nsaa_clear_wp_c_meta').click(function(e){ 
    e.preventDefault();
$('.display_result_ajax_call').html('<img id="pend-load" src="../wp-content/plugins/no-spam-at-all/assets/img/loading_options.gif"/>');
var akis_meta= {
action:   'nsaaClearMetaAkis',
delete_ak_meta:'1'
}

$.post(ajaxurl, akis_meta, function(report){
	$('.display_result_ajax_call').html(report);

});
    
});




$('#nsaa_clear_wp_c_links').click(function(e){ 
    e.preventDefault();
$('.display_result_ajax_call').html('<img id="pend-load" src="../wp-content/plugins/no-spam-at-all/assets/img/loading_options.gif"/>');
var akis_meta= {
action:   'nsaaDeleteHasLinks',
delete_has_links:'1'
}

$.post(ajaxurl, akis_meta, function(report){
$('.display_result_ajax_call').html(report);

});
    
});



$('#delete_all_logged_xed').click( function(e){ 
e.preventDefault();
var sendxed ={
    action:'callDeleteAllSpam',
    delete_xed_log:'1'
    
}
$.post(ajaxurl, sendxed, function(report){
$('.display_result_ajax_call').html(oncourse);

});

});

});// ready