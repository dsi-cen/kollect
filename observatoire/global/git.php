<?php

/**
 * Get the hash of the current git HEAD
 * @param str $branch The git branch to check
 * @return mixed Either the hash or a boolean false
 */

function get_current_git_commit( $branch ) {
    if ( $hash = file_get_contents( sprintf( '../.git/refs/heads/%s', $branch ) ) ) {
        return substr($hash, 0, 7);
    } else {
        return false;
    }
}

$git = '<img class="mr-2" src="../dist/img/git.png" height="25px" width="25px">';
$branch = implode('/', array_slice(explode('/', file_get_contents('../.git/HEAD')), 2));
$git .= 'Branch : <span class="badge color1_bg blanc">' . $branch . '</span>';
$git .= ' Commit : <span class="badge color1_bg blanc"> ' . get_current_git_commit( trim($branch) ) . '</span>';
echo $git;