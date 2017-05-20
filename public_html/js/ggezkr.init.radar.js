function initChrt(canvasElement /* Canvas */){
	// Chart Plugin
//		Chart.plugins.register({
//			  beforeDraw: function(chartInstance) {
//			    var ctx = chartInstance.chart.ctx;
//			    ctx.fillStyle= "rgba(0,0,0,0.1)";
//			    ctx.fillRect(50, 50, chartInstance.chart.width, chartInstance.chart.height);
//			  }
//			});
	
	var labels = "";
	var datas = [];

//	var labels = ["", "", "", "", "", ""];
//	var datas = [0, 0, 0, 0, 0, 0];

	// Create Default Chart
	
	var ctx = canvasElement.getContext("2d");
	var data = {
//	    labels: ["1", "2", "3", "4", "5", "6"],
	    datasets: [
   	        {
	            label: labels,
	            backgroundColor: "rgba(255,99,132,0.2)",
	            borderColor: "rgba(255,99,132,1)",
	            pointBackgroundColor: "rgba(255,99,132,1)",
	            pointBorderColor: "#fff",
	            pointHoverBackgroundColor: "#fff",
	            pointHoverBorderColor: "rgba(255,99,132,1)",
	            data: datas,
	        },
	        {
	            label: labels,
				backgroundColor: "rgba(154,208,245,0.2)",
				borderColor: "rgba(154,208,245,1)",
//	            backgroundColor: "rgba(179,181,198,0.2)",
//	            borderColor: "rgba(179,181,198,1)",
	            pointBackgroundColor: "rgba(154,208,245,1)",
	            pointBorderColor: "#fff",
	            pointHoverBackgroundColor: "#fff",
	            pointHoverBorderColor: "rgba(179,181,198,1)",
	            data: datas,
	        },
	        {
	            label: labels,
	            backgroundColor: "rgba(255,230,170,0.2)",
	            borderColor: "rgba(255,230,170,1)",
	            pointBackgroundColor: "rgba(255,230,170,1)",
	            pointBorderColor: "#fff",
	            pointHoverBackgroundColor: "#fff",
	            pointHoverBorderColor: "rgba(255,99,132,1)",
	            data: datas,
	        }
	    ]
	}
	var options =  {
    		responsive: true,
	    	maintainAspectRatio: true,	// true:fit to width, false : fit to height;
	    	title: {
	            display: true,
//		            text: '냅둬라날#3934의 경기요약'
	        },
            legend: {
            	position: 'bottom',
	            display: true,
	            labels: {
	                fontColor: 'white'
	            }
            },
            tooltips: {
	            enabled: true,
	            mode: 'index',
				intersect: true,//false means always applied
				custom: function(tooltip){
					if(!tooltip){
						return;
					} 
					
				}		            
            },
            scale: {
            	reverse: false,
	            gridLines: {
	            	color: '#708090',	// 외곽라인
	            	lineWidth: 1.5,
	            },
	            angleLines: {						//	방사형 직선라인
	            	color: '#7FFFD4',
	            	lineWidth: 2,
	            },
	            pointLabels: {		// 라벨 사이즈
	            	fontColor: 'white',
	            	fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
	            	fontStyle: 'normal',
	            	fontSize: 12,
	            },
                ticks: { // Step Label
				  fontSize : 15,
				  fontColor : 'rgba(204,204,204,1)',	// gray
                  backdropColor: 'rgba(204,204,204,0)',
	              min: 0,
	              max: 100,
	              stepSize: 20,
                  beginAtZero: true,
                }
            },
//            hover: {
//				mode: 'index',
//				intersect: false,
//            }
    }
	return new Chart(ctx, {
	    type: "radar",
	    data: data,
	    options: options,
	});
}

/** Create 2 Color Chart **/
//Data Sample
//		var data = {
//			    labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
//			    datasets: [
//			        {
//			            label: "My First dataset",
//			            backgroundColor: "rgba(179,181,198,0.2)",
//			            borderColor: "rgba(179,181,198,1)",
//			            pointBackgroundColor: "rgba(179,181,198,1)",
//			            pointBorderColor: "#fff",
//			            pointHoverBackgroundColor: "#fff",
//			            pointHoverBorderColor: "rgba(179,181,198,1)",
//			            data: [65, 59, 90, 81, 56, 55, 40],
//			        },
//			        {
//			            label: "My Second dataset",
//			            backgroundColor: "rgba(255,99,132,0.2)",
//			            borderColor: "rgba(255,99,132,1)",
//			            pointBackgroundColor: "rgba(255,99,132,1)",
//			            pointBorderColor: "#fff",
//			            pointHoverBackgroundColor: "#fff",
//			            pointHoverBorderColor: "rgba(255,99,132,1)",
//			            data: [28, 60, 40, 19, 96, 27, 100]
//			        }
//			    ]
//			};