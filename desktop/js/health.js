/* Rafraîchissement de la page Santé, sans modifier les réglages du plugin. */
(function () {
    var button = document.getElementById('bt_refreshHealth_hdsentinel');
    if (!button) return;
    button.addEventListener('click', function () {
        var dialog = button.closest('.jeeDialog');
        jeeDialog.dialog({
            id: dialog && dialog.id ? dialog.id : 'jee_modal',
            title: '{{Santé Hard Disk Sentinel}}',
            contentUrl: 'index.php?v=d&plugin=hdsentinel&modal=health'
        });
    });
})();

var accordion = document.getElementById('accordionHdsentinel');
if (accordion) {
    accordion.addEventListener('click', function(event) {
        if (!event.target.classList.contains('history')) return;
        var cmdId = event.target.getAttribute('data-cmd_id');
        jeeDialog.dialog({
            id: 'md_cmdHistory',
            title: '{{Historique}}',
            contentUrl: 'index.php?v=d&modal=cmd.history&id=' + cmdId
        });
    });
}
