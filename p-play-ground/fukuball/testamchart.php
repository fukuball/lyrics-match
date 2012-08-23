

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>amCharts: Stacked bar chart</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="google-site-verification" content="OQwAR0ce3f81VG9qBEkVtZzoEkjQInY13YR6F_EzRCM" />
<meta name="description" content="Animated, 2D or 3D charts, supports column, bar, line, area, step line, smoothed line, candlestick, ohlc graph types. Supported by all popular browsers, including iPad and iPhone.">
<meta name="keywords" content="charts, javascript, stock chart, financial, candlestick, ohlc, flash charts, html5, canvas, ipad, android, free flash charts, customizable, 3D, configurable, logarithmic, stepline, reverse, timeplot, time plot, scatter, radar, spider, polar, bubble, pie, doughnut, donut, dough-nut, pie & doughnut, amcharts, ampie, ambar, amcolumn, line, area, line chart, column, bar">
<link rel="alternate" type="application/rss+xml" title="amCharts RSS" href="http://feeds.feedburner.com/amcharts">
<link href="http://amcharts.com/lib/style.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://amcharts.com/lib/favicon.ico">
<script type="text/javascript" src="http://amcharts.com/lib/swfobject2.js"></script>
<script src="http://amcharts.com/lib/amstock.js" type="text/javascript"></script>
<script src="http://amcharts.com/lib/amfallback.js" type="text/javascript"></script>
<script type="text/javascript">

  var hide = false;

  function showlayer(){
    hide = false;
    document.getElementById("charts").style.display="block";
  }

  function hidelayer(){
    hide = true;
    setTimeout("hidelayerreal()", 700);
  }

  function hidelayerreal(){
    if(hide != false){
      document.getElementById("charts").style.display="none";
    }
  }
</script>
</head>

<body>

<table width="956" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="18"><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="18" height="1"></td>
    <td width="920" colspan="2" align="right">
    <table cellspacing="0" cellpadding="0"><tr>
      <td bgcolor="#92C7E7" width="16" ><img alt="" src="http://amcharts.com/lib/img/l1.gif" width="16" height="26"></td>
      <td bgcolor="#92C7E7" align="center"><a class="topmenu" href="http://www.amcharts.com">JavaScript charts</a></td>
      <td bgcolor="#92C7E7" width="16" ><img alt="" src="http://amcharts.com/lib/img/j1.gif" width="16" height="26"></td>
      <td width="1" ><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="26"></td>
      <td bgcolor="#9B9B9B" width="16" ><img alt="" src="http://amcharts.com/lib/img/l4.gif" width="16" height="26"></td>
      <td bgcolor="#9B9B9B" align="center"><a class="topmenu" href="http://flex.amcharts.com">Flex charts</a></td>
      <td bgcolor="#9B9B9B" width="16" ><img alt="" src="http://amcharts.com/lib/img/j4.gif" width="16" height="26"></td>
      <td width="1" ><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="26"></td>
      <td bgcolor="#95CE25" width="16" ><img alt="" src="http://amcharts.com/lib/img/l2.gif" width="16" height="26"></td>
      <td bgcolor="#95CE25" align="center"><a class="topmenu" href="http://wpf.amcharts.com">WPF &amp; Silverlight charts</a></td>
      <td bgcolor="#95CE25" width="16" ><img alt="" src="http://amcharts.com/lib/img/j2.gif" width="16" height="26"></td>
      <td width="1" ><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="26"></td>
      <td bgcolor="#F2B31C" width="16" ><img alt="" src="http://amcharts.com/lib/img/l3.gif" width="16" height="26"></td>
      <td bgcolor="#F2B31C" align="center"><a class="topmenu" href="http://www.ammap.com" onclick="_gaq.push(['_link', this.href]); return false;">Flash maps</a></td>
      <td bgcolor="#F2B31C" width="16" ><img alt="" src="http://amcharts.com/lib/img/j3.gif" width="16" height="26"></td>
      <td width="1" ><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="26"></td>
      <td bgcolor="#5c5c5c" width="16" ><img alt="" src="http://amcharts.com/lib/img/l5.gif" width="16" height="26"></td>
      <td bgcolor="#5c5c5c" align="center"><a class="topmenu" href="http://flex.ammap.com" onclick="_gaq.push(['_link', this.href]); return false;">Flex maps</a></td>
      <td bgcolor="#5c5c5c" width="16" ><img alt="" src="http://amcharts.com/lib/img/j5.gif" width="16" height="26"></td>
    </tr></table>
    </td>
    <td width="18"></td>
  </tr>
  <tr>
    <td width="18"><div style="position:absolute; width:1px; height:1px;">    <div style="position: relative; z-index:100; left:121px; top:78px; display:none; width:220px;" id="charts" onmouseover="showlayer();" onmouseout="hidelayer();">
      <table cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td width="25"><img class="drop" alt="" src="http://amcharts.com/lib/img/d_tl.png" width="25" height="21"></td>
          <td class="drop" style="background-image:url(http://amcharts.com/lib/img/d_tt.png);"></td>
          <td width="31"><img class="drop" alt="" src="http://amcharts.com/lib/img/d_tr.png" width="31" height="21"></td>
        </tr>
        <tr>
          <td class="drop" style="background-image:url(http://amcharts.com/img/d_ll.png);"></td>
          <td class="drop" bgcolor="#DAF0FD" style="background-image:url(http://amcharts.com/img/d_bg.png);">
          <table border="0">
          <tr><td><a class="menu" href="/javascript/">JavaScript Charts</a></td></tr>
          <tr><td><a class="menu" href="/stock/">JavaScript Stock Chart</a></td></tr>
          </table>
          </td>
          <td class="drop" style="background-image:url(http://amcharts.com/lib/img/d_rr.png);"></td>
        </tr>
        <tr>
          <td width="25"><img class="drop" alt="" src="http://amcharts.com/lib/img/d_bl.png" width="25" height="30"></td>
          <td class="drop" style="background-image:url(http://amcharts.com/img/d_bb.png);"></td>
          <td width="31"><img class="drop" alt="" src="http://amcharts.com/lib/img/d_br.png" width="31" height="30"></td>
        </tr>
       </table>
    </div></div><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="18" height="1"></td>
    <td width="186"><a href="/"><img src="http://amcharts.com/lib/img/logo.gif" alt="amCharts" width="186" height="80" border="0"></a></td>
    <td align="right"><img src="http://amcharts.com/lib/img/bestjavascriptcharts.gif" alt="the best javascript charts *" width="357" height="80"></td>
    <td width="18"><img alt="" src="http://amcharts.com/lib/img/asterisk.gif" width="18" height="80"></td>
  </tr>

  <tr>
    <td></td>
    <td colspan="2">
      <!-- menu -->
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td onmouseover="this.style.cursor='pointer'" onclick="window.location.href='/'">
  <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#DAF0FD">
    <tr>
      <td rowspan="3" width="15" valign="top"><img alt="" src="http://amcharts.com/lib/img/btn_l.gif" width="15" height="45"></td>
      <td style="background-image:url(http://amcharts.com/img/btn_t.gif);"><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="19"></td>
      <td rowspan="3" width="14" valign="top"><img alt="" src="http://amcharts.com/lib/img/btn_r.gif" width="14" height="45"></td>
    </tr>
    <tr>
      <td align="center">
      <table cellspacing="0" cellpadding="0" border="0"><tr>
      <td><img src="http://amcharts.com/lib/img/menu0.gif" width="20" height="18" alt="Home"></td><td>
      <a href="/" class="menu">Home</a>
      </td>
      </tr></table>
      </td>
    </tr>
    <tr>
      <td style="background-image:url(http://amcharts.com/img/btn_b.gif);"><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="8"></td>
    </tr></table>
  </td>
  <td onmouseover="showlayer();" onmouseout="hidelayer();">
  <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#FFFFFF">
    <tr>
      <td rowspan="3" width="15" valign="top"><img alt="" src="http://amcharts.com/lib/img/btn_lh.gif" width="15" height="45"></td>
      <td style="background-image:url(http://amcharts.com/lib/img/btn_th.gif);"><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="19"></td>
      <td rowspan="3" width="14" valign="top"><img alt="" src="http://amcharts.com/lib/img/btn_rh.gif" width="14" height="45"></td>
    </tr>
    <tr>
      <td align="center">
      <table cellspacing="0" cellpadding="0" border="0"><tr>
      <td><img src="http://amcharts.com/img/menu1h.gif" width="20" height="18" alt="Products"></td><td>
      <a href="/column" class="menu">Products</a>
      </td>
      </tr></table>
      </td>
    </tr>
    <tr>
      <td style="background-image:url(http://amcharts.com/lib/img/btn_bh.gif);"><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="8"></td>
    </tr></table>
  </td>
  <td onmouseover="this.style.cursor='pointer'" onclick="window.location.href='/download'">
  <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#DAF0FD">
    <tr>
      <td rowspan="3" width="15" valign="top"><img alt="" src="http://amcharts.com/lib/img/btn_l.gif" width="15" height="45"></td>
      <td style="background-image:url(http://amcharts.com/img/btn_t.gif);"><img alt="" src="http://amcharts.com/lib/img/spacer.gif" width="1" height="19"></td>
      <td rowspan="3" width="14" valign="top"><img alt="" src="http://amcharts.com/lib/img/btn_r.gif" width="14" height="45"></td>
    </tr>
    <tr>
      <td align="center">
      <table cellspacing="0" cellpadding="0" border="0"><tr>
      <td><img src="http://amcharts.com/img/menu2.gif" width="20" height="18" alt="Download"></td><td>
      <a href="/download" class="menu">Download</a>
      </td>
      </tr></table>
      </td>
    </tr>
    <tr>
      <td style="background-image:url(http://amcharts.com/lib/img/btn_b.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="8"></td>
    </tr></table>
  </td>
  <td onmouseover="this.style.cursor='pointer'" onclick="window.location.href='/buy'">
  <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#DAF0FD">
    <tr>
      <td rowspan="3" width="15" valign="top"><img alt="" src="http://amcharts.com/img/btn_l.gif" width="15" height="45"></td>
      <td style="background-image:url(http://amcharts.com/lib/img/btn_t.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="19"></td>
      <td rowspan="3" width="14" valign="top"><img alt="" src="http://amcharts.com/lib/img/btn_r.gif" width="14" height="45"></td>
    </tr>
    <tr>
      <td align="center">
      <table cellspacing="0" cellpadding="0" border="0"><tr>
      <td><img src="http://amcharts.com/lib/img/menu3.gif" width="20" height="18" alt="Buy"></td><td>
      <a href="/buy" class="menu">Buy</a>
      </td>
      </tr></table>
      </td>
    </tr>
    <tr>
      <td style="background-image:url(http://amcharts.com/img/btn_b.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="8"></td>
    </tr></table>
  </td>
  <td onmouseover="this.style.cursor='pointer'" onclick="window.location.href='/faq'">
  <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#DAF0FD">
    <tr>
      <td rowspan="3" width="15" valign="top"><img alt="" src="http://amcharts.com/img/btn_l.gif" width="15" height="45"></td>
      <td style="background-image:url(http://amcharts.com/img/btn_t.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="19"></td>
      <td rowspan="3" width="14" valign="top"><img alt="" src="http://amcharts.com/img/btn_r.gif" width="14" height="45"></td>
    </tr>
    <tr>
      <td align="center">
      <table cellspacing="0" cellpadding="0" border="0"><tr>
      <td><img src="http://amcharts.com/img/menu4.gif" width="20" height="18" alt="FAQ"></td><td>
      <a href="/faq" class="menu">FAQ</a>
      </td>
      </tr></table>
      </td>
    </tr>
    <tr>
      <td style="background-image:url(http://amcharts.com/img/btn_b.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="8"></td>
    </tr></table>
  </td>
  <td onmouseover="this.style.cursor='pointer'" onclick="window.location.href='/docs'">
  <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#DAF0FD">
    <tr>
      <td rowspan="3" width="15" valign="top"><img alt="" src="http://amcharts.com/img/btn_l.gif" width="15" height="45"></td>
      <td style="background-image:url(http://amcharts.com/img/btn_t.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="19"></td>
      <td rowspan="3" width="14" valign="top"><img alt="" src="http://amcharts.com/img/btn_r.gif" width="14" height="45"></td>
    </tr>
    <tr>
      <td align="center">
      <table cellspacing="0" cellpadding="0" border="0"><tr>
      <td><img src="http://amcharts.com/img/menu5.gif" width="20" height="18" alt="Documentation"></td><td>
      <a href="/docs" class="menu">Documentation</a>
      </td>
      </tr></table>
      </td>
    </tr>
    <tr>
      <td style="background-image:url(http://amcharts.com/img/btn_b.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="8"></td>
    </tr></table>
  </td>
  <td onmouseover="this.style.cursor='pointer'" onclick="window.location.href='/forum'">
  <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#DAF0FD">
    <tr>
      <td rowspan="3" width="15" valign="top"><img alt="" src="http://amcharts.com/img/btn_l.gif" width="15" height="45"></td>
      <td style="background-image:url(http://amcharts.com/img/btn_t.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="19"></td>
      <td rowspan="3" width="14" valign="top"><img alt="" src="http://amcharts.com/img/btn_r.gif" width="14" height="45"></td>
    </tr>
    <tr>
      <td align="center">
      <table cellspacing="0" cellpadding="0" border="0"><tr>
      <td><img src="http://amcharts.com/img/menu6.gif" width="20" height="18" alt="Support forum"></td><td>
      <a href="/forum" class="menu">Support forum</a>
      </td>
      </tr></table>
      </td>
    </tr>
    <tr>
      <td style="background-image:url(http://amcharts.com/img/btn_b.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="1" height="8"></td>
    </tr></table>
  </td>

        </tr>
      </table>
      <!-- end of menu -->
    </td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="2">
      <table border="0" cellspacing="0" cellpadding="0" width="920">
        <tr>
          <td width="30" bgcolor="#FFFFFF" style="background-image:url(http://amcharts.com/img/shadow_l.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="30" height="1"></td>
          <td width="860" bgcolor="#FFFFFF">

                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
              <td valign="top" width="860">

                   <h1 style='margin: 35px 0 10px 0; padding: 0;'><span style='font-weight:normal; font-size:12px;'>JavaScript Charts: </span>Stacked bar chart</h1><div style="background: url(http://amcharts.com/img/dots_h.gif) repeat-x bottom; height: 1px; margin: 0 0 10px 0;"></div>        <script type="text/javascript">
            var chart;

            var chartData = [{
                year: "2003",
                europe: 2.5,
                namerica: 2.5,
                asia: 2.1,
                lamerica: 0.3,
                meast: 0.2,
                africa: 0.1
            }, {
                year: "2004",
                europe: 2.6,
                namerica: 2.7,
                asia: 2.2,
                lamerica: 0.3,
                meast: 0.3,
                africa: 0.1
            }, {
                year: "2005",
                europe: 2.8,
                namerica: 2.9,
                asia: 2.4,
                lamerica: 0.3,
                meast: 0.3,
                africa: 0.1
            }];

            AmCharts.ready(function () {
                // SERIALL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "year";
                chart.rotate = true;

                // AXES
                // Category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.gridAlpha = 0;
                categoryAxis.axisAlpha = 0;
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.stackType = "regular";
                valueAxis.dashLength = 1;
                valueAxis.gridAlpha = 0.3;
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPHS
                // firstgraph
                var graph = new AmCharts.AmGraph();
                graph.title = "Europe";
                graph.labelText = "[[value]]";
                graph.valueField = "europe";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = "#C72C95";
                chart.addGraph(graph);

                // second graph
                graph = new AmCharts.AmGraph();
                graph.title = "North America";
                graph.labelText = "[[value]]";
                graph.valueField = "namerica";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = "#D8E0BD";
                chart.addGraph(graph);

                // thirdt graph
                graph = new AmCharts.AmGraph();
                graph.title = "Asia-Pacific";
                graph.labelText = "[[value]]";
                graph.valueField = "asia";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = "#B3DBD4";
                chart.addGraph(graph);

                // fourth graph
                graph = new AmCharts.AmGraph();
                graph.title = "Latin America";
                graph.labelText = "[[value]]";
                graph.valueField = "lamerica";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = "#69A55C";
                chart.addGraph(graph);

                // fifth graph
                graph = new AmCharts.AmGraph();
                graph.title = "Middle-East";
                graph.labelText = "[[value]]";
                graph.valueField = "meast";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = "#B5B8D3";
                chart.addGraph(graph);

                // sixth graph
                graph = new AmCharts.AmGraph();
                graph.title = "Africa";
                graph.labelText = "[[value]]";
                graph.valueField = "africa";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = "#F4E23B";
                chart.addGraph(graph);

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.position = "right";
                legend.horizontalGap = 10;
                legend.switchType = "v";
                chart.addLegend(legend);

                // WRITE
                chart.write("chartdiv");
            });

        </script>
        <div id="chartdiv" style="width: 100%; height: 400px;"></div>
    </div><div style="clear:both; background: url(http://amcharts.com/img/dots_h.gif) repeat-x bottom; height: 1px; margin: 0 0 10px 0;"></div><div class="sampleNav"><a onclick="document.getElementById('sample-source').style.display = 'block'; this.style.display='none';">View sample source</><a href="/javascript/floating-bar-chart/" class="prevSample">&laquo; Previous example</a><a href="/javascript/bar-chart-with-background-image/" class="nextSample">Next example &raquo;</a></div><div style="background: url(http://amcharts.com/img/dots_h.gif) repeat-x bottom; height: 1px; margin: 0 0 10px 0;"></div><pre class="sourceCode" id="sample-source" style="display: none;">        &lt;script type=&quot;text/javascript&quot;&gt;
            var chart;

            var chartData = [{
                year: &quot;2003&quot;,
                europe: 2.5,
                namerica: 2.5,
                asia: 2.1,
                lamerica: 0.3,
                meast: 0.2,
                africa: 0.1
            }, {
                year: &quot;2004&quot;,
                europe: 2.6,
                namerica: 2.7,
                asia: 2.2,
                lamerica: 0.3,
                meast: 0.3,
                africa: 0.1
            }, {
                year: &quot;2005&quot;,
                europe: 2.8,
                namerica: 2.9,
                asia: 2.4,
                lamerica: 0.3,
                meast: 0.3,
                africa: 0.1
            }];

            AmCharts.ready(function () {
                // SERIALL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = &quot;year&quot;;
                chart.rotate = true;

                // AXES
                // Category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.gridAlpha = 0;
                categoryAxis.axisAlpha = 0;
                categoryAxis.gridPosition = &quot;start&quot;;

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.stackType = &quot;regular&quot;;
                valueAxis.dashLength = 1;
                valueAxis.gridAlpha = 0.3;
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPHS
                // firstgraph
                var graph = new AmCharts.AmGraph();
                graph.title = &quot;Europe&quot;;
                graph.labelText = &quot;[[value]]&quot;;
                graph.valueField = &quot;europe&quot;;
                graph.type = &quot;column&quot;;
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = &quot;#C72C95&quot;;
                chart.addGraph(graph);

                // second graph
                graph = new AmCharts.AmGraph();
                graph.title = &quot;North America&quot;;
                graph.labelText = &quot;[[value]]&quot;;
                graph.valueField = &quot;namerica&quot;;
                graph.type = &quot;column&quot;;
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = &quot;#D8E0BD&quot;;
                chart.addGraph(graph);

                // thirdt graph
                graph = new AmCharts.AmGraph();
                graph.title = &quot;Asia-Pacific&quot;;
                graph.labelText = &quot;[[value]]&quot;;
                graph.valueField = &quot;asia&quot;;
                graph.type = &quot;column&quot;;
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = &quot;#B3DBD4&quot;;
                chart.addGraph(graph);

                // fourth graph
                graph = new AmCharts.AmGraph();
                graph.title = &quot;Latin America&quot;;
                graph.labelText = &quot;[[value]]&quot;;
                graph.valueField = &quot;lamerica&quot;;
                graph.type = &quot;column&quot;;
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = &quot;#69A55C&quot;;
                chart.addGraph(graph);

                // fifth graph
                graph = new AmCharts.AmGraph();
                graph.title = &quot;Middle-East&quot;;
                graph.labelText = &quot;[[value]]&quot;;
                graph.valueField = &quot;meast&quot;;
                graph.type = &quot;column&quot;;
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = &quot;#B5B8D3&quot;;
                chart.addGraph(graph);

                // sixth graph
                graph = new AmCharts.AmGraph();
                graph.title = &quot;Africa&quot;;
                graph.labelText = &quot;[[value]]&quot;;
                graph.valueField = &quot;africa&quot;;
                graph.type = &quot;column&quot;;
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = &quot;#F4E23B&quot;;
                chart.addGraph(graph);

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.position = &quot;right&quot;;
                legend.horizontalGap = 10;
                legend.switchType = &quot;v&quot;;
                chart.addLegend(legend);

                // WRITE
                chart.write(&quot;chartdiv&quot;);
            });

        &lt;/script&gt;
        &lt;div id=&quot;chartdiv&quot; style=&quot;width: 100%; height: 400px;&quot;&gt;&lt;/div&gt;
    &lt;/div&gt;</pre>
                    <!--<h2 style="clear: both;">Examples</h2> -->
                    <div class="sampleBlock" /><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/100-stacked-area-chart/" class="menu2">100% stacked area chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/stacked-area-chart/" class="menu2">Stacked area chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/area-chart-with-time-based-data/" class="menu2">Area chart with time based data</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/3d-bar-chart/" class="menu2">3D bar chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/bar-and-line-chart-mix/" class="menu2">Bar and line chart mix</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/clustered-bar-chart/" class="menu2">Clustered bar chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/floating-bar-chart/" class="menu2">Floating bar chart</a></div><div class="sample active"><img src="http://amcharts.com/img/bullet.gif" />Stacked bar chart</div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/bar-chart-with-background-image/" class="menu2">Bar chart with background image</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/candlestick-chart/" class="menu2">Candlestick chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/100-stacked-column-chart/" class="menu2">100% stacked column chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/3d-column-chart/" class="menu2">3D column chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/3d-stacked-column-chart/" class="menu2">3D stacked column chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/column-and-line-chart-mix/" class="menu2">Column and line chart mix</a></div></div><div class="sampleBlock" /><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/column-chart-with-rotated-series/" class="menu2">Column chart with rotated series</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/simple-column-chart/" class="menu2">Simple column chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/stacked-column-chart/" class="menu2">Stacked column chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/column-chart-with-gradients/" class="menu2">Column chart with gradients</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/column-chart-with-images-on-top/" class="menu2">Column chart with images on top</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/smoothed-line-chart/" class="menu2">Smoothed line chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/step-line-chart/" class="menu2">Step line chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-custom-bullets/" class="menu2">Line chart with custom bullets</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-gaps-in-data/" class="menu2">Line chart with gaps in data</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-date-based-data/" class="menu2">Line chart with date-based data</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-different-bullet-sizes/" class="menu2">Line chart with different bullet sizes</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-duration-on-value-axis/" class="menu2">Line chart with duration on value axis</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-filled-value-ranges/" class="menu2">Line chart with filled value ranges</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-logarithmic-value-axis/" class="menu2">Line chart with logarithmic value axis</a></div></div><div class="sampleBlock" /><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-multiple-value-axes/" class="menu2">Line chart with multiple value axes</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-reversed-value-axis/" class="menu2">Line chart with reversed value axis</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-scroll-and-zoom/" class="menu2">Line chart with scroll and zoom</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/line-chart-with-trend-lines/" class="menu2">Line chart with trend lines</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/ohlc-chart/" class="menu2">OHLC chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/3d-pie-chart/" class="menu2">3D pie chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/3d-donut-chart/" class="menu2">3D donut chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/simple-pie-chart/" class="menu2">Simple pie chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/pie-chart-with-legend/" class="menu2">Pie chart with legend</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/polar-chart/" class="menu2">Polar chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/radar-chart/" class="menu2">Radar chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/bubble-chart/" class="menu2">Bubble chart</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/scrollable-and-zoomable-bubble-chart-/" class="menu2">Scrollable and zoomable bubble chart </a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/javascript/scatter-chart/" class="menu2">Scatter chart</a></div></div><div class="sampleHeader">Examples of <a href="/stock/" class="menu2">Stock Chart</a></div><div class="sampleBlock" /><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/stock/add-or-remove-panel/" class="menu2">Add or Remove Panel</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/stock/drawing-trend-lines/" class="menu2">Drawing Trend Lines</a></div></div><div class="sampleBlock" /><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/stock/events/" class="menu2">Events</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/stock/intraday-data/" class="menu2">Intraday Data</a></div></div><div class="sampleBlock" /><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/stock/multiple-datasets/" class="menu2">Multiple Datasets</a></div><div class="sample"><img src="http://amcharts.com/img/bullet.gif" /><a href="/stock/multiple-panels/" class="menu2">Multiple Panels</a></div></div>

              </td>
                          </tr>
          </table>

          </td>
          <td width="30" bgcolor="#FFFFFF" style="background-image:url(http://amcharts.com/img/shadow_r.gif);"><img alt="" src="http://amcharts.com/img/spacer.gif" width="30" height="1"></td>
        </tr>
        <tr>
          <td colspan="3"><img alt="" src="http://amcharts.com/img/bottom.gif"></td>
        </tr>
        <tr>
          <td></td>
          <td>
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr><td>&copy; amCharts | <a href="http://www.amcharts.com/about/">About us</a> | Contact and feedback: <a href="mailto:info@amcharts.com">info@amcharts.com</a></td>
            <td><a href="http://feeds.feedburner.com/amcharts" target="_blank"><img alt="Subscribe to amcharts news feed by e-mail or RSS reader" src="http://amcharts.com/img/feed.png" width="14" height="14" border="0"></a>
            <a href="http://twitter.com/amcharts"><img alt="Follow amCharts on Twitter" src="http://amcharts.com/img/twitter_logo.png" border="0"></a></td>
            <td>
              <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
              </script>
              <script type="text/javascript">
              _uacct = "UA-296088-3";
              urchinTracker();

              var _gaq = _gaq || [];
              _gaq.push(['_setAccount', 'UA-22221037-1']);
              _gaq.push(['_trackPageview']);
              _gaq.push(['_setDomainName', 'amcharts.com']);
              _gaq.push(['_setAllowLinker', true]);

              (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
              })();

              </script>
             </td>

             <td align="right">* <a href="http://blog.amcharts.com/2008/02/what-amcharts-ammaps-users-say.html">many people say so</a> ;)</td>
             </tr>
             </table>
             </td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </td>
      <td></td>
    </tr>
  </table>
  <br><br>

  </body>
  </html>

