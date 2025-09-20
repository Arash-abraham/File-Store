// حرکت خودکار بین inputها از چپ به راست
function moveToNext(field, index) {
    if (field.value.length >= field.maxLength) {
      if (index < 5) { // تا 5 چون 6 تای داریم (0 تا 5)
        const nextInput = document.querySelectorAll('.code-input')[index + 1];
        nextInput.focus();
        highlightInput(nextInput);
      }
    } else if (field.value.length === 0 && index > 0) {
      const prevInput = document.querySelectorAll('.code-input')[index - 1];
      prevInput.focus();
      highlightInput(prevInput);
    }
    
    // به‌روزرسانی مقدار hidden input
    updateFullCode();
}
  
  // هایلایت کردن input فعال
function highlightInput(input) {
  document.querySelectorAll('.code-input').forEach(inp => {
    inp.classList.remove('active');
    });
  input.classList.add('active');
}

// حذف هایلایت از input
function unhighlightInput(input) {
  input.classList.remove('active');
}

function updateFullCode() {
  const inputs = document.querySelectorAll('.code-input');
  let fullCode = '';
  inputs.forEach(input => {
    fullCode += input.value || ''; // اضافه کردن مقدار خالی اگه چیزی نباشه
  });
  document.getElementById('fullCode').value = fullCode;
  console.log('Full Code:', fullCode); // برای دیباگ
}

// تایمر معتبر بودن کد
function startTimer(duration, display) {
  let timer = duration, minutes, seconds;
  setInterval(function () {
    minutes = parseInt(timer / 60, 10);
    seconds = parseInt(timer % 60, 10);
    
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;
    
    display.textContent = minutes + ":" + seconds;
    
    if (--timer < 0) {
      timer = duration; // می‌تونی اینجا ریست کنی یا غیرفعال کنی
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

// فوکوس خودکار روی اولین input هنگام لود صفحه
document.addEventListener('DOMContentLoaded', function() {
  const firstInput = document.querySelectorAll('.code-input')[0];
  firstInput.focus();
  highlightInput(firstInput);
});