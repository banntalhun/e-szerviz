<?php
$this->setData(['title' => 'Beállítások']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-cog"></i> Beállítások</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Telephelyek</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Alapértelmezett</th>
                                <th>Aktív</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings['locations'] as $location): ?>
                            <tr>
                                <td><?= $this->escape($location['name']) ?></td>
                                <td><?= $location['is_default'] ? '<i class="fas fa-check text-success"></i>' : '' ?></td>
                                <td><?= $location['is_active'] ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Státuszok</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Szín</th>
                                <th>Lezárt</th>
                                <th>Sorrend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings['status_types'] as $status): ?>
                            <tr>
                                <td><?= $this->escape($status['name']) ?></td>
                                <td><span class="badge" style="background-color: <?= $status['color'] ?>"><?= $status['color'] ?></span></td>
                                <td><?= $status['is_closed'] ? '<i class="fas fa-check text-success"></i>' : '' ?></td>
                                <td><?= $status['sort_order'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Prioritások</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Szín</th>
                                <th>Szint</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings['priority_types'] as $priority): ?>
                            <tr>
                                <td><?= $this->escape($priority['name']) ?></td>
                                <td><span class="badge" style="background-color: <?= $priority['color'] ?>"><?= $priority['color'] ?></span></td>
                                <td><?= $priority['level'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Javítás típusok</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php foreach ($settings['repair_types'] as $type): ?>
                    <li><?= $this->escape($type['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>