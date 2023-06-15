/* datepicker JS */

let calendar = document.querySelector('.calendar');
if(calendar) {
    function dayClick(e) {
        let wrapper = e.closest(`.calendar-wrapper`);
        let curDate = new Date(parseInt(e.dataset.year), parseInt(e.dataset.month), parseInt(e.innerHTML), 0, 0, 0, 0);
        wrapper.parentNode.selectCB(curDate);
    }

    function generateDateTableBody(month, year) {
        let result = '';
        let counter = 0;
        let curDate = new Date(year, month, 1, 0, 0, 0, 0);
        let startDatOffset = curDate.getDate();



        for(let day=0; day<31; day++) {

            var days = [
                'Вс',
                'Пн',
                'Вт',
                'Ср',
                'Чт',
                'Пт',
                'Сб'
            ];



            let d = new Date(year, month, counter + startDatOffset, 0, 0, 0, 0);
            if( d.getMonth() == month )
                result += `<div class="calendar-curmonth calendar-item swiper-slide" data-month="${d.getMonth()}" data-year="${d.getFullYear()}" onclick="dayClick(this)">${d.getDate()}<div class="calendar-item__day">${days[d.getDay()]}</div></div>`;
            counter ++;
        }

        return result;
    }

    function datePickerMonthChange(e) {
        let month = e.options[e.selectedIndex].value;
        let wrapper = e.closest(`.calendar-wrapper`);
        let tableBody = wrapper.getElementsByClassName('calendar-slider-wrapper')[ 0 ];
        let yearSelector = wrapper.getElementsByClassName('calendar-year')[ 0 ];
        tableBody.innerHTML = generateDateTableBody(parseInt(month), parseInt(yearSelector.value));
        new Swiper('.calendar-slider', {
            slidesPerView: 5,
            navigation: {
                prevEl: '.calendar-slider-arrow__prev',
                nextEl: '.calendar-slider-arrow__next'
            }
        });
    }

    function datePickerYearChange(e) {
        let year = parseInt(e.value);
        let wrapper = e.closest(`.calendar-wrapper`);
        let tableBody = wrapper.getElementsByClassName('calendar-slider-wrapper')[ 0 ];
        let monthSelector = wrapper.getElementsByClassName('calendar-month')[ 0 ];
        tableBody.innerHTML =generateDateTableBody(parseInt(monthSelector.options[monthSelector.selectedIndex].value), year);
    }



    function generateCalendar(month, year) {
        let result = '';
        let curTime = new Date();
        let months = [ 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' ];

        result += '<div class="calendar-wrapper">';
        result += '<div class="calendar-header">';
        result += '</div>';
        result += '<div class="calendar-body">';
        result += '<div class="row">';
        result += '<div class="col">';
        result += '<div class="consultation-calendar-top"><div class="consultation-calendar__title">Выберите дату</div><select class="form-control calendar-month" onchange="datePickerMonthChange(this)"></div>';

        for(let index in months)
            if( index == month )
                result += `<option value="${index}" selected>${months[ index ]}</option>`;
            else
                result += `<option value="${index}">${months[ index ]}</option>`;

        result += '</select>';
        result += '</div>';

        result += '<input type="number" class="form-control calendar-year visually-hidden" value="' + year + '" onchange="datePickerYearChange(this)"/>';

        result += '</div>';
        result += '<div class="col-sm-12 consultation-calendar__wrapper">';
        result += '<div class="calendar-slider swiper-container"><div class="icon-arrow-left calendar-slider-arrow calendar-slider-arrow__prev"></div><div class="calendar-slider-wrapper swiper-wrapper">' + generateDateTableBody(month, year) + '</div><div class="icon-arrow-right calendar-slider-arrow calendar-slider-arrow__next"></div></div>';

        result += '</div>';
        result += '</div>';
        result += '</div>';
        result += '';

        return result;
    }

    function datePicker(wrapper, cb, startDate=null) {
        let curDate = new Date();

        if( startDate !== null )
            curDate = new Date(startDate);

        wrapper.innerHTML = generateCalendar(curDate.getMonth(), curDate.getFullYear());
        wrapper.selectCB = cb;
    }

    function calendarCB(date) {
        document.getElementById('dateInput').value = date;
        console.log(date)
    }

    function showCalend() {
        datePicker(document.getElementById('datePickerWrapper'), calendarCB);
    }

    showCalend();

    let calendarSlider = new Swiper('.calendar-slider', {
        slidesPerView: 5,
        navigation: {
            prevEl: '.calendar-slider-arrow__prev',
            nextEl: '.calendar-slider-arrow__next'
        }
    });

    let calendarItems = document.querySelectorAll('.calendar-item');
    calendarItems.forEach(function (item) {
        item.classList.remove('calendar-item_active');
        item.addEventListener('click', function () {
            calendarItems.forEach(function (unit) {
                unit.classList.remove('calendar-item_active');
            });
            item.classList.add('calendar-item_active')
        })
    });
}

