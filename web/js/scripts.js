

/***********************************************************/
// Add and remove hobbie forms to the user form

var $collectionHolder;
var $addHobbyLink = $('<a href="#" class="add_hobby_link btn btn-primary">Add a hobby</a>');
var $newLinkLi = $('<li></li>').append($addHobbyLink);

jQuery(document).ready(function() {

    $collectionHolder = $('ul.hobbies');
    $collectionHolder.find('li').each(function() {
        addHobbyFormDeleteLink($(this));
    });
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addHobbyLink.on('click', function(e) {
        e.preventDefault();
        addHobbyForm($collectionHolder, $newLinkLi);
    });
});


function addHobbyForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    addHobbyFormDeleteLink($newFormLi);
}

function addHobbyFormDeleteLink($hobbyFormLi) {
    var $removeFormA = $('<a class="btn btn-danger delete" href="#">Delete this hobby</a>');
    $hobbyFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $hobbyFormLi.remove();
    });
  }


  //********************************************************//
  // Preview the image in
  var $input = $("input[type='file']");
  var $output = $(".profile_image");
  $input.on("change", function(e) {
    $output.attr("src", URL.createObjectURL(e.target.files[0]));
  });

  //********************************************************//
  //Drag and Drop
  var $drop_zone = $(".profile_image_container");
  var $form = $(".form");
  var droppedFiles = false;
  var $biography = $(".biography");

  $drop_zone.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
    e.preventDefault();
    e.stopPropagation();
  })
  .on('dragover dragenter', function() {
  $output.addClass('is-dragover');
  })
  .on('dragleave dragend drop', function() {
  $output.removeClass('is-dragover');
  })
  .on('drop', function(e) {
    droppedFiles = e.originalEvent.dataTransfer.files;
    $output.attr("src", URL.createObjectURL(droppedFiles[0]));
    $input.attr("required", false);
  });


  //*******************************************************//
  //Validate the form before submission

  $form.on('submit', function(e) {
    // Prevent the form from being submitted straight away/
    e.preventDefault();
    e.stopImmediatePropagation();

    // Store all of the data from the form in a FormData object
    // The biography data will need ammending.
    var ajaxData = new FormData($form.get(0));


    //Check the text input fields to ensure they only contain
    // alpha-numeric characters
    var validation = true;
    var validationPattern = /^[a-z0-9 ]+$/;
    $("input[type='text']").each(function() {
      if ($(this).val().match(validationPattern) == null && validation == true) {
        validation = false;
      }
    })

    /* The data in the biography field will need extracting
     * From the CKEDITOR. First need to find which form is
     * being used, either the create or the update, then
     * change the value of the biography text area and replace
     * it in the FormData object
     */
    if ($('#update_user_biography').length) {
      var instance = CKEDITOR.instances.update_user_biography;
      instance.updateElement();
      $biography = $("#update_user_biography");
    }
    if ($('#create_user_biography').length) {
      var instance = CKEDITOR.instances.create_user_biography;
      instance.updateElement();
      $biography = $("#create_user_biography");
    }
    $biography.val(instance.getData());
    ajaxData.append($biography.attr('name'), $biography.val());

    // Check if the image has been selected using drag and drop
    // and if so place the last file dragged into the FormData.
    if (droppedFiles) {
      ajaxData.append( $input.attr('name'), droppedFiles[droppedFiles.length-1] );
    }

    // If validation has been successful, send the data to the
    // controller.
    if (validation) {
      $(".message").removeClass("show");
      $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: ajaxData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          window.location.href ="/"
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert("Something went wrong - Error:  " + thrownError + " " + ajaxOptions);
        }
      });
    } else {
      $(".message").addClass("show");
      $(".message").html("Please only use alpha-numeric characters.")
    }
  });
