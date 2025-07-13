@extends('layouts.admin.main')

@push('styles')
@endpush

@push('script')
    <script src="{{ asset('assets/public/plugins/custom/fullcalendar/fullcalendar.bundle.js'); }}"></script>
    <script src="{{ asset('assets/admin/js/modul/dashboard/calendar.js'); }}"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/indonesiaLow.js"></script>

    <!-- BAR SCRIPT -->
    <!-- CHART -->
    <script>
    am5.ready(function() {

    // Ambil nilai var(--bs-primary) dari CSS
    var primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--bs-primary').trim();

    // Create root element
    var root = am5.Root.new("grafik_training");

    // Set themes
    root.setThemes([
        am5themes_Animated.new(root)
    ]);

    // Create chart
    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true,
        paddingLeft:0,
        paddingRight:1
    }));

    // Add cursor
    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    // Create axes
    var xRenderer = am5xy.AxisRendererX.new(root, { 
        minGridDistance: 30, 
        minorGridEnabled: true
    });

    xRenderer.labels.template.setAll({
        visible: false
    });

    xRenderer.grid.template.setAll({
        location: 1
    });

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        maxDeviation: 0.3,
        categoryField: "training",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {})
    }));

    var yRenderer = am5xy.AxisRendererY.new(root, {
        strokeOpacity: 0.1
    });

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        maxDeviation: 0.3,
        renderer: yRenderer
    }));

    // Create series
    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Trafik Sesi",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "value",
        sequencedInterpolation: true,
        categoryXField: "training",
        tooltip: am5.Tooltip.new(root, {
        labelText: "{valueY} Member"
        })
    }));

    series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });

    // Gunakan var(--bs-primary) untuk warna chart
    series.columns.template.adapters.add("fill", function () {
        return am5.color(primaryColor);
    });

    series.columns.template.adapters.add("stroke", function () {
        return am5.color(primaryColor);
    });

    // Set data
    var data = <?= $grafik; ?>

    xAxis.data.setAll(data);
    series.data.setAll(data);

    // Make stuff animate on load
    series.appear(1000);
    chart.appear(1000, 100);

    }); // end 
    am5.ready();

    </script>
@endpush


@section('content')
<!--begin::Container-->
<div class="container-xxl" id="kt_content_container">
	<!--begin::Row-->

    <div class="row gx-5 gx-xl-10 mb-xl-10">
         <!--begin::Col-->
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">
        <!--begin::Card widget 16-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 h-md-100 mb-3 mb-xl-6 shadow-sm">
            <!--begin::Card body-->
            <div class="card-body d-flex justify-content-center py-7 flex-column">
                 <!--begin::Amount-->
                <div class="fs-1 fw-bold text-dark me-2 lh-1 ls-n2"><i class="fa-solid {{ (salamWaktu()->dark == true) ? 'fa-cloud-moon' : 'fa-cloud-sun' }}"></i> {{ salamWaktu()->message }} <span class="text-primary">{{ session(config('session.prefix') . '_name') }}</span></div>
                <!--end::Amount-->
                <span class="text-dark opacity-50 pt-1 mt-3 fw-semibold fs-6">Selamat datang di Website Rekomendasi Pelatihan Menggunakan Metode K-Nearest Neighbor (KNN) </span>
                <!--end::Subtitle-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 16-->
        </div>
        <!--end::Col-->
    </div>
    
    <div class="row gx-5 gx-xl-10 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 mb-5">
        <!--begin::Card widget 16-->
        <div class="card card-custom bgi-no-repeat gutter-b card-stretch border-0 h-md-100 mb-5 mb-xl-10 shadow-sm bgi-size-contain bgi-position-x-center" style="background-position: right top; background-size: 30% auto; background-image: url({{ asset('assets/admin/svg/abstract.svg') }});background-color: var(--bs-primary);">
            <!--begin::Card body-->
            <div class="card-body d-flex justify-content-center py-7 flex-column">
                 <!--begin::Amount-->
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ (isset($cnt_training) && $cnt_training) ? number_format($cnt_training,0,',','.') : 0 }}</span>
                <!--end::Amount-->
                <span class="text-white opacity-50 pt-1 mt-3 fw-semibold fs-6">Total Pelatihan</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

        
        <!--begin::Col-->
        <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 mb-5">
        <!--begin::Card widget 16-->
        <div class="card card-custom bgi-no-repeat gutter-b card-stretch border-0 h-md-100 mb-5 mb-xl-10 shadow-sm bgi-size-contain bgi-position-x-center" style="background-position: calc(100% + 20px) calc(0% + 10px);background-size: 30% auto; background-image: url({{ asset('assets/admin/svg/database.svg') }});">
            <!--begin::Card body-->
            <div class="card-body d-flex justify-content-center py-7 flex-column">
                  <!--begin::Amount-->
                <span class="fw-bold text-primary me-2 lh-1 ls-n2" style="font-size : 25px;">{{ (isset($cnt_admin) && $cnt_admin) ? number_format($cnt_admin,0,',','.') : 0 }}</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-dark opacity-50 pt-1 mt-3 fw-semibold fs-6">Total Admin</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 mb-5">
        <!--begin::Card widget 16-->
        <div class="card card-custom bgi-no-repeat gutter-b card-stretch border-0 h-md-100 mb-5 mb-xl-10 shadow-sm bgi-size-contain bgi-position-x-center" style="background-position: calc(100% + 20px) calc(0% + 10px);background-size: 30% auto; background-image: url({{ asset('assets/admin/svg/users.svg') }});">
            <!--begin::Card body-->
            <div class="card-body d-flex justify-content-center py-7 flex-column">
                  <!--begin::Amount-->
                <span class="fw-bold text-primary me-2 lh-1 ls-n2" style="font-size : 25px;">{{ (isset($cnt_user) && $cnt_user) ? number_format($cnt_user,0,',','.') : 0 }}</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-dark opacity-50 pt-1 mt-3 fw-semibold fs-6">Total Member</span>
                <!--end::Subtitle-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

    </div>
    <!--end::Row-->
    
    <div class="row gx-5 gx-xl-10 mb-xl-10">
        <div class="col-xl-12 mb-7">
            <div class="card mb-5 mb-xl-8">
                <!--begin::Body-->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!--begin::Page title-->
                    <div class="page-title d-flex align-items-center">
                        <!--begin::Title-->
                        <h1 class="d-flex text-primary fw-bold m-0 fs-3">Trafic Laporan</h1>
                        <!--end::Title-->
                    </div>
                </div>
                <div class="card-body py-3" >
                    <div id="grafik_training" style="min-height : 450px; height : auto;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Container-->
@endsection