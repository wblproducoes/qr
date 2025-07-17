<h1>Login</h1>
<?php
$session = new \App\Core\Session();
$errors = $session->getFlash('errors');
?>
<form action="/login" method="post">
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" required>
        <?php if (isset($errors['email'])): ?>
            <div class="invalid-feedback"><?= $errors['email'][0] ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
        <?php if (isset($errors['password'])): ?>
            <div class="invalid-feedback"><?= $errors['password'][0] ?></div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <a href="/forgot-password" class="float-end">Forgot Password?</a>
</form>
