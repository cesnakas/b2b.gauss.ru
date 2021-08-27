<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div style="text-align: center; font-weight: 400" id="resultSuccessVote">
    Спасибо за участие в опросе!
</div>

<script>
    (function () {
        var pos = $('#resultSuccessVote').offset();
        $('html,body').animate({scrollTop: pos.top - 170}, 500);
    })();
</script>

