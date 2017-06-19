var btns = document.querySelectorAll('.share>.nav>.item');
var slider = document.querySelector('.share>.slider-container');


for(var i = 0; i< btns.length; i++)
{
	btns[i].index = i

	btns[i].addEventListener('click', function(e) {
		if(!isCurrent(this))
		{
			removeClass()
			moveDiv(e.target.index, this)
		}
	})
}

function removeClass()
{
	for(btn of btns)
	{
		if(btn.classList[1] == 'current-item'){btn.classList = 'item'}
	}
}

function isCurrent(e)
{
	if(e.classList[1] == 'current-item'){return true}
	else{return false}
}

function moveDiv(index, element)
{
	if(index == 0)
	{
		slider.style.transform = 'translateX(0%)'
		btns[index].classList += ' current-item'
	}
	else if (index == 1)
	{
		slider.style.transform = 'translateX(-50%)'
		btns[index].classList += ' current-item'
	}
}


var slider_btns = document.querySelectorAll('.event .item');
var event_slider = document.querySelector('.event>.slider-container');

for(var i = 0; i< slider_btns.length; i++)
{
	slider_btns[i].index = i

	slider_btns[i].addEventListener('click', function(e) {
		if(!isCurrentSlide(this))
		{
			removeClassSlide()
			moveDivSlide(e.target.index, this)
		}
	})
}

function removeClassSlide()
{
	for(btn of slider_btns)
	{
		if(btn.classList[1] == 'current-slide'){btn.classList = 'item'}
	}
}

function isCurrentSlide(e)
{
	if(e.classList[1] == 'current-slide'){return true}
	else{return false}
}

function moveDivSlide(index, element)
{
	if(index == 0)
	{
		event_slider.style.transform = 'translateX(0%)'
		slider_btns[index].classList += ' current-slide'
	}
	else if (index == 1)
	{
		event_slider.style.transform = 'translateX(-33.33%)'
		slider_btns[index].classList += ' current-slide'
	}
	else if (index == 2)
	{
		event_slider.style.transform = 'translateX(-66.66%)'
		slider_btns[index].classList += ' current-slide'
	}
}