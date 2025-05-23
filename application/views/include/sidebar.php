<aside class="app-sidebar sticky" id="sidebar">
   <!-- Start::app-sidebar -->
   <!-- Start::main-sidebar-header -->
   <div class="main-sidebar-header">
      <a href="#" class="header-logo">
      <img src="<?= base_url('assets/images/invoicelogo.png'); ?>" alt="logo" class="desktop-logo">
      <img src="<?= base_url('assets/images/invoicelogo.png'); ?>" alt="logo" class="toggle-logo">
      <img src="<?= base_url('assets/images/invoicelogo.png'); ?>" alt="logo" class="desktop-dark">
      <img src="<?= base_url('assets/images/invoicelogo.png'); ?>" alt="logo" class="toggle-dark">
      <img src="<?= base_url('assets/images/invoicelogo.png'); ?>" alt="logo" class="desktop-white">
      <img src="<?= base_url('assets/images/invoicelogo.png'); ?>" alt="logo" class="toggle-white">
      </a>
   </div>
   <!-- End::main-sidebar-header -->
   <!-- Start::main-sidebar -->
   <div class="main-sidebar" id="sidebar-scroll">
      <!-- Start::nav -->
      <nav class="main-menu-container nav nav-pills flex-column sub-open">
         <div class="slide-left" id="slide-left">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
               <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
            </svg>
         </div>
         <ul class="main-menu">
    <li class="slide__category"><span class="category-name">Menus</span></li>
    <li class="slide has-sub <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
        <a href="<?= base_url('dashboard') ?>" class="side-menu__item">
            <i class="bx bxs-dashboard side-menu__icon"></i>
            <span class="side-menu__label">Dashboard</span>
        </a>
        <ul class="slide-menu child1 <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
            <li class="slide side-menu__label1">
                <a href="<?= base_url('dashboard') ?>">Dashboard</a>
            </li>
        </ul>
    </li>
    <li class="slide has-sub <?= (uri_string() == 'invoice') ? 'active' : '' ?>">
        <a href="<?= base_url('invoice') ?>" class="side-menu__item">
            <i class="bx bx-file-blank side-menu__icon"></i>
            <span class="side-menu__label">Invoice</span>
        </a>
        <ul class="slide-menu child1 <?= (uri_string() == 'invoice') ? 'active' : '' ?>">
            <li class="slide side-menu__label1">
                <a href="<?= base_url('invoice') ?>">Invoice</a>
            </li>
        </ul>
    </li>
    <li class="slide has-sub <?= (uri_string() == 'expense') ? 'active' : '' ?>">
        <a href="<?= base_url('expense') ?>" class="side-menu__item">
            <i class="bx bx-file-blank side-menu__icon"></i>
            <span class="side-menu__label">Expense</span>
        </a>
        <ul class="slide-menu child1 <?= (uri_string() == 'expense') ? 'active' : '' ?>">
            <li class="slide side-menu__label1">
                <a href="<?= base_url('expense') ?>">Expense</a>
            </li>
        </ul>
    </li>
    <li class="slide has-sub <?= (uri_string() == 'customers') ? 'active' : '' ?>">
        <a href="<?= base_url('customers') ?>" class="side-menu__item">
            <i class="bx bx-user side-menu__icon"></i>
            <span class="side-menu__label">Customers</span>
        </a>
        <ul class="slide-menu child1 <?= (uri_string() == 'customers') ? 'active' : '' ?>">
            <li class="slide side-menu__label1">
                <a href="<?= base_url('customers') ?>">Customers</a>
            </li>
        </ul>
    </li>
</ul>
         <div class="slide-right" id="slide-right">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
               <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
            </svg>
         </div>
      </nav>
      <!-- End::nav -->
   </div>
   <!-- End::main-sidebar -->
</aside>