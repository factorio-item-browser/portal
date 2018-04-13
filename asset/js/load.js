// Note: File must be ES5 compatible.
(function($, fib) {
    $.ajaxSetup({
        cache: true
    });
    try {
        eval('var bar = (x) => x + 1; class Foo {}; "foo".includes("bar");');
        $.getScript('/asset/js/main.min.' + fib.config.version + '.js');
    } catch (e) {
        $.getScript('/asset/js/main.es5.min.' + fib.config.version + '.js');
    }
})(jQuery, factorioItemBrowser);