<style type="text/css">
    .nav-link {
        color: #0000F0 !important; 
        font-weight: bold;
        font-size: 16px;
    }
</style>
<?php
$port = ":14002";
$url = "http://{$_SERVER['SERVER_NAME']}${port}";
$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
$_finalUrl = $escaped_url;
?>
<div class="side-nav col-2">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link" id="v-pills-dashboard-tab" href="<?php echo $_finalUrl;?>" target="_blank" role="tab" aria-controls="v-pills-dashboard" aria-selected="true"><div class="nav-link-icon"></div>Dashboard</a>
        <a class="nav-link" id="v-pills-config-tab" href="config.php" role="tab" aria-controls="v-pills-config" aria-selected="true" style="border-bottom: 1px solid lightgrey;"><div class="nav-link-icon"></div>Config</a>
        <a class="nav-link" id="v-pills-dashboard-tab" href="wizard.php" role="tab" aria-controls="v-pills-dashboard" aria-selected="true"><div class="nav-link-icon"></div>Wizard</a>
    </div>
</div>
