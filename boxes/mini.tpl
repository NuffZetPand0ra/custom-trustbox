<!doctype html>
<html>
    <head>
        <title>Trusbox for {$data->display_name}</title>
        <link href="{$base_path}/static/themes/{$theme}.css" rel="stylesheet">
        <link href="<link href="https://fonts.googleapis.com/css?family=Lato:100,300&display=swap" rel="preload" as="style" onload="this.rel='stylesheet'">
        <meta charset="utf-8">
    </head>
    <body style="background-color:{$background_color};font-size:{$font_size}px">
    <a target="_blank" href="{$data->profile_url}">
        <div id="content-wrapper">
            <h3><img src="{$base_path}/static/img/trustpilot_logo_10x10.gif">{$trustscore_str}</h3>
            <div id="trust-stars">
                {for $i=1 to $data->stars}
                <span class="trust-star star-{$i}">&#9733;</span>
                {/for}
            </div>
            <p id="review-count">{$review_count_str}</p>
        </div>
    </a>
    </body>
</html>