<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>Stocks</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/app.css">
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
        <script src="js/d3plus/d3.js"></script>
    <script src="js/d3plus/d3plus.full.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <style type="text/css">
        .dropdown-toggle::after {
            display: none !important;
        }
        .grid-stack {
            /** background: lightgoldenrodyellow; */
        }
        class-overflow {
            overflow: auto !important;
        }

        .test {
            border: 2px dashed grey;
            border-radius: 2%;
        }

        .grid-stack-item-content {
            color: #2c3e50;
            text-align: center;
            border: 10px solid black;
            /* background-color: #18bc9c; */
        }
        #viz {
  height: 200px;
  width: 500px;
}
    </style>
</head>
<body>
    <div id="app">
        <dashboard inline-template>

            <div class="wrapper">
                <!-- Sidebar  -->
                <nav id="sidebar">
                    <div class="sidebar-header">
                        <h3>Bootstrap Sidebar</h3>
                        <strong>BS</strong>
                        <button type="button" id="sidebarCollapse" class="btn btn-info">
                            <i class="fas fa-align-left"></i>
                            <span></span>
                        </button>
                    </div>

                    <ul class="list-unstyled components">
                        <li @click="$modal.show('launcher')">
                            <i class="pointer fas fa-chart-line" aria-hidden="true" style="cursor: pointer; height: 100%; width: 35%;"></i>
                        </li>
                        <!-- <li class="active">
                            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <i class="fas fa-home"></i>
                                Home
                            </a>
                            <ul class="collapse list-unstyled" id="homeSubmenu">
                                <li>
                                    <span>Home 1</span>
                                </li>
                            </ul>
                        </li> -->
                        <li style="margin-top: 20px">
                            <span>
                                <i class="fas fa-paper-plane"></i>
                                Contact
                            </span>
                        </li>
                    </ul>

                    <ul class="list-unstyled CTAs">
                        <li>
                            <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
                        </li>
                        <li>
                            <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
                        </li>
                    </ul>
                </nav>

                <!-- Page Content  -->
                <div id ="content">
                    <div class="container-fluid">
                        <grid-layout
                                :layout.sync="gridItems"
                                :col-num="12"
                                :row-height="30"
                                :is-draggable="true"
                                :is-resizable="true"
                                :is-mirrored="false"
                                :vertical-compact="true"
                                :margin="[10, 10]"
                                :use-css-transforms="true">
                     
                            <grid-item style="overflow:auto; border: 2px dashed grey; border-radius: 2%;" :class="{classOverflow : item.type=='table'}" v-for="(item, index) in gridItems" :key="item.i"
                                       :x="item.x"
                                       :y="item.y"
                                       :w="item.w" :min-w="item.minW"
                                       :h="item.h" :min-h="item.minH"
                                       :i="item.i" @resized="resizedEvent" @moved="movedEvent">
                                <span style="z-index:1; position:absolute; right:0; height: 25px; width: 22px; background-color: red; color: white; border-radius: 100%; text-align: center; font-size: medium; cursor: pointer" class="pull-right; cursor: pointer;" @click="deleteGridItem(index)">X</span>
                                
                                <div v-if="item.type == 'combo'">
                                    <div style="text-align: center; font-size: 18px;">
                                        <span style="width: 35px; border-radius: 15%; background-color: #7386D5; color: white;" @click="updateCombo('1m', item)">1m</span>
                                        <span style="width: 35px; border-radius: 15%; background-color: #7386D5; color: white;" @click="updateCombo('3m', item)">3m</span>
                                        <span style="width: 35px; border-radius: 15%; background-color: #7386D5; color: white;" @click="updateCombo('6m', item)">6m</span>
                                        <span style="width: 35px; border-radius: 15%; background-color: #7386D5; color: white;" @click="updateCombo('ytd', item)">ytd</span>
                                        <span style="width: 35px; border-radius: 15%; background-color: #7386D5; color: white;" @click="updateCombo('1y', item)">1y</span>
                                        <span style="width: 35px; border-radius: 15%; background-color: #7386D5; color: white;" @click="updateCombo('2y', item)">2y</span>
                                    </div>
                                    <h4 style="text-align: center;" v-if="item.heading == true">@{{ item.symbol.name }}<h4>
                                    <apex-charts :height="item.charts[0].height" :width="item.charts[0].width" type="candlestick" :options="item.charts[0].options" :series="item.charts[0].series"></apex-charts>
                                    <br />
                                    <apex-charts class="combo-bar-chart" :height="item.charts[1].height" :width="item.charts[1].width" type="bar" :options="item.charts[1].options" :series="item.charts[1].series"></apex-charts>
                                </div>
                                <template v-if="item.type == 'treemap'">
                                   <!--  <kendo-treemap :ref="'treemap'+item.i" :data-source="item.data" 
                                        :value-field="'value'" 
                                        :text-field="'name'" style="text-align: center; width: 100%; height: 100%"
                                        >
                                    </kendo-treemap> -->
                                    <d3-treemap 
                                        :map-data="item.data"
                                        :container="item.i"
                                        :height="item.height"
                                        :width="item.width"
                                    >
                                    </d3-treemap>
                                </template>
                                <div v-if="item.type == 'sticker'" :style="{backgroundColor: item.color, height:'100%', width:'100%', textAlign: 'center', fontSize: 'medium'}">
                                    <p style="color: white">@{{item.symbol}}</p>
                                    <span style="color: white" v-if="item.open"><span>Open:</span>&nbsp @{{item.open}}</span>
                                    <span style="color: white" v-if="item.high"><span>High:</span>&nbsp@{{item.high}}</span>
                                    <span style="color: white" v-if="item.low"><span>Low:</span>&nbsp@{{item.low}}</span>
                                    <span style="color: white" v-if="item.close"><span>Close:</span>&nbsp@{{item.close}}</span>
                                </div>
                                <template v-if="item.type == 'table'">
                                    <b-table responsive striped hover :items="item.items"></b-table>
                                </template>
                            </grid-item>
                        </grid-layout>
                    </div>
                </div>
      
                {{-- Ticker Launcher Modal --}}
                <modal name="launcher">
                    <div class="launcher-options" v-if="route=='home'">
                        <div class="row" style="height: 50%">
                            <div class="col-md-6" @click="route = 'chart'" style="height:150px; width: 300px; cursor: pointer;">
                                <img class="launcher-images" src="svg/chart.svg" style="height: 100%; width: 100%;">
                            </div>
                            <div class="col-md-6" @click="route = 'table'" style=" background-color: #7386D5; height:150px; width: 300px; cursor: pointer;">
                                <i class="fas fa-table" style="height: 70%; width: 80%; margin: 8%; color: white;"></i>
                            </div>                     
                        </div>
                        <div class="row">
                            <div class="col-md-6" @click="route = 'stickers'" style="background-color: #7386D5; height:150px; width: 300px; text-align: center; font-size: 22px; cursor: pointer;">
                                <span style="margin: 15%; color: white">OHLC <br />STCK</span>
                            </div>
                            <div class="col-md-6" @click="route = 'heat-map'" style="height:150px; width: 300px; cursor: pointer;">
                                <img class="launcher-images" src="svg/tree-map.svg" style="height: 100%; width: 100%">
                            </div>                     
                        </div>
                    </div>
                    <div v-else class="row">
                        <div class="col-md-1" style="color: #7386D5;" @click="goHome">
                            <span class="fas fa-arrow-alt-circle-left" style="height: 50%; width: 100%; margin-left: 50%; margin-top: 20%; cursor: pointer;"></span>
                        </div>
                        <div class="col-md-11" v-if="route == 'chart'" style="margin-top: 3%">
                            <v-select @search="onSearch" v-model="selectedSymbol" :filterable="false" :options="filteredSymbols"></v-select>
                            <button @click="addChart(selectedSymbol)" class="btn btn-primary" style="margin-top: 5%; margin-left: 37%">Add</button>
                        </div>
                        <div class="col-md-11" v-if="route == 'table'" style="margin-top: 3%; text-align: center;">
                            <h4>Top Trends</h4>
                            <div class="row"  style="margin-bottom: 4%">
                                <div class="col-md-12">
                                    <h2 style="display: inline-block; cursor: pointer;" @click="getTableForSignal('mostactive')">
                                        <span class="label label-primary">Top Active</span>
                                    </h2>
                                    <h2 style="display: inline-block; cursor: pointer;" @click="getTableForSignal('gainers')">
                                        <span class="label label-success">Top Gainers</span>
                                    </h2>
                                    <h2 style="display: inline-block; cursor: pointer;" @click="getTableForSignal('losers')">
                                        <span class="label label-danger">Top Losers</span>
                                    </h2>
                                    <h2 style="display: inline-block; cursor: pointer;" @click="getTableForSignal('infocus')">
                                        <span class="label label-warning">In the news</span>
                                    </h2>
                                </div>
                            </div>
                            <h4>or just Add your own desired Tickers using box below</h4>
                            <v-select multiple @search="onSearch" v-model="selectedSymbol" :filterable="false" :options="filteredSymbols"></v-select>
                            <button @click="addTable" class="btn btn-primary" style="margin-top: 5%;">Add</button>
                        </div>
                        <div class="col-md-11" v-if="route == 'stickers'" style="margin-top: 3%">
                            <v-select @search="onSearch" v-model="selectedSymbol" :filterable="false" :options="filteredSymbols"></v-select>
                            <button @click="addSticker" class="btn btn-primary" style="margin-top: 5%; margin-left: 37%">Add</button>
                        </div>
                        <div class="col-md-11" v-if="route == 'heat-map'" style="margin-top: 3%">
                            <v-select multiple @search="onSearch" v-model="selectedSymbol" :filterable="false" :options="filteredSymbols"></v-select>
                            <button @click="addHeatMap" class="btn btn-primary" style="margin-top: 5%; margin-left: 37%">Add</button>
                        </div>                         
                    </div>
                </modal>

            </div>

        </div>
        

    </dashboard>
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.0/jquery-ui.js"></script>

    <script src="js/gridstack.js"></script>
    <script src="js/gridstack.jQueryUI.js"></script>
<!--     <script src="js/d3plus/d3.js"></script>
    <script src="js/d3plus/d3plus.full.js"></script> -->
<script>
//   var data = [
//   {parent: "Group 1", id: "alpha", value: 29},
//   {parent: "Group 1", id: "beta", value: 10},
//   {parent: "Group 1", id: "gamma", value: 2},
//   {parent: "Group 2", id: "delta", value: 29},
//   {parent: "Group 2", id: "eta", value: 25}
// ];
//   // instantiate d3plus
//   new d3plus.Treemap()
//   .data(data)
//   .groupBy(["parent", "id"])
//   .select("#viz")
//   .sum("value")
//   .render();             // finally, draw the visualization!
</script>
    <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
