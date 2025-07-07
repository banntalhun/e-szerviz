<?php
$this->setData(['title' => 'Kimutatások']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-chart-bar"></i> Kimutatások</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Bevételi kimutatás</h5>
                <p class="card-text">Bevételek időszak szerint, top ügyfelek, legnépszerűbb szolgáltatások.</p>
                <a href="<?= $this->url('reports/revenue') ?>" class="btn btn-primary">Megnyitás</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-cog"></i> Szerelő teljesítmény</h5>
                <p class="card-text">Szerelők munkáinak statisztikái, hatékonyság elemzés.</p>
                <a href="<?= $this->url('reports/technician') ?>" class="btn btn-primary">Megnyitás</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-bicycle"></i> Eszköz statisztikák</h5>
                <p class="card-text">Eszközök állapota, javítási gyakoriság, költségek.</p>
                <a href="<?= $this->url('reports/device') ?>" class="btn btn-primary">Megnyitás</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users"></i> Ügyfél elemzés</h5>
                <p class="card-text">Top ügyfelek, látogatási gyakoriság, bevétel eloszlás.</p>
                <a href="<?= $this->url('reports/customer') ?>" class="btn btn-primary">Megnyitás</a>
            </div>
        </div>
    </div>
</div>