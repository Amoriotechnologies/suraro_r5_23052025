<style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    .error{
        color: red;
    }
    .invalid-feedback {
        font-size: 14px;
        color: #dc3545;
        display: block;
        margin-top: 5px;
    }
</style>
<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><?php if($id == 0){ echo "Add "; } else { echo "Edit "; }?>Customer</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php if($id == 0){ echo "Add "; } else { echo "Edit "; }?>Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form id="customer_insertdata">
                             <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
                             <?php if($id !== 0){ ?>
                                <input type="hidden" name="thisid" value="<?= $id; ?>">
                             <?php } ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Customer Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control onlytextnonumber" name="customer_name" id="customer_name" value="<?= isset($customer_data['customer_name']) ? $customer_data['customer_name'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Email Address</label>
                                        <input type="email" class="form-control" name="customer_email" id="customer_email" value="<?= isset($customer_data['customer_email']) ? $customer_data['customer_email'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Mobile Number</label>
                                        <input type="tel" name="customer_mobilenumber" class="form-control onlynumbersnoname" id="customer_mobilenumber" maxlength="10" value="<?= isset($customer_data['customer_mobilenumber']) ? $customer_data['customer_mobilenumber'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_address" class="form-control" id="customer_address" value="<?= isset($customer_data['customer_address']) ? $customer_data['customer_address'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" name="" class="form-label fs-14 text-dark">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control onlytextnonumber" name="customer_city" id="customer_city" value="<?= isset($customer_data['customer_city']) ? $customer_data['customer_city'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">State <span class="text-danger">*</span></label>
                                         <?php if(isset($customer_data['customer_state'])){  ?>
                                            <input type="hidden" id="" name="customer_state" value="<?= (isset($customer_data['customer_state'])) ? $customer_data['customer_state'] : '' ?>">
                                        <?php } ?>
                                        <select class="form-select" id="customer_state" name="customer_state" <?= isset($customer_data['customer_city']) ? "disabled" : ''; ?>>
                                            <option value="" disabled selected> - - - - - Select state - - - - - </option>
                                            <?php foreach ($states as $state): ?>
                                                <option value="<?= htmlspecialchars($state->id) ?>" <?= (isset($customer_data['customer_state']) && $customer_data['customer_state'] == $state->id) ? 'selected' : ''; ?> <?php if($state->name == "Tamil Nadu" && $id == 0){ echo "selected"; }?>><?= htmlspecialchars($state->name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div> 
                                </div>
                            </div> <br>
                            <button class="btn btn-success" id="submit_btn" type="submit">Save</button>
                            <a href="<?= base_url('customers'); ?>" class="btn btn-danger">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
      <?php if($id == 0){ ?>
        var thisurl = "<?= base_url('Customer/insert_customer'); ?>";
    <?php } else { ?>
        var thisurl = "<?= base_url('Customer/update_customer'); ?>";
    <?php } ?>
    $("#customer_insertdata").validate({
        rules: {
            customer_name: {
                required: true,
                validName: true
            },
            customer_address: {
                required: true,
                minlength: 5
            },
            customer_city: {
                required: true
            },
            customer_mobilenumber: {
                digits: true,
                minlength: 10,
                maxlength: 10
            }
        },
        messages: {
            customer_name: {
                required: "Name is required"
            },
            customer_address: {
                required: "Address is required",
                minlength: "Address must be at least 5 characters"
            },
            customer_city: {
                required: "City is required"
            },
            customer_mobilenumber: {
                digits: "Please enter only digits",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number must be 10 digits"
            }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            $('#submit_btn').prop('disabled', false);
            var formData = new FormData(form);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            formData.append('<?= $this->security->get_csrf_token_name(); ?>', csrfToken);
            $.ajax({
                type: 'POST',
                url: thisurl,
                data: formData,
                 dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response){
                     if (response.status === "success") {
                        showToast("Customer Details Saved Successfully", "success");
                        setTimeout(function () {
                            window.location.href = "<?= base_url('customers') ?>";
                        }, 3000);
                    } else if(response.status === "show error"){
                         showToast(response.message, "error");
                    }else {
                        showToast("Failed to Save Customer Details.", "error");
                    }
                }
            });
            return false;
        }
    });
});
function exitnumbers(input, maxLength) {
    input.value = input.value.replace(/\D/g, '');
    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
    }
}
document.getElementById('customer_email').addEventListener('input', function () {
    var emailInput = this;
    // Remove all whitespace characters from the input value
    emailInput.value = emailInput.value.replace(/\s/g, '');
    var emailValue = emailInput.value;
    // Regex to validate email (supports multiple domain extensions)
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (emailPattern.test(emailValue)) {
        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid');
        emailInput.setAttribute('aria-invalid', 'false');
    } else {
        emailInput.classList.remove('is-valid');
        emailInput.classList.add('is-invalid');
        emailInput.setAttribute('aria-invalid', 'true');
    }
});
</script>
