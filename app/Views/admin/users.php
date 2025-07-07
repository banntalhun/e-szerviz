<?php
$this->setData(['title' => 'Felhasználók']);
?>
<div class="row mb-4">
    <div class="col-md-6">
        <h1 class="h3"><i class="fas fa-users"></i> Felhasználók</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= $this->url('admin/users/create') ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Új felhasználó
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Felhasználónév</th>
                        <th>Név</th>
                        <th>Email</th>
                        <th>Szerepkör</th>
                        <th>Aktív</th>
                        <th>Munkalapok</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagination['items'] as $user): ?>
                    <tr>
                        <td><?= $this->escape($user['username']) ?></td>
                        <td><?= $this->escape($user['full_name']) ?></td>
                        <td><?= $this->escape($user['email']) ?></td>
                        <td><?= $this->escape($user['role']['display_name'] ?? '-') ?></td>
                        <td>
                            <?php if ($user['is_active']): ?>
                                <span class="badge bg-success">Aktív</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inaktív</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $user['worksheet_count'] ?></td>
                        <td>
                            <a href="<?= $this->url('admin/users/' . $user['id'] . '/edit') ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>