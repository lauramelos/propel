/**
  * This function is used to validate form data
  */
  function validate_form() {
    var post_type = jQuery("input[name=e2e_post_type]:checked").val();
    if (typeof(post_type) == 'undefined') {
      alert ('Please select a selection criteria');
      return false;
    }

    return true;
  }