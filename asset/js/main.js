(($, fib) => {
    $(document).ready(() => {
        // Core features
        fib.helper = new fib.Helper();
        fib.cache = new fib.Cache();
        fib.mediaQuery = new fib.MediaQuery();
        fib.browser = new fib.Browser();

        // Global features
        fib.loadingCircle = new fib.LoadingCircle($('#loading-circle'));
        fib.searchBox = new fib.SearchBox($('#search-box'));
        fib.sidebar = new fib.Sidebar($('#sidebar'));
        fib.tooltip = new fib.Tooltip($('#tooltip'));

        // Local features
        fib.modList = new fib.ModList();
        fib.modListFileUpload = new fib.ModListFileUpload();

        // Let's go!
        fib.browser.initialize();
    });
})(jQuery, factorioItemBrowser);