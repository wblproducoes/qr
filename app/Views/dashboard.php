<h1>Dashboard</h1>
<h2>Generate New QR Code</h2>
<form action="/generate" method="post">
    <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <input type="text" class="form-control" id="content" name="content" required>
    </div>
    <button type="submit" class="btn btn-primary">Generate</button>
</form>

<hr>

<h2>Your Saved QR Codes</h2>
<div class="row">
    <?php if (empty($qrcodes)): ?>
        <p>No QR codes saved yet.</p>
    <?php else: ?>
        <?php foreach ($qrcodes as $qrcode): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?= $qrcode['image_path'] ?>" class="card-img-top" alt="QR Code">
                    <div class="card-body">
                        <p class="card-text"><?= htmlspecialchars($qrcode['content']) ?></p>
                        <form action="/delete-qrcode" method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $qrcode['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
