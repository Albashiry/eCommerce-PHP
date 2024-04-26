$(function () {
  'use strict';

  // switch between login and signup
  $('.login-page h1 span').click(function () {
    $(this).addClass('selected').siblings().removeClass('selected');
    $('.login-page form').hide();
    $('.' + $(this).data('class')).fadeIn(100);
  });

  // trigger the selectboxit
  $("select").selectBoxIt({
    autoWidth: false
  });
  // hide the first empty option
  $("select").click(function () {
    $(this).find('option:first').hide();
  });


  // hide placeholder on form focus
  $('[placeholder]').focus(function () {
    $(this).attr('data-text', $(this).attr('placeholder'));
    $(this).attr('placeholder', '');

  }).blur(function () {
    $(this).attr('placeholder', $(this).attr('data-text'));
  });


  // add astrisk on required fileds
  $('input').each(function () {
    if ($(this).attr('required') === 'required') {
      $(this).after('<span class="astrisk">*</span>');
    }
  });


  // confirmation message on delete button
  $('.confirm').click(function () {
    return confirm("Are you sure?");
  });


  $('.live').keyup(function () {
    $($(this).data('class')).text($(this).val());
  });

});
