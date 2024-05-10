window.onload = function() {
  
  var divs = document.querySelectorAll('div[id^="isCompleted-"]'); 

  divs.forEach(function(div) {
    var recurringInstanceId = div.getAttribute('data-value');
    console.log("Initial recurringInstanceId:", recurringInstanceId);
    var instancesString = document.getElementById('instances-' + recurringInstanceId).getAttribute('data-value');
    var instances = JSON.parse(instancesString);  
    var index = document.getElementById('index-' + recurringInstanceId);
    var currentIndex = parseInt(index.getAttribute('data-value'), 10);  
    index.setAttribute('data-value', instances.length); 
    // console.log("instances:", instances); // 直接输出数组对象
    // console.log("instancesString:", instancesString); 
    // console.log("instances.length:", instances.length); 
    // console.log("currentIndex:", currentIndex);
    // console.log("currentInstance:", instances[currentIndex]); 
  });
}

window.incrementValue = function(recurringInstanceId) {
    console.log("Initial recurringInstanceId:", recurringInstanceId);
    var instancesElement = document.getElementById('instances-' + recurringInstanceId);
    var instances = JSON.parse(instancesElement.getAttribute('data-value'));  // 转换 JSON 字符串为 JavaScript 数组
    var index = document.getElementById('index-' + recurringInstanceId);
    var currentIndex = parseInt(index.getAttribute('data-value'), 10);  

    if(currentIndex+1 <= instances.length) {
      index.setAttribute('data-value', currentIndex+1); 
      console.log("instances:", instances); // 直接输出数组对象
      console.log("instancesString:", instancesElement); 
      console.log("currentIndex:", currentIndex+1);
      console.log("currentInstance:", instances[currentIndex-1+1]); 
   
      instancesElement.textContent = instances[currentIndex-1+1].start_date; 
      index.textContent = instances[currentIndex-1+1].end_date; 
    } else {
      console.log("已為最後一筆");
    }
}

window.decrementValue = function(recurringInstanceId) {
  console.log("Initial recurringInstanceId:", recurringInstanceId);
  var instancesElement = document.getElementById('instances-' + recurringInstanceId);
  var instances = JSON.parse(instancesElement.getAttribute('data-value'));  // 转换 JSON 字符串为 JavaScript 数组
  var index = document.getElementById('index-' + recurringInstanceId);
  var currentIndex = parseInt(index.getAttribute('data-value'), 10);  
  console.log("currentIndex:", currentIndex);
  console.log("currentIndex-1:", currentIndex-1);
  console.log("instances.length:", instances.length);

  if(currentIndex-1 <= instances.length && currentIndex-1 >=0) {
    index.setAttribute('data-value', currentIndex-1); 
    console.log("instances:", instances); // 直接输出数组对象
    console.log("instancesString:", instancesElement); 
    console.log("currentIndex:", currentIndex-1);
    console.log("currentInstance:", instances[currentIndex-1-1]); 

    instancesElement.textContent = instances[currentIndex-1-1].start_date; 
    index.textContent = instances[currentIndex-1-1].end_date; 
  } else {
    console.log("已為最後一筆");
  }
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
