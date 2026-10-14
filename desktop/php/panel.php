
<?php
if (!isConnect('admin')) {
    throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
echo '<br/>';
include __DIR__ . '/../modal/health.php';
?>