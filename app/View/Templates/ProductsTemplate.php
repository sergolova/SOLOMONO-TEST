<?php
/** @var array $categories */
/** @var array $products */
/** @var Product $product */
/** @var string $sortName */
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
                    <li data-category-id="<?= $category['cat']->id; ?>" class="<?=$category['cat']->id === $currentCategory ? 'active-category' : '' ?>">
                        <?= $category['cat']->name; ?> (<span class="product-count"><?= $category['numProducts'] ?></span>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div id="products">
            <h2>Products</h2>
            <select id="sort-selector">
                <option value="price">Cheaper first</option>
                <option value="name">Alphabetical order</option>
                <option value="created_at">Newer first</option>
            </select>
            <ul id="product-list" class="product-grid">
                    <li data-product-id="0" class="product-item">
                        <div class="product-info">
                            <p class='product-name'></p>
                            <p class='product-price'></p>
                            <p class='product-created_at'></p>
                        </div>
                        <button class="buy-button" onclick="showModal('0')">Buy</button>
                    </li>
            </ul>
        </div>
    </div>
<?php
include 'FooterTemplate.php';
?>