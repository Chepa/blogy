{extends file="layout.tpl"}
{block name="content"}
<div class="container">
    {foreach $categories as $category}
    <section class="category-section">
        <div class="category-header">
            <h2 class="category-title">{$category.name|upper}</h2>
            <a href="{url_category id=$category.id}" class="view-all">View All</a>
        </div>

        <div class="articles-grid">
            {foreach $category.articles as $article}
            <article class="article-card">
                <a href="{url_article id=$article.id}" class="article-image-link">
                    <img src="{$article.image|absolute_image|escape}" alt="{$article.title|escape}" class="article-image">
                </a>
                <h3 class="article-title">
                    <a href="{url_article id=$article.id}">{$article.title|escape}</a>
                </h3>
                <time class="article-date">{$article.published_at|date_format}</time>
                <p class="article-description">{$article.description|escape|truncate:120}</p>
                <a href="{url_article id=$article.id}" class="continue-reading">Continue Reading</a>
            </article>
            {/foreach}
        </div>
    </section>
    {/foreach}

    {if empty($categories)}
    <p class="empty-message">Пока нет категорий с статьями. Запустите сидер.</p>
    {/if}
</div>
{/block}
