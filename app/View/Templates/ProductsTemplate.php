<?php
/** @var array<> $categories */
/** @var array<Model\Product> $products */
/** @var string $sortName */
/** @var array<string> $sortOptions */
/** @var int $currentCategory */
/** @var string $message */
/** @var string $messageType */

use Model\Product;
use Model\Category;

include 'HeaderTemplate.php';
?>
    <div class='content-container'>
        <div id='categories'>
            <h2>Categories</h2>
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
            <h2>Products</h2>
            <select id="sort-selector" <?=$sortName ?>>
                <?php foreach ($sortOptions as $key => $title): ?>
                    <option value="<?= $key ?>" <?= $key === $sortName ? 'selected' : '' ?>><?= $title ?></option>
                <?php endforeach; ?>
            </select>
            <ul id="product-list" class="product-grid">
                <li data-product-id="0" class="product-item">
                    <div class="product-info">
                        <p class='product-name'></p>
                        <p class='product-price'></p>
                        <p class='product-created_at'></p>
                    </div>
                    <button class="buy-button" data-bs-toggle='modal'
                            data-bs-target='#buyModal'
                            onclick='openModal(this)'
                    >Buy</button>
                </li>
            </ul>
        </div>
    </div>

<?php
include 'ProductModalTemplate.php';
include 'FooterTemplate.php';
?>