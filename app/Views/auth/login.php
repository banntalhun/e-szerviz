<?php
// app/Views/auth/login.php
?>
<form action="<?= $this->url('login') ?>" method="POST">
    <?= $this->csrfField() ?>
    
    <div class="mb-3">
        <label for="username" class="form-label">
            <i class="fas fa-user"></i> Felhasználónév
        </label>
        <input type="text" 
               class="form-control <?= $this->hasError('username') ? 'is-invalid' : '' ?>" 
               id="username" 
               name="username" 
               value="<?= $this->old('username') ?>" 
               required 
               autofocus>
        <?= $this->error('username') ?>
    </div>
    
    <div class="mb-4">
        <label for="password" class="form-label">
            <i class="fas fa-lock"></i> Jelszó
        </label>
        <input type="password" 
               class="form-control <?= $this->hasError('password') ? 'is-invalid' : '' ?>" 
               id="password" 
               name="password" 
               required>
        <?= $this->error('password') ?>
    </div>
    
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Bejelentkezés
        </button>
    </div>
</form>

<div class="mt-4 text-center text-muted small">
    <p class="mb-1">Demo belépési adatok:</p>
    <p class="mb-0">Felhasználó: <strong>admin</strong> | Jelszó: <strong>admin123</strong></p>
</div>
