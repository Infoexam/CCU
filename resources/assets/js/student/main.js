(function ($) {
    $(function() {
        // 判斷是否帶有 signIn 參數，如有則頁面載入後即顯示登入框
        if (-1 !== location.search.indexOf('signIn=1')) {
            setTimeout(function() {$('#sign-in-modal').openModal();}, 50);
        }
    });
})(jQuery);
