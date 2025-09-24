
function moveToNext(field, index) {
    if (field.value.length >= field.maxLength) {
      if (index < 5) { 
        const nextInput = document.querySelectorAll('.code-input')[index + 1];
        nextInput.focus();
        highlightInput(nextInput);
      }
    } else if (field.value.length === 0 && index > 0) {
      const prevInput = document.querySelectorAll('.code-input')[index - 1];
      prevInput.focus();
      highlightInput(prevInput);
    }

    updateFullCode();
}
  
function highlightInput(input) {
  document.querySelectorAll('.code-input').forEach(inp => {
    inp.classList.remove('active');
    });
  input.classList.add('active');
}

function unhighlightInput(input) {
  input.classList.remove('active');
}

function updateFullCode() {
  const inputs = document.querySelectorAll('.code-input');
  let fullCode = '';
  inputs.forEach(input => {
    fullCode += input.value || ''; 
  });
  document.getElementById('fullCode').value = fullCode;
  console.log('Full Code:', fullCode); // برای دیباگ
}

function startTimer(duration, display) {
  let timer = duration, minutes, seconds;
  setInterval(function () {
    minutes = parseInt(timer / 60, 10);
    seconds = parseInt(timer % 60, 10);
    
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;
    
    display.textContent = minutes + ":" + seconds;
    
    if (--timer < 0) {
      timer = duration; 
    }
  }, 1000);
}

window.onload = function () {
  const fiveMinutes = 60 * 5;
  const display = document.querySelector('#countdown');
  startTimer(fiveMinutes, display);
};

document.addEventListener('dragstart', function(e) {
  e.preventDefault();
});

document.addEventListener('DOMContentLoaded', function() {
  const firstInput = document.querySelectorAll('.code-input')[0];
  firstInput.focus();
  highlightInput(firstInput);
});