window.onload = function() {
  
  var divs = document.querySelectorAll('div[id^="isCompleted-"]'); 

  divs.forEach(function(div) {
    var isCompleted = div.getAttribute('data-value');
    var buttons = div.getElementsByTagName('button');
    var inputs = div.getElementsByTagName('input');

    if (isCompleted === '1') {
      div.classList.add('bg-completed');
      Array.from(inputs).forEach(input => input.disabled = true);
      Array.from(buttons).forEach(button => button.disabled = true);
    } else {
      div.classList.add('bg-in-progress');
      Array.from(inputs).forEach(input => input.disabled = false); 
      Array.from(buttons).forEach(button => button.disabled = false); 
    }
  });
}

window.incrementValue = function(recurringInstanceId) {
    console.log("Initial recurringInstanceId:", recurringInstanceId);
    var input = document.getElementById('input-' + recurringInstanceId);
    var currentValue = parseInt(input.value, 10);
    input.value = currentValue + 1;
}

window.decrementValue = function(recurringInstanceId) {
  console.log("Initial recurringInstanceId:", recurringInstanceId);
  var input = document.getElementById('input-' + recurringInstanceId);
  var currentValue = parseInt(input.value, 10);
  if (currentValue > 0) {
      input.value = currentValue - 1;
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
      currentTotalElement.textContent = newTotal; // 更新 DOM 显示
      currentTotalElement.setAttribute('data-value', newTotal); // 更新存储的 data-value
  })
  .catch((error) => {
      console.error('Error:', error);
  });
}
