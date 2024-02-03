<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection("pageTitle") ?> | Savoria Technical Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/app.css">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <div class="py-5 min-vh-100 bg-secondary-subtle">
        <div class="container">
            <div class="card">
                <h1 class="card-header fs-3"><?= $this->renderSection("title") ?></h1>
                <div class="card-body shadow">
                    <?= $this->renderSection("content") ?>
                </div>

            </div>
        </div>
    </div>

    <?php if (session("notif")) : ?>
        <div x-data="{ notif: '<?= toJSON(session("notif")) ?>' }" x-init="toast.fire({
            icon: JSON.parse(notif).type,
            title: JSON.parse(notif).message,
        })"></div>
    <?php endif ?>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
</body>

</html>