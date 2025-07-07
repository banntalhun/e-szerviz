<?php
$this->setData(['title' => 'Szerelő teljesítmény']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-user-cog"></i> Szerelő teljesítmény</h1>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-3">
                <label>Kezdő dátum</label>
                <input type="date" name="date_from" class="form-control" value="<?= $dateFrom ?>">
            </div>
            <div class="col-md-3">
                <label>Záró dátum</label>
                <input type="date" name="date_to" class="form-control" value="<?= $dateTo ?>">
            </div>
            <div class="col-md-3">
                <label>Szerelő</label>
                <select name="technician_id" class="form-select">
                    <option value="">Összes</option>
                    <?php foreach ($technicians as $tech): ?>
                    <option value="<?= $tech['id'] ?>" <?= $technicianId == $tech['id'] ? 'selected' : '' ?>>
                        <?= $this->escape($tech['full_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Szűrés</button>
            </div>
        </form>
    </div>
</div>

<?php if (!$technicianId && !empty($performanceData)): ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Összehasonlítás</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Szerelő</th>
                        <th>Munkalapok</th>
                        <th>Befejezett</th>
                        <th>Bevétel</th>
                        <th>Átlag bevétel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($performanceData as $data): ?>
                    <tr>
                        <td><?= $this->escape($data['technician']['full_name']) ?></td>
                        <td><?= $data['total_worksheets'] ?></td>
                        <td><?= $data['completed_worksheets'] ?? 0 ?></td>
                        <td><?= $this->formatPrice($data['total_revenue'] ?? 0) ?></td>
                        <td><?= $this->formatPrice($data['avg_revenue'] ?? 0) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>