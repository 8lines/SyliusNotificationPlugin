import $ from 'jquery';

$(() => {
  $('#actions a[data-form-collection="add"]').on('click', () => {
    setTimeout(() => {
      $('select[name^="cart_link[actions]"][name$="[type]"]').last().change();
    }, 50);
  });
});
