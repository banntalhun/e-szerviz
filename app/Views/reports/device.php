<?php
$this->setData(['title' => 'Eszköz statisztikák']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-bicycle"></i> Eszköz statisztikák</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Összes eszköz</h5>
                <h2><?= $deviceStats['total_devices'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Javítás alatt</h5>
                <h2><?= $deviceStats['under_repair'] ?? 0 ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Ügyfelek száma</h5>
                <h2><?= $deviceStats['unique_customers'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Állapot szerinti megoszlás</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Állapot</th>
                        <th>Darabszám</th>
                        <th>Százalék</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = array_sum(array_column($byCondition, 'count'));
                    foreach ($byCondition as $condition): 
                        $percent = $total > 0 ? round(($condition['count'] / $total) * 100, 1) : 0;
                    ?>
                    <tr>
                        <td><?= $this->escape($condition['name']) ?></td>
                        <td><?= $condition['count'] ?></td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" style="width: <?= $percent ?>%"><?= $percent ?>%</div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>