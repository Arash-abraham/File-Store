let currentInputId = null;
        let currentYear = 1404; // سال شمسی پیش‌فرض (برای 15 سپتامبر 2025)
        let currentMonth = 6; // ماه پیش‌فرض (شهریور)

        // باز کردن تقویم
        function openDatePicker(inputId) {
            try {
                currentInputId = inputId;
                const input = document.getElementById(inputId);
                if (!input) {
                    console.error('Input element not found:', inputId);
                    return;
                }
                const datePicker = document.getElementById('persianDatePicker');
                datePicker.classList.remove('hidden');
                populateYears();
                updateCalendar();
            } catch (e) {
                console.error('Error opening datepicker:', e);
            }
        }

        // بستن تقویم
        function closeDatePicker() {
            document.getElementById('persianDatePicker').classList.add('hidden');
            currentInputId = null;
        }

        // پر کردن سال‌ها
        function populateYears() {
            const yearSelect = document.getElementById('yearSelect');
            yearSelect.innerHTML = '';
            for (let i = 1394; i <= 1414; i++) { // محدوده 20 ساله
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                if (i === currentYear) option.selected = true;
                yearSelect.appendChild(option);
            }
        }

        // تغییر ماه
        function changeMonth(direction) {
            currentMonth += direction;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            } else if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            document.getElementById('monthSelect').value = currentMonth;
            document.getElementById('yearSelect').value = currentYear;
            updateCalendar();
        }

        // محاسبه تعداد روزهای ماه شمسی
        function getDaysInJalaliMonth(year, month) {
            if (month <= 6) return 31; // ماه‌های اول تا ششم: 31 روز
            if (month <= 11) return 30; // ماه‌های هفتم تا یازدهم: 30 روز
            // سال کبیسه (تقریبی: هر 4 سال یک‌بار)
            return (year % 4 === 3) ? 30 : 29; // اسفند: 29 یا 30 روز
        }

        // محاسبه روز اول ماه (تقریبی برای ترازبندی)
        function getFirstDayOfJalaliMonth(year, month) {
            // تبدیل تقریبی به میلادی و محاسبه روز اول
            const baseYear = 1390; // سال پایه شمسی
            const baseGregorian = 2011; // معادل میلادی
            const yearDiff = year - baseYear;
            const approxGregorianYear = baseGregorian + yearDiff;
            const gregorianDate = new Date(approxGregorianYear, month - 1, 1);
            return (gregorianDate.getDay() + 1) % 7; // شنبه=0
        }

        // به‌روزرسانی تقویم
        function updateCalendar() {
            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';

            const firstDayOfWeek = getFirstDayOfJalaliMonth(currentYear, currentMonth);
            const daysInMonth = getDaysInJalaliMonth(currentYear, currentMonth);

            // روزهای خالی قبل از شروع ماه
            for (let i = 0; i < firstDayOfWeek; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'text-center p-2';
                calendarDays.appendChild(emptyDiv);
            }

            // پر کردن روزهای ماه
            for (let day = 1; day <= daysInMonth; day++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'text-center p-2 cursor-pointer';
                dayDiv.textContent = day;
                dayDiv.onclick = () => selectDate(day);
                calendarDays.appendChild(dayDiv);
            }

            // به‌روزرسانی انتخابگرها
            document.getElementById('monthSelect').value = currentMonth;
            document.getElementById('yearSelect').value = currentYear;
        }

        // انتخاب تاریخ
        function selectDate(day) {
            if (currentInputId) {
                const formattedDate = `${currentYear}/${String(currentMonth).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
                const input = document.getElementById(currentInputId);
                if (input) {
                    input.value = formattedDate;
                } else {
                    console.error('Input element not found:', currentInputId);
                }
            }
            closeDatePicker();
        }

        // انتخاب امروز
        function selectToday() {
            // تاریخ امروز (15 سپتامبر 2025 ≈ 24 شهریور 1404)
            currentYear = 1404;
            currentMonth = 6;
            selectDate(24);
        }

        // پاک کردن تاریخ
        function clearDate() {
            if (currentInputId) {
                const input = document.getElementById(currentInputId);
                if (input) {
                    input.value = '';
                } else {
                    console.error('Input element not found:', currentInputId);
                }
            }
            closeDatePicker();
        }

        // اتصال رویدادها
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cursor-pointer').forEach(icon => {
                icon.addEventListener('click', function(event) {
                    event.preventDefault();
                    const input = this.previousElementSibling;
                    if (input && input.id) {
                        openDatePicker(input.id);
                    } else {
                        console.error('No input element found before icon');
                    }
                });
            });

            document.querySelectorAll('#startDatePersian, #endDatePersian').forEach(input => {
                input.addEventListener('click', function(event) {
                    event.preventDefault();
                    openDatePicker(this.id);
                });
            });
        });