<?php
if (!defined('ABSPATH')) die();

return [
    'articles_over_time' => [
        'name' => 'Vývoj počtu vydaných článků v čase',
        'sql'  => "SELECT DATE({prefix}posts.post_date) AS datum, COUNT({prefix}posts.ID) AS pocet FROM {prefix}posts WHERE {prefix}posts.post_status = 'publish' AND {prefix}posts.post_type = 'post' AND {prefix}posts.post_date > NOW() - INTERVAL {interval} GROUP BY datum ORDER BY datum ASC"
    ],
    'top_authors' => [
        'name' => 'Nejaktivnější autoři článků',
        'sql'  => "SELECT {prefix}users.display_name AS autor, COUNT({prefix}posts.ID) AS pocet_clanku FROM {prefix}users JOIN {prefix}posts ON {prefix}users.ID = {prefix}posts.post_author WHERE {prefix}posts.post_status = 'publish' AND {prefix}posts.post_type = 'post' AND {prefix}posts.post_date > NOW() - INTERVAL {interval} GROUP BY autor ORDER BY pocet_clanku DESC LIMIT 10"
    ],
    'top_categories' => [
        'name' => 'Nejčastěji používané kategorie',
        'sql'  => "SELECT {prefix}terms.name AS kategorie, COUNT({prefix}term_relationships.object_id) AS pocet FROM {prefix}terms JOIN {prefix}term_taxonomy ON {prefix}terms.term_id = {prefix}term_taxonomy.term_id JOIN {prefix}term_relationships ON {prefix}term_taxonomy.term_taxonomy_id = {prefix}term_relationships.term_taxonomy_id JOIN {prefix}posts ON {prefix}term_relationships.object_id = {prefix}posts.ID WHERE {prefix}term_taxonomy.taxonomy = 'category' AND {prefix}posts.post_status = 'publish' AND {prefix}posts.post_date > NOW() - INTERVAL {interval} GROUP BY kategorie ORDER BY pocet DESC LIMIT 10"
    ],
    'comment_status' => [
        'name' => 'Stav komentářů (Schválené / Čekající / Spam)',
        'sql'  => "SELECT CASE WHEN {prefix}comments.comment_approved = '1' THEN 'Schválené' WHEN {prefix}comments.comment_approved = '0' THEN 'Čekající' ELSE 'Spam' END AS stav, COUNT({prefix}comments.comment_ID) AS pocet FROM {prefix}comments WHERE {prefix}comments.comment_date > NOW() - INTERVAL {interval} GROUP BY stav ORDER BY pocet DESC"
    ],
    'most_commented_articles' => [
        'name' => 'Články s největším počtem komentářů',
        'sql'  => "SELECT {prefix}posts.post_title AS clanek, {prefix}posts.comment_count AS pocet_komentaru FROM {prefix}posts WHERE {prefix}posts.post_status = 'publish' AND {prefix}posts.post_type = 'post' AND {prefix}posts.post_date > NOW() - INTERVAL {interval} AND {prefix}posts.comment_count > 0 ORDER BY pocet_komentaru DESC LIMIT 10"
    ],
    'top_commenters' => [
        'name' => 'Nejaktivnější komentátoři',
        'sql'  => "SELECT {prefix}comments.comment_author AS komentator, COUNT({prefix}comments.comment_ID) AS pocet FROM {prefix}comments WHERE {prefix}comments.comment_approved = '1' AND {prefix}comments.comment_date > NOW() - INTERVAL {interval} AND {prefix}comments.comment_author != '' GROUP BY komentator ORDER BY pocet DESC LIMIT 10"
    ]
];