@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Project Stats</h2>
                <a class="btn btn-primary mb-2" href="/pdf">Create pdf docs</a>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th>All projects</th>
            <th>Project in start</th>
            <th>End project</th>
            <th>Project in observe</th>
        </tr>
            <tr>
                <td>{{ $all_project }}</td>
                <td>{{ $start_project }}</td>
                <td>{{ $end_project}}</td>
                <td>{{ $observe_project}}</td>
            </tr>
    </table>


    <!-- HTML -->
    <div id="chartdiv">

    </div>


        <!-- Styles -->
        <style>
            #chartdiv {
            width: 100%;
            height: 500px;
        }
        </style>

    <!-- Chart code -->
    <script>
        var root = am5.Root.new("chartdiv");

        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        var chart = root.container.children.push(
            am5percent.PieChart.new(root, {
                layout: root.verticalLayout
            })
        );

        // Define data
        var data =JSON.parse({!! json_encode($pie) !!});

        // Create series
        var series = chart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category"
            })
        );
        series.data.setAll(data);

        // Add legend
        var legend = chart.children.push(am5.Legend.new(root, {
            centerX: am5.percent(50),
            x: am5.percent(50),
            layout: root.horizontalLayout
        }));

        legend.data.setAll(series.dataItems);
    </script>


@endsection
