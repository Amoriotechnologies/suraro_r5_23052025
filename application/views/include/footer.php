<footer class="footer mt-auto py-3 bg-white text-center">
    <div class="container">
        <span class="text-muted"> Copyright © 2025 <span id="year"></span>Designed & Developed <span
                class="bi bi-heart-fill text-danger"></span> by <a href="https://amoriotech.com/" target="_blank">
                <span class="fw-semibold text-primary text-decoration-underline">Amorio Technologies</span></a>
        </span>
    </div>
</footer>
</div>
<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>
<script type="text/javascript">
$(document).ready(function () {
    $('.text-number-only').on('input', function () {
        let value = $(this).val();
        // Remove special characters except space
        value = value.replace(/[^a-zA-Z0-9 ]/g, '');
        // Replace multiple spaces with a single space
        value = value.replace(/\s{2,}/g, ' ');
        // Remove leading spaces
        value = value.replace(/^\s+/, '');
        // Set the cleaned value back to the input
        $(this).val(value);
    });
});
   document.querySelectorAll('input[type="text"], input[type="email"], input[type="search"], input[type="tel"]').forEach(function(inputField) {
    inputField.addEventListener('input', function () {
        // Replace multiple spaces with a single space
        this.value = this.value.replace(/\s{2,}/g, ' ');
    });
});
    function showToast(message, type = 'success') {
        let backgroundColor;
        if (type === 'success') {
            backgroundColor = "linear-gradient(to right, #28a745, #218838)";
        } else if (type === 'error') {
            backgroundColor = "linear-gradient(to right, #ff4d4d, #ff0000)";
        }
        Toastify({
            text: message,
            backgroundColor: backgroundColor,
            gravity: "top",
            position: 'right',
            duration: 3000,
            close: true
        }).showToast();
    }
    $.validator.addMethod("validName", function (value, element) {
        return this.optional(element) || /^[A-Za-z]+(?: [A-Za-z]+)*$/.test(value.trim());
    }, "Only letters and single spaces allowed (no special characters or numbers)");
</script>
<!-- Scroll To Top -->
<!-- toastify JS -->
<script src="<?= base_url('assets/js/toastify.js'); ?>"></script>
<!-- toastify JS -->
<script src="<?= base_url('assets/js/sweet-alert.min.js'); ?>"></script>
<!-- Popper JS -->
<script src="<?= base_url('assets/libs/@popperjs/core/umd/popper.min.js'); ?>"></script>
<!-- Bootstrap JS -->
<script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- Defaultmenu JS -->
<script src="<?= base_url('assets/js/defaultmenu.min.js'); ?>"></script>
<!-- Node Waves JS-->
<script src="<?= base_url('assets/libs/node-waves/waves.min.js'); ?>"></script>
<!-- Sticky JS -->
<script src="<?= base_url('assets/js/sticky.js'); ?>"></script>
<!-- Simplebar JS -->
<script src="<?= base_url('assets/libs/simplebar/simplebar.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/simplebar.js'); ?>"></script>
<!-- Color Picker JS -->
<script src="<?= base_url('assets/libs/@simonwep/pickr/pickr.es5.min.js'); ?>"></script>
<!-- Apex Charts JS -->
<script src="<?= base_url('assets/libs/apexcharts/apexcharts.min.js'); ?>"></script>
<!-- JSVector Maps JS -->
<script src="<?= base_url('assets/libs/jsvectormap/js/jsvectormap.min.js'); ?>"></script>
<!-- JSVector Maps MapsJS -->
<script src="<?= base_url('assets/libs/jsvectormap/maps/world-merc.js'); ?>"></script>
<!-- Date & Time Picker JS -->
<script src="<?= base_url('assets/libs/flatpickr/flatpickr.min.js'); ?>"></script>
<!-- Sales-Dashboard JS -->
<script src="<?= base_url('assets/js/sales-dashboard.js'); ?>"></script>
<!-- Custom JS -->
<script src="<?= base_url('assets/js/mycustom.js'); ?>"></script>
<!-- Chartjs Chart JS -->
<script src="<?= base_url('assets/libs/chart.js/chart.min.js'); ?>"></script>
<!--dataTables.min.js -->
<script src="<?= base_url('assets/datatable/dataTables.min.js'); ?>"></script>
<!-- Datatables Cdn -->
<script src="<?= base_url('assets/datatable/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/dataTables.buttons.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/buttons.html5.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/buttons.print.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/dataTables.bootstrap5.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/jszip.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/pdfmake.min.js'); ?>"></script>
<script src="<?= base_url('assets/datatable/vfs_fonts.js'); ?>"></script>
</body>
</html>