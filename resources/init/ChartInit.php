<?php


// json 형태로 내보내기...
function createDefChrt(){
	
	//btg를 이용해서 라벨에도 붙이고 내보내기.
// 	labels: labels,
	echo '{
// 		    labels: ["1", "2", "3", "4", "5", "6"],
		    datasets: [
		        {
		            label: "",
					backgroundColor: "rgba(154,208,245,0.2)",
					borderColor: "rgba(154,208,245,1)",
// 		            backgroundColor: "rgba(179,181,198,0.2)",
// 		            borderColor: "rgba(179,181,198,1)",
		            pointBackgroundColor: "rgba(154,208,245,1)",
		            pointBorderColor: "#fff",
		            pointHoverBackgroundColor: "#fff",
		            pointHoverBorderColor: "rgba(179,181,198,1)",
		            data: []
		        },
		        {
		            label: "",
		            backgroundColor: "rgba(255,99,132,0.2)",
		            borderColor: "rgba(255,99,132,1)",
		            pointBackgroundColor: "rgba(255,99,132,1)",
		            pointBorderColor: "#fff",
		            pointHoverBackgroundColor: "#fff",
		            pointHoverBorderColor: "rgba(255,99,132,1)",
		            data: []
		        },
		        {
		            label: "",
		            backgroundColor: "rgba(255,230,170,0.2)",
		            borderColor: "rgba(255,230,170,1)",
		            pointBackgroundColor: "rgba(255,230,170,1)",
		            pointBorderColor: "#fff",
		            pointHoverBackgroundColor: "#fff",
		            pointHoverBorderColor: "rgba(255,99,132,1)",
		            data: []
		        }
		    ]
		}';
}
