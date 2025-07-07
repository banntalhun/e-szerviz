<?php
$this->setData(['title' => $user ? 'Felhasználó szerkesztése' : 'Új felhasználó']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">
            <i class="fas fa-user-<?= $user ? 'edit' : 'plus' ?>"></i> 
            <?= $user ? 'Felhasználó szerkesztése' : 'Új felhasználó' ?>
        </h1>
    </div>
</div>

<form method="POST" action="<?= $user ? $this->url('admin/users/' . $user['id'] . '/update') : $this->url('admin/users/store') ?>">
    <?= $this->csrfField() ?>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Felhasználónév *</label>
                    <input type="text" name="username" class="form-control" value="<?= $this->escape($user['username'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teljes név *</label>
                    <input type="text" name="full_name" class="form-control" value="<?= $this->escape($user['full_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= $this->escape($user['email'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control" value="<?= $this->escape($user['phone'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jelszó <?= !$user ? '*' : '(üresen hagyva nem változik)' ?></label>
                    <input type="password" name="password" class="form-control" <?= !$user ? 'required' : '' ?>>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jelszó megerősítés <?= !$user ? '*' : '' ?></label>
                    <input type="password" name="password_confirmation" class="form-control" <?= !$user ? 'required' : '' ?>>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Szerepkör *</label>
                    <select name="role_id" class="form-select" required>
                        <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= ($user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                            <?= $this->escape($role['display_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telephely</label>
                    <select name="location_id" class="form-select">
                        <option value="">- Nincs -</option>
                        <?php foreach ($locations as $location): ?>
                        <option value="<?= $location['id'] ?>" <?= ($user['location_id'] ?? '') == $location['id'] ? 'selected' : '' ?>>
                            <?= $this->escape($location['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                               <?= ($user['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            Aktív felhasználó
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Mentés
            </button>
            <a href="<?= $this->url('admin/users') ?>" class="btn btn-secondary">Mégsem</a>
        </div>
    </div>
</form>