$(function () {
  'use strict';

  // Dashboard
  $('.toggle-info').click(function () {
    $(this).toggleClass('selected').parent().next('.card-body').slideToggle();

    if ($(this).hasClass('selected')) {
      $(this).html('<i class="fa fa-plus fa-lg"></i>');
    }
    else {
      $(this).html('<i class="fa fa-minus fa-lg"></i>');
    }
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


  var passField = $('.password');
  // convert password field to text field on hover
  $('.show-pass').hover(function () {
    passField.attr('type', 'text');
    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
  }, function () {
    passField.attr('type', 'password');
    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
  });


  // confirmation message on delete button
  $('.confirm').click(function () {
    return confirm("Are you sure?");
  });


  // category view option
  $('.cat h3').click(function () {
    $(this).next('.full-view').slideToggle();
  });

  $('.option span').click(function () {
    $(this).addClass('active').siblings('span').removeClass('active');
    if ($(this).data('view') == 'full') {
      $('.cat .full-view').fadeIn();
    }
    else {
      $('.cat .full-view').fadeOut();
    }
  });

  // Show Delete button on child cats
  $('.child-link').hover(function () {
    $(this).find('.show-delete').fadeIn();
  }, function () {
    $(this).find('.show-delete').fadeOut();
  });
});
