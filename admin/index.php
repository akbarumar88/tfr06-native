<?php 

session_start();
if (empty($_SESSION['userid'])) {
    // User belum login, arahkan ke halaman login.
    header("location: ../index.php");
    return;
}

require_once("../layout/header.php");
require_once("../config/db.php");

$barang = $conn
    ->query("SELECT id, nama,stock from barang order by stock desc LIMIT 10;")
    ->fetchAll();

$kategori = $conn
    ->query("SELECT idkategori, kategori, count(b.id) as jumlah_barang, sum(b.stock) as jumlah_stok
    from barang b inner join kategori k on k.id = b.idkategori
    group by idkategori order by jumlah_stok desc;")
    ->fetchAll();

?>


<h3 class="mb-4">Admin Dashboard</h3>

<div id="myVizzu" style="width:800px; height:480px;"></div>

<!-- Container untuk Diagram Lingkaran -->
<div id="vizzuRadial" style="width:800px; height:480px;"></div>


<!-- <script type="module">

</script> -->
<script type="module">
    import Vizzu from 'https://cdn.jsdelivr.net/npm/vizzu@0.4.3/dist/vizzu.min.js';
    (async () => {
        let barangUrutPengadaan =  <?= json_encode($barang) ?>;
        let kategoriUrutPengadaan = <?= json_encode($kategori) ?>;
        // console.log({barangUrutPengadaan,kategoriUrutPengadaan})

        // Data By Series
        let dataSample = {
            series: [{
                    name: 'Kategori',
                    type: 'dimension',
                    values: kategoriUrutPengadaan.map(el => el.kategori)
                },
                {
                    name: 'Stok',
                    type: 'measure',
                    values: kategoriUrutPengadaan.map(el => el.jumlah_stok)
                },
            ]
        };
        // console.log(dataSample)

        // Inisialisasi chart Vizzu
        let chart = new Vizzu('myVizzu', {
            data: dataSample
        })
        chart.animate({
            x: 'Kategori',
            y: 'Stok',
            geometry: 'line',
        });
        chart.animate({
            config: {
                title: 'Data Pengadaan Stok Kategori Terbanyak',
                channels: {
                    label: {
                        attach: ['Stok']
                    }
                },
            }
        })


        // Diagram Lingkaran
        let radialChart = new Vizzu('vizzuRadial');
        let radialChartData = {
            series: [{
                    name: 'Barang',
                    type: 'dimension',
                    values: barangUrutPengadaan.map(el => el.nama)
                },
                {
                    name: 'Stok',
                    type: 'measure',
                    values: barangUrutPengadaan.map(el => el.stock)
                },
            ]
        }

        radialChart.animate({
            data: radialChartData,
            config: {
                channels: {
                    x: {
                        set: ['Stok']
                    },
                    y: {
                        set: ['Barang'],
                        /* Setting the radius of the empty circle
                        in the centre. */
                        range: {
                            min: '-30%'
                        }
                    },
                    color: {
                        set: ['Barang']
                    },
                    label: {
                        set: ['Stok']
                    }
                },
                title: 'Data Pengadaan Stok Barang Terbanyak',
                coordSystem: 'cartesian'
            },
            /* All axes and axis labels are unnecessary 
            on these types of charts, except for the labels 
            of the y-axis. */
            style: {
                plot: {
                    yAxis: {
                        color: '#ffffff00',
                        label: {
                            paddingRight: 20
                        }
                    },
                    xAxis: {
                        title: {
                            color: '#ffffff00'
                        },
                        label: {
                            color: '#ffffff00'
                        },
                        interlacing: {
                            color: '#ffffff00'
                        }
                    }
                }
            }
        });
        let i = 0
        setInterval(() => {
            let geometryChart1
            let coordSystemChart2
            if (i % 2 == 0) {
                geometryChart1 = 'rectangle'
                coordSystemChart2 = "polar"
            } else {
                geometryChart1 = 'line'
                coordSystemChart2 = "cartesian"
            }
            // Animasikan chart 1 (Batang)
            chart.animate({
                geometry: geometryChart1,
            });
            // Animasikan chart 2 (Lingkaran / Kartesius)
            radialChart.animate({
                config: {
                    coordSystem: coordSystemChart2
                }
            })
            i++
        }, 5000)

        // chart.initializing.then(
        //     chart => chart.animate({
        //         dataBySeries
        //     })
        // )
    })()
</script>


<?php 

require_once("../layout/footer.php");

?>