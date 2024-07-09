<?php
require 'html.php';
?>
<body>

<div class="container mt-5">
    <h1 class="text-center">O'z hisob</h1>
    <?php

    date_default_timezone_set('Asia/Tashkent');

    require 'pwot.php';

    require_once 'logic.php';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $total_pages = $tracker->getTotalPages(5);
    require 'form.php';
    ?>

    
    <?php
    $tracker->fetchRecords($page);
    require 'pagination.php';
    ?>
    
</div>

<?php
require 'modal-form.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var confirmModal = document.getElementById('confirmModal');
    confirmModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var modalInput = document.getElementById('workedOffId');
        modalInput.value = id;
    });
</script>
</body>
</html>
