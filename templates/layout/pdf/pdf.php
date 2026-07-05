<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?= h($this->fetch('title')) ?></title>
    <style>
        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
        }
        .pdf-container {
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        <?= $this->fetch('content') ?>
    </div>
</body>
</html>
