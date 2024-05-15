const recurringInstancesData = {};

window.onload = function() {
  document.querySelectorAll('div[id^="isCompleted-"]').forEach(div => {
    const recurringInstanceId = div.getAttribute('data-value');
    const instancesString = document.getElementById('instances-' + recurringInstanceId).getAttribute('data-value');
    const instances = JSON.parse(instancesString);
    recurringInstancesData[recurringInstanceId] = {
      instances: instances,
      currentIndex: instances.length-1 
    };
    updateDisplay(recurringInstanceId,false);
  });
}

function updateDisplay(recurringInstanceId,update) {
  const data = recurringInstancesData[recurringInstanceId];
  const chartId = document.getElementById('chart-' + recurringInstanceId).getAttribute('data-value');
  if (!data) return;
  const { instances, currentIndex } = data;
  var index = document.getElementById('index-' + recurringInstanceId);
  var instancesElement = document.getElementById('instances-' + recurringInstanceId);
  if (instances[currentIndex]) {
    instancesElement.textContent = instances[currentIndex].start_date; 
    index.textContent = instances[currentIndex].end_date; 
    console.log("instances[currentIndex].id", instances[currentIndex].id);
    if(update){
      updateChartData(chartId,recurringInstanceId)
    }
  }
}

window.incrementValue = function(recurringInstanceId) {
  const data = recurringInstancesData[recurringInstanceId];
  if (!data || data.currentIndex + 1 >= data.instances.length) {
    console.log("已為最後一筆");
    return;
  }
  data.currentIndex++;
  updateDisplay(recurringInstanceId,true);
}

window.decrementValue = function(recurringInstanceId) {
  const data = recurringInstancesData[recurringInstanceId];
  if (!data || data.currentIndex <= 0) {
    console.log("已是第一筆");
    return;
  }
  data.currentIndex--;
  updateDisplay(recurringInstanceId,true);
}

function updateChartData(chartId,recurringInstanceId) {
  const instancesData = recurringInstancesData[recurringInstanceId];
  console.log("recurringInstancesData",recurringInstancesData);
  console.log("recurringInstanceId",recurringInstanceId);
  console.log("instancesData",instancesData);
  axios.post(`/charts/${recurringInstanceId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    instancesData: instancesData
  })
  .then(function (response) { 
    console.log("chartId",chartId);
    var chart = window[chartId];
    console.log("chart",chart);
    const data = response.data;
    console.log("labels",data.labels[0]);
    console.log("max",data.max);
    console.log("datasetsData",data.datasetsData[0]);
    if (chart) {
      chart.data.labels = data.labels[0];
      chart.options.scales.yAxes[0].ticks.max = data.max;
      chart.data.datasets[0].data = data.datasetsData[0];
      chart.update();
    } else {
      console.log("chartId找不到",chartId);
    }
  })
  .catch(function (error) {
      console.error('Error fetching chart data:', error);
  });
}
