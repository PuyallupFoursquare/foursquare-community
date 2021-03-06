<?php

/* Foursquare Community Site
 * 
 * Copyright (C) 2011 Foursquare Church.
 * 
 * Developers: Jesse Morgan <jmorgan@foursquarestaff.com>
 *
 */

require_once "src/base.inc.php";

require_once "src/header.inc.php";

// Do we have a path info?
if (isset($_SERVER['PATH_INFO'])) {
    // Get the category
    $category = addslashes(substr($_SERVER['PATH_INFO'], 1));

    $category = Category::getByShortname($category);
    if ($category) {
        displayEvents($category);
    
    } else {
        // Bad category. List them all.
        listCategories();
    }

} else {
    // No category. List them all.
    listCategories();
}


function listCategories() {
    echo "<h2>Categories</h2>";

    $cats = Category::getCategories();
    echo "<dl>";
    foreach ($cats as $short => $cat) {
        $url = $GLOBALS['CONFIG']['urlroot'] . "/categories/$short";
        echo "<dt><a href=\"$url\">". $cat->getName() ."</a></dt>";
        echo "<dd>". $cat->getDescription() ."</dd>";
    }
    echo "</dl>";
}

function displayEvents($category) {
    echo "<h2>". $category->getName() ."</h2>";

    // Get all recent, approved posts.
    $posts = new PostIterator();
    $posts->filterCategory($category->getId());
    $posts->filterStage('approved');
    $posts->query();

    if ($posts->valid()) {
        foreach ($posts as $id => $post) {
            $title = $post->getName();
            if ($post->getPrice() != 0) {
                $title .= ' - $' . $post->getPrice();
            }

            printf("<div class=\"post\"><p><a href=\"". $GLOBALS['CONFIG']['urlroot']
                    . "/postings/%s.html\">%s</a></p>"
                    . "<div class=\"desc\"><span class=\"location\">%s</span>"
                    . " <span class=\"age\">%s</span></div></div>",
                    
                    $id, $title, $post->getLocation(), $post->getAge());
        }

    } else {
        echo "<p>No recent posts.</p>";
    }
}

require_once "src/footer.inc.php";

?>

