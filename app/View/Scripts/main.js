document.addEventListener('DOMContentLoaded', function() {
    // Найти все элементы категорий
    var categoryItems = document.querySelectorAll('#categories li');

    // Перебрать элементы и добавить обработчик события для каждого
    categoryItems.forEach(function(categoryItem) {

        categoryItem.addEventListener('click', function(event) {
            // Получить id выбранной категории
            var categoryId = this.getAttribute('data-category-id');

            var catItems = document.querySelectorAll('#categories li');
            catItems.forEach((elem)=>elem.classList.remove('active-category'));
            event.target.classList.add('active-category');

            // Получить текущий выбранный порядок сортировки (предположим, что он хранится в переменной sortName)
            var sortName = 'alphabetically'; // Замените это на фактическую логику получения порядка сортировки

            // Отправить AJAX-запрос
            var xhr = new XMLHttpRequest();
            const url = '/getProductsAjax?category_id=' + categoryId + '&sort_name=' + sortName;
            console.log(url);
            xhr.open('GET', url, true);

            // Определить обработчик события для завершения запроса
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var jsonResponse = JSON.parse(xhr.responseText);
                    console.log(jsonResponse);
                    updateProductList(jsonResponse.products);
                } else {
                    console.error('Request failed with status', xhr.status);
                }
            };

            // Обработать возможные ошибки запроса
            xhr.onerror = function() {
                console.error('Request failed');
            };

            // Отправить запрос
            xhr.send();
        });
    });

    function updateProductList(products) {
        var productList = document.getElementById('product-list');
        var productTemplate = document.querySelector('.product-item[data-product-id="0"]')

        // Удаляем все дочерние элементы с классом .product-item
        var productItems = document.querySelectorAll('.product-item');
        productItems.forEach(function(item) {
            if (item !== productTemplate) {
                item.remove();
            }
        });

        // Перебрать полученные товары и добавить их в список
        products.forEach(function(product) {
            // Клонировать шаблон товара
            var listItem = productTemplate.cloneNode(true);
            // Заменить значения в клоне данными из полученного товара
            listItem.setAttribute('data-product-id', product.id);
            listItem.querySelector('.product-name').textContent = product.name;
            listItem.querySelector('.product-price').textContent = product.price;
            listItem.querySelector('.product-created_at').textContent = product.created_at;

            // Добавить клон в список товаров
            productList.appendChild(listItem);
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Получаем первый элемент категории
    var firstCategory = document.querySelector('#categories ul li:first-child');

    // Проверяем, что элемент существует, прежде чем добавлять обработчик событий
    if (firstCategory) {
        // Вызываем событие click на первом элементе
        firstCategory.click();
    }
});