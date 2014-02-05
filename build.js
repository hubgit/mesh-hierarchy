$.getJSON('data/mtrees2014.json', function(data) {
    var addNode = function(node, parent) {
        var span = $('<span/>', {
            text: node.name
        });

        span.addClass('name');

        var link = $('<a/>', {
            //href: 'https://www.nlm.nih.gov/cgi/mesh/2014/MB_cgi?view=expanded&term=' + encodeURIComponent(node.name),
            href: 'http://www.ncbi.nlm.nih.gov/pubmed/?term=' + encodeURIComponent(node.name + '[MeSH]'),
            text: '⇢',
            target: '_blank',
        });

        var container = $('<div>').addClass('container').append(span).append(link).appendTo(parent);

        if (node.children) {
            span.addClass('heading');

            parent = $('<div/>').addClass('category').appendTo(parent);

            node.children.forEach(function(child) {
                addNode(child, parent);
            });
        }
    };

    var parent = $('<div/>').appendTo('#categories');

    data.children.forEach(function(child) {
        addNode(child, parent);
    });
});
