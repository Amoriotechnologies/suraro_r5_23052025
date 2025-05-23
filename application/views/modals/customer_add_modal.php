<div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                             <input type="hidden" name="<?= $this->security->get_csrf_token_name();?>" value="<?= $this->security->get_csrf_hash();?>">
                             <?php if($id !== 0){ ?>
                                <input type="hidden" name="thisid" value="<?= $id; ?>">
                             <?php } ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Customer Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control onlytextnonumber" name="customer_name" id="customer_name" value="<?= isset($customer_data['customer_name']) ? $customer_data['customer_name'] : ''; ?>" >
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Email Address<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="customer_email" id="customer_email" value="<?= isset($customer_data['customer_email']) ? $customer_data['customer_email'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Mobile Number<span class="text-danger">*</span></label>
                                        <input type="tel" name="customer_mobilenumber" class="form-control onlynumbersnoname" id="customer_mobilenumber" maxlength="10" value="<?= isset($customer_data['customer_mobilenumber']) ? $customer_data['customer_mobilenumber'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">Address</label>
                                        <input type="text" name="customer_address" class="form-control" id="customer_address" value="<?= isset($customer_data['customer_address']) ? $customer_data['customer_address'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" name="" class="form-label fs-14 text-dark">City</label>
                                        <input type="text" class="form-control onlytextnonumber" name="customer_city" id="customer_city" value="<?= isset($customer_data['customer_city']) ? $customer_data['customer_city'] : ''; ?>">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="form-text" class="form-label fs-14 text-dark">State</label>
                                        <select  id="customer_state" name="customer_state" class="form-control">
                                            <option value="" disabled selected> - - - - - Select state - - - - - </option>
                                            <?php if($states): foreach ($states as $state): ?>
                                                <option value="<?= htmlspecialchars($state->id) ?>" <?= (isset($customer_data['customer_state']) && $customer_data['customer_state'] == $state->id) ? 'selected' : ''; ?> <?php if($state->name == "Tamil Nadu" && $id == 0){ echo "selected"; }?>><?= htmlspecialchars($state->name); ?></option>
                                            <?php endforeach;
                                            	endif; ?>
                                        </select>
                                    </div> 
                                </div>
                            </div> 
                    </div>
                </div>
            </div>
        </div>