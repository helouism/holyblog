<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin - <?= esc($title ?? "Dashboard") ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Pure CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pure/3.0.0/pure-min.css"
        integrity="sha512-X2yGIVwg8zeG8N4xfsidr9MqIfIE8Yz1It+w2rhUJMqxUwvbVqC5OPcyRlPKYOw/bsdJut91//NO9rSbQZIPRQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />>

    <!-- Summernote (WYSIWYG) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-lite.min.css"
        integrity="sha512-ySljI0ZbsJxjJIpfsg+7ZJOyKzBduAajCJpc4mBiVpGDPln2jVQ0kwYu3e2QQM5fwxLp6VSVaJm8XCK9uWD4uA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Filepond -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.32.9/filepond.min.css"
        integrity="sha512-Qtbx5LB6ocRgAyQ2YggWSyjNfzHjGMJvG0QWLzhBz0vQM8UG0M6Cmech2e4bxDttcDAlW2i1kIu46zp3EpYmrg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .admin-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            border-right: 1px solid #eee;
            padding: 1rem;
            background: #fafafa;
        }

        .sidebar a {
            display: block;
            padding: 0.5rem 0;
            color: #333;
            text-decoration: none;
        }

        .sidebar a.active {
            font-weight: bold;
        }

        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-content {
            padding: 1.5rem;
        }

        .badge-status {
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            font-size: 0.75rem;
        }

        .badge-status.published {
            background: #def3e4;
            color: #207245;
        }

        .badge-status.draft {
            background: #fdf2d7;
            color: #946200;
        }
    </style>
</head>

<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <h3><i class="fa-solid fa-user-shield"></i> Admin</h3>

            <a href="<?= route_to("admin.dashboard") ?>" class="<?= service("uri")->getSegment(1) === "admin" &&
                  !service("uri")->getSegment(2)
                  ? "active"
                  : "" ?>">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>

            <a href="<?= route_to("admin.posts.index") ?>" class="<?= service("uri")->getSegment(2) === "posts"
                  ? "active"
                  : "" ?>">
                <i class="fa-solid fa-file-lines"></i> Posts
            </a>

            <hr>
            <a href="<?= site_url(
                "logout",
            ) ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </aside>



        <div class="admin-main">
            <header class="admin-header">
                <h2><?= esc($title ?? "Dashboard") ?></h2>
            </header>
            <section class="admin-content">
                <?= $this->renderSection("content") ?>
            </section>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/htmx/2.0.7/htmx.min.js"
        integrity="sha512-IisGoumHahmfNIhP4wUV3OhgQZaaDBuD6IG4XlyjT77IUkwreZL3T3afO4xXuDanSalZ57Un+UlAbarQjNZCTQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-lite.min.js"
        integrity="sha512-sIOi8vwsJpzCHtHd06hxJa2umWfY1FfEEE0nGAaolGlR73EzNnQaWdijVyLueB0PSnIp8Mj2TDQLLHTiIUn1gw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.32.9/filepond.min.js"
        integrity="sha512-IE3iJDF02/or0l/tpLnEtzFX+9pm/H2bAr4hRGkEc5IJdI4IhGcukE/AlZ36E0y/+d2uBEsziz/vJ7PqzyAGUA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script>
        // FilePond global config
        document.addEventListener('DOMContentLoaded', function () {
            // Register plugins
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType
            );

            // Global options
            FilePond.setOptions({
                credits: false, // hide FilePond badge
                allowMultiple: false,
                maxFileSize: '3MB',
                acceptedFileTypes: ['image/*'],
                server: {
                    process: {
                        url: '<?= route_to('admin.uploads.cover') ?>',
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            // IMPORTANT: use X-CSRF-TOKEN as header name
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>',
                        },
                        // Server returns the filename string; use it as the field value
                        onload: (response) => response,
                        onerror: (response) => response
                    },
                    revert: null
                }
            });

            // Turn any image_cover input into FilePond instance
            const coverInput = document.querySelector('input[name="image_cover"]');
            if (coverInput) {
                FilePond.create(coverInput);
            }
        });
    </script>

    <script>
        // Reusable flash messages (success, error, etc.)
        <?php if (session()->getFlashdata("success")): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: <?= json_encode(session()->getFlashdata("success")) ?>,
                timer: 2500,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata("error")): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: <?= json_encode(session()->getFlashdata("error")) ?>,
            });
        <?php endif; ?>

        <?php if ($errors = session()->getFlashdata("errors")): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul>' +
                    <?php foreach ($errors as $e): ?>
                                            '<li><?= esc($e) ?></li>' +
                    <?php endforeach; ?>
                            '</ul>'
                    });
        <?php endif; ?>

        // Attach CSRF token to ALL htmx requests (POST, PUT, DELETE, etc.)
        document.body.addEventListener('htmx:configRequest', function (event) {
            event.detail.headers['X-CSRF-TOKEN'] = '<?= csrf_hash() ?>';
        });
    </script>

    <?= $this->renderSection("scripts") ?>

</body>

</html>