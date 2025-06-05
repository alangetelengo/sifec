@extends('layout.app')
@section('titre')
    Accueil
@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('carte/css/map.css')}}">
@endsection
@section('corps')

<div class="card" style="opacity: 0.92; min-height: 650px;">
    @php
        setlocale(LC_TIME, "fr_FR", "French");
    @endphp
    <div class="card-header"> <h4> Statistiques de la semaine dernière </h4> </div>
    <div class="card-body">

        <div class="row" id="carte">
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <span class="d-none">
                            <input type="text" id='pr' value="{{$pr[0]->TOTAL}}">
                            <input type="text" id='dx' value="{{$dx[0]->TOTAL}}">
                            <input type="text" id='tr' value="{{$tr[0]->TOTAL}}">
                            <input type="text" id='qt' value="{{$qt[0]->TOTAL}}">
                            <input type="text" id='cq' value="{{$cq[0]->TOTAL}}">
                            <input type="text" id='sx' value="{{$sx[0]->TOTAL}}">
                            <input type="text" id='sp' value="{{$sp[0]->TOTAL}}">

                            <input type="text" id='prd' value="{{$prd[0]->TOTAL}}">
                            <input type="text" id='dxd' value="{{$dxd[0]->TOTAL}}">
                            <input type="text" id='trd' value="{{$trd[0]->TOTAL}}">
                            <input type="text" id='qtd' value="{{$qtd[0]->TOTAL}}">
                            <input type="text" id='cqd' value="{{$cqd[0]->TOTAL}}">
                            <input type="text" id='sxd' value="{{$sxd[0]->TOTAL}}">
                            <input type="text" id='spd' value="{{$spd[0]->TOTAL}}">

                            <input type="text" id='pra' value="{{$pra[0]->TOTAL}}">
                            <input type="text" id='dxa' value="{{$dxa[0]->TOTAL}}">
                            <input type="text" id='tra' value="{{$tra[0]->TOTAL}}">
                            <input type="text" id='qta' value="{{$qta[0]->TOTAL}}">
                            <input type="text" id='cqa' value="{{$cqa[0]->TOTAL}}">
                            <input type="text" id='sxa' value="{{$sxa[0]->TOTAL}}">
                            <input type="text" id='spa' value="{{$spa[0]->TOTAL}}">

                            <input type="text" id='prb' value="{{$prb[0]->TOTAL}}">
                            <input type="text" id='dxb' value="{{$dxb[0]->TOTAL}}">
                            <input type="text" id='trb' value="{{$trb[0]->TOTAL}}">
                            <input type="text" id='qtb' value="{{$qtb[0]->TOTAL}}">
                            <input type="text" id='cqb' value="{{$cqb[0]->TOTAL}}">
                            <input type="text" id='sxb' value="{{$sxb[0]->TOTAL}}">
                            <input type="text" id='spb' value="{{$spb[0]->TOTAL}}">
                        </span>
                        <h4 class="card-title">Déclarations</h4>
                    </div>
                    <div class="card-body">
                        <ul class="chart-point-list">
                            <li><i class="fa fa-circle text-success me-1"></i>Naissances | <i class="fa fa-circle text-primary me-1"></i>Décès</li>
                            {{-- <li><i class="fa fa-circle text-success me-1"></i>Naissances </li> --}}
                        </ul>
                        <div id="svg-animation" class="ct-chart ct-golden-section chartlist-chart"></div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actes</h4>
                    </div>
                    <div class="card-body">
                        <ul class="chart-point-list">
                            <li><i class="fa fa-circle text-success me-1"></i>Naissances | <i class="fa fa-circle text-primary me-1"></i>Décès</li>
                            <li><i class="fa fa-circle text-success me-1"></i>Naissances </li>
                        </ul>
                        <div id="svg-decesanimation" class="ct-chart ct-golden-section chartlist-chart"></div>
                    </div>
                </div>
            </div> --}}

            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actes</h4>
                    </div>
                    <div class="card-body">
                        <ul class="chart-point-list">
                            <li><i class="fa fa-circle text-success me-1"></i>Naissances | <i class="fa fa-circle text-primary me-1"></i>Décès</li>
                            {{-- <li><i class="fa fa-circle text-success me-1"></i>Naissances </li> --}}
                        </ul>
                        <div id="smil-animations" class="ct-chart ct-golden-section chartlist-chart"></div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actes de décès</h4>
                    </div>
                    <div class="card-body">
                        <div id="smil-decesanimations" class="ct-chart ct-golden-section chartlist-chart"></div>
                    </div>
                </div>
            </div> --}}

        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/chartist/js/chartist.min.js') }}"></script>
<script src="{{ asset('tpl/vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>

<script>

    (function($) {
        var dzChartlist = function(){

        var screenWidth = $(window).width();

        var setChartWidth = function(){

            if(screenWidth <= 768)
            {
                var chartBlockWidth = 0;
                if(screenWidth >= 500)
                {
                    chartBlockWidth = 250;
                }else{
                    chartBlockWidth = 300;
                }

                jQuery('.chartlist-chart').css('min-width',chartBlockWidth - 31);
            }
        }

        // Acte de naissance
        var lineAnimatedChart = function(){
            var una = $('#pra').val();
            var dxa = $('#dxa').val();
            var tra = $('#tra').val();
            var qta = $('#qta').val();
            var cqa = $('#cqa').val();
            var sxa = $('#sxa').val();
            var spa = $('#spa').val();

            var unb = $('#prb').val();
            var dxb = $('#dxb').val();
            var trb = $('#trb').val();
            var qtb = $('#qtb').val();
            var cqb = $('#cqb').val();
            var sxb = $('#sxb').val();
            var spb = $('#spb').val();

            var data = ['Lun', 'Mar', 'Mer', 'jeu', 'Ven', 'Sam', 'Dim'];
            var chart = new Chartist.Line('#smil-animations', {
                labels: data,
                series: [
                // Décès
                [unb,dxb,trb,qtb,cqb,sxb,spb],

                // Naissance
                [una,dxa,tra,qta,cqa,sxa,spa]

                ]
            }, {
                low: 0,
                plugins: [
                Chartist.plugins.tooltip()
                ]
            });

            // Let's put a sequence number aside so we can use it in the event callbacks
            var seq = 0,
                delays = 80,
                durations = 500;

            // Once the chart is fully created we reset the sequence
            chart.on('created', function() {
                seq = 0;
            });

            // On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
            chart.on('draw', function(data) {
                seq++;

                if(data.type === 'line') {
                // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
                data.element.animate({
                    opacity: {
                    // The delay when we like to start the animation
                    begin: seq * delays + 1000,
                    // Duration of the animation
                    dur: durations,
                    // The value where the animation should start
                    from: 0,
                    // The value where it should end
                    to: 1
                    }
                });
                } else if(data.type === 'label' && data.axis === 'x') {
                data.element.animate({
                    y: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.y + 100,
                    to: data.y,
                    // We can specify an easing function from Chartist.Svg.Easing
                    easing: 'easeOutQuart'
                    }
                });
                } else if(data.type === 'label' && data.axis === 'y') {
                data.element.animate({
                    x: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 100,
                    to: data.x,
                    easing: 'easeOutQuart'
                    }
                });
                } else if(data.type === 'point') {
                data.element.animate({
                    x1: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 10,
                    to: data.x,
                    easing: 'easeOutQuart'
                    },
                    x2: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 10,
                    to: data.x,
                    easing: 'easeOutQuart'
                    },
                    opacity: {
                    begin: seq * delays,
                    dur: durations,
                    from: 0,
                    to: 1,
                    easing: 'easeOutQuart'
                    }
                });
                } else if(data.type === 'grid') {
                // Using data.axis we get x or y which we can use to construct our animation definition objects
                var pos1Animation = {
                    begin: seq * delays,
                    dur: durations,
                    from: data[data.axis.units.pos + '1'] - 30,
                    to: data[data.axis.units.pos + '1'],
                    easing: 'easeOutQuart'
                };

                var pos2Animation = {
                    begin: seq * delays,
                    dur: durations,
                    from: data[data.axis.units.pos + '2'] - 100,
                    to: data[data.axis.units.pos + '2'],
                    easing: 'easeOutQuart'
                };

                var animations = {};
                animations[data.axis.units.pos + '1'] = pos1Animation;
                animations[data.axis.units.pos + '2'] = pos2Animation;
                animations['opacity'] = {
                    begin: seq * delays,
                    dur: durations,
                    from: 0,
                    to: 1,
                    easing: 'easeOutQuart'
                };

                data.element.animate(animations);
                }
            });

            // For the sake of the example we update the chart every time it's created with a delay of 10 seconds
            chart.on('created', function() {
                if(window.__exampleAnimateTimeout) {
                    clearTimeout(window.__exampleAnimateTimeout);
                    window.__exampleAnimateTimeout = null;
                }
                window.__exampleAnimateTimeout = setTimeout(chart.update.bind(chart), 12000);
            });


        }

        // Acte de décès
        var lineAnimatedDeces = function(){
            var unb = $('#prb').val();
            var dxb = $('#dxb').val();
            var trb = $('#trb').val();
            var qtb = $('#qtb').val();
            var cqb = $('#cqb').val();
            var sxb = $('#sxb').val();
            var spb = $('#spb').val();

            var data = ['Lun', 'Mar', 'Mer', 'jeu', 'Ven', 'Sam', 'Dim'];
            var chart = new Chartist.Line('#smil-decesanimations', {
                labels: data,
                series: [
                [unb,dxb,trb,qtb,cqb,sxb,spb]
                ]
            }, {
                low: 0,
                plugins: [
                Chartist.plugins.tooltip()
                ]
            });

            // Let's put a sequence number aside so we can use it in the event callbacks
            var seq = 0,
                delays = 80,
                durations = 500;

            // Once the chart is fully created we reset the sequence
            chart.on('created', function() {
                seq = 0;
            });

            // On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
            chart.on('draw', function(data) {
                seq++;

                if(data.type === 'line') {
                // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
                data.element.animate({
                    opacity: {
                    // The delay when we like to start the animation
                    begin: seq * delays + 1000,
                    // Duration of the animation
                    dur: durations,
                    // The value where the animation should start
                    from: 0,
                    // The value where it should end
                    to: 1
                    }
                });
                } else if(data.type === 'label' && data.axis === 'x') {
                data.element.animate({
                    y: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.y + 100,
                    to: data.y,
                    // We can specify an easing function from Chartist.Svg.Easing
                    easing: 'easeOutQuart'
                    }
                });
                } else if(data.type === 'label' && data.axis === 'y') {
                data.element.animate({
                    x: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 100,
                    to: data.x,
                    easing: 'easeOutQuart'
                    }
                });
                } else if(data.type === 'point') {
                data.element.animate({
                    x1: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 10,
                    to: data.x,
                    easing: 'easeOutQuart'
                    },
                    x2: {
                    begin: seq * delays,
                    dur: durations,
                    from: data.x - 10,
                    to: data.x,
                    easing: 'easeOutQuart'
                    },
                    opacity: {
                    begin: seq * delays,
                    dur: durations,
                    from: 0,
                    to: 1,
                    easing: 'easeOutQuart'
                    }
                });
                } else if(data.type === 'grid') {
                // Using data.axis we get x or y which we can use to construct our animation definition objects
                var pos1Animation = {
                    begin: seq * delays,
                    dur: durations,
                    from: data[data.axis.units.pos + '1'] - 30,
                    to: data[data.axis.units.pos + '1'],
                    easing: 'easeOutQuart'
                };

                var pos2Animation = {
                    begin: seq * delays,
                    dur: durations,
                    from: data[data.axis.units.pos + '2'] - 100,
                    to: data[data.axis.units.pos + '2'],
                    easing: 'easeOutQuart'
                };

                var animations = {};
                animations[data.axis.units.pos + '1'] = pos1Animation;
                animations[data.axis.units.pos + '2'] = pos2Animation;
                animations['opacity'] = {
                    begin: seq * delays,
                    dur: durations,
                    from: 0,
                    to: 1,
                    easing: 'easeOutQuart'
                };

                data.element.animate(animations);
                }
            });

            // For the sake of the example we update the chart every time it's created with a delay of 10 seconds
            chart.on('created', function() {
                if(window.__exampleAnimateTimeout) {
                    clearTimeout(window.__exampleAnimateTimeout);
                    window.__exampleAnimateTimeout = null;
                }
                window.__exampleAnimateTimeout = setTimeout(chart.update.bind(chart), 12000);
            });


        }


    //   Déclaration naissance
        var svgAnimationChart = function(){
            //SVG Path animation
            var un = $('#pr').val();
            var dx = $('#dx').val();
            var tr = $('#tr').val();
            var qt = $('#qt').val();
            var cq = $('#cq').val();
            var sx = $('#sx').val();
            var sp = $('#sp').val();

            var und = $('#prd').val();
            var dxd = $('#dxd').val();
            var trd = $('#trd').val();
            var qtd = $('#qtd').val();
            var cqd = $('#cqd').val();
            var sxd = $('#sxd').val();
            var spd = $('#spd').val();

            var chart = new Chartist.Line('#svg-animation', {
                labels: ['Lun', 'Mar', 'Mer', 'jeu', 'Ven', 'Sam', 'Dim'],
                series: [
                // Décès
                [und, dxd,trd,qtd,cqd,sxd,spd],

                // Naissance
                [un, dx,tr,qt,cq,sx,sp]

                ]
            }, {
                low: 0,
                showArea: true,
                showPoint: true,
                fullWidth: true
            });

            chart.on('draw', function(data) {
                if(data.type === 'line' || data.type === 'area') {
                data.element.animate({
                    d: {
                    begin: 2000 * data.index,
                    dur: 2000,
                    from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                    to: data.path.clone().stringify(),
                    easing: Chartist.Svg.Easing.easeOutQuint
                    }
                });
                }
            });
        }

        // déclaration décès
        var svgDeces = function(){
            //SVG Path animation
            var und = $('#prd').val();
            var dxd = $('#dxd').val();
            var trd = $('#trd').val();
            var qtd = $('#qtd').val();
            var cqd = $('#cqd').val();
            var sxd = $('#sxd').val();
            var spd = $('#spd').val();

            var chart = new Chartist.Line('#svg-decesanimation', {
                labels: ['Lun', 'Mar', 'Mer', 'jeu', 'Ven', 'Sam', 'Dim'],
                series: [
                [und, dxd,trd,qtd,cqd,sxd,spd]
                ]
            }, {
                low: 0,
                showArea: true,
                showPoint: true,
                fullWidth: true
            });

            chart.on('draw', function(data) {
                if(data.type === 'line' || data.type === 'area') {
                data.element.animate({
                    d: {
                    begin: 2000 * data.index,
                    dur: 2000,
                    from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                    to: data.path.clone().stringify(),
                    easing: Chartist.Svg.Easing.easeOutQuint
                    }
                });
                }
            });
        }



        /* Function ============ */
            return {
                init:function(){
                },


                load:function(){
                    setChartWidth();
                    lineAnimatedChart();
                    lineAnimatedDeces();
                    svgAnimationChart();
                    svgDeces();
                },

                resize:function(){
                    setChartWidth();
                    lineAnimatedChart();
                    lineAnimatedDeces();
                    svgAnimationChart();
                    svgDeces();
                }
            }

        }();

        jQuery(document).ready(function(){
        });

        jQuery(window).on('load',function(){
            dzChartlist.load();
        });

        jQuery(window).on('resize',function(){
            dzChartlist.resize();
        });

    })(jQuery);


</script>

@endsection
