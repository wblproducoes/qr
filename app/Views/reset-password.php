<h1>Reset Password</h1>
<?php
$session = new \App\Core\Session();
$errors = $session->getFlash('errors');
?>
<form action="/reset-password?token=<?= htmlspecialchars($token) ?>" method="post">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
        <?php if (isset($errors['password'])): ?>
            <div class="invalid-feedback"><?= $errors['password'][0] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
    </div>
    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>
