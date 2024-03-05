<?php
/** @var array<> $categories */
/** @var array<Model\Product> $products */
/** @var string $sortName */
/** @var array<string> $sortOptions */
/** @var int $currentCategory */

include 'HeaderTemplate.php';
?>
    <div class='content-container'>
        <div id='categories'>
            <h2>Категорії</h2>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li data-category-id="<?= $category['cat']->id; ?>"
                        class="<?= $category['cat']->id === $currentCategory ? 'active-category' : '' ?>">
                        <?= $category['cat']->name; ?> (<span
                            class="product-count"><?= $category['numProducts'] ?></span>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div id="products">
            <h2>Продукти</h2>
            <label>
                Сортування
                <select id="sort-selector" <?= $sortName ?>>
                    <?php foreach ($sortOptions as $key => $title): ?>
                        <option value="<?= $key ?>" <?= $key === $sortName ? 'selected' : '' ?>><?= $title ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <ul id="product-list" class="product-grid">
                <li data-product-id="0" class="product-item">
                    <div class="product-info">
                        <div class='product-name'></div>
                        <div class='product-price'></div>
                        <div class='product-created_at'></div>
                    </div>
                    <button class="btn buy-button" data-bs-toggle='modal'
                            data-bs-target='#buyModal'>Купити
                    </button>
                </li>
            </ul>
        </div>
    </div>

<?php
include 'ProductModalTemplate.php';
include 'FooterTemplate.php';
?>