'use strict';

let cartPanel = document.querySelector('.cart');
cartPanel.addEventListener('click', function(event) {
	event.stopPropagation();
});

let toCartBtns = document.querySelectorAll('.toCartBtn');
toCartBtns.forEach(function (btn) {
	btn.addEventListener('click', function (event) {
		let id = event.srcElement.dataset.id;
		let price = event.srcElement.dataset.price;
		let name = event.srcElement.dataset.name;
		cart.addProduct({
			id: id,
			price: price,
			name: name
		})
	})
});

let cart = {
	products: {},

	addProduct(product) {
		this.addProductToProducts(product);
		this.addProductToCart(product);
		this.renderTotalSum();
		this.addRemoveBtnListeners();
	},

	removeProductListener(event) {
		cart.removeProduct(event);
		cart.renderTotalSum();
	},

	addRemoveBtnListeners() {
		let removeBtns = document.querySelectorAll('.productRemoveBtn');
		for (let i = 0; i < removeBtns.length; i++) {
			removeBtns[i].addEventListener('click', this.removeProductListener);
		}
	},

	removeProduct(event) {
		let id = event.srcElement.dataset.id;
		this.removeProductFromCart(id);
		this.removeProductFromProducts(id);
	},

	removeProductFromCart(id) {
		let qty = document.querySelector(`.productQty[data-id="${id}"]`);
		if (qty.textContent == 1) {
			qty.parentNode.remove();
		} else {
			qty.textContent--;
		}
	},

	removeProductFromProducts(id) {
		if (this.products[id].qty == 1) {
			delete this.products[id];
		} else {
			this.products[id].qty--;
		}
	},

	renderTotalSum() {
		document.querySelector('.total').textContent = this.getTotalSum();
	},

	getTotalSum() {
		let sum = 0;
		for (let key in this.products) {
			sum += this.products[key].price * this.products[key].qty;
		}
		return sum;
	},

	addProductToProducts(product){
		if (this.products[product.id] == undefined) {
			this.products[product.id] = {
				id: product.id,
				price: product.price,
				name: product.name,
				qty: 1
			}
		} else {
			this.products[product.id].qty++;
		}
	},

	addProductToCart(product) {
		let productExist = document.querySelector(`.productQty[data-id="${product.id}"]`);
		if (productExist) {
			productExist.textContent++;
			// return;
		} else {
			let productInCartRow = `
						<tr>
							<td>${product.id}</td>
							<td colspan="4">${product.name}</td>
							<td>${product.price} <i class="fas fa-euro-sign"></i></td><td class="productQty" data-id="${product.id}">1</td>
							<td><i class="fas fa-trash-alt productRemoveBtn" data-id="${product.id}"></i></td>
						</tr>`;
			let tbody = document.querySelector('tbody');
			tbody.insertAdjacentHTML('beforeend', productInCartRow);
		}
	},
};