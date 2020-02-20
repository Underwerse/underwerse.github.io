'use strict';

document.head.insertAdjacentHTML("beforeend", '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">')

let slider = document.querySelector('.slider');

let loadIcon = document.createElement('i');
loadIcon.classList.add('fas', 'fa-spinner', 'fa-spin');
slider.insertAdjacentElement("afterbegin", loadIcon);

let leftArrow = document.createElement('i');
leftArrow.classList.add('fas', 'fa-arrow-circle-left', 'leftArrow');
slider.insertAdjacentElement("beforeend", leftArrow);

let rightArrow = document.createElement('i');
rightArrow.classList.add('fas', 'fa-arrow-circle-right', 'rightArrow');
slider.insertAdjacentElement("beforeend", rightArrow);

window.addEventListener('load', function() {
	leftArrow.addEventListener('click', function() {
		images.setNextLeftImage();
	});
	rightArrow.addEventListener('click', function() {
		images.setNextRightImage();
	});

	images.init();

	hideLoadIcon(loadIcon);
});

function hideLoadIcon(element) {
	document.element.style.visibility = 'hidden';
};

let images = {
	currentIndex: 0,
	slides: [],

	init() {
		this.slides = document.querySelectorAll('.slide');
		this.showNewImage();
	},

	showNewImage() {
		this.slides[this.currentIndex].classList.remove('hidden');
	},

	hideAllImagesExceptCurrent(index, animationDirection) {
		this.slides.forEach(function(slide) {
			slide.classList.add('hidden');
			slide.classList.remove('magictime', 'slideLeft', 'slideRight', 'slideLeftReturn', 'slideRightReturn');
		});
		this.setAnimation(index, animationDirection);
		this.slides[index].classList.remove('hidden');
	},

	setAnimation(index, animationDirection) {
		this.slides[index].classList.add('magictime', animationDirection);
	},

	setNextLeftImage() {
		this.hideAllImagesExceptCurrent(this.currentIndex, 'slideRight');
		// this.setAnimation(this.currentIndex, 'slideLeftReturn');
		if (this.currentIndex == 0) {
			this.currentIndex = this.slides.length - 1;
		} else {
			this.currentIndex--;
		}
		this.setAnimation(this.currentIndex, 'slideLeftReturn');
		this.showNewImage();
	},

	setNextRightImage() {
		this.hideAllImagesExceptCurrent(this.currentIndex, 'slideLeft');
		// this.setAnimation(this.currentIndex, 'slideRightReturn');
		if (this.currentIndex == this.slides.length - 1) {
			this.currentIndex = 0;
		} else {
			this.currentIndex++;
		}
		this.setAnimation(this.currentIndex, 'slideRightReturn');
		this.showNewImage();
	},

};


// Это код без анимации слайдов:

/* document.head.insertAdjacentHTML("beforeend", '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">')

let slider = document.querySelector('.slider');

let loadIcon = document.createElement('i');
loadIcon.classList.add('fas', 'fa-spinner', 'fa-spin');
slider.insertAdjacentElement("afterbegin", loadIcon);

let leftArrow = document.createElement('i');
leftArrow.classList.add('fas', 'fa-arrow-circle-left', 'leftArrow');
slider.insertAdjacentElement("beforeend", leftArrow);

let rightArrow = document.createElement('i');
rightArrow.classList.add('fas', 'fa-arrow-circle-right', 'rightArrow');
slider.insertAdjacentElement("beforeend", rightArrow);

window.addEventListener('load', function() {
	leftArrow.addEventListener('click', function() {
		images.setNextLeftImage();
	});
	rightArrow.addEventListener('click', function() {
		images.setNextRightImage();
	});

	images.init();

	hideLoadIcon();
});

function hideLoadIcon(loadIcon) {
	loadIcon.style.display = 'none';
};

let images = {
	currentIndex: 0,
	slides: [],

	init() {
		this.slides = document.querySelectorAll('.slide');
		this.showCurrentImage();
	},

	showCurrentImage() {
		this.slides[this.currentIndex].classList.remove('hidden');
	},

	hideAllImages() {
		this.slides.forEach(function(slide) {
			slide.classList.add('hidden');
		});
	},

	setNextLeftImage() {
		this.hideAllImages();
		if (this.currentIndex == 0) {
			this.currentIndex = this.slides.length - 1;
		} else {
			this.currentIndex--;
		}
		this.showCurrentImage();
	},

	setNextRightImage() {
		this.hideAllImages();
		if (this.currentIndex == this.slides.length - 1) {
			this.currentIndex = 0;
		} else {
			this.currentIndex++;
		}
		this.showCurrentImage();
	},
}; */







