<?php
$this->setData(['title' => 'Admin']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-user-shield"></i> Adminisztráció</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users"></i> Felhasználók</h5>
                <p class="card-text">Felhasználók kezelése, szerepkörök beállítása.</p>
                <a href="<?= $this->url('admin/users') ?>" class="btn btn-primary">Kezelés</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-cog"></i> Beállítások</h5>
                <p class="card-text">Rendszer beállítások, törzsadatok kezelése.</p>
                <a href="<?= $this->url('admin/settings') ?>" class="btn btn-primary">Beállítások</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-key"></i> Jogosultságok</h5>
                <p class="card-text">Szerepkör jogosultságok beállítása.</p>
                <a href="<?= $this->url('admin/permissions') ?>" class="btn btn-primary">Jogosultságok</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Rendszer információk</h5>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Felhasználók száma:</dt>
            <dd class="col-sm-9"><?= $stats['total_users'] ?></dd>
            
            <dt class="col-sm-3">Aktív felhasználók:</dt>
            <dd class="col-sm-9"><?= $stats['active_users'] ?></dd>
            
            <dt class="col-sm-3">Szerepkörök:</dt>
            <dd class="col-sm-9"><?= $stats['total_roles'] ?></dd>
            
            <dt class="col-sm-3">Telephelyek:</dt>
            <dd class="col-sm-9"><?= $stats['total_locations'] ?></dd>
        </dl>
    </div>
</div>