
window.incrementValue = function(todoId) {
    console.log("Initial todoId:", todoId);
    var input = document.getElementById('input-' + todoId);
    var currentValue = parseInt(input.value, 10);
    input.value = currentValue + 1;
}

window.decrementValue = function(todoId) {
  console.log("Initial todoId:", todoId);
  var input = document.getElementById('input-' + todoId);
  var currentValue = parseInt(input.value, 10);
  if (currentValue > 0) {
      input.value = currentValue - 1;
  }
}