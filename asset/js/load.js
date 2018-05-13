// Note: File must be ES5 compatible.
(function($, fib) {
    $.ajaxSetup({
        cache: true
    });
    try {
        eval('var bar = (x) => x + 1; class Foo {}; "foo".includes("bar");');
        $.getScript(fib.config.script.default);
    } catch (e) {
        $.getScript(fib.config.script.fallback);
    }
})(jQuery, factorioItemBrowser);