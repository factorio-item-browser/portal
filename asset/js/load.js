// Note: File must be ES5 compatible.
(function($) {
    $.ajaxSetup({
        cache: true
    });
    try {
        eval('var bar = (x) => x + 1; class Foo {}; "foo".includes("bar");');
        $.getScript('/assets/js/main.min.js'); // @todo Add deploy timestamp or version.
    } catch (e) {
        $.getScript('/assets/js/main.es5.min.js'); // @todo Add deploy timestamp or version.
    }
})(jQuery);