(function ($) {
  $(document).ready(function () {
    $(".select-button").each(function () {
      $(this).click(function (e) {
        const radio = $(this)
          .closest(".option__wrap.option--available")
          .find('input[type="radio"]');
        $('input[type="radio"]', ".company-options__container").each(
          function () {
            $(this).attr("checked", false);
            $(".btn--selected", ".company-options__container").each(
              function () {
                $(this).removeClass("btn--selected");
              }
            );
          }
        );
        radio.attr("checked") !== "checked"
          ? radio.attr("checked", "checked")
          : radio.attr("checked", "");
        $(this).toggleClass("btn--selected");
      });
    });
  });
})(jQuery);
