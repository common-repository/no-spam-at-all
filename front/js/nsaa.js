
jQuery(document).ready(function($) {

var vform= jQuery('#commentform');
   vform.submit(function(e){ 
   	e.preventDefault();

  $('body').find('.image_loader_pre_content').remove();
  $('body').find('.ptext_loader_pre_content').remove();


if(isloggednsaa_comment =='No'){
if($('#author').val().trim().length ==0){
  $('#commentform').prepend('<p class="error ptext_loader_pre_content"> '+author_field_required+' </p>');
  return false;
}

if($('#email').val().trim().length ==0){
  $('#commentform').prepend('<p class="ptext_loader_pre_content error"> '+email_field_required+'</p>');
  return false;
}

 if(!validateEmail($('#email').val())){
    $('#commentform').prepend('<p class="ptext_loader_pre_content error"> '+valid_email_field_required+'</p>');
  return false;
 }
}

if($('#comment').val().trim().length ==0){
  $('#commentform').prepend('<p class="ptext_loader_pre_content error"> '+comment_field_required+'</p>');
  return false;
}

var formURL = vform.attr('action'); 
var commentdata = {

    author : $('#author').val(),
    email : $('#email').val(),
    comment : $('#comment').val(),
    comment_post_ID: $('input[name=comment_post_ID]').val(),
    comment_parent: $('input[name=comment_parent]').val(),
    i_token: TYD344KDK487JDJQJS7JDH23UDJKKDH43UDJJJHFJSH,
}


$('#commentform').prepend(imageSpinnerEle);

$.ajax({
      url:formURL,
      type:'post',
      data:commentdata,
      dataType:'text',
      success:function(thetext){

$('body').find('.image_loader_pre_content').remove();

    if(thetext.trim() =="tags"){ 
$('#commentform').prepend('<p class="ptext_loader_pre_content error"> Not All tags are allowed! Please remove html tags from your comments and try again </p>');
}
else{ 
$('#commentform').prepend('<p class="ptext_loader_pre_content success">'+ goodmart_image + nsaa_success_comment + '</p>');
}

      },
      error:function(thetext){

$('body').find('.image_loader_pre_content').remove();
$('#commentform').prepend('<p class="ptext_loader_pre_content error">'+ nsaa_bad_comment +'</p>');
$('#commentform').prepend( thetext.responseText);

}


})
    
    });

}); //ready



function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}