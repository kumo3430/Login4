const recurringInstancesData = {};  // 存储重复的实例数据以供快速访问
window.onload = function() {
  document.querySelectorAll('div[id^="isCompleted-"]').forEach(div => {
    const recurringInstanceId = div.getAttribute('data-value');
    const instancesString = document.getElementById('instances-' + recurringInstanceId).getAttribute('data-value');
    const instances = JSON.parse(instancesString);
    recurringInstancesData[recurringInstanceId] = {
      instances: instances,
      currentIndex: instances.length-1 
    };
    updateDisplay(recurringInstanceId);
  });
}

function updateDisplay(recurringInstanceId) {
  const data = recurringInstancesData[recurringInstanceId];
  if (!data) return;
  const { instances, currentIndex } = data;
  var index = document.getElementById('index-' + recurringInstanceId);
  var instancesElement = document.getElementById('instances-' + recurringInstanceId);
  if (instances[currentIndex]) {
    instancesElement.textContent = instances[currentIndex].start_date; 
    index.textContent = instances[currentIndex].end_date; 
  }
}

window.incrementValue = function(recurringInstanceId) {
  const data = recurringInstancesData[recurringInstanceId];
  if (!data || data.currentIndex + 1 >= data.instances.length) {
    console.log("已為最後一筆");
    return;
  }
  data.currentIndex++;
  updateDisplay(recurringInstanceId);
}

window.decrementValue = function(recurringInstanceId) {
  const data = recurringInstancesData[recurringInstanceId];
  if (!data || data.currentIndex <= 0) {
    console.log("已是第一筆");
    return;
  }
  data.currentIndex--;
  updateDisplay(recurringInstanceId);
}

window.submitValue = function(recurringInstanceId) {
  // var isCompleted = document.getElementById('isCompleted-' + recurringInstanceId).getAttribute('data-value');
  var isCompleted = 0;
  var input = document.getElementById('input-' + recurringInstanceId);
  var value = parseInt(input.value, 10);
  const currentTotalElement = document.getElementById('currentTotal-' + recurringInstanceId);
  const currentTotal = parseInt(currentTotalElement.getAttribute('data-value'), 10) || 0;
  const goalValue = parseInt(document.getElementById('goalValue-' + recurringInstanceId).getAttribute('data-value'), 10);

    // 計算新的總值
    const newTotal = currentTotal + value;
    console.log("value: " + value);
    console.log("currentTotal: " + currentTotal);
    console.log("goalValue: " + goalValue);
    console.log("newTotal: " + newTotal);
    if (newTotal > goalValue) {
      alert("恭喜您已完成習慣");
      isCompleted = 1;
    } else {
      alert("已紀錄 再接再厲");
    }
  // 假設您有一個路由和對應的控制器方法來處理這個請求
  fetch(`/checks/${recurringInstanceId}/record`, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
          value: value,
          isCompleted: isCompleted
      })
  })
  .then(response => response.json())
  .then(data => {
      console.log('Success:', data);
      input.value = 0;
      currentTotalElement.textContent = newTotal; 
      currentTotalElement.setAttribute('data-value', newTotal); 
  })
  .catch((error) => {
      console.error('Error:', error);
  });
}
