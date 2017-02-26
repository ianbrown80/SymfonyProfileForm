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
    var $removeFormA = $('<a class="btn btn-danger" href="#">delete this hobby</a>');
    $hobbyFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $hobbyFormLi.remove();
    });
}
