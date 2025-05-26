<div>
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        @livewire('admin.dashboard.sales-this-week')
                    </div>
                    <!-- Add other stat cards here -->
                    <div class="col-6 col-lg-3 col-md-6">
                        @livewire('admin.dashboard.sales-this-week-amount')
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        @livewire('admin.dashboard.reparations-this-week')
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        @livewire('admin.dashboard.products-count')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        @livewire('admin.dashboard.users-location')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-xl-6">
                        @livewire('admin.dashboard.new-orders')
                    </div>
                    <div class="col-12 col-xl-6">
                        @livewire('admin.dashboard.new-reparations')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-xl-6">
                        @livewire('admin.dashboard.top-selling-products')
                    </div>
                    <div class="col-12 col-xl-6">
                        @livewire('admin.dashboard.low-stock-products')
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
