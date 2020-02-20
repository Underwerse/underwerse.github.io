'use strict';

// // Подключение необходимых css-стилей
// const title = document.querySelector('title');
// title.insertAdjacentHTML('beforebegin', '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">');
// title.insertAdjacentHTML('beforebegin', '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">');

// // Подключение необходимых скриптов
// const mainScriptLink = document.querySelector('script[src="scripts/script.js"]');
// mainScriptLink.insertAdjacentHTML('beforebegin', '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>');
// mainScriptLink.insertAdjacentHTML('beforebegin', '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>');
// mainScriptLink.insertAdjacentHTML('beforebegin', '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>');

let descriptionButtons = document.querySelectorAll('.descriptionBtn');
descriptionButtons.forEach(function (button) {
	button.addEventListener('click', function (event) {
		watchWhomClicked(event);
	})
});

function watchWhomClicked(clickedEvent) {
	let cardClicked = clickedEvent.target.parentNode;

	let card = {
		wrap: cardClicked,
		product: cardClicked.querySelector('.product'),
		h3: cardClicked.querySelector('h3'),
		img: cardClicked.querySelector('img'),
		button: cardClicked.querySelector('button'),
	};

	function showButtonText(card) {
		if (card.button.innerText == 'Подробнее') {
			showDescription(card);
		} else {
			showImage(card);
		}
	};
	showButtonText(card);
};

function showDescription(card) {
	card.img.style.display = 'none';
	card.button.innerText = 'Вернуться';
	let description = 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur maxime dolorum quia molestias laudantium molestiae ut! Cum sit dignissimos consectetur.';
	card.h3.insertAdjacentHTML('afterend', `<p class="shownDescription">${description}</p>`);
};

function showImage(card) {
	card.img.style.display = 'block';
	card.button.innerText = 'Подробнее';
	card.wrap.querySelector('.shownDescription').remove();
};