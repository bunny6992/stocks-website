<script>
import interact from 'interactjs';
import gridstack from 'gridstack';
    export default {
        data: function () {
            return {
                layout: [],
                heatMapdata: {},
                layoutItems: [],
                stackGridItems: [],
                componentKey: 0,
                gridOptions: {
                    cellHeight: 80,
        verticalMargin: 10
                },
                grid: {

                },
                currentItem: {},
                symbols: [],
                symbolOptions: [],
                filteredSymbols: [],
                selectedSymbol: '',
                route: "home",
                scrollable: true,
                gridItems: [],
                width: 200,
                height: 150,
                x: 0,
                y: 0,
                chartWidth1: 500,
                chartWidth2: 500,
                chartHeight1: 300,
                chartHeight2: 200,

                options: {
                    chart: {
                        id: 'vuechart-example',
                        toolbar: {
                            show: false,
                            tools: {
                                pan: true,
                              },
                            autoSelected: 'pan'
                        },
                    },
                    plotOptions: {
                        candlestick: {
                          colors: {
                            upward: '#00B746',
                            downward: '#EF403C'
                          }
                        }
                    },
                    xaxis: {
                      labels: {
                        show: false,
                        formatter: function (value) {
                          return new Date(value);
                        }
                      }
                    },
                    yaxis: {
                        labels: {
                        show: true,
                        // formatter: function (value) {
                        //   return new Date(value);
                        // }
                      }
                    }
                },
                series: [{
                  data: []
                }],
                 options2: {
                    chart: {
                      id: 'vuechart-example'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    yaxis: {
                        labels: {
                        show: true,
                        // formatter: function (value) {
                        //   return new Date(value);
                        // }
                      }
                    },
                    xaxis: {
                        labels: {
                        show: false,
                        // formatter: function (value) {
                        //   return new Date(value);
                        // }
                      },
                      categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998]
                    }
                  },
                  series2: [{
                    name: 'volume',
                    data: [30, 40, 45, 50, 49, 60, 70, 91]
                  }],
                  localDataSource: [
                    {
                        'name': 'Population in USA',
                        'value': 20,
                        'items': [
                            {
                                'name': 'Anchorage',
                                'value': 10
                            },
                            {
                                'name': 'B',
                                'value': 3
                            },
                            {
                                'name': 'C',
                                'value': 7
                            }
                        ]
                    }
                  ]
            }
        },
        mounted() {
            $('#sidebar').toggleClass('active');
            this.getSymbols();
            setTimeout(() => {
$('.grid-stack').gridstack(this.gridOptions);
            }, 500);
            // var options = this.gridOptions;
            // $('.grid-stack').gridstack(this.options);
            // this.addChart();
            // $(() => {
                    // var options = {
                    //     cellHeight: 80,
                    //     verticalMargin: 10
                    // };
                    // $('.grid-stack').gridstack(options);
                //});
                //this.getHeatMap();
        },
        methods: {
            test2(event) {
                console.log(event);
            },
            addNewWidget() {
                // if (!$('.grid-stack').data('gridstack')) {
                //     $('.grid-stack').gridstack(this.gridOptions);
                // }
                // $('.grid-stack').data('gridstack').addWidget($('<div><div onClick="console.log(test12())" class="grid-stack-item-content">This is content</div><div/>'),
                //     0, 0, '2', '2');
                // this.stackGridItems.push(1);
                // $('.grid-stack').gridstack(this.gridOptions);
                // return false;
                 let grid = $('.grid-stack').data('gridstack')
    	let widget = grid.addWidget(`
        <grid-stack-item data-gs-x="0" data-gs-y="0"
          data-gs-width="1" @click="test2()" data-gs-height="1"><div class="grid-stack-item-content ui-draggable-handle">
        <p>Vue (added)</p>
      </div></grid-stack-item>
      `, 0, 0, 1, 1, true)
      
      grid.$compile(widget.get(0))
            },
            toggleSidebar() {
                $('#sidebar').toggleClass('active');
            },
            getSymbols() {
                axios.get('https://api.iextrading.com/1.0/ref-data/symbols')
                    .then( (response) => {
                        if(response.status == '200') {
                           this.symbols = response.data;
                           let symbolOptions = [];
                           _.forEach(this.symbols, (symbol, index) => {
                                let symbolOption = symbol;
                                symbolOption.value = symbol.iexId;
                                symbolOption.label = symbol.symbol + "-" + symbol.name;
                                symbolOptions.push(symbolOption)
                            })
                            this.symbolOptions = symbolOptions;
                            this.filteredSymbols = this.symbolOptions.prototype.splice(0,19);
                        }
                    })
                    .catch( (error) => {
                        console.log(error);
                    });
            },
            updateOptions() {
                let text = $($('.search')[1]).val();
                 this.filteredSymbols = [];
                if (text.length > 0) {
                    this.filteredSymbols = _.filter(this.symbolOptions, (o) => { 
                        return o.text.includes(text); 
                    });
                }
            },
            onSearch(search, loading) {
                loading(true);
                this.filteredSymbols = [];
                let symbolOptions = [];
               _.forEach(this.symbolOptions, (symbol) => {
                    if (symbol.label.toLowerCase().includes(search)) {
                        symbolOptions.push(symbol);
                    }
                    if (symbolOptions.length > 19) {
                        return false;
                    }
                })
                this.filteredSymbols = symbolOptions;
                loading(false);
            },
            addChart(symbol) {
                //undo-redo

                axios.get(`https://api.iextrading.com/1.0/stock/${symbol.symbol}/chart/1m`)
                    .then( (response) => {
                        if(response.status == '200') {
                           let days = response.data;
                           let series = [];
                           let dataArray = [];
                           this.options2.xaxis.categories = [];
                           this.series2[0].data = [];
                           _.forEach(days, (day, index) => {
                                let date = new Date(day.date);
                                let test = [date.getTime(), [day.open, day.high, day.low, day.close]]
                                this.options2.xaxis.categories.push(day.date);
                                this.series2[0].data.push(day.volume);
                                dataArray.push(test);
                            })
                            this.series[0].data = dataArray;
                            // this.$refs.test.refresh();
                            // this.$refs.test2.refresh();
                            let charts = [];
                            charts.push({options: this.options, series: this.series, height: 150, width: 400, type: 'candlestick'})
                            charts.push({options: this.options2, series: this.series2, height: 100, width: 400, type: 'bar'})
                            // this.layout.push({ w: 4, h: 3, x: 0, y: 0, i: 1});
                            let newIndex = 1;
                            while(_.find(this.gridItems, ['i', newIndex]) != undefined) {
                                newIndex++;
                            }
                            this.gridItems.push({
                                type: 'combo', charts: charts, symbol: symbol, heading: true,
                                w: 3, h: 8, x: 0, y: 0, i: newIndex, minH: 8, minW: 3
                            })
                            // this.gridItems.push({type: 'combo', charts: charts, symbol: symbol, heading: true})
                            this.route = 'home';
                            this.$modal.hide('launcher');
                            this.selectedSymbol = '';
                        }
                    })
                    .catch( (error) => {
                        console.log(error);
                    });

            },
            updateCombo(duration, item) {
                axios.get(`https://api.iextrading.com/1.0/stock/${item.symbol.symbol}/chart/${duration}`)
                    .then( (response) => {
                        if(response.status == '200') {
                           let days = response.data;
                           let series = [];
                           let dataArray = [];
                           this.options2.xaxis.categories = [];
                           this.series2[0].data = [];
                           _.forEach(days, (day, index) => {
                                let date = new Date(day.date);
                                let test = [date.getTime(), [day.open, day.high, day.low, day.close]]
                                this.options2.xaxis.categories.push(date);
                                this.series2[0].data.push(day.close);
                                dataArray.push(test);
                            })
                            this.series[0].data = dataArray;
                            // this.$refs.test.refresh();
                            // this.$refs.test2.refresh();
                            // let charts = [];
                            // let c1Height = item.charts[0]['height'];
                            // let c2Height = item.charts[1]['height'];
                            // let c1Width = item.charts[0]['width'];
                            // let c2Width = item.charts[1]['width'];

                            // charts.push({options: this.options, series: this.series, height: c1Height, width: c1Width, type: 'candlestick'})
                            // charts.push({options: this.options2, series: this.series2, height: c2Height, width: c2Width, type: 'bar'})
                            // this.layout.push({ w: 4, h: 3, x: 0, y: 0, i: 1});
                            // let newIndex = 1;
                            // while(_.find(this.gridItems, ['i', newIndex]) != undefined) {
                            //     newIndex++;
                            // }
                            //item.charts = {};
                            //item.charts = charts;
                            item.charts[0]['options'] = this.options;
                            item.charts[0].series = this.series;

                            item.charts[1]['options'] = this.options2;
                            item.charts[1].series = this.series2;
                            // setTimeout(() => {
                                item.charts[0].height = item.charts[0].height + 1;
                                item.charts[1].height = item.charts[1].height + 1;
                                item.charts[0].width = item.charts[0].width + 1;
                                item.charts[1].width = item.charts[1].width + 1;
                            // }, 500);
                            // this.gridItems.push({
                            //     type: 'combo', charts: charts, symbol: symbol, heading: true,
                            //     w: 3, h: 7, x: 0, y: 0, i: newIndex, minH: 7, minW: 3
                            // })
                            // this.gridItems.push({type: 'combo', charts: charts, symbol: symbol, heading: true})
                            // this.route = 'home';
                            // this.$modal.hide('launcher');
                            // this.selectedSymbol = '';
                        }
                    })
                    .catch( (error) => {
                        console.log(error);
                    });
            },
            resizedEvent: function(i, newH, newW, newHPx, newWPx) {
                let item = _.find(this.gridItems, ['i', i]);
                console.log("RESIZE i=" + i + ", H=" + newH + ", W=" + newW + ", H(px)=" + newHPx + ", W(px)=" + newWPx);
                if (item.type == 'combo') {
                    item.charts[0].height = (newHPx / 2);
                    item.charts[1].height = (newHPx / 2) - 50;
                    item.charts[0].width = newWPx - 50;
                    item.charts[1].width = newWPx - 50;
                    if (newHPx > 350 && newWPx > 320) {
                        // item.charts[0].options.xaxis.labels.show = true;
                        // item.charts[1].options.xaxis.labels.show = true;
                        // item.charts[0].options.yaxis.labels.show = true;
                        // item.charts[1].options.yaxis.labels.show = true;
                    } else {
                        // item.charts[0].options.xaxis.labels.show = false;
                        // item.charts[1].options.xaxis.labels.show = false;
                        // item.charts[0].options.yaxis.labels.show = false;
                        // item.charts[1].options.yaxis.labels.show = false;
                    }

                    if (newHPx > 200 && newWPx > 200) {
                        item.heading = true;
                    } else {
                        item.heading = false;
                    }
                }
                if (item.type == 'treemap') {
                    item.height = newHPx;
                    item.width = newWPx;
                }
            },
            movedEvent: function(event) {
                console.log(event);
            },
            onResize: function (x, y, width, height) {
            console.log(width);
              this.width = width       
              this.height = height              
            },

            addHeatMap() {
                let symbols = '';
                _.forEach(this.selectedSymbol, (symbol, index) => {
                    if (index > 0) {
                        symbols += ',';
                    }
                    symbols += symbol.symbol;
                })
                axios.get(`https://api.iextrading.com/1.0/stock/market/batch?symbols=${symbols}&types=quote`)
                    .then( (response) => {
                        
                            // let itemData = {
                            //     symbol: symbol.quote.symbol,
                            //     low: symbol.quote.low,
                            //     previousClose: symbol.quote.previousClose,
                            //     changePercent: symbol.quote.changePercent,
                            //     latestVolume: symbol.quote.latestVolume
                            // };
                            // items.push(itemData);
                            setTimeout(() => {
                                console.log(response.data);
                                let grandTotal = 0;
                                let parent = {
                                    'name': 'Tree Map',
                                    'value': 0,
                                    'items': []
                                };
                                let groupName = 'Gainers';
                                let color = 'green';
                                _.forEach(response.data, (symbol) => {
                                    console.log(symbol);
                                    let dataSource = [];
                                    let items = [];                                    
                                    //_.forEach(this.selectedSymbol, (symbol) => {
                                        groupName = 'Gainers';
                                        color = 'green';
                                        if(symbol.quote.change < 0) {
                                            groupName = 'Losers';
                                            color = 'red';
                                        }
                                        // grandTotal +=  symbol.quote.latestPrice;
                                        let symbolName = symbol.quote.symbol + " " + symbol.quote.changePercent;
                                        parent.items.push({color: color, parent: groupName, id: symbolName, value: symbol.quote.latestPrice});
                                        // parent.items.push({name: symbolName, value: symbol.quote.latestPrice});
                                    //})

                                });
                                console.log(parent.items);
                                // parent.value = grandTotal;
                                let newIndex = 1;
                                while(_.find(this.gridItems, ['i', newIndex]) != undefined) {
                                    newIndex++;
                                }
                                this.gridItems.push({
                                    type: 'treemap', data: parent.items, w: 4, h: 7, x: 0, y: 0, i: newIndex, minH: 7, minW: 4, height: 230, width: 420
                                });
                                this.updateTreeMap(newIndex);
                                this.route = 'home';
                                this.$modal.hide('launcher');
                                this.selectedSymbol = '';

                            }, 1000);
                        //console.log(response.data)
                        //symbol.price = symbol.latestPrice;
                        // if (index + 1 == this.selectedSymbol.length) {
                        
                        // }
                    })
                    .catch( (error) => {
                        console.log(error);
                    });
            },
            generateData(count, yrange) {
                var i = 0;
                var series = [];
                while (i < count) {
                var x = (i + 1).toString();
                var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

                series.push({
                  x: x,
                  y: y
                });
                i++;
                }
                return series;
            },
            updateTreeMap(index) {
                setTimeout(() => {
                    this.$refs['treemap'+index][0].updateWidget();
                }, 500);
            },
            addSticker() {
                axios.get(`https://api.iextrading.com/1.0/stock/${this.selectedSymbol.symbol}/ohlc`)
                    .then( (response) => {
                        if(response.status == '200') {
                            let data = response.data;
                           let newIndex = 1;
                            while(_.find(this.gridItems, ['i', newIndex]) != undefined) {
                                newIndex++;
                            }
                            let color = (data.open.price > data.close.price) ? 'red' : '#1DD062';

                            this.gridItems.push({
                                type: 'sticker', symbol: this.selectedSymbol.symbol, color: color, open: data.open.price, high: data.high, low: data.low, close: data.close.price, w: 2, h: 4, x: 0, y: 0, i: newIndex, minH: 4, minW: 2
                            });
                            this.route = 'home';
                            this.$modal.hide('launcher');
                            this.selectedSymbol = '';
                        }
                    })
                    .catch( (error) => {
                        console.log(error);
                    });
            },
            addTable() {
                let symbols = '';
                _.forEach(this.selectedSymbol, (symbol, index) => {
                    if (index > 0) {
                        symbols += ',';
                    }
                    symbols += symbol.symbol;
                })
                axios.get(`https://api.iextrading.com/1.0/stock/market/batch?symbols=${symbols}&types=quote`)
                    .then( (response) => {
                        if(response.status == '200') {
                            let data = response.data;
                            console.log(data);
                            let items = [];
                            _.forEach(data, (symbol) => {
                                let itemData = {
                                    symbol: symbol.quote.symbol,
                                    low: symbol.quote.low,
                                    previousClose: symbol.quote.previousClose,
                                    changePercent: symbol.quote.changePercent,
                                    latestVolume: symbol.quote.latestVolume
                                };
                                items.push(itemData);
                            });
                             console.log(items);
                           let newIndex = 1;
                            while(_.find(this.gridItems, ['i', newIndex]) != undefined) {
                                newIndex++;
                            }
                            
                            this.gridItems.push({
                                type: 'table', items: items, w: 6, h: 5, x: 0, y: 0, i: newIndex, minH: 2, minW: 6
                            });
                            this.route = 'home';
                            this.$modal.hide('launcher');
                            this.selectedSymbol = '';
                        }
                    })
                    .catch( (error) => {
                        console.log(error);
                    });
            },

            getTableForSignal(type) {
                axios.get(`https://api.iextrading.com/1.0/stock/market/list/${type}`)
                    .then( (response) => {
                        if(response.status == '200') {
                            let data = response.data;
                            console.log(data);
                            let items = [];
                             _.forEach(data, (symbol) => {
                                let itemData = {
                                    symbol: symbol.symbol,
                                    low: symbol.low,
                                    previousClose: symbol.previousClose,
                                    changePercent: symbol.changePercent,
                                    latestVolume: symbol.latestVolume
                                };
                                items.push(itemData);
                            });
                           //   console.log(items);
                           let newIndex = 1;
                            while(_.find(this.gridItems, ['i', newIndex]) != undefined) {
                                newIndex++;
                            }
                            
                            this.gridItems.push({
                                type: 'table', items: items, w: 6, h: 5, x: 0, y: 0, i: newIndex, minH: 2, minW: 6
                            });
                            this.route = 'home';
                            this.$modal.hide('launcher');
                            this.selectedSymbol = '';
                        }
                    })
                    .catch( (error) => {
                        console.log(error);
                    });
            },

            goHome() {
                this.route = 'home';
                this.$modal.hide('launcher');
                setTimeout(() => {
                    this.$modal.show('launcher');
                }, 300)
                
            },

            deleteGridItem(index) {
                this.gridItems.splice(index, 1);
            }
            
        },
        watch: {
        }
    }

</script>
