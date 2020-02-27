/* ---hightchart1----*/
$(function(e){
  'use strict';

	Highcharts.chart('statistic', {
		chart: {
			backgroundColor: 'transparent',
			type: 'pie'
		},
		title: {
			text: ''
		},
		exporting: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		xAxis: {
			gridLineColor: 'rgba(0,0,0,0.03)',
			categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			labels: {
				style: {
					color: '#bbc1ca',
				}
			}
		},
		series: [{
			type: 'pie',
			allowPointSelect: true,
			keys: ['name', 'y', 'selected', 'sliced'],
			data: [
				['5%', 5, false],
				['20%', 20, false],
				['38%', 38, false],
				['24%', 24, false],
				['13%', 13, false]
			],
			colors: ['#1753fc', ' #00b3ff', '#9258f1', '#fc0', '#ed2a00', '#004ced', '#00eda1', '#ed00c3', '#6AF9C4'],
			showInLegend: false
		}]
	});

 });
 