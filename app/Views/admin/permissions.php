<?php
$this->setData(['title' => 'Jogosultságok']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-key"></i> Jogosultságok kezelése</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= $this->url('admin/permissions/update') ?>">
            <?= $this->csrfField() ?>
            
            <div class="mb-4">
                <label class="form-label">Szerepkör kiválasztása:</label>
                <select name="role_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>" <?= ($_POST['role_id'] ?? 1) == $role['id'] ? 'selected' : '' ?>>
                        <?= $this->escape($role['display_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php $selectedRole = $_POST['role_id'] ?? 1; ?>
            
            <h5>Jogosultságok:</h5>
            
            <?php foreach ($permissionsByCategory as $category => $perms): ?>
            <div class="mb-3">
                <h6 class="text-muted"><?= ucfirst($category) ?></h6>
                <?php foreach ($perms as $permission): ?>
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="permissions[]" 
                           value="<?= $permission['id'] ?>"
                           id="perm_<?= $permission['id'] ?>"
                           <?= in_array($permission['id'], $rolePermissions[$selectedRole] ?? []) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="perm_<?= $permission['id'] ?>">
                        <?= $this->escape($permission['display_name']) ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Mentés
            </button>
        </form>
    </div>
</div>