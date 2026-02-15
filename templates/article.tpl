{extends file="layout.tpl"}
{block name="content"}
<div class="container">
    <article class="article-full">
        <img src="{$article.image|absolute_image|escape}" alt="{$article.title|escape}" class="article-image-full">
        <h1 class="article-title-full">{$article.title|escape}</h1>
        <div class="article-meta">
            <time class="article-date">{$article.published_at|date_format}</time>
            <span class="article-views">{$viewsCount} просмотров</span>
        </div>
        <div class="article-text">{$article.text|escape|nl2br}</div>
    </article>

    {if !empty($similar)}
    <section class="similar-section">
        <h2>Похожие статьи</h2>
        <div class="articles-grid">
            {foreach $similar as $item}
            <article class="article-card">
                <a href="{url_article id=$item.id}" class="article-image-link">
                    <img src="{$item.image|absolute_image|escape}" alt="{$item.title|escape}" class="article-image">
                </a>
                <h3 class="article-title">
                    <a href="{url_article id=$item.id}">{$item.title|escape}</a>
                </h3>
                <time class="article-date">{$item.published_at|date_format}</time>
                <a href="{url_article id=$item.id}" class="continue-reading">Continue Reading</a>
            </article>
            {/foreach}
        </div>
    </section>
    {/if}
</div>
{/block}
