<style>
  .welcome {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
  }
</style>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<section class="content">
  <div class="container-fluid">
    <!-- <img src="<?= base_url('assets') ?>/dist/img/welcome.jpg" class="brand-image img-circle welcome" style="opacity: .8"> -->
    <div class="row">
      <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3 class="joining-member"></h3>

            <p>Total Member Joining This Year</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3 class="earned-point"></h3>

            <p>Total Earned Points This Year</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3 class="redeemed-point"></h3>

            <p>Total Redeemed Points This Year</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
        </div>
      </div>
      <?php /*
      <div class="col-lg-3 col-6">

        <div class="small-box bg-danger">
          <div class="inner">
            <h3>65</h3>

            <p>Unique Visitors</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
        </div>
        */ ?>
    </div>
  </div>

  <div class="row">
    <section class="col-lg-6 connectedSortable">
      <div class="card bg-gradient-info">
        <div class="card-header border-0">
          <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            Member data per year
          </h3>

          <div class="card-tools">
            <?php /*
            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
             */ ?>
          </div>
        </div>
        <div class="card-body">
          <canvas class="chart" id="line-chart" style="min-height: 300px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
        <?php /* 
        <div class="card-footer bg-transparent">
          <div class="row">
            <div class="col-4 text-center">
              <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgColor="#39CCCC">
              <div class="text-white">Mail-Orders</div>
            </div>
            <div class="col-4 text-center">
              <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC">
              <div class="text-white">Online</div>
            </div>
            <div class="col-4 text-center">
              <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#39CCCC">
              <div class="text-white">In-Store</div>
            </div>
          </div>
        </div>
        */ ?>
      </div>



    </section>
    <section class="col-lg-6 connectedSortable">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            <b class="title-carts"></b>

          </h3>
          <div class="card-tools">
            <ul class="nav nav-pills ml-auto">
              <li class="nav-item">
                <a class="nav-link active graf" id="graf" href="#revenue-chart" data-toggle="tab">Monthly</a>
              </li>
              <li class="nav-item">
                <a class="nav-link graf" id="dount" href="#sales-chart" data-toggle="tab">Total</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
              <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
            </div>
            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
              <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  </div>
</section>
</div>
</div>
</section>
<aside class="control-sidebar control-sidebar-dark">
</aside>
</div>

<script src="<?= base_url('assets') ?>/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/chart.js/Chart.min.js"></script>

<script>
  $(document).ready(function() {

    $(".title-carts").html("Monthly member data this year");

    $(".graf").click(function() {
      var prop = $(this).prop("id");
      if (prop == "dount") {
        $(".title-carts").html("Member data this year")
      } else {
        $(".title-carts").html("Monthly member data this year")
      }
    })
  })


  $.ajax({
    url: '<?= base_url("backend/dashboard/get_data_dashboard") ?>',
    type: "POST",
    dataType: "json",
    success: function(response) {
      //console.log(response);
      $(".joining-member").html(parseInt(response.member_jioning_this_year.total) + '<sup style="font-size: 20px"> Members</sup>');
      $(".earned-point").html(parseInt(response.earned_point_this_year.total) + '<sup style="font-size: 20px"> Points</sup>');
      $(".redeemed-point").html(parseInt(20) + '<sup style="font-size: 20px"> Points</sup>');
      $(function() {
        var salesChartCanvas = document
          .getElementById("revenue-chart-canvas")
          .getContext("2d");

        var salesChartData = {
          labels: response.data_member_carts, // sini
          datasets: [{
            label: "Digital Goods",
            backgroundColor: "rgba(60,141,188,0.9)",
            borderColor: "rgba(60,141,188,0.8)",
            pointRadius: false,
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: response.data_member_carts_total, //sini
          }],
        };

        var salesChartOptions = {
          maintainAspectRatio: false,
          responsive: true,
          legend: {
            display: false,
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: false,
              },
            }, ],
            yAxes: [{
              gridLines: {
                display: false,
              },
            }, ],
          },
        };

        // DONAT //
        var salesChart = new Chart(salesChartCanvas, {
          type: "line",
          data: salesChartData,
          options: salesChartOptions,
        });

        var pieChartCanvas = $("#sales-chart-canvas").get(0).getContext("2d");
        var pieData = {
          labels: ["Total Member Joining This Year", "Total Earned Points This Year", "Total Redeemed Points This Year"],
          datasets: [{
            data: response.data_member_donut, // sini
            backgroundColor: ["#17a2b8", '#00a65a', '#f39c12'],
          }, ],
        };
        var pieOptions = {
          legend: {
            display: false,
          },
          maintainAspectRatio: false,
          responsive: true,
        };

        var pieChart = new Chart(pieChartCanvas, {
          type: "doughnut",
          data: pieData,
          options: pieOptions,
        });

        // DONAT //

        var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')

        var salesGraphChartData = {
          labels: response.data_member_cart_blue, //sini
          datasets: [{
            label: 'Digital Goods',
            fill: false,
            borderWidth: 2,
            lineTension: 0,
            spanGaps: true,
            borderColor: '#efefef',
            pointRadius: 3,
            pointHoverRadius: 7,
            pointColor: '#efefef',
            pointBackgroundColor: '#efefef',
            data: response.data_member_cart_blue_total, //sini
          }]
        }

        var salesGraphChartOptions = {
          maintainAspectRatio: false,
          responsive: true,
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              ticks: {
                fontColor: '#efefef'
              },
              gridLines: {
                display: false,
                color: '#efefef',
                drawBorder: false
              }
            }],
            yAxes: [{
              ticks: {
                stepSize: 5000,
                fontColor: '#efefef'
              },
              gridLines: {
                display: true,
                color: '#efefef',
                drawBorder: false
              }
            }]
          }
        }

        var salesGraphChart = new Chart(salesGraphChartCanvas, {
          type: 'line',
          data: salesGraphChartData,
          options: salesGraphChartOptions
        })

      });

    },
    error: function(ex) {
      console.log(ex.responseText);
    }
  })
</script>
