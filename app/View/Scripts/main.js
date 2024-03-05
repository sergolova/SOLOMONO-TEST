document.addEventListener('DOMContentLoaded', function () {
    const categoryItems = document.querySelectorAll('#categories li');

    categoryItems.forEach((categoryItem) =>
        categoryItem.addEventListener('click', onCategoryChange)
    );

    document.getElementById('sort-selector')?.addEventListener('change', onSortChange);

    updateProducts();

    function onSortChange() {
        updateProducts();
    }

    function onCategoryChange() {
        const catItems = document.querySelectorAll('#categories li');

        catItems.forEach((elem) => elem.classList.remove('active-category'));
        this.classList.add('active-category');

       updateProducts();
    }

    function updateProductItem(listItem, product) {
        listItem.setAttribute('data-product-id', product.id);
        listItem.dataset.productId = product.id;
        listItem.querySelector('.product-name').textContent = product.name;
        listItem.querySelector('.product-price').textContent = product.price;
        listItem.querySelector('.product-created_at').textContent = product.created_at;
    }

    function updateProductList(products) {
        const productList = document.getElementById('product-list');
        const productTemplate = document.querySelector('.product-item[data-product-id="0"]')

        const productItems = document.querySelectorAll('.product-item');
        productItems && productItems.forEach((item) => (item !== productTemplate) && item.remove());

        products.forEach(function (product) {
            const listItem = productTemplate.cloneNode(true);
            updateProductItem(listItem, product)
            productList.appendChild(listItem);
        });
    }

    function fetchProducts() {
        const activeCategory = document.querySelector('#categories ul li.active-category');
        const categoryId = activeCategory.dataset.categoryId;
        const sortSelector = document.getElementById('sort-selector');
        const sortName = sortSelector.value;
        const url = '/getProductsAjax?category_id=' + categoryId + '&sort_name=' + sortName;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 400) {
                const jsonResponse = JSON.parse(xhr.responseText);
                updateProductList(jsonResponse.products);
            } else {
                console.error('Request failed with status', xhr.status);
            }
        };
        xhr.onerror = () => console.error('Request failed');
        xhr.send();
    }

    function updateProducts() {
        fetchProducts();
        updateURL();
    }

    function updateURL() {
        const activeCategory = document.querySelector('#categories li.active-category');
        const sortSelector = document.getElementById('sort-selector');
        const categoryId = activeCategory.dataset.categoryId;
        const sortValue = sortSelector.value;
        const newURL = window.location.origin + window.location.pathname + '?category_id=' + categoryId + '&sort_name=' + sortValue;
        history.replaceState(null, null, newURL);
    }
});