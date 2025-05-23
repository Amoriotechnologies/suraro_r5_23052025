<style>
#month_results {
   margin-left: 12px;
}
@media screen and (max-width: 482px) {
   #month_results {
      margin-left: 12px !important;
   }
   #month-filter {
      margin-left: 45px !important;
   }
}
.page-title {
   text-transform: uppercase;
}
.readmore {
   cursor: pointer;
}
.card.custom-card {
    min-height: 150px;
}
.bx-up-arrow:before {
    content: "\ea3a"; /* Unicode for bx-up-arrow */
}
</style>
<!-- Start::app-content -->
<div class="main-content app-content">
   <div class="container">
      <!-- Page Header -->
      <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
         <h1 class="page-title fw-semibold fs-18 mb-0">Dashboard</h1>
         <div class="ms-md-1 ms-0">
            <div class="input-group">
               <input type="text" name="datepicker" id="datepicker" class="form-control datepicker" />               
               <label for="datepicker" class="btn btn-dark"> <i class="bx bx-calendar"></i> </label>
            </div>
         </div>
      </div>
      <!-- Page Header Close -->
   
      <?php
         if(!empty($cmpys)) { 
            $cmpycnt = count($cmpys);
            $colors = ['success', 'warning'];
            $i = 0;
            foreach($cmpys as $key => $value) { ?>
         <div class="row">
            <h1 class="page-title fw-semibold text-center fs-18 mb-3">
               <span class="badge bg-primary fs-6"> <i class="bx bx-buildings"></i> <?= $value['companyname']; ?> </span>
            </h1>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
               <div class="card custom-card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-6 pe-0">
                           <p class="mb-2">
                              <span class="fs-5">Sales</span>
                           </p>
                           <p class="mb-2 fs-12">
                              <span class="fs-25 fw-semibold lh-1 vertical-bottom sales<?= $value['id']; ?>">₹0</span>
                           </p>
                        </div>
                        <div class="col-6">
                           <i class="bx bx-archive-in text-<?= $colors[$i]; ?> fs-1 main-card-icon"></i>
                        </div>
                     </div>
                     <p class="readmore w-25 text-info" onclick="cmpySelection('sales', '<?= urlencode(base64_encode($value['id'])); ?>')"> Show Details <i class="ti ti-chevron-right ms-1"></i></p>
                  </div>
               </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
               <div class="card custom-card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-6 pe-0">
                           <p class="mb-2">
                              <span class="fs-5">Expense</span>
                           </p>
                           <p class="mb-2 fs-12">
                              <span class="fs-25 fw-semibold lh-1 vertical-bottom expense<?= $value['id']; ?>">₹0</span>
                           </p>
                        </div>
                        <div class="col-6">
                           <i class="bx bx-archive-out text-<?= $colors[$i + 1]; ?> fs-1 main-card-icon"></i>
                        </div>
                     </div>
                     <p class="readmore w-25 text-info" onclick="cmpySelection('expense', '<?= urlencode(base64_encode($value['id'])); ?>')"> Show Details <i class="ti ti-chevron-right ms-1"></i></p>
                  </div>
               </div>
            </div>
            <hr>
         </div>
      <?php } } ?>
   </div>
</div>
<script>
$(document).ready(function() {
   var csrfName = "<?= $this->security->get_csrf_token_name() ?>";
   var csrfHash = "<?= $this->security->get_csrf_hash() ?>";
   $(function() {
   var start = moment().startOf('month');
   var end = moment().endOf('month');
   function customrange(start, end) {
      $('#reportrange span').html(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
   }
   $('.datepicker').daterangepicker({
      locale: {
         format: 'DD-MM-YYYY',
         separator: ' to ',
         monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      },
      opens: 'left',
      startDate: start,
      endDate: end,
      ranges: {
         'This Month': [moment().startOf('month'), moment().endOf('month')],
         'Today': [moment(), moment()],
         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
   }, customrange);
   customrange(start, end);
});
   $('body').on('change', '.datepicker, .applyBtn', function() {
      var datefilter = $('.datepicker').val();
      var cmpycnt = '<?= $cmpycnt; ?>';
      $.ajax({
         url: 'getDashboardData',
         type: 'POST',
         data: {[csrfName]: csrfHash, 'calfilter': datefilter},
         dataType: 'json',
         success:function(res) {
            $('.tot_sales, .tot_expense').html('₹0');
            for(var i = 1; i <= cmpycnt; i++) {
               $('.sales'+i).html('₹'+0);
               $('.expense'+i).html('₹'+0);
            }
            const result = Object(res);
            if(res.sales) {
               $.each(result.sales, function(key, value) {
                  $('.sales'+key).html('₹'+value);
                  $('.tot_sales').html('₹'+result.sales.total);
               });
            }
            if(res.expense) {
               $.each(result.expense, function(key, value) {
                  $('.expense'+key).html('₹'+value);
                  $('.tot_expense').html('₹'+result.expense.total);
               });
            }
         },
         error:function(xhr, status, error) {}
      });
   });
});
// redirect with respective page
function cmpySelection(type, cmpyid) {
   var calendar = $('#datepicker').val();
   var path = (type == 'sales') ? 'invoice' : 'expense';
   var calendar = calendar.replace(/\s+/g, '_');
   window.location.href = path+'?cmpyid='+cmpyid+'&datefilter='+calendar
}
</script>