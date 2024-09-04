<?php
$config = require '../app/config/config.php';
$appConfig = $config['app'];
?>
<div class="border-end bg-white" id="sidebar-wrapper">
    <div class="sidebar-heading border-bottom bg-light" style="font-size:1.350em">Gabinete Digital</div>
    <div class="list-group list-group-flush">

        <p style="margin-left: 17px; margin-top:20px;font-weight: bolder;" class="text-muted mb-2"><i class="fas fa-bars"></i> Legislativo</p>
        <!--<a class="list-group-item list-group-item-action list-group-item-light px-3" href="./comissoes"><i class="fa-solid fa-building-columns"></i> Comissões</a>-->
        <!--a class="list-group-item list-group-item-action list-group-item-light px-3" href="./reunioes"><i class="fa-solid fa-calendar-days"></i> Agenda das comissões</a> -->
        <p style="margin-left: 17px; margin-top:20px;font-weight: bolder;" class="text-muted mb-2"><i class="fas fa-bars"></i> Gestão de pessoas</p>
        <a class="list-group-item list-group-item-action list-group-item-light px-3" href="<?php echo $appConfig['url'] ?>/orgaos"><i class="fas fa-hotel"></i> Órgãos e instituições</a>
        <a class="list-group-item list-group-item-action list-group-item-light px-3" href="<?php echo $appConfig['url'] ?>/pessoas"><i class="fas fa-user-friends"></i> Pessoas</a>
    </div>
</div>