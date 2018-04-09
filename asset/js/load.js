// Note: File must be ES5 compatible.
(function($) {
    $.ajaxSetup({
        cache: true
    });
    try {
        eval('var bar = (x) => x + 1; class Foo {}; "foo".includes("bar");');
        $.getScript('/asset/js/main.min.js'); // @todo Add deploy timestamp or version.
    } catch (e) {
        $.getScript('/asset/js/main.es5.min.js'); // @todo Add deploy timestamp or version.
    }
})(jQuery);