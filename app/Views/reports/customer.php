<?php
$this->setData(['title' => 'Ügyfél elemzés']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-users"></i> Ügyfél elemzés</h1>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-4">
                <label>Kezdő dátum</label>
                <input type="date" name="date_from" class="form-control" value="<?= $dateFrom ?>">
            </div>
            <div class="col-md-4">
                <label>Záró dátum</label>
                <input type="date" name="date_to" class="form-control" value="<?= $dateTo ?>">
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Szűrés</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Top ügyfelek bevétel szerint</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ügyfél</th>
                                <th>Munkalapok</th>
                                <th>Bevétel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($topByRevenue, 0, 10) as $customer): ?>
                            <tr>
                                <td><?= $this->escape($customer['name']) ?></td>
                                <td><?= $customer['worksheet_count'] ?></td>
                                <td><?= $this->formatPrice($customer['total_revenue'] ?? 0) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Prioritás szerinti megoszlás</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Prioritás</th>
                                <th>Ügyfelek száma</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($byPriority as $priority): ?>
                            <tr>
                                <td>
                                    <span class="badge" style="background-color: <?= $priority['color'] ?>">
                                        <?= $this->escape($priority['name']) ?>
                                    </span>
                                </td>
                                <td><?= $priority['count'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>