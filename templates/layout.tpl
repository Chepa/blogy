<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle|default:'Blogy.'}</title>
    {if isset($pageDescription) && $pageDescription}
    <meta name="description" content="{$pageDescription|escape}">
    {/if}
    {if isset($canonicalUrl) && $canonicalUrl}
    <link rel="canonical" href="{$canonicalUrl|escape}">
    {/if}
    {* Open Graph *}
    <meta property="og:type" content="{if isset($ogType) && $ogType}{$ogType|escape}{else}website{/if}">
    <meta property="og:title" content="{$pageTitle|default:'Blogy.'|escape}">
    {if isset($pageDescription) && $pageDescription}
    <meta property="og:description" content="{$pageDescription|escape}">
    {/if}
    <meta property="og:url" content="{$canonicalUrl|default:$baseUrl|escape}">
    <meta property="og:locale" content="ru_RU">
    {if isset($ogImage) && $ogImage}
    <meta property="og:image" content="{$ogImage|escape}">
    {/if}
    {* Twitter Card *}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{$pageTitle|default:'Blogy.'|escape}">
    {if isset($pageDescription) && $pageDescription}
    <meta name="twitter:description" content="{$pageDescription|escape}">
    {/if}
    {if isset($ogImage) && $ogImage}
    <meta name="twitter:image" content="{$ogImage|escape}">
    {/if}
    <link rel="stylesheet" href="{$baseUrl}/css/style.css">
    {if isset($pageSchema) && $pageSchema}
    <script type="application/ld+json">{$pageSchema nofilter}</script>
    {/if}
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="{$baseUrl}/" class="logo">Blogy.</a>
        </div>
    </header>

    <main class="main">
        {block name="content"}{/block}
    </main>

    <footer class="footer">
        <div class="container">
            <p>Copyright &copy;2025. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
