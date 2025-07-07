<?php
$this->setData(['title' => 'Bevételi kimutatás']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-money-bill-wave"></i> Bevételi kimutatás</h1>
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
                <label>Csoportosítás</label>
                <select name="group_by" class="form-select">
                    <option value="day" <?= $groupBy == 'day' ? 'selected' : '' ?>>Napi</option>
                    <option value="week" <?= $groupBy == 'week' ? 'selected' : '' ?>>Heti</option>
                    <option value="month" <?= $groupBy == 'month' ? 'selected' : '' ?>>Havi</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Szűrés</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Összes bevétel</h5>
                <h2><?= $this->formatPrice($summary['total_revenue'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Fizetett</h5>
                <h2><?= $this->formatPrice($summary['paid_revenue'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Fizetetlen</h5>
                <h2><?= $this->formatPrice($summary['unpaid_revenue'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Munkalapok</h5>
                <h2><?= $summary['total_worksheets'] ?? 0 ?></h2>
            </div>
        </div>
    </div>
</div>