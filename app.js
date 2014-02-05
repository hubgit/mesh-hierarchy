$(document).on('click', '.heading', function(event) {
    event.preventDefault();

    var arrow = $(event.target);

    var container = arrow.parent().next('.category');

    if (container.length) {
        container.toggle(!container.is(':visible'));
    }

    arrow.toggleClass('expanded');
});

$('#categories').text('Loading…').load('data/mtrees2014.html');