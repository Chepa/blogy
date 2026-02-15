{extends file="layout.tpl"}
{block name="content"}
<div class="container">
    <section class="category-section">
        <h1 class="category-title">{$category.name|upper}</h1>
        {if $category.description}
        <p class="category-description">{$category.description|escape}</p>
        {/if}

        <div class="sort-bar">
            <span>Сортировка:</span>
            <a href="{url_category id=$category.id sort='date' dir='desc' page=1}" class="{if $sortBy == 'date' && $sortDir == 'desc'}active{/if}">По дате (новые)</a>
            <a href="{url_category id=$category.id sort='date' dir='asc' page=1}" class="{if $sortBy == 'date' && $sortDir == 'asc'}active{/if}">По дате (старые)</a>
            <a href="{url_category id=$category.id sort='views' dir='desc' page=1}" class="{if $sortBy == 'views'}active{/if}">По просмотрам</a>
        </div>

        <div class="articles-grid">
            {foreach $articles as $article}
            <article class="article-card">
                <a href="{url_article id=$article.id}" class="article-image-link">
                    <img src="{$article.image|absolute_image|escape}" alt="{$article.title|escape}" class="article-image">
                </a>
                <h3 class="article-title">
                    <a href="{url_article id=$article.id}">{$article.title|escape}</a>
                </h3>
                <time class="article-date">{$article.published_at|date_format}</time>
                <p class="article-description">{$article.description|escape|truncate:120}</p>
                <span class="article-views">{$article.views_count} просмотров</span>
                <a href="{url_article id=$article.id}" class="continue-reading">Continue Reading</a>
            </article>
            {/foreach}
        </div>

        {if empty($articles)}
        <p class="empty-message">В этой категории пока нет статей.</p>
        {/if}

        {if $totalPages > 1}
        <nav class="pagination">
            {if $currentPage > 1}
            <a href="{url_category id=$category.id sort=$sortBy dir=$sortDir page=$currentPage-1}" class="pagination-link">&laquo; Назад</a>
            {/if}

            {for $p = 1 to $totalPages}
            <a href="{url_category id=$category.id sort=$sortBy dir=$sortDir page=$p}" class="pagination-link {if $p == $currentPage}active{/if}">{$p}</a>
            {/for}

            {if $currentPage < $totalPages}
            <a href="{url_category id=$category.id sort=$sortBy dir=$sortDir page=$currentPage+1}" class="pagination-link">Вперёд &raquo;</a>
            {/if}
        </nav>
        {/if}
    </section>
</div>
{/block}
