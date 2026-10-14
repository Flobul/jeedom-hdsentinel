<?php
/* This file is part of Jeedom. */
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    include_file('desktop', '404', 'php');
    die();
}

$plugin = plugin::byId('hdsentinel');
$update = $plugin->getUpdate();
$eqLogics = eqLogic::byType('hdsentinel');
sendVarToJS('version', hdsentinel::$_hdsentinelVersion);
?>
<?php include_file('desktop', 'plugin-banner', 'css', 'hdsentinel'); ?>

<form class="form-horizontal" id="configuration_plugin_hdsentinel">
    <div class="hdsentinel-config-page">
        <header class="hdsentinel-config-hero">
            <div class="hdsentinel-config-identity">
                <div class="hdsentinel-config-logo"><img src="plugins/hdsentinel/plugin_info/hdsentinel_icon.png" alt="Hard Disk Sentinel"></div>
                <div>
                    <span class="hdsentinel-config-eyebrow">{{Configuration du plugin}}</span>
                    <h2>Hard Disk Sentinel</h2>
                    <p>{{Retrouvez dans Jeedom les informations de vos disques suivis par Hard Disk Sentinel.}}</p>
                </div>
            </div>
            <div class="hdsentinel-config-meta">
                <span><i class="fas fa-code-branch"></i> {{Version}} <strong>v<?php echo htmlspecialchars((string) hdsentinel::$_hdsentinelVersion, ENT_QUOTES, 'UTF-8'); ?></strong></span>
                <?php if (is_object($update)) { ?>
                    <span><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars((string) $update->getLocalVersion(), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><i class="fas fa-stream"></i> <?php echo htmlspecialchars((string) $update->getConfiguration('version', 'stable'), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><i class="fas fa-cloud-download-alt"></i> <?php echo htmlspecialchars((string) $update->getSource(), ENT_QUOTES, 'UTF-8'); ?></span>
                <?php } ?>
            </div>
            <div class="hdsentinel-config-links">
                <a class="btn btn-sm hdsentinel-config-link-documentation" target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars((string) $plugin->getDocumentation(), ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-book"></i> {{Documentation}}</a>
                <a class="btn btn-sm hdsentinel-config-link-changelog" target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars((string) $plugin->getChangelog(), ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-list"></i> {{Changelog}}</a>
                <a class="btn btn-sm hdsentinel-config-link-community" target="_blank" rel="noopener noreferrer" href="https://community.jeedom.com/tag/plugin-hdsentinel"><i class="fas fa-comments"></i> {{Communauté}}</a>
            </div>
        </header>

        <section class="hdsentinel-config-card">
            <div class="hdsentinel-config-card-header">
                <i class="fas fa-sliders-h"></i>
                <div>
                    <h3>{{Options du plugin}}</h3>
                    <p>{{Contrôlez la mise à jour automatique des installations Hard Disk Sentinel distantes.}}</p>
                </div>
            </div>
            <div class="hdsentinel-config-card-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label">
                        {{Mise à jour automatique des équipements distants}}
                        <sup><i class="fas fa-question-circle tooltips" title="{{Lors d’une installation ou mise à jour du plugin, renvoie les scripts, réinstalle HD Sentinel et relance le cron sur les équipements distants compatibles.}}"></i></sup>
                    </label>
                    <div class="col-lg-2">
                        <input type="checkbox" class="configKey form-control" data-l1key="autoUpdateRemote">
                    </div>
                    <div class="col-lg-6">
                        <span class="label label-warning hdsentinel-config-warning">{{À activer seulement si les accès SSH/sudo sont fonctionnels sur les hôtes distants.}}</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="hdsentinel-config-card">
            <div class="hdsentinel-config-card-header">
                <i class="fas fa-route"></i>
                <div>
                    <h3>{{Modes de fonctionnement}}</h3>
                    <p>{{Choisissez le mode adapté au système distant lors de la configuration de chaque équipement.}}</p>
                </div>
            </div>
            <div class="hdsentinel-config-card-body">
                <div class="alert alert-info hdsentinel-config-modes">
                    <p><strong>{{Linux / Raspberry / Synology}}</strong> : {{renseigner un hôte SSH Manager, envoyer les fichiers, installer HD Sentinel, puis lancer le cron distant.}}</p>
                    <p><strong>{{Synology}}</strong> : {{le script détecte DSM via synoinfo.conf et mappe l’architecture Synology vers le binaire HD Sentinel compatible lorsque celui-ci existe. Les architectures PowerPC ne sont pas supportées par HD Sentinel Linux.}}</p>
                    <p><strong>{{Windows}}</strong> : {{cocher Windows dans l’équipement, renseigner l’adresse IP, le port XML du service HD Sentinel et le mot de passe si nécessaire.}}</p>
                    <p><strong>{{Installation manuelle}}</strong> : {{à utiliser si HD Sentinel est déjà installé et que vous souhaitez uniquement interroger l’équipement depuis Jeedom.}}</p>
                </div>
            </div>
        </section>

        <section class="hdsentinel-config-card">
            <div class="hdsentinel-config-card-header">
                <i class="fas fa-server"></i>
                <div>
                    <h3>{{Équipements distants}}</h3>
                    <p>{{Consultez les modes, versions et fréquences des équipements configurés.}}</p>
                </div>
            </div>
            <div class="hdsentinel-config-card-body">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>{{Équipement}}</th>
                                <th>{{Mode}}</th>
                                <th>{{Hôte / IP}}</th>
                                <th>{{Version HD Sentinel}}</th>
                                <th>{{Dernière communication}}</th>
                                <th>{{Auto-actualisation}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eqLogics as $eqLogic) {
                                $mode = '{{Distant SSH}}';
                                $host = 'N/A';
                                if ($eqLogic->getConfiguration('windows', false)) {
                                    $mode = '{{Windows XML}}';
                                    $host = $eqLogic->getConfiguration('addressip', 'N/A') . ':' . $eqLogic->getConfiguration('portssh', 'N/A');
                                } else {
                                    if ($eqLogic->getConfiguration('manually', false)) {
                                        $mode = '{{Manuel}}';
                                    }
                                    $sshmanager = eqLogic::byId($eqLogic->getConfiguration('host_id'));
                                    if (is_object($sshmanager)) {
                                        $host = $sshmanager->getConfiguration(sshmanager::CONFIG_HOST, 'N/A');
                                    }
                                }
                            ?>
                                <tr>
                                    <td><a href="<?php echo htmlspecialchars((string) $eqLogic->getLinkToConfiguration(), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $eqLogic->getHumanName(true); ?></a></td>
                                    <td><span class="label label-info"><?php echo $mode; ?></span></td>
                                    <td><?php echo htmlspecialchars((string) $host, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars((string) $eqLogic->getConfiguration('Installed_version', 'N/A'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars((string) $eqLogic->getConfiguration('Current_Date_And_Time', 'N/A'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars((string) $eqLogic->getConfiguration('autorefresh', 'N/A'), ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (count($eqLogics) === 0) { ?>
                                <tr><td colspan="6" class="text-center text-muted">{{Aucun équipement Hard Disk Sentinel}}</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-warning">
                    {{Ordre recommandé pour un nouvel équipement distant : sauvegarder l’équipement, envoyer les fichiers, installer HD Sentinel, tester, puis lancer le cron. Consultez les logs de dépendances si l’installation reste bloquée.}}
                </div>
                <div class="hdsentinel-config-actions">
                    <a class="btn btn-warning allEqlogics" data-action="upload"><i class="fas fa-arrow-up"></i> {{Mettre à jour les fichiers sur tous}}</a>
                    <a class="btn btn-warning allEqlogics" data-action="update"><i class="fas fa-download"></i> {{Installer HD Sentinel sur tous}}</a>
                    <a class="btn btn-success allEqlogics" data-action="launch"><i class="fas fa-play"></i> {{Tout relancer}}</a>
                    <a class="btn btn-danger allEqlogics" data-action="stop"><i class="fas fa-stop"></i> {{Tout arrêter}}</a>
                    <a class="btn btn-danger allEqlogics" data-action="stopNdelete"><i class="fas fa-trash-alt"></i> {{Tout arrêter et supprimer}}</a>
                </div>
            </div>
        </section>
    </div>
</form>

<?php include_file('desktop', 'configuration', 'js', 'hdsentinel'); ?>