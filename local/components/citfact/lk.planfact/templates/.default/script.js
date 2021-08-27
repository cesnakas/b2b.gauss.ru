function reloadPlanFactData(select) {
    let activeTab = $('.lk-chart__link.active').attr('data-chart-link');
    $('#active_tab').val(activeTab);
    $('#form_plan_fact').submit();
}