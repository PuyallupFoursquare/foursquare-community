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


// Make sure we had a path info
if (!isset($_SERVER['PATH_INFO'])) {
    errorNotFound();
}

// Clean up the id in the path info.
$id = substr($_SERVER['PATH_INFO'], 1, strpos($_SERVER['PATH_INFO'], '.') - 1);

if (!is_numeric($id)) {
    errorNotFound();
}

// Get the post.
$post = Post::getById($id);

if (!$post or (!isset($_GET['moderate']) and $post->getStage() != 'approved')) {
    errorNotFound();
}

if (isset($_GET['moderate'])) {
    if (!isset($_SESSION['currentUser'])) {
        header('Location: ' . $CONFIG['urlroot'].'/moderate/login.php');
        exit();
    }
    echo "<div class=\"moderationbox\">You are moderating this post: ";
    printf("<a href=\"../moderate/moderate.php?id=%s&action=approve\">Approve</a> "
        . "<a href=\"../moderate/moderate.php?id=%s&action=reject\">Reject</a>",
        $post->getId(), $post->getId());
    echo "<p><a href=\"../moderate/index.php\">Return to moderation</a></p>";
    echo "</div>";

}

// Display the post.

echo "<h2>". $post->getName() ."</h2>";

echo "<p>Date: ". date('r', $post->getTimestamp()) ."</p>";
echo "<p>Email: <a href=\"mailto:". $post->getPublicEmail() ."\">"
    . $post->getPublicEmail() ."</a></p>";

echo "<p class=\"desc\">". 
    str_replace("\n", '<br />', $post->getDescription())
    ."</p>";

foreach ($post->getImages() as $imgid) {
    echo "<p><img src=\"". $GLOBALS['CONFIG']['urlroot']
        . "/postimages/$imgid\" /></p>";
}

require_once "src/footer.inc.php";

function errorNotFound() {
    // TODO: Better 404 error
    echo "404";
    exit;
}

?>
