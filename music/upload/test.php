<?php
include("pre.php");
echo("    <form id=\"fileupload\" action=\"".SITE_LINK_REL."ajax/file-upload.php\" method=\"POST\" enctype=\"multipart/form-data\">
        <div class=\"row\">
            <div class=\"span12 fileupload-buttonbar\">
                <div class=\"progressbar fileupload-progressbar\"><div style=\"width:0%;\"></div></div>
                <span class=\"btn success fileinput-button\">
                    <span>Add files...</span>
                    <input type=\"file\" name=\"files[]\" multiple>
                </span>
                <button type=\"submit\" class=\"btn primary start\">Start upload</button>
                <button type=\"reset\" class=\"btn info cancel\">Cancel upload</button>
                <button type=\"button\" class=\"btn danger delete\">Delete selected</button>
                <input type=\"checkbox\" class=\"toggle\">
            </div>
        </div>
        <br>
        <div class=\"row\">
            <div class=\"span12\">
                <table class=\"zebra-striped\"><tbody class=\"files\"></tbody></table>
            </div>
        </div>
    </form>
 ");
 ?>