<h1>Generate QR Code</h1>
<form action="/generate" method="post">
    <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <input type="text" class="form-control" id="content" name="content" required>
    </div>
    <button type="submit" class="btn btn-primary">Generate</button>
</form>

<hr>

<h2>Your QR Codes (last 24 hours)</h2>
<div class="row">
    <?php if (empty($qrcodes)): ?>
        <p>No QR codes generated yet.</p>
    <?php else: ?>
        <?php foreach ($qrcodes as $qrcode): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?= $qrcode['image_path'] ?>" class="card-img-top" alt="QR Code">
                    <div class="card-body">
                        <p class="card-text"><?= htmlspecialchars($qrcode['content']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
